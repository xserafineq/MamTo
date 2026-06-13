<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Category::where('name', 'Praca')->whereNull('parentId')->exists()) {
            return;
        }

        $praca = Category::create([
            'name' => 'Praca',
            'parentId' => null,
            'imageId' => null,
        ]);

        foreach ([
            'IT i technologie',
            'Handel i sprzedaż',
            'Produkcja',
            'Biuro i administracja',
            'Inne oferty pracy',
        ] as $name) {
            Category::create([
                'name' => $name,
                'parentId' => $praca->id,
                'imageId' => null,
            ]);
        }
    }

    public function down(): void
    {
        $praca = Category::where('name', 'Praca')->whereNull('parentId')->first();

        if (! $praca) {
            return;
        }

        Category::where('parentId', $praca->id)->delete();
        $praca->delete();
    }
};
