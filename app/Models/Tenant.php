<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * Custom columns stored in the tenants table data (JSON).
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'plan',
            'is_demo',
            'trial_ends_at',
        ];
    }
}
