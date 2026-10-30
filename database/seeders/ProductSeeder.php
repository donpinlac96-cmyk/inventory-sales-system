<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Notebooks',
                'product_code' => 'NB001',
                'name' => 'College Notebook',
                'description' => '80 leaves college notebook.',
                'price' => 45.00,
                'stock_quantity' => 50,
                'low_stock_threshold' => 10,
            ],
            [
                'category' => 'Notebooks',
                'product_code' => 'NB002',
                'name' => 'Composition Notebook',
                'description' => 'Standard composition notebook.',
                'price' => 35.00,
                'stock_quantity' => 30,
                'low_stock_threshold' => 10,
            ],
            [
                'category' => 'Writing Tools',
                'product_code' => 'WT001',
                'name' => 'Black Ballpen',
                'description' => 'Black ink ballpoint pen.',
                'price' => 12.00,
                'stock_quantity' => 100,
                'low_stock_threshold' => 20,
            ],
            [
                'category' => 'Writing Tools',
                'product_code' => 'WT002',
                'name' => 'Pencil',
                'description' => 'HB wooden pencil.',
                'price' => 8.00,
                'stock_quantity' => 75,
                'low_stock_threshold' => 15,
            ],
            [
                'category' => 'Writing Tools',
                'product_code' => 'WT003',
                'name' => 'Highlighter',
                'description' => 'Yellow fluorescent highlighter.',
                'price' => 25.00,
                'stock_quantity' => 5,
                'low_stock_threshold' => 10,
            ],
            [
                'category' => 'Art Supplies',
                'product_code' => 'AS001',
                'name' => 'Coloring Set',
                'description' => '12-piece coloring pencil set.',
                'price' => 85.00,
                'stock_quantity' => 20,
                'low_stock_threshold' => 5,
            ],
            [
                'category' => 'Art Supplies',
                'product_code' => 'AS002',
                'name' => 'Watercolor Set',
                'description' => 'Basic watercolor paint set.',
                'price' => 65.00,
                'stock_quantity' => 15,
                'low_stock_threshold' => 5,
            ],
            [
                'category' => 'Office Supplies',
                'product_code' => 'OS001',
                'name' => 'Stapler',
                'description' => 'Standard office stapler.',
                'price' => 95.00,
                'stock_quantity' => 12,
                'low_stock_threshold' => 5,
            ],
            [
                'category' => 'Office Supplies',
                'product_code' => 'OS002',
                'name' => 'Bond Paper',
                'description' => 'Short-size bond paper, 1 ream.',
                'price' => 220.00,
                'stock_quantity' => 8,
                'low_stock_threshold' => 10,
            ],
            [
                'category' => 'School Supplies',
                'product_code' => 'SS001',
                'name' => 'School Ruler',
                'description' => '12-inch plastic ruler.',
                'price' => 15.00,
                'stock_quantity' => 40,
                'low_stock_threshold' => 10,
            ],
        ];

        foreach ($products as $item) {
            $category = Category::where(
                'name',
                $item['category']
            )->firstOrFail();

            Product::updateOrCreate(
                ['product_code' => $item['product_code']],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'stock_quantity' => $item['stock_quantity'],
                    'low_stock_threshold' => $item['low_stock_threshold'],
                ]
            );
        }
    }
}