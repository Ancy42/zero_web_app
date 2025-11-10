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
        Schema::create('verify_manages', function (Blueprint $table) {
            $table->id();
            $table->boolean('register_otp')->default(0);
            $table->string('register_otp_type')->nullable();
            $table->boolean('forgot_otp')->default(1);
            $table->string('forgot_otp_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verify_manages');
    }
};
