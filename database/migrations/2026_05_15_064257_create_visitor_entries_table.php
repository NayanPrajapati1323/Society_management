<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->onDelete('cascade');
            $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
            $table->foreignId('society_unit_id')->nullable()->constrained('society_units')->onDelete('set null'); // Flat
            $table->foreignId('guard_id')->nullable()->constrained('guards')->onDelete('set null');
            $table->foreignId('visitor_type_id')->constrained('visitor_types')->onDelete('cascade');
            $table->foreignId('resident_id')->nullable()->constrained('users')->onDelete('set null'); // The resident being visited
            
            $table->string('purpose')->nullable();
            $table->timestamp('entry_time')->nullable();
            $table->timestamp('exit_time')->nullable();
            
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Checked In', 'Checked Out', 'Blocked', 'Expired'])->default('Pending');
            
            $table->string('otp')->nullable();
            $table->string('qr_code')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_entries');
    }
};
