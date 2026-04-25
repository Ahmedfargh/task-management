<?php

namespace App\Repositories\Contracts;

use App\Models\Tenant;

interface TenantRepositoryInterface
{
    public function create(array $data): Tenant;
    public function findById(string $id): ?Tenant;
}
