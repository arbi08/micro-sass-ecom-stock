<?php

namespace App\Actions\Category;

use App\Repositories\Types\CategoryRepository;

class FindCategoryByIdAction
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function execute($id, $tenantId)
    {
        return $this->categoryRepository->findById($id, $tenantId);
    }
}
