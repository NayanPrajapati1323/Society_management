<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resident_visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('visitor_id')->constrained('visitors')->onDelete('cascade');
            $table->boolean('is_frequent')->default(false);
            $table->timestamp('scheduled_at')->nullable(); // For expected visitors
            $table->timestamps();
            
            $table->unique(['resident_id', 'visitor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_visitors');
    }
};
