<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'School Supplies',
                'description' => 'General school supplies and materials.',
            ],
            [
                'name' => 'Notebooks',
                'description' => 'Notebooks, pads, and writing paper.',
            ],
            [
                'name' => 'Writing Tools',
                'description' => 'Pens, pencils, markers, and highlighters.',
            ],
            [
                'name' => 'Art Supplies',
                'description' => 'Art materials and drawing equipment.',
            ],
            [
                'name' => 'Office Supplies',
                'description' => 'Office materials and equipment.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}