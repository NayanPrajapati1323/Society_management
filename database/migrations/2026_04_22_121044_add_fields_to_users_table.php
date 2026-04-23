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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'unit_number')) {
                $table->string('unit_number')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'document_path')) {
                $table->string('document_path')->nullable()->after('unit_number');
            }
            if (!Schema::hasColumn('users', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['society_id']);
            $table->dropColumn(['society_id', 'phone', 'unit_number', 'document_path', 'is_approved']);
        });
    }
};
