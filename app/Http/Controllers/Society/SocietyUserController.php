<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceBill;
use App\Models\PassbookEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Unit;

class SocietyUserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $society = $user->society;

        $unit = Unit::where('user_id', $user->id)->first();
        if (!$unit) {
            return view('society.user.dashboard', [
                'totalPaid' => 0,
                'pendingAmount' => 0,
                'recentTransactions' => collect(),
                'society' => $society
            ]);
        }

        $totalPaid = MaintenanceBill::where('unit_id', $unit->id)
            ->where('status', 'paid')
            ->sum('total_amount');

        $pendingAmount = MaintenanceBill::where('unit_id', $unit->id)
            ->where('status', 'unpaid')
            ->sum('total_amount');

        $recentTransactions = MaintenanceBill::where('unit_id', $unit->id)
            ->latest()
            ->take(5)
            ->get();

        return view('society.user.dashboard', compact('totalPaid', 'pendingAmount', 'recentTransactions', 'society'));
    }

    public function passbook(Request $request)
    {
        $user = Auth::user();
        $unit = Unit::where('user_id', $user->id)->first();

        if (!$unit) {
            $bills = collect();
        } else {
            $query = MaintenanceBill::where('unit_id', $unit->id);

            if ($request->month) {
                $query->where('month', $request->month);
            }
            if ($request->year) {
                $query->where('year', $request->year);
            }

            $bills = $query->latest()->get();
        }

        if ($request->has('export') && $bills->count() > 0) {
            $pdf = Pdf::loadView('society.user.pdf.passbook', compact('bills', 'user'));
            return $pdf->download('passbook_' . $user->unit_number . '.pdf');
        }

        return view('society.user.passbook', compact('bills'));
    }

    public function settings()
    {
        return view('society.user.settings');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $user->update($request->only('name', 'phone'));
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password changed successfully.');
    }

    public function visitors()
    {
        $user = Auth::user();
        $visitor_types = \App\Models\VisitorType::all();
        $entries = \App\Models\VisitorEntry::with('visitor', 'visitorType')
            ->where('resident_id', $user->id)
            ->latest()
            ->get();
            
        return view('society.user.visitors', compact('visitor_types', 'entries'));
    }

    public function storeVisitor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'visitor_type_id' => 'required|exists:visitor_types,id',
            'purpose' => 'nullable|string',
            'visit_date_time' => 'nullable|date',
        ]);

        $user = Auth::user();
        
        // Find or create visitor
        $visitor = \App\Models\Visitor::firstOrCreate(
            ['mobile' => $request->mobile],
            ['name' => $request->name, 'vehicle_number' => $request->vehicle_number]
        );

        // Find unit of the user
        $unit = \App\Models\Unit::where('user_id', $user->id)->first();

        // Create entry
        \App\Models\VisitorEntry::create([
            'visitor_id' => $visitor->id,
            'society_id' => $user->society_id,
            'society_unit_id' => $unit ? $unit->id : null,
            'visitor_type_id' => $request->visitor_type_id,
            'resident_id' => $user->id,
            'purpose' => $request->purpose,
            'status' => 'Pre-Approved', // Pre-approved by resident
            'entry_time' => $request->visit_date_time,
            'otp' => rand(100000, 999999),
            'qr_code' => \Illuminate\Support\Str::random(20),
        ]);

        return back()->with('success', 'Visitor scheduled successfully.');
    }
}
