<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('min_units')->default(0)->after('description');
            $table->decimal('monthly_price', 10, 2)->default(0)->after('max_users');
        });

        Schema::table('societies', function (Blueprint $table) {
            $table->integer('plan_duration')->nullable()->after('plan_id'); // 6 or 12
            $table->decimal('plan_price', 10, 2)->nullable()->after('plan_duration');
            $table->date('plan_expiry_date')->nullable()->after('plan_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['min_units', 'monthly_price']);
        });

        Schema::table('societies', function (Blueprint $table) {
            $table->dropColumn(['plan_duration', 'plan_price', 'plan_expiry_date']);
        });
    }
};
