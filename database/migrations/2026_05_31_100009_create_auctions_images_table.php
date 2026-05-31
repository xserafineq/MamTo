<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('AuctionsImages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imageId')->nullable()->constrained('Images')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('auctionId')->nullable()->constrained('Auctions')->onDelete('no action')->onUpdate('no action');
            $table->integer('order')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('AuctionsImages');
    }
};
