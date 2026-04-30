<?php

namespace App\Actions\SubCategory;

use App\Repositories\Types\SubCategoryRepository;

class UpdateSubCategoryAction
{
    public function __construct(
        private SubCategoryRepository $subCategoryRepository
    ) {}

    public function execute($id, $tenantId, array $data)
    {
        $subCategory = $this->subCategoryRepository->findById($id, $tenantId);
        return $this->subCategoryRepository->update($subCategory, $data);
    }
}
