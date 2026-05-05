<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class UpdateTypeAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($id, $tenantId = null, array $data)
    {
        $type = $tenantId 
            ? $this->typeRepository->findTenantType($id, $tenantId)
            : $this->typeRepository->findType($id);
        
        return $tenantId 
            ? $this->typeRepository->updateTenantType($type, $data)
            : $this->typeRepository->updateType($type, $data);
    }
}
