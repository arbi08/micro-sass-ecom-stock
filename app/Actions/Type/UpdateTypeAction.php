<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class UpdateTypeAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($id, $tenantId, array $data)
    {
        $type = $this->typeRepository->findById($id, $tenantId);
        return $this->typeRepository->update($type, $data);
    }
}
