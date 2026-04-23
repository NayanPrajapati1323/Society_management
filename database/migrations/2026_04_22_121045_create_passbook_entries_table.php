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
        if (!Schema::hasTable('passbook_entries')) {
            Schema::create('passbook_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->enum('type', ['credit', 'debit']);
                $table->decimal('amount', 12, 2);
                $table->string('category')->nullable(); // Maintenance, Expense, Donation
                $table->text('description')->nullable();
                $table->date('entry_date');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('passbook_entries');
    }
};
