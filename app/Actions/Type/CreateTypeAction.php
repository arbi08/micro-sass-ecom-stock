<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class CreateTypeAction
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute(array $data)
    {
        return $this->typeRepository->create($data);
    }
}
