<?php

namespace App\Actions\Type;

use App\Models\Category;
use App\Repositories\Types\TypeRepository;

class AttachTypeToTenant
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute(Category $type)
    {
        return $this->typeRepository->attachTypeToTenant($type);
    }
}
