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
        Schema::table('verify_manages', function (Blueprint $table) {
            $table->boolean('phone_required')->default(1);
            $table->boolean('email_required')->default(0);
            $table->string('phone_min_length')->nullable();
            $table->string('phone_max_length')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verify_manages', function (Blueprint $table) {
            $table->dropColumn('phone_required');
            $table->dropColumn('phone_min_length');
            $table->dropColumn('phone_max_length');
            $table->dropColumn('email_required');
        });
    }
};
