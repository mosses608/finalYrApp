<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecyclableMaterialCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RecyclableMaterialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        RecyclableMaterialCategory::create([
            'name' => 'Plastic',
        ]);

        RecyclableMaterialCategory::create([
            'name' => 'Metal',
        ]);

        RecyclableMaterialCategory::create([
            'name' => 'Paper',
        ]);

        RecyclableMaterialCategory::create([
            'name' => 'Glass',
        ]);

        RecyclableMaterialCategory::create([
            'name' => 'Electronics',
        ]);

        RecyclableMaterialCategory::create([
            'name' => 'Others',
        ]);
    }
}
