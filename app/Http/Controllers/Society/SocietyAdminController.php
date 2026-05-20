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
        return Auth::user()->society->load('plan');
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
            'total_funds'     => PassbookEntry::where('society_id', $society->id)->where('entry_type', 'credit')->sum('amount'),
            'total_expenses'  => PassbookEntry::where('society_id', $society->id)->where('entry_type', 'debit')->sum('amount'),
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

            $newUnitsCount = $request->floors * $request->flats_per_floor;
            $currentUnitsCount = Unit::where('society_id', $society->id)->count();
            $maxUnits = $society->plan->max_units ?? 0;

            if ($maxUnits > 0 && ($currentUnitsCount + $newUnitsCount) > $maxUnits) {
                return back()->with('error', "Cannot add {$newUnitsCount} units. Your plan limit is {$maxUnits} units (Currently used: {$currentUnitsCount}).");
            }

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

            $newUnitsCount = $request->total_houses;
            $currentUnitsCount = Unit::where('society_id', $society->id)->count();
            $maxUnits = $society->plan->max_units ?? 0;

            if ($maxUnits > 0 && ($currentUnitsCount + $newUnitsCount) > $maxUnits) {
                return back()->with('error', "Cannot add {$newUnitsCount} units. Your plan limit is {$maxUnits} units (Currently used: {$currentUnitsCount}).");
            }

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

        $society = $this->getSociety();
        $currentUnitsCount = Unit::where('society_id', $society->id)->count();
        $maxUnits = $society->plan->max_units ?? 0;

        if ($maxUnits > 0 && ($currentUnitsCount + $request->count) > $maxUnits) {
            return back()->with('error', "Cannot add {$request->count} units. Your plan limit is {$maxUnits} units (Currently used: {$currentUnitsCount}).");
        }

        $societyId = $society->id;
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

    public function updateBuilding(Request $request, Building $building)
    {
        if ($building->society_id !== $this->getSociety()->id) abort(403);
        
        $request->validate(['name' => 'required|string|max:50']);
        $building->update(['name' => $request->name]);
        return back()->with('success', 'Building renamed successfully.');
    }

    public function deleteBuilding(Building $building)
    {
        if ($building->society_id !== $this->getSociety()->id) abort(403);
        
        // Delete all units in this building
        Unit::where('building_id', $building->id)->delete();
        $building->delete();
        
        return back()->with('success', 'Building and all its units deleted successfully.');
    }

    public function updateUnit(Request $request, Unit $unit)
    {
        if ($unit->society_id !== $this->getSociety()->id) abort(403);
        
        $request->validate([
            'unit_number' => 'required|string|max:20',
            'status'      => 'required|in:vacant,occupied,maintenance'
        ]);

        $unit->update($request->only('unit_number', 'status'));
        return back()->with('success', 'Unit updated successfully.');
    }

    public function deleteUnit(Unit $unit)
    {
        if ($unit->society_id !== $this->getSociety()->id) abort(403);
        
        $unit->delete();
        return back()->with('success', 'Unit deleted successfully.');
    }

    // ─── Manage Users ─────────────────────────────────────────
    public function users()
    {
        $society = $this->getSociety();
        $users = User::where('society_id', $society->id)->where('role_id', 3)->latest()->get();
        return view('society.admin.users.index', compact('users', 'society'));
    }

    public function storeUser(Request $request)
    {
        $society = $this->getSociety();
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8',
            'unit_number' => 'nullable|string'
        ]);

        $user = User::create([
            'society_id'  => $society->id,
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role_id'     => 3,
            'unit_number' => $request->unit_number,
            'is_approved' => true,
            'is_active'   => true
        ]);

        // Link to unit if provided
        if ($request->unit_number) {
            Unit::where('society_id', $society->id)
                ->where('unit_number', $request->unit_number)
                ->update(['status' => 'occupied', 'user_id' => $user->id]);
        }

        return back()->with('success', 'Resident added successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->society_id !== $this->getSociety()->id) abort(403);
        
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'unit_number' => 'nullable|string'
        ]);

        $user->update($request->only('name', 'email', 'unit_number'));
        
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Resident updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->society_id !== $this->getSociety()->id) abort(403);
        
        // Unlink from units
        Unit::where('user_id', $user->id)->update(['status' => 'vacant', 'user_id' => null]);
        
        $user->delete();
        return back()->with('success', 'Resident deleted successfully.');
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
            'amount'       => 'required|numeric',
            'month'        => 'required',
            'year'         => 'required',
            'description'  => 'nullable'
        ]);

        $society = $this->getSociety();
        $units = Unit::where('society_id', $society->id)->get();

        foreach ($units as $unit) {
            MaintenanceBill::create([
                'society_id'   => $society->id,
                'unit_id'      => $unit->id,
                'user_id'      => $unit->user_id, // Link to user if unit is occupied
                'total_amount' => $request->amount,
                'month'        => $request->month,
                'year'         => $request->year,
                'details'      => ['description' => $request->description],
                'status'       => 'unpaid'
            ]);
        }

        return back()->with('success', "Maintenance bills generated for all " . $units->count() . " units.");
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
            'entry_type'  => $request->type,
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

    public function visitors()
    {
        $society = $this->getSociety();
        $units = \App\Models\Unit::where('society_id', $society->id)->get();
        $visitor_types = \App\Models\VisitorType::all();
        
        $entries = \App\Models\VisitorEntry::with('visitor', 'visitorType', 'unit')
            ->where('society_id', $society->id)
            ->latest()
            ->get();
        
        return view('society.admin.visitors', compact('units', 'visitor_types', 'society', 'entries'));
    }

    public function storeVisitor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'visitor_type_id' => 'required|exists:visitor_types,id',
            'society_unit_id' => 'required|exists:society_units,id',
            'purpose' => 'nullable|string',
        ]);

        $society = $this->getSociety();
        
        // Find or create visitor
        $visitor = \App\Models\Visitor::firstOrCreate(
            ['mobile' => $request->mobile],
            ['name' => $request->name, 'vehicle_number' => $request->vehicle_number]
        );

        // Find resident of the unit
        $unit = \App\Models\Unit::find($request->society_unit_id);
        $residentId = $unit ? $unit->user_id : null;

        // Create entry
        \App\Models\VisitorEntry::create([
            'visitor_id' => $visitor->id,
            'society_id' => $society->id,
            'society_unit_id' => $request->society_unit_id,
            'visitor_type_id' => $request->visitor_type_id,
            'resident_id' => $residentId,
            'purpose' => $request->purpose,
            'status' => 'In Society', // Admin creates it as active
            'entry_time' => now(), // Assume checked in
        ]);

        return back()->with('success', 'Visitor added successfully.');
    }

    public function updateVisitorStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pre-Approved,In Society,Completed',
        ]);

        $entry = \App\Models\VisitorEntry::findOrFail($id);
        
        // Ensure entry belongs to this society
        if ($entry->society_id !== $this->getSociety()->id) {
            abort(403);
        }

        $data = ['status' => $request->status];

        // Set entry/exit time based on status
        if ($request->status == 'In Society' && !$entry->entry_time) {
            $data['entry_time'] = now();
        } elseif ($request->status == 'Completed' && !$entry->exit_time) {
            $data['exit_time'] = now();
        }

        $entry->update($data);

        return back()->with('success', 'Visitor status updated to ' . $request->status);
    }
}
