<?php

namespace App\Actions\SubCategory;

use App\Repositories\Types\SubCategoryRepository;

class CreateSubCategoryAction
{
    public function __construct(
        private SubCategoryRepository $subCategoryRepository
    ) {}

    public function execute(array $data)
    {
        return $this->subCategoryRepository->create($data);
    }
}
