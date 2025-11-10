<?php

use App\Models\Shop;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('slug')->nullable()->after('name');
        });

        foreach (Shop::all() as $shop) {
            $shop->update([
                'slug' => str($shop->name)->slug(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->dropSoftDeletes();
        });

    }
};
