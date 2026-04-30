<?php

namespace App\Actions\Category;

use App\Repositories\Types\CategoryRepository;

class CreateCategoryAction
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    public function execute(array $data)
    {
        return $this->categoryRepository->create($data);
    }
}
