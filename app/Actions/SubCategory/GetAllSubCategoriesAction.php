<?php

namespace App\Actions\SubCategory;

use App\Repositories\Types\SubCategoryRepository;

class GetAllSubCategoriesAction
{
    public function __construct(
        private SubCategoryRepository $subCategoryRepository
    ) {}

    public function execute($tenantId)
    {
        return $this->subCategoryRepository->getAllByTenant($tenantId);
    }
}
