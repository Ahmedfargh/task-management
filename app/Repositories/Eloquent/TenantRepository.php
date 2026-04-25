<?php

namespace App\Repositories\Eloquent;

use App\Models\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;

class TenantRepository implements TenantRepositoryInterface
{
    public function create(array $data): Tenant
    {
        return Tenant::create($data);
    }

    public function findById(string $id): ?Tenant
    {
        return Tenant::find($id);
    }
}
