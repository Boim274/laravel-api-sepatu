<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh data kategori
        $categories = [
            ['name' => 'Sekolah'],
            ['name' => 'Olahraga'],
            ['name' => 'Santai'],
            ['name' => 'Kerja'],
            ['name' => 'Safety'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
