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
        Schema::create('theme_colors', function (Blueprint $table) {
            $table->id();
            $table->string('primary');
            $table->string('secondary');
            $table->string('variant_50')->nullable();
            $table->string('variant_100')->nullable();
            $table->string('variant_200')->nullable();
            $table->string('variant_300')->nullable();
            $table->string('variant_400')->nullable();
            $table->string('variant_500')->nullable();
            $table->string('variant_600')->nullable();
            $table->string('variant_700')->nullable();
            $table->string('variant_800')->nullable();
            $table->string('variant_900')->nullable();
            $table->string('variant_950')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('theme_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_colors');
    }
};
