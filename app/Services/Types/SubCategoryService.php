<?php

namespace App\Services\Types;

use App\Actions\SubCategory\CreateSubCategoryAction;
use App\Actions\SubCategory\GetAllSubCategoriesAction;
use App\Actions\SubCategory\FindSubCategoryByIdAction;
use App\Actions\SubCategory\UpdateSubCategoryAction;
use App\Actions\SubCategory\DeleteSubCategoryAction;

class SubCategoryService
{
    public function __construct(
        private CreateSubCategoryAction $createSubCategoryAction,
        private GetAllSubCategoriesAction $getAllSubCategoriesAction,
        private FindSubCategoryByIdAction $findSubCategoryByIdAction,
        private UpdateSubCategoryAction $updateSubCategoryAction,
        private DeleteSubCategoryAction $deleteSubCategoryAction
    ) {}

    public function getAll($tenantId)
    {
        return $this->getAllSubCategoriesAction->execute($tenantId);
    }

    public function findById($id, $tenantId)
    {
        return $this->findSubCategoryByIdAction->execute($id, $tenantId);
    }

    public function create(array $data)
    {
        return $this->createSubCategoryAction->execute($data);
    }

    public function update($id, $tenantId, array $data)
    {
        return $this->updateSubCategoryAction->execute($id, $tenantId, $data);
    }

    public function delete($id, $tenantId)
    {
        return $this->deleteSubCategoryAction->execute($id, $tenantId);
    }
}
