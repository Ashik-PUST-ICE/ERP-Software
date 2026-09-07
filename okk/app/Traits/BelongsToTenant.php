<?php

namespace App\Traits;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    /**
     * Automatically apply tenant filtering and assignment.
     *
     * - On queries: adds a global scope so non-super-admin users
     *   only see rows for their own tenant_id.
     * - On create: automatically fills tenant_id from the authenticated user
     *   if it's not already set.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model): void {
            $user = Auth::user();

            if ($user && empty($model->getAttribute('tenant_id')) && !empty($user->tenant_id)) {
                $model->setAttribute('tenant_id', $user->tenant_id);
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder): void {
            $user = Auth::user();

            // No authenticated user → no tenant scoping (e.g. cron, queue workers)
            if (!$user) {
                return;
            }

            // Super admin (role 1) should see all tenants - skip filtering
            if ($user->role == USER_ROLE_SUPER_ADMIN) {
                return;
            }

            // All other roles (admin, user, staff) should be filtered by tenant_id
            if (!empty($user->tenant_id)) {
                $builder->where(
                    $builder->getModel()->getTable() . '.tenant_id',
                    $user->tenant_id
                );
            }
        });
    }
}