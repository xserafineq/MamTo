<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auctionId')->nullable()->constrained('Auctions')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('sellerId')->constrained('Users')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('buyerId')->constrained('Users')->onDelete('no action')->onUpdate('no action');
            $table->timestamp('buyerLastReadAt')->nullable();
            $table->timestamp('sellerLastReadAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Chats');
    }
};
