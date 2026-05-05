<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class SubCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Electronics' => ['Phones', 'Laptops', 'Accessories'],
            'Fashion' => ['Men', 'Women', 'Kids'],
            'Home & Kitchen' => ['Furniture', 'Appliances', 'Decor'],
            'Beauty' => ['Makeup', 'Skincare', 'Perfumes'],
            'Sports' => ['Fitness', 'Outdoor', 'Equipment'],
        ];

        foreach ($data as $parentName => $subs) {
            $parent = Category::where('name', $parentName)->first();

            foreach ($subs as $index => $sub) {
                Category::create([
                    'tenant_id' => null,
                    'name' => $sub,
                    'parent_id' => $parent->id,
                    'slug' => Str::slug($parentName . '-' . $sub),
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}