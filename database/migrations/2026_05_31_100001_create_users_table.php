<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName', 100);
            $table->string('lastName', 100);
            $table->string('email', 200)->unique();
            $table->string('phoneNumber', 12);
            $table->string('password', 255);
            $table->timestamp('joinedAt');
            $table->timestamp('lastOnline');
            $table->boolean('isAdmin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};
