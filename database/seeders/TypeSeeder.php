<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Electronics',
            'Fashion',
            'Home & Kitchen',
            'Beauty',
            'Sports',
            'Automotive',
            'Books',
            'Toys'
        ];

        foreach ($types as $index => $type) {
            Category::create([
                'tenant_id' => null,
                'name' => $type,
                'slug' => Str::slug($type),
                'icon' => 'category',
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}