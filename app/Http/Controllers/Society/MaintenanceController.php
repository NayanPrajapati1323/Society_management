<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceBill;
use App\Models\MaintenanceSetting;
use App\Services\MaintenanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    protected $maintenanceService;

    public function __construct(MaintenanceService $maintenanceService)
    {
        $this->maintenanceService = $maintenanceService;
    }

    /**
     * Get dashboard stats for Maintenance.
     */
    public function getStats()
    {
        $societyId = Auth::user()->society_id;

        $stats = [
            'total_collection' => MaintenanceBill::where('society_id', $societyId)->where('status', 'paid')->sum('paid_amount'),
            'total_pending'    => MaintenanceBill::where('society_id', $societyId)->where('status', '!=', 'paid')->sum('total_amount'),
            'paid_users'       => MaintenanceBill::where('society_id', $societyId)->where('status', 'paid')->distinct('user_id')->count(),
            'unpaid_users'     => MaintenanceBill::where('society_id', $societyId)->where('status', 'unpaid')->distinct('user_id')->count(),
            'overdue_bills'    => MaintenanceBill::where('society_id', $societyId)->where('status', 'overdue')->count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * List all maintenance bills.
     */
    public function index(Request $request)
    {
        $societyId = Auth::user()->society_id;
        $query = MaintenanceBill::with('user', 'unit')->where('society_id', $societyId);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->month) {
            $query->where('month', $request->month);
        }

        $bills = $query->latest()->paginate(20);

        return response()->json(['success' => true, 'data' => $bills]);
    }

    /**
     * Update maintenance settings.
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'title'          => 'required|string',
            'amount'         => 'required|numeric',
            'due_date_day'   => 'required|integer|min:1|max:28',
            'penalty_type'   => 'required|in:fixed,percentage',
            'penalty_value'  => 'required|numeric',
        ]);

        $setting = MaintenanceSetting::updateOrCreate(
            ['society_id' => Auth::user()->society_id],
            $request->all()
        );

        return response()->json(['success' => true, 'data' => $setting]);
    }

    /**
     * Manually trigger monthly bill generation.
     */
    public function generateBills(Request $request)
    {
        $this->maintenanceService->generateMonthlyBills(Auth::user()->society_id, $request->month, $request->year);
        return response()->json(['success' => true, 'message' => 'Bills generated successfully.']);
    }
}
