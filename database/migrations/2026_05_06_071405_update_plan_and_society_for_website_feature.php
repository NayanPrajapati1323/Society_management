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
            $table->dropColumn('max_users');
            $table->decimal('website_price', 10, 2)->default(0)->after('monthly_price');
        });

        Schema::table('societies', function (Blueprint $table) {
            $table->boolean('has_website')->default(false)->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_users')->default(0)->after('max_units');
            $table->dropColumn('website_price');
        });

        Schema::table('societies', function (Blueprint $table) {
            $table->dropColumn('has_website');
        });
    }
};
