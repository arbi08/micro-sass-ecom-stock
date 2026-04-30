<?php

namespace App\Actions\SubCategory;

use App\Repositories\Types\SubCategoryRepository;

class DeleteSubCategoryAction
{
    public function __construct(
        private SubCategoryRepository $subCategoryRepository
    ) {}

    public function execute($id, $tenantId)
    {
        $subCategory = $this->subCategoryRepository->findById($id, $tenantId);
        return $this->subCategoryRepository->delete($subCategory);
    }
}