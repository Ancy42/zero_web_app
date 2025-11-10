<?php

use App\Models\Brand;
use App\Models\Media;
use App\Models\Shop;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('code')->nullable();
            $table->string('slug')->nullable();
            $table->foreignIdFor(Shop::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('product_thumbnail')->nullable();
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete();
            $table->float('price');
            $table->float('buy_price')->nullable()->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('min_order_quantity')->default(1);
            $table->float('discount_price')->nullable();
            $table->text('short_description')->nullable();
            $table->text('short_description_ar')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_ar')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_approve')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_new')->default(1);
            $table->string('unit')->nullable();
            $table->foreignId('video_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
