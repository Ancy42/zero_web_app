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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('title')->nullable();
            $table->string('original_name')->nullable();
            $table->string('original_url')->nullable();
            $table->integer('order')->default(0);
            $table->string('target')->default('_self');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_default')->default(0);
            $table->boolean('is_external')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
