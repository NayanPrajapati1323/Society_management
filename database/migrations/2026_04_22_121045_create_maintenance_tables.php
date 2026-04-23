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
        if (!Schema::hasTable('maintenance_types')) {
            Schema::create('maintenance_types', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->string('name');
                $table->decimal('default_amount', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('maintenance_bills')) {
            Schema::create('maintenance_bills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->foreignId('unit_id')->constrained('society_units')->onDelete('cascade');
                $table->decimal('total_amount', 12, 2);
                $table->string('month');
                $table->string('year');
                $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
                $table->text('details')->nullable(); // JSON of items
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_bills');
        Schema::dropIfExists('maintenance_types');
    }
};
