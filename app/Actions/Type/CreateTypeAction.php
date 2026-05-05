<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class CreateTypeAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute(array $data, $tenantId = null)
    {
        return $tenantId 
            ? $this->typeRepository->createTenantType(array_merge($data, ['tenant_id' => $tenantId]))
            : $this->typeRepository->createType($data);
    }
}
