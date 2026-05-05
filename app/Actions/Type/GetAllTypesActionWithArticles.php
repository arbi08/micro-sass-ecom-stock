<?php

namespace App\Actions\Type;

use App\Repositories\Types\TypeRepository;

class GetAllTypesActionWithArticles
{
    public function __construct(
        private TypeRepository $typeRepository
    ) {}

    public function execute($tenantId = null)
    {
        return $this->typeRepository->getFeaturedTypesWithArticles($tenantId);
    }
}
