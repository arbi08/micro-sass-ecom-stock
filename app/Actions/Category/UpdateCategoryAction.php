<?php

namespace App\Actions\Category;

use App\Repositories\Types\CategoryRepository;

class UpdateCategoryAction
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function execute($id, $tenantId, array $data)
    {
        $category = $this->categoryRepository->findById($id, $tenantId);
        return $this->categoryRepository->update($category, $data);
    }
}
