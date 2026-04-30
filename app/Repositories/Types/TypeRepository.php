<?php

namespace App\Repositories\Types;

use App\Models\Category;

class TypeRepository
{
    public function getAllByTenant($tenantId)
    {
        return Category::query()
            ->with(['children' => function ($q) {
                $q->with(['children' => function ($q2) {
                    $q2->orderBy('sort_order');
                }])->orderBy('sort_order');
            }])
            ->forTenant($tenantId)
            ->types()  // 🔥 هنا استعملنا scope
            ->orderBy('sort_order')
            ->get();
    }

    public function findById($id, $tenantId)
    {
        return Category::query()
            ->with(['children' => function ($q) {
                $q->with(['children' => function ($q2) {
                    $q2->orderBy('sort_order');
                }])->orderBy('sort_order');
            }])
            ->forTenant($tenantId)
            ->types()
            ->where('id', $id)
            ->first();  // 🔥 بدل findOrFail
    }

    public function create(array $data)
    {
        return Category::create([
            'tenant_id' => $data['tenant_id'],
            'name' => $data['name'],
            'parent_id' => null,  // type = root
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
            throw new \Exception('Cannot delete type with categories');
        }

        return $type->delete();
    }
}
