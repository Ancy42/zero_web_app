<?php

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
        Schema::create('generate_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->foreignId('logo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('favicon_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('google_playstore_url')->nullable();
            $table->string('app_store_url')->nullable();
            $table->foreignId('app_logo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('show_download_app')->default(true);
            $table->string('admin_login_img')->nullable();
            $table->string('shop_login_img')->nullable();
            $table->boolean('show_sku')->default(0);
            $table->string('primary_color')->default('#8b5cf6');
            $table->string('secondary_color')->default('#ede9fe');
            $table->string('business_based_on')->default('commission');
            $table->float('commission')->default(10);
            $table->string('commission_type')->nullable()->default('percentage');
            $table->string('commission_charge')->default('per_order');
            $table->boolean('shop_pos')->default(1);
            $table->boolean('shop_register')->default(1);
            $table->string('shop_type')->nullable()->default('multi')->comment('multi, single');
            $table->boolean('new_product_approval')->default(1);
            $table->boolean('update_product_approval')->default(1);
            $table->float('min_withdraw')->nullable();
            $table->float('max_withdraw')->nullable();
            $table->integer('withdraw_request')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_position')->nullable();
            $table->string('direction')->nullable();
            $table->string('footer_text')->nullable();
            $table->boolean('show_footer')->default(true);
            $table->string('footer_phone')->nullable();
            $table->string('footer_email')->nullable();
            $table->string('footer_description')->nullable();
            $table->foreignId('footer_logo_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('footer_qrcode_id')->nullable()->constrained('media')->nullOnDelete();
            $table->float('default_delivery_charge')->default(0);
            $table->boolean('cash_on_delivery')->default(1);
            $table->boolean('online_payment')->default(1);
            $table->string('product_description')->nullable();
            $table->string('page_description')->nullable();
            $table->string('blog_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generate_settings');
    }
};
