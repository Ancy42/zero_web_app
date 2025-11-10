<?php

use App\Models\Currency;
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
        Schema::table('generate_settings', function (Blueprint $table) {
            $table->foreignIdFor(Currency::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    // /**
    //  * Reverse the migrations.
    //  */
    public function down(): void
    {
        Schema::table('generate_settings', function (Blueprint $table) {
           $table->dropForeign(['currency_id']);
           $table->dropColumn('currency_id');
        });
    }


};
