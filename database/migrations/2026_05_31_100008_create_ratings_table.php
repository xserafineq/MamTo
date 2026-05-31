<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sellerId')->constrained('Users')->onDelete('no action')->onUpdate('no action');
            $table->integer('rating')->nullable();
            $table->foreignId('userId')->nullable()->constrained('Users')->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Ratings');
    }
};
