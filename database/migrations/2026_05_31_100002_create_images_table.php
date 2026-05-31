<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Images', function (Blueprint $table) {
            $table->id();
            $table->integer('uuid')->nullable();
            $table->text('filename');
            $table->timestamp('uploadedAt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Images');
    }
};
