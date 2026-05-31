<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatId')->nullable()->constrained('Chats');
            $table->text('text');
            $table->timestamp('sentAt');
            $table->foreignId('senderId')->constrained('Users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Messages');
    }
};
