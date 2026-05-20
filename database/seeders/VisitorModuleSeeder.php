<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\VisitorType;

class VisitorModuleSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Guard Role if not exists
        if (!Role::where('name', 'security_guard')->exists()) {
            Role::create([
                'id' => 4,
                'name' => 'security_guard',
                'display_name' => 'Security Guard',
            ]);
        }

        // Seed Visitor Types
        $types = [
            ['name' => 'Guest', 'description' => 'Personal guests of residents'],
            ['name' => 'Delivery', 'description' => 'Food, courier, etc.'],
            ['name' => 'Cab', 'description' => 'Taxi, Uber, Ola, etc.'],
            ['name' => 'Service Staff', 'description' => 'Plumber, electrician, etc.'],
            ['name' => 'Daily Help', 'description' => 'Maid, driver, etc.'],
            ['name' => 'Emergency Visitor', 'description' => 'Ambulance, fire, etc.'],
        ];

        foreach ($types as $type) {
            VisitorType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
