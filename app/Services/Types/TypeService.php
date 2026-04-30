<?php

namespace App\Services\Types;

use App\Actions\Type\CreateTypeAction;
use App\Actions\Type\GetAllTypesAction;
use App\Actions\Type\FindTypeByIdAction;
use App\Actions\Type\UpdateTypeAction;
use App\Actions\Type\DeleteTypeAction;

class TypeService
{
    public function __construct(
        private CreateTypeAction $createTypeAction,
        private GetAllTypesAction $getAllTypesAction,
        private FindTypeByIdAction $findTypeByIdAction,
        private UpdateTypeAction $updateTypeAction,
        private DeleteTypeAction $deleteTypeAction
    ) {}

    public function getAll($tenantId)
    {
        return $this->getAllTypesAction->execute($tenantId);
    }

    public function findById($id, $tenantId)
    {
        return $this->findTypeByIdAction->execute($id, $tenantId);
    }

    public function create(array $data)
    {
        return $this->createTypeAction->execute($data);
    }

    public function update($id, $tenantId, array $data)
    {
        return $this->updateTypeAction->execute($id, $tenantId, $data);
    }

    public function delete($id, $tenantId)
    {
        return $this->deleteTypeAction->execute($id, $tenantId);
    }
}
