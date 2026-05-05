<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class FindTypeByIdAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($id, $tenantId = null)
    {
        return $tenantId 
            ? $this->typeRepository->findTenantType($id, $tenantId)
            : $this->typeRepository->findType($id);
    }
}
