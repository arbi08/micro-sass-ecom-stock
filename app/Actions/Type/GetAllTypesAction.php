<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class GetAllTypesAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($tenantId)
    {
        return $this->typeRepository->getAllByTenant($tenantId);
    }
}
