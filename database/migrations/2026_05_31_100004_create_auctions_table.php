<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Auctions', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('negotiable');
            $table->string('location', 200)->nullable();
            $table->string('status', 100);
            $table->timestamp('createdAt');
            $table->timestamp('updatedAt');
            $table->foreignId('userId')->constrained('Users')->onDelete('no action')->onUpdate('no action');
            $table->foreignId('categoryId')->constrained('Categories');
            $table->foreignId('imageId')->constrained('Images')->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Auctions');
    }
};
