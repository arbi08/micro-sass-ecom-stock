<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class DeleteTypeAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($id, $tenantId = null)
    {
        $type = $tenantId 
            ? $this->typeRepository->findTenantType($id, $tenantId)
            : $this->typeRepository->findType($id);
        
        return $tenantId 
            ? $this->typeRepository->deleteTenantType($type)
            : $this->typeRepository->deleteType($type);
    }
}