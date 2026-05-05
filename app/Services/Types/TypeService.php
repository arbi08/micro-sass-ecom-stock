<?php

namespace App\Services\Types;

use App\Actions\Type\CreateTypeAction;
use App\Actions\Type\GetAllTypesAction;
use App\Actions\Type\FindTypeByIdAction;
use App\Actions\Type\UpdateTypeAction;
use App\Actions\Type\DeleteTypeAction;
use App\Actions\Type\GetAllTypesActionWithArticles;
use App\Actions\Type\AttachTypeToTenant;
use App\Models\Category;

class TypeService
{
    public function __construct(
        private CreateTypeAction $createTypeAction,
        private GetAllTypesAction $getAllTypesAction,
        private FindTypeByIdAction $findTypeByIdAction,
        private UpdateTypeAction $updateTypeAction,
        private DeleteTypeAction $deleteTypeAction,
        private GetAllTypesActionWithArticles $getAllTypesActionWithArticles,
        private AttachTypeToTenant $attachTypeToTenantAction
    ) {}

    public function getAll($tenantId = null)
    {
        return $tenantId 
            ? $this->getAllTypesAction->execute($tenantId)
            : $this->getAllTypesAction->execute();
    }

    public function findById($id, $tenantId = null)
    {
        return $tenantId 
            ? $this->findTypeByIdAction->execute($id, $tenantId)
            : $this->findTypeByIdAction->execute($id);
    }

    public function create(array $data, $tenantId = null)
    {
        return $tenantId 
            ? $this->createTypeAction->execute($data, $tenantId)
            : $this->createTypeAction->execute($data);
    }

    public function update($id, $tenantId = null, array $data)
    {
        return $tenantId 
            ? $this->updateTypeAction->execute($id, $tenantId, $data)
            : $this->updateTypeAction->execute($id, null, $data);
    }

    public function delete($id, $tenantId = null)
    {
        return $tenantId 
            ? $this->deleteTypeAction->execute($id, $tenantId)
            : $this->deleteTypeAction->execute($id);
    }
    
    public function getFeaturedTypesWithArticles($tenantId = null)
    {
        return $this->getAllTypesActionWithArticles->execute($tenantId);
    }

    public function attachTypeToTenant(Category $type)
    {
        return $this->attachTypeToTenantAction->execute($type);
    }
}
