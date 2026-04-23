<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('societies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('admin_id')->nullable(); // Society Admin user id
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('societies');
    }
};
