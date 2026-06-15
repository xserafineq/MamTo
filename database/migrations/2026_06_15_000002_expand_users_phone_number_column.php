<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE "Users" ALTER COLUMN "phoneNumber" TYPE VARCHAR(12)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE "Users" ALTER COLUMN "phoneNumber" TYPE VARCHAR(11)');
    }
};
