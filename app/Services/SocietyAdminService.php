<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Unit;
use App\Models\User;
use App\Models\MaintenanceBill;
use App\Models\PassbookEntry;

class SocietyAdminService
{
    public function generateStructure(int $societyId, array $data, string $type)
    {
        if ($type == 'flat') {
            $building = Building::create([
                'society_id' => $societyId,
                'name'       => $data['name'],
                'floors'     => $data['floors']
            ]);

            for ($f = 1; $f <= $data['floors']; $f++) {
                for ($p = 1; $p <= $data['flats_per_floor']; $p++) {
                    Unit::create([
                        'society_id'  => $societyId,
                        'building_id' => $building->id,
                        'unit_number' => ($f * 100) + $p,
                        'floor'       => $f,
                        'status'      => 'vacant'
                    ]);
                }
            }
            return $building;
        } else {
            $building = Building::create([
                'society_id' => $societyId,
                'name'       => $data['name'],
                'floors'     => 1
            ]);

            for ($h = 1; $h <= $data['total_houses']; $h++) {
                Unit::create([
                    'society_id'  => $societyId,
                    'building_id' => $building->id,
                    'unit_number' => $data['name'] . '-' . $h,
                    'floor'       => 1,
                    'status'      => 'vacant'
                ]);
            }
            return $building;
        }
    }

    public function approveResident(User $user, int $societyId)
    {
        if ($user->society_id !== $societyId) throw new \Exception('Unauthorized action.');
        
        $user->update(['is_approved' => true, 'is_active' => true]);

        $unit = Unit::where('society_id', $societyId)
                    ->where('unit_number', $user->unit_number)
                    ->first();
        
        if ($unit) {
            $unit->update([
                'status' => 'occupied',
                'user_id' => $user->id
            ]);
        }
    }
}
