<?php

namespace App\Repositories\Types;

use App\Models\Category;

class CategoryRepository
{
    public function getAllByTenantByParent($tenantId, $parentId = null)
    {
        return Category::query()
            ->with('children')
            ->forTenant($tenantId)
            ->categories()  // 🔥 هنا استعملنا scope
            ->orderBy('sort_order')
            ->get();
    }

    public function findById($id, $tenantId)
    {
        return Category::query()
            ->with('children')
            ->forTenant($tenantId)
            ->categories()
            ->where('id', $id)
            ->first();  // 🔥 بدل findOrFail
    }

    public function create(array $data)
    {
        return Category::create([
            'tenant_id' => $data['tenant_id'],
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] ?? null,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(Category $type, array $data)
    {
        $type->update([
            'name' => $data['name'] ?? $type->name,
            'icon' => $data['icon'] ?? $type->icon,
            'sort_order' => $data['sort_order'] ?? $type->sort_order,
            'description' => $data['description'] ?? $type->description,
            'meta_title' => $data['meta_title'] ?? $type->meta_title,
            'meta_description' => $data['meta_description'] ?? $type->meta_description,
            'is_active' => $data['is_active'] ?? $type->is_active,
        ]);

        return $type;
    }

    public function delete(Category $type)
    {
        if ($type->children()->exists()) {
            throw new \Exception('Cannot delete type with subcategories');
        }

        return $type->delete();
    }
}
