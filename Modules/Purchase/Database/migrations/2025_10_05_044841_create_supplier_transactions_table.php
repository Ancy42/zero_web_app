<?php

use App\Models\Media;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Purchase\App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Supplier::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Media::class)->nullable()->constrained()->nullOnDelete();
            $table->string('transaction_no')->nullable();
            $table->date('transaction_date');
            $table->string('type')->default('credit')->comment('credit, debit');
            $table->float('amount')->default(0);
            $table->boolean('is_paid')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_transactions');
    }
};
