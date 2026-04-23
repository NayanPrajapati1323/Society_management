<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Society;
use App\Models\Building;
use App\Models\Unit;
use App\Models\User;
use App\Models\MaintenanceBill;
use App\Models\MaintenanceType;
use App\Models\PassbookEntry;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SocietyAdminController extends Controller
{
    private function getSociety() {
        return Auth::user()->society;
    }

    // ─── Dashboard ───────────────────────────────────────────
    public function dashboard()
    {
        $society = $this->getSociety();
        $stats = [
            'total_units'     => Unit::where('society_id', $society->id)->count(),
            'occupied_units'  => Unit::where('society_id', $society->id)->where('status', 'occupied')->count(),
            'total_users'     => User::where('society_id', $society->id)->where('is_approved', true)->count(),
            'pending_users'   => User::where('society_id', $society->id)->where('is_approved', false)->count(),
            'total_funds'     => PassbookEntry::where('society_id', $society->id)->where('type', 'credit')->sum('amount'),
            'total_expenses'  => PassbookEntry::where('society_id', $society->id)->where('type', 'debit')->sum('amount'),
            'unpaid_bills'    => MaintenanceBill::where('society_id', $society->id)->where('status', 'unpaid')->count(),
        ];
        
        $recent_activities = PassbookEntry::where('society_id', $society->id)->latest()->take(5)->get();

        return view('society.admin.dashboard', compact('stats', 'recent_activities', 'society'));
    }

    // ─── Society Structure ────────────────────────────────────
    public function structure()
    {
        $society = $this->getSociety();
        $buildings = Building::with('units')->where('society_id', $society->id)->get();
        return view('society.admin.structure', compact('society', 'buildings'));
    }

    public function storeBuilding(Request $request)
    {
        $society = $this->getSociety();
        
        if ($society->type == 'flat') {
            $request->validate([
                'name'            => 'required|string|max:50',
                'floors'          => 'required|integer|min:1',
                'flats_per_floor' => 'required|integer|min:1'
            ]);

            $building = Building::create([
                'society_id' => $society->id,
                'name'       => $request->name,
                'floors'     => $request->floors
            ]);

            // Auto-generate units for Tower: 101, 102, 201, 202, etc.
            for ($f = 1; $f <= $request->floors; $f++) {
                for ($p = 1; $p <= $request->flats_per_floor; $p++) {
                    Unit::create([
                        'society_id'  => $society->id,
                        'building_id' => $building->id,
                        'unit_number' => ($f * 100) + $p,
                        'floor'       => $f,
                        'status'      => 'vacant'
                    ]);
                }
            }
            return back()->with('success', "Tower '{$request->name}' and units generated successfully.");
        } else {
            // Row House Logic
            $request->validate([
                'name'         => 'required|string|max:50',
                'total_houses' => 'required|integer|min:1'
            ]);

            $building = Building::create([
                'society_id' => $society->id,
                'name'       => $request->name,
                'floors'     => 1
            ]);

            // Auto-generate Houses: A-1, A-2, etc.
            for ($h = 1; $h <= $request->total_houses; $h++) {
                Unit::create([
                    'society_id'  => $society->id,
                    'building_id' => $building->id,
                    'unit_number' => $request->name . '-' . $h,
                    'floor'       => 1,
                    'status'      => 'vacant'
                ]);
            }
            return back()->with('success', "Block '{$request->name}' and houses generated successfully.");
        }
    }

    public function storeUnits(Request $request)
    {
        $request->validate([
            'building_id' => 'required|exists:society_buildings,id',
            'start_number' => 'required|integer',
            'count'        => 'required|integer|min:1|max:100',
            'floor'        => 'nullable|integer'
        ]);

        $societyId = $this->getSociety()->id;
        for ($i = 0; $i < $request->count; $i++) {
            Unit::create([
                'society_id'  => $societyId,
                'building_id' => $request->building_id,
                'unit_number' => $request->start_number + $i,
                'floor'       => $request->floor,
                'status'      => 'vacant'
            ]);
        }
        return back()->with('success', 'Units created successfully.');
    }

    // ─── Manage Users ─────────────────────────────────────────
    public function users()
    {
        $society = $this->getSociety();
        $users = User::where('society_id', $society->id)->where('role_id', 3)->latest()->get();
        return view('society.admin.users.index', compact('users', 'society'));
    }

    public function approveUser(User $user)
    {
        $society = $this->getSociety();
        if ($user->society_id !== $society->id) abort(403);
        
        $user->update(['is_approved' => true, 'is_active' => true]);

        // Automatically link user to the correct Unit if it exists
        $unit = Unit::where('society_id', $society->id)
                    ->where('unit_number', $user->unit_number)
                    ->first();
        
        if ($unit) {
            $unit->update([
                'status' => 'occupied',
                'user_id' => $user->id
            ]);
        }

        return back()->with('success', 'User approved successfully and assigned to Unit ' . $user->unit_number);
    }

    public function rejectUser(User $user)
    {
        if ($user->society_id !== $this->getSociety()->id) abort(403);
        // Maybe delete or just keep as unapproved
        Storage::disk('public')->delete($user->document_path);
        $user->delete();
        return back()->with('success', 'User residency request rejected.');
    }

    // ─── Manage Maintenance ──────────────────────────────────
    public function maintenance()
    {
        $society = $this->getSociety();
        $bills = MaintenanceBill::with('unit')->where('society_id', $society->id)->latest()->get();
        $units = Unit::where('society_id', $society->id)->get();
        return view('society.admin.maintenance.index', compact('bills', 'units', 'society'));
    }

    public function storeBill(Request $request)
    {
        $request->validate([
            'unit_id'      => 'required|exists:society_units,id',
            'amount'       => 'required|numeric',
            'month'        => 'required',
            'year'         => 'required',
            'description'  => 'nullable'
        ]);

        MaintenanceBill::create([
            'society_id'   => $this->getSociety()->id,
            'unit_id'      => $request->unit_id,
            'total_amount' => $request->amount,
            'month'        => $request->month,
            'year'         => $request->year,
            'details'      => ['description' => $request->description],
            'status'       => 'unpaid'
        ]);

        return back()->with('success', 'Maintenance bill generated.');
    }

    // ─── Society Passbook ─────────────────────────────────────
    public function passbook(Request $request)
    {
        $society = $this->getSociety();
        $query = PassbookEntry::where('society_id', $society->id);
        
        if ($request->month) $query->whereMonth('entry_date', $request->month);
        if ($request->year) $query->whereYear('entry_date', $request->year);

        $entries = $query->latest()->get();
        return view('society.admin.passbook', compact('entries', 'society'));
    }

    public function storePassbookEntry(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:credit,debit',
            'amount'       => 'required|numeric',
            'category'     => 'required',
            'description'  => 'nullable',
            'date'         => 'required|date'
        ]);

        PassbookEntry::create([
            'society_id'  => $this->getSociety()->id,
            'type'        => $request->type,
            'amount'      => $request->amount,
            'category'    => $request->category,
            'description' => $request->description,
            'entry_date'  => $request->date
        ]);

        return back()->with('success', 'Entry added to passbook.');
    }

    // ─── Settings ────────────────────────────────────────────
    public function settings()
    {
        $society = $this->getSociety();
        $plans = Plan::where('is_active', true)->get();
        return view('society.admin.settings', compact('society', 'plans'));
    }

    public function updateProfile(Request $request)
    {
        $society = $this->getSociety();
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $society->update($request->only('name', 'address'));
        return back()->with('success', 'Profile updated successfully.');
    }
}
