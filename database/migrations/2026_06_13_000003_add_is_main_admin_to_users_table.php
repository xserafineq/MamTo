<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Users', function (Blueprint $table) {
            $table->boolean('isMainAdmin')->default(false);
        });

        User::where('email', 'admin@mamto.test')->update(['isMainAdmin' => true]);
    }

    public function down(): void
    {
        Schema::table('Users', function (Blueprint $table) {
            $table->dropColumn('isMainAdmin');
        });
    }
};
