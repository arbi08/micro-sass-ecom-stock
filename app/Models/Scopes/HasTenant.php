<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait HasTenant
{
    protected static function bootHasTenant()
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if (auth()->check()) {
                $query->where('tenant_id', auth()->user()->tenant_id);
            }
        });
    }
}