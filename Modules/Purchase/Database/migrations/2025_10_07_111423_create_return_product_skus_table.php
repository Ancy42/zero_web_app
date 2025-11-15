<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Purchase\App\Models\PurchaseReturnProduct;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_product_skus', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PurchaseReturnProduct::class)->constrained()->cascadeOnDelete();
            $table->string('sku');
            $table->float('price')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_product_skus');
    }
};
