<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Roles
        Role::insert([
            ['id' => 1, 'name' => 'super_admin',   'display_name' => 'Super Admin',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'society_admin',  'display_name' => 'Society Admin',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'user',            'display_name' => 'User',            'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed Super Admin
        User::create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@society.com',
            'password'  => Hash::make('password'),
            'role_id'   => 1,
            'is_active' => true,
        ]);

        // Seed Plans
        $basic = Plan::create([
            'name'             => 'Basic',
            'description'      => 'Perfect for small housing societies',
            'features_summary' => 'Manage up to 50 units with essential features',
            'max_units'        => 50,
            'max_users'        => 100,
            'is_active'        => true,
            'sort_order'       => 1,
        ]);
        PlanFeature::insert([
            ['plan_id' => $basic->id, 'feature_text' => 'Up to 50 Units',               'is_included' => true,  'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $basic->id, 'feature_text' => 'Member Management',             'is_included' => true,  'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $basic->id, 'feature_text' => 'Maintenance Billing',           'is_included' => true,  'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $basic->id, 'feature_text' => 'Notice Board',                  'is_included' => true,  'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $basic->id, 'feature_text' => 'Visitor Management',            'is_included' => false, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $basic->id, 'feature_text' => 'Advanced Reports',              'is_included' => false, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $standard = Plan::create([
            'name'             => 'Standard',
            'description'      => 'Ideal for medium-sized residential societies',
            'features_summary' => 'Manage up to 200 units with advanced features',
            'max_units'        => 200,
            'max_users'        => 400,
            'is_active'        => true,
            'sort_order'       => 2,
        ]);
        PlanFeature::insert([
            ['plan_id' => $standard->id, 'feature_text' => 'Up to 200 Units',            'is_included' => true,  'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $standard->id, 'feature_text' => 'Everything in Basic',        'is_included' => true,  'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $standard->id, 'feature_text' => 'Visitor Management',         'is_included' => true,  'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $standard->id, 'feature_text' => 'Complaint Management',       'is_included' => true,  'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $standard->id, 'feature_text' => 'Multiple Admins',            'is_included' => true,  'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $standard->id, 'feature_text' => 'Custom Reports',             'is_included' => false, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $premium = Plan::create([
            'name'             => 'Premium',
            'description'      => 'For large townships and gated communities',
            'features_summary' => 'Unlimited units with full-featured management',
            'max_units'        => 9999,
            'max_users'        => 9999,
            'is_active'        => true,
            'sort_order'       => 3,
        ]);
        PlanFeature::insert([
            ['plan_id' => $premium->id, 'feature_text' => 'Unlimited Units',             'is_included' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $premium->id, 'feature_text' => 'Everything in Standard',      'is_included' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $premium->id, 'feature_text' => 'Advanced Analytics & Reports','is_included' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $premium->id, 'feature_text' => 'Gate Automation Integration', 'is_included' => true, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $premium->id, 'feature_text' => 'API Access',                  'is_included' => true, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['plan_id' => $premium->id, 'feature_text' => 'Dedicated Support',           'is_included' => true, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
