<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Society;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // ─── Dashboard ───────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_societies'    => Society::count(),
            'active_societies'   => Society::where('is_active', true)->count(),
            'pending_societies'  => Society::where('is_active', false)->count(),
            'flat_society_count' => Society::where('type', 'flat')->count(),
            'row_house_count'    => Society::where('type', 'row_house')->count(),
            'total_users'        => User::where('role_id', '!=', 1)->count(),
            'society_admins'     => User::where('role_id', 2)->count(),
            'regular_users'      => User::where('role_id', 3)->count(),
            'total_plans'        => Plan::count(),
            'active_plans'       => Plan::where('is_active', true)->count(),
        ];

        $recent_societies = Society::with(['plan', 'admin'])->latest()->take(5)->get();
        $recent_users     = User::with('role')->where('role_id', '!=', 1)->latest()->take(5)->get();

        return view('society.super_admin.dashboard', compact('stats', 'recent_societies', 'recent_users'));
    }

    // ─────────────────────────────────────────────────────────────
    //  SOCIETY MANAGEMENT
    // ─────────────────────────────────────────────────────────────
    public function societies(Request $request)
    {
        $query = Society::with(['plan', 'admin']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        $societies = $query->latest()->paginate(10);
        $plans = Plan::where('is_active', true)->get();
        return view('society.super_admin.societies.index', compact('societies', 'plans'));
    }

    public function createSociety()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $admins = User::where('role_id', 2)->get();
        return view('society.super_admin.societies.create', compact('plans', 'admins'));
    }
    public function storeSociety(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:flat,row_house',
            'address'       => 'required|string',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'pincode'       => 'nullable|string|max:10',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'plan_id'       => 'nullable|exists:plans,id',
            'plan_duration' => 'nullable|in:6,12',
            'has_website'   => 'nullable|boolean',
        ]);

        $data = $request->only([
            'name', 'type', 'address', 'city', 'state', 'pincode',
            'contact_email', 'contact_phone', 'plan_id', 'plan_duration', 'has_website'
        ]);

        if ($request->plan_id && $request->plan_duration) {
            $plan = Plan::find($request->plan_id);
            if ($plan) {
                $basePrice = $plan->monthly_price * $request->plan_duration;
                $websitePrice = $request->boolean('has_website') ? $plan->website_price : 0;
                $data['plan_price'] = $basePrice + $websitePrice;
                
                $monthsToAdd = ($request->plan_duration == 12) ? 13 : $request->plan_duration;
                $data['plan_expiry_date'] = now()->addMonths($monthsToAdd);
            }
        }

        Society::create($data + ['is_active' => false, 'country' => 'India']);

        return redirect()->route('super-admin.societies')->with('success', 'Society created successfully.');
    }

    public function createSocietyAdmin(Request $request, Society $society)
    {
        $request->validate([
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'       => $request->admin_name,
            'email'      => $request->admin_email,
            'password'   => Hash::make($request->admin_password),
            'role_id'    => 2, // Society Admin
            'society_id' => $society->id,
            'is_active'  => true,
        ]);

        $society->update(['admin_id' => $user->id]);

        return redirect()->back()->with('success', 'Society Admin created and assigned successfully.');
    }

    public function editSociety(Society $society)
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $admins = User::where('role_id', 2)->get();
        return view('society.super_admin.societies.edit', compact('society', 'plans', 'admins'));
    }

    public function updateSociety(Request $request, Society $society)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'plan_id'       => 'nullable|exists:plans,id',
            'plan_duration' => 'nullable|in:6,12',
            'has_website'   => 'nullable|boolean',
            'admin_id'      => 'nullable|exists:users,id',
            'contact_email' => 'nullable|email',
        ]);

        $data = $request->only([
            'name', 'address', 'city', 'state', 'pincode', 'country',
            'contact_email', 'contact_phone', 'plan_id', 'admin_id', 'plan_duration', 'has_website'
        ]);

        if ($request->plan_id && $request->plan_duration) {
            $plan = Plan::find($request->plan_id);
            if ($plan) {
                $basePrice = $plan->monthly_price * $request->plan_duration;
                
                // If already had website, use maintenance price, else use activation price
                if ($society->has_website && $request->boolean('has_website')) {
                    $websitePrice = $plan->website_maintenance_price;
                } else {
                    $websitePrice = $request->boolean('has_website') ? $plan->website_price : 0;
                }
                
                $data['plan_price'] = $basePrice + $websitePrice;
                
                // Only update expiry if it's a new plan or duration changed or it was expired
                if ($society->plan_id != $request->plan_id || $society->plan_duration != $request->plan_duration || $society->is_plan_expired) {
                    $monthsToAdd = ($request->plan_duration == 12) ? 13 : $request->plan_duration;
                    $data['plan_expiry_date'] = now()->addMonths($monthsToAdd);
                }
            }
        }

        $society->update($data);

        return redirect()->route('super-admin.societies')->with('success', 'Society updated successfully.');
    }

    public function toggleSociety(Society $society)
    {
        $society->update(['is_active' => !$society->is_active]);
        $status = $society->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Society {$status} successfully.");
    }

    public function deleteSociety(Society $society)
    {
        $society->delete();
        return redirect()->route('super-admin.societies')->with('success', 'Society deleted.');
    }

    // ─────────────────────────────────────────────────────────────
    //  PLAN MANAGEMENT
    // ─────────────────────────────────────────────────────────────
    public function plans()
    {
        $plans = Plan::with('features')->orderBy('sort_order')->get();
        return view('society.super_admin.plans.index', compact('plans'));
    }

    public function createPlan()
    {
        return view('society.super_admin.plans.create');
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'features_summary' => 'nullable|string',
            'min_units'        => 'required|integer|min:0',
            'max_units'        => 'required|integer|min:1',
            'monthly_price'    => 'required|numeric|min:0',
            'website_price'    => 'required|numeric|min:0',
            'website_maintenance_price' => 'required|numeric|min:0',
            'sort_order'       => 'nullable|integer',
            'features'         => 'nullable|array',
            'features.*'       => 'string|max:255',
        ]);

        $plan = Plan::create([
            'name'             => $request->name,
            'description'      => $request->description,
            'features_summary' => $request->features_summary,
            'min_units'        => $request->min_units,
            'max_units'        => $request->max_units,
            'monthly_price'    => $request->monthly_price,
            'website_price'    => $request->website_price,
            'website_maintenance_price' => $request->website_maintenance_price,
            'is_active'        => $request->boolean('is_active', true),
            'sort_order'       => $request->sort_order ?? 0,
        ]);

        if ($request->filled('features')) {
            foreach (array_filter($request->features) as $i => $feature) {
                PlanFeature::create([
                    'plan_id'       => $plan->id,
                    'feature_text'  => $feature,
                    'is_included'   => true,
                    'sort_order'    => $i,
                ]);
            }
        }

        return redirect()->route('super-admin.plans')->with('success', 'Plan created successfully.');
    }

    public function editPlan(Plan $plan)
    {
        $plan->load('features');
        return view('society.super_admin.plans.edit', compact('plan'));
    }

    public function updatePlan(Request $request, Plan $plan)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'min_units'        => 'required|integer|min:0',
            'max_units'        => 'required|integer|min:1',
            'monthly_price'    => 'required|numeric|min:0',
            'website_price'    => 'required|numeric|min:0',
            'website_maintenance_price' => 'required|numeric|min:0',
        ]);

        $plan->update([
            'name'             => $request->name,
            'description'      => $request->description,
            'features_summary' => $request->features_summary,
            'min_units'        => $request->min_units,
            'max_units'        => $request->max_units,
            'monthly_price'    => $request->monthly_price,
            'website_price'    => $request->website_price,
            'website_maintenance_price' => $request->website_maintenance_price,
            'is_active'        => $request->boolean('is_active'),
            'sort_order'       => $request->sort_order ?? 0,
        ]);

        // Rebuild features
        $plan->features()->delete();
        if ($request->filled('features')) {
            foreach (array_filter($request->features) as $i => $feature) {
                PlanFeature::create([
                    'plan_id'      => $plan->id,
                    'feature_text' => $feature,
                    'is_included'  => true,
                    'sort_order'   => $i,
                ]);
            }
        }

        return redirect()->route('super-admin.plans')->with('success', 'Plan updated successfully.');
    }

    public function deletePlan(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('super-admin.plans')->with('success', 'Plan deleted.');
    }

    public function togglePlan(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return redirect()->back()->with('success', 'Plan status updated.');
    }

    // ─────────────────────────────────────────────────────────────
    //  USER MANAGEMENT
    // ─────────────────────────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::with(['role', 'society'])->where('role_id', '!=', 1);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        $users = $query->latest()->paginate(10);
        $societies = \App\Models\Society::orderBy('name')->get();
        return view('society.super_admin.users.index', compact('users', 'societies'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id'  => 'required|in:2,3', // Admin or Resident
            'society_id' => 'nullable|exists:societies,id'
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role_id'    => $request->role_id,
            'society_id' => $request->society_id,
            'is_active'  => true,
            'is_approved'=> true
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|in:2,3',
            'society_id' => 'nullable|exists:societies,id'
        ]);

        $data = $request->only('name', 'email', 'role_id', 'society_id');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->role_id == 1) abort(403);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return redirect()->back()->with('success', 'User status updated.');
    }

    // ─────────────────────────────────────────────────────────────
    //  SETTINGS MANAGEMENT
    // ─────────────────────────────────────────────────────────────
    public function settings()
    {
        $admin = auth()->user();
        $plans = Plan::with('features')->orderBy('sort_order')->get();
        return view('society.super_admin.settings.index', compact('admin', 'plans'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
