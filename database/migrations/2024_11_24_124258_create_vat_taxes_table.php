<?php

use App\Enums\DeductionType;
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
        Schema::create('vat_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('order_base')->nullable();
            $table->string('name')->nullable();
            $table->float('percentage')->default(0);
            $table->string('deduction')->nullable()->default(DeductionType::EXCLUSIVE->value);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vat_taxes');
    }
};
