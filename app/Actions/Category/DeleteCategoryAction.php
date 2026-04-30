<?php

namespace App\Actions\Category;

use App\Repositories\Types\CategoryRepository;

class DeleteCategoryAction
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function execute($id, $tenantId)
    {
        $category = $this->categoryRepository->findById($id, $tenantId);
        return $this->categoryRepository->delete($category);
    }
}