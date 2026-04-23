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
        if (!Schema::hasTable('society_buildings')) {
            Schema::create('society_buildings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->string('name');
                $table->integer('floors')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('society_units')) {
            Schema::create('society_units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->foreignId('building_id')->constrained('society_buildings')->onDelete('cascade');
                $table->string('unit_number');
                $table->integer('floor')->nullable();
                $table->enum('status', ['vacant', 'occupied', 'sold'])->default('vacant');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('society_units');
        Schema::dropIfExists('society_buildings');
    }
};
