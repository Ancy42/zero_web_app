<?php

use App\Models\Footer;
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
        Schema::create('footer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Footer::class)->constrained()->cascadeOnDelete();
            $table->string('type')->default('link');
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('shop_type')->default('single');
            $table->string('target')->default('_self');
            $table->boolean('is_active')->default(1);
            $table->integer('order')->default(0);
            $table->boolean('is_default')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_items');
    }
};
