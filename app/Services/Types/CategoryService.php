<?php

namespace App\Services\Types;

use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\GetAllCategoriesAction;
use App\Actions\Category\FindCategoryByIdAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;

class CategoryService
{
    public function __construct(
        private CreateCategoryAction $createCategoryAction,
        private GetAllCategoriesAction $getAllCategoriesAction,
        private FindCategoryByIdAction $findCategoryByIdAction,
        private UpdateCategoryAction $updateCategoryAction,
        private DeleteCategoryAction $deleteCategoryAction
    ) {}

    public function getAll($tenantId)
    {
        return $this->getAllCategoriesAction->execute($tenantId);
    }

    public function findById($id, $tenantId)
    {
        return $this->findCategoryByIdAction->execute($id, $tenantId);
    }

    public function create(array $data)
    {
        return $this->createCategoryAction->execute($data);
    }

    public function update($id, $tenantId, array $data)
    {
        return $this->updateCategoryAction->execute($id, $tenantId, $data);
    }

    public function delete($id, $tenantId)
    {
        return $this->deleteCategoryAction->execute($id, $tenantId);
    }
}
