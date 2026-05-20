<?php

namespace App\Services;

use App\Repositories\VisitorRepository;
use App\Repositories\VisitorEntryRepository;
use App\Models\Visitor;
use App\Models\VisitorEntry;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VisitorService
{
    protected $visitorRepo;
    protected $entryRepo;

    public function __construct(VisitorRepository $visitorRepo, VisitorEntryRepository $entryRepo)
    {
        $this->visitorRepo = $visitorRepo;
        $this->entryRepo = $entryRepo;
    }

    public function scheduleVisit(array $data, $residentId, $societyId)
    {
        // Find or create visitor
        $visitor = $this->visitorRepo->findByMobile($data['mobile']);
        if (!$visitor) {
            $visitor = $this->visitorRepo->create([
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'vehicle_number' => $data['vehicle_number'] ?? null,
            ]);
        }

        // Generate OTP and QR code
        $otp = rand(100000, 999999);
        $qrCode = Str::random(20);

        // Create entry
        $entry = $this->entryRepo->create([
            'visitor_id' => $visitor->id,
            'society_id' => $societyId,
            'society_unit_id' => $data['society_unit_id'] ?? null,
            'visitor_type_id' => $data['visitor_type_id'],
            'resident_id' => $residentId,
            'purpose' => $data['purpose'] ?? null,
            'status' => 'Approved', // Pre-approved by resident
            'otp' => $otp,
            'qr_code' => $qrCode,
            'entry_time' => $data['visit_date_time'] ?? null,
        ]);

        return $entry;
    }

    public function createWalkIn(array $data, $guardId, $societyId)
    {
        // Find or create visitor
        $visitor = $this->visitorRepo->findByMobile($data['mobile']);
        if (!$visitor) {
            $visitor = $this->visitorRepo->create([
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'vehicle_number' => $data['vehicle_number'] ?? null,
            ]);
        }

        // Create entry with status Pending
        $entry = $this->entryRepo->create([
            'visitor_id' => $visitor->id,
            'society_id' => $societyId,
            'society_unit_id' => $data['society_unit_id'],
            'guard_id' => $guardId,
            'visitor_type_id' => $data['visitor_type_id'],
            'resident_id' => $data['resident_id'],
            'purpose' => $data['purpose'] ?? null,
            'status' => 'Pending',
        ]);

        // Trigger notification to resident (handled elsewhere or mocked)

        return $entry;
    }

    public function updateStatus($entryId, $status, $userId)
    {
        $entry = $this->entryRepo->find($entryId);
        
        if (!$entry) {
            throw new \Exception('Visitor entry not found.');
        }

        // Validate permissions based on role (could be moved to policy)
        // For now, simple logic

        if ($status == 'Checked In') {
            $entry->update([
                'status' => 'Checked In',
                'entry_time' => now(),
            ]);
        } elseif ($status == 'Checked Out') {
            $entry->update([
                'status' => 'Checked Out',
                'exit_time' => now(),
            ]);
        } else {
            $entry->update(['status' => $status]);
        }

        return $entry;
    }

    public function verifyOtp($entryId, $otp)
    {
        $entry = $this->entryRepo->find($entryId);
        
        if (!$entry) {
            throw new \Exception('Visitor entry not found.');
        }

        if ($entry->otp === $otp) {
            $entry->update(['status' => 'Checked In', 'entry_time' => now()]);
            return true;
        }

        return false;
    }

    public function verifyQr($qrCode)
    {
        $entry = VisitorEntry::where('qr_code', $qrCode)->first();
        
        if (!$entry) {
            throw new \Exception('Invalid QR Code.');
        }

        $entry->update(['status' => 'Checked In', 'entry_time' => now()]);
        return $entry;
    }
}
