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
        Schema::create('whats_app_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('whats_app_contacts')->onDelete('cascade');
            $table->enum('direction', ['inbound', 'outbound']);
            $table->string('message_type')->default('text');
            $table->text('body')->nullable();
            $table->text('media_urls')->nullable();
            $table->string('status')->default('sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_messages');
    }
};
