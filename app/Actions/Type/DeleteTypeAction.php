<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class DeleteTypeAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($id, $tenantId)
    {
        $type = $this->typeRepository->findById($id, $tenantId);
        return $this->typeRepository->delete($type);
    }
}