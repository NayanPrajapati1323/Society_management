<?php

namespace App\Repositories;

use App\Models\VisitorEntry;

class VisitorEntryRepository
{
    public function find($id)
    {
        return VisitorEntry::with(['visitor', 'visitorType', 'unit', 'resident'])->find($id);
    }

    public function create(array $data)
    {
        return VisitorEntry::create($data);
    }

    public function update($id, array $data)
    {
        $entry = VisitorEntry::findOrFail($id);
        $entry->update($data);
        return $entry;
    }

    public function getActiveEntriesBySociety($societyId)
    {
        return VisitorEntry::with(['visitor', 'visitorType', 'unit'])
                           ->where('society_id', $societyId)
                           ->whereIn('status', ['Checked In'])
                           ->orderBy('entry_time', 'desc')
                           ->get();
    }

    public function getHistoryBySociety($societyId, $perPage = 15)
    {
        return VisitorEntry::with(['visitor', 'visitorType', 'unit'])
                           ->where('society_id', $societyId)
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);
    }

    public function getPendingApprovalsForResident($residentId)
    {
        return VisitorEntry::with(['visitor', 'visitorType'])
                           ->where('resident_id', $residentId)
                           ->where('status', 'Pending')
                           ->get();
    }
}
