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
        Schema::create('hide_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable()->constrained("users")->nullOnDelete();
            $table->foreignId("message_id")->constrained("messages")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hide_messages');
    }
};
