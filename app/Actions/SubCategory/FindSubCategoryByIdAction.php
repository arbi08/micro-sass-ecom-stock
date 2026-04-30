<?php

namespace App\Actions\SubCategory;

use App\Repositories\Types\SubCategoryRepository;

class FindSubCategoryByIdAction
{
    public function __construct(
        private SubCategoryRepository $subCategoryRepository
    ) {}

    public function execute($id, $tenantId)
    {
        return $this->subCategoryRepository->findById($id, $tenantId);
    }
}
