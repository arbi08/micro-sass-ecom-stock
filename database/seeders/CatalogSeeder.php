<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Phones' => ['iPhone', 'Samsung', 'Xiaomi'],
            'Laptops' => ['Gaming', 'Ultrabook', 'Business'],
            'Men' => ['Shirts', 'Shoes', 'Watches'],
            'Women' => ['Dresses', 'Bags', 'Heels'],
        ];

        foreach ($data as $parentName => $children) {
            $parent = Category::where('name', $parentName)->first();

            if (!$parent) continue;

            foreach ($children as $index => $child) {
                Category::create([
                    'tenant_id' => null,
                    'name' => $child,
                    'parent_id' => $parent->id,
                    'slug' => Str::slug($parentName . '-' . $child),
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}