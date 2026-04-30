<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class FindTypeByIdAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($id, $tenantId)
    {
        return $this->typeRepository->findById($id, $tenantId);
    }
}
