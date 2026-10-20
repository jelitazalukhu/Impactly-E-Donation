<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bencana',      'description' => 'Bantuan korban bencana alam dan kemanusiaan'],
            ['name' => 'Tempat Ibadah',       'description' => 'Pembangunan dan renovasi tempat ibadah'],
            ['name' => 'Panti Asuhan', 'description' => 'Dukungan untuk panti asuhan dan anak yatim'],
            ['name' => 'Pendidikan',   'description' => 'Beasiswa dan fasilitas pendidikan'],
            ['name' => 'Kesehatan',    'description' => 'Biaya pengobatan dan layanan kesehatan'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
