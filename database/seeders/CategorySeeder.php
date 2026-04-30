<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = 1;

        // 🟢 TYPES (parent_id = null)
        $electronics = Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Electronics',
            'parent_id' => null,
            'icon' => 'bolt',
            'sort_order' => 1,
            'description' => 'All electronic products',
            'is_active' => true,
        ]);

        $fashion = Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Fashion',
            'parent_id' => null,
            'icon' => 'shirt',
            'sort_order' => 2,
            'description' => 'Clothing and accessories',
            'is_active' => true,
        ]);

        $home = Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Home & Kitchen',
            'parent_id' => null,
            'icon' => 'home',
            'sort_order' => 3,
            'description' => 'Home appliances and kitchen tools',
            'is_active' => true,
        ]);

        // 🔵 SUB CATEGORIES (children)

        Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Smartphones',
            'parent_id' => $electronics->id,
            'icon' => 'mobile',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Laptops',
            'parent_id' => $electronics->id,
            'icon' => 'laptop',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Men Clothing',
            'parent_id' => $fashion->id,
            'icon' => 'user',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Women Clothing',
            'parent_id' => $fashion->id,
            'icon' => 'user-female',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'tenant_id' => $tenantId,
            'name' => 'Kitchen Appliances',
            'parent_id' => $home->id,
            'icon' => 'blender',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }
}