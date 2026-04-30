<?php

namespace App\Actions\Category;

use App\Repositories\Types\CategoryRepository;

class GetAllCategoriesAction
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function execute($tenantId)
    {
        return $this->categoryRepository->getAllByTenant($tenantId);
    }
}
