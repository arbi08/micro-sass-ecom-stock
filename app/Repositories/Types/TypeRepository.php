<?php

namespace App\Repositories\Types;

use App\Models\Category;
use App\Models\TenantCategory;

class TypeRepository
{
    /* =========================================================
     |  GLOBAL TYPES (Category)
     ========================================================= */

    public function getAll()
    {
        return Category::withoutGlobalScopes()
            ->whereNull('parent_id')
            ->with('children.children')
            ->orderBy('sort_order')
            ->get();
    }

    public function findType($id)
    {
        return Category::withoutGlobalScopes()
            ->whereNull('parent_id')
            ->with('children.children')
            ->findOrFail($id);
    }

    public function createType(array $data)
    {
        return Category::create([
            'tenant_id' => $data['tenant_id'] ?? null,
            'name' => $data['name'],
            'parent_id' => null,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateType(Category $type, array $data)
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

    public function deleteType(Category $type)
    {
        if ($type->children()->exists()) {
            throw new \Exception('Cannot delete type with children');
        }

        return $type->delete();
    }

    /* =========================================================
     |  TENANT TYPES (TenantCategory)
     ========================================================= */

    public function getAllByTenant($tenantId)
    {
        return TenantCategory::where('tenant_id', $tenantId)
            ->whereNull('parent_id')
            ->with('children.children')
            ->orderBy('sort_order')
            ->get();
    }

    public function findTenantType($id, $tenantId)
    {
        return TenantCategory::where('tenant_id', $tenantId)
            ->whereNull('parent_id')
            ->with('children.children')
            ->findOrFail($id);
    }

    public function createTenantType(array $data)
    {
        return TenantCategory::create([
            'tenant_id' => $data['tenant_id'],
            'category_id' => $data['category_id'] ?? null,
            'name' => $data['name'],
            'parent_id' => null,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function updateTenantType(TenantCategory $type, array $data)
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

    public function deleteTenantType(TenantCategory $type)
    {
        if ($type->children()->exists()) {
            throw new \Exception('Cannot delete tenant type with children');
        }

        return $type->delete();
    }

    /* =========================================================
     |  EXTRA HELPERS
     ========================================================= */

    public function attachTypeToTenant(Category $type)
    {
        $tenantId = app('tenant_id');
        $existing = TenantCategory::withTrashed()->where('tenant_id', $tenantId)->where('category_id', $type->id)->first();
        if ($existing) {
            $existing->restore();
            return $existing;
        }
        return TenantCategory::create([
            'tenant_id' => $tenantId,
            'category_id' => $type->id,
            'name' => $type->name,
            'parent_id' => null,
            'icon' => $type->icon,
            'sort_order' => $type->sort_order,
            'description' => $type->description,
            'meta_title' => $type->meta_title,
            'meta_description' => $type->meta_description,
            'is_active' => $type->is_active,
        ]);
    }

    public function getFeaturedTypesWithArticles($tenantId = null)
    {
        $types = Category::withoutGlobalScopes()
            ->when($tenantId, fn($q) => $q->forTenant($tenantId))
            ->types()
            ->with('children.children')
            ->orderBy('sort_order')
            ->get();

        return $types
            ->map(function ($type) {
                // 🔥 collect all category ids (type + children + subchildren)
                $categoryIds = collect([$type->id]);

                foreach ($type->children as $child) {
                    $categoryIds->push($child->id);

                    foreach ($child->children as $subChild) {
                        $categoryIds->push($subChild->id);
                    }
                }

                // 🧠 get random 20 articles
                $articles = \App\Models\Article::query()
                    ->whereIn('category_id', $categoryIds)
                    ->inRandomOrder()
                    ->limit(20)
                    ->get(['id', 'name', 'image']);

                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'image' => $type->icon ?? null,  // ولا image إلا كان عندك
                    'products' => $articles->map(fn($a) => [
                        'id' => $a->id,
                        'name' => $a->name,
                        'image' => $a->image,
                    ]),
                ];
            })
            ->sortByDesc('products')
            ->values();  // مهم باش يرجع array clean
    }
}
