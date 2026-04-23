<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->default(3)->after('email'); // 1=super_admin, 2=society_admin, 3=user
            $table->unsignedBigInteger('society_id')->nullable()->after('role_id');
            $table->string('phone')->nullable()->after('society_id');
            $table->boolean('is_active')->default(true)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role_id', 'society_id', 'phone', 'is_active']);
        });
    }
};
