<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('FollowedAuctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('Users')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('auctionId')->constrained('Auctions')->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('FollowedAuctions');
    }
};
