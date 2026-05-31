<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->foreignId('imageId')->nullable()->constrained('Images')->nullOnDelete();
            $table->foreignId('parentId')->nullable()->constrained('Categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Categories');
    }
};
