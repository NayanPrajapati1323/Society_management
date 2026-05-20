<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Services\VisitorService;
use App\Http\Resources\Society\VisitorEntryResource;
use App\Repositories\VisitorEntryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    protected $visitorService;
    protected $entryRepo;

    public function __construct(VisitorService $visitorService, VisitorEntryRepository $entryRepo)
    {
        $this->visitorService = $visitorService;
        $this->entryRepo = $entryRepo;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Guard or Admin can see all entries for the society
        if (in_array($user->role_id, [2, 4])) {
            $entries = $this->entryRepo->getHistoryBySociety($user->society_id);
            return VisitorEntryResource::collection($entries);
        }
        
        // Resident can see their own entries
        if ($user->role_id == 3) {
            $entries = \App\Models\VisitorEntry::where('resident_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            return VisitorEntryResource::collection($entries);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'visitor_type_id' => 'required|exists:visitor_types,id',
            'society_unit_id' => 'nullable|exists:society_units,id',
            'purpose' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Resident pre-scheduling
        if ($user->role_id == 3) {
            $entry = $this->visitorService->scheduleVisit($data, $user->id, $user->society_id);
            return new VisitorEntryResource($entry);
        }

        // Guard creating walk-in
        if ($user->role_id == 4) {
            // Guard must specify unit and resident
            if (!$request->has('society_unit_id') || !$request->has('resident_id')) {
                return response()->json(['message' => 'Unit and Resident are required for walk-in.'], 422);
            }
            
            $guard = \App\Models\Guard::where('user_id', $user->id)->first();
            if (!$guard) {
                return response()->json(['message' => 'Guard profile not found.'], 403);
            }

            $entry = $this->visitorService->createWalkIn($data, $guard->id, $user->society_id);
            return new VisitorEntryResource($entry);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $entry = $this->entryRepo->find($id);

        if (!$entry) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // Resident can approve/reject pending visits for them
        if ($user->role_id == 3 && $entry->resident_id == $user->id) {
            if ($request->has('status') && in_array($request->status, ['Approved', 'Rejected'])) {
                $updated = $this->visitorService->updateStatus($id, $request->status, $user->id);
                return new VisitorEntryResource($updated);
            }
        }

        // Guard can check in/out
        if ($user->role_id == 4) {
            if ($request->has('status') && in_array($request->status, ['Checked In', 'Checked Out'])) {
                $updated = $this->visitorService->updateStatus($id, $request->status, $user->id);
                return new VisitorEntryResource($updated);
            }
        }

        return response()->json(['message' => 'Unauthorized or invalid status'], 403);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'entry_id' => 'required|exists:visitor_entries,id',
            'otp' => 'required|string',
        ]);

        try {
            $verified = $this->visitorService->verifyOtp($request->entry_id, $request->otp);
            if ($verified) {
                return response()->json(['message' => 'OTP verified and visitor checked in.']);
            }
            return response()->json(['message' => 'Invalid OTP'], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function verifyQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        try {
            $entry = $this->visitorService->verifyQr($request->qr_code);
            return new VisitorEntryResource($entry);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
