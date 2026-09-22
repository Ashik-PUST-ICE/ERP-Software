<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'admin.dashboard.view', 'display_name' => 'View Admin Dashboard'],
            ['name' => 'admin.team-members.manage', 'display_name' => 'Manage Team Members'],
            ['name' => 'admin.merchandiser.view', 'display_name' => 'View Merchandiser Workspace'],
            ['name' => 'admin.merchandiser.manage', 'display_name' => 'Manage Merchandiser Activities'],
            ['name' => 'admin.merchandiser.tasks.manage', 'display_name' => 'Manage Merchandiser Tasks'],
            ['name' => 'admin.merchandiser.communication.manage', 'display_name' => 'Manage Merchandiser Communications'],
            ['name' => 'admin.merchandiser.insights.view', 'display_name' => 'View Merchandiser Insights'],
            ['name' => 'admin.buyers.manage', 'display_name' => 'Manage Buyers'],
            ['name' => 'admin.orders.manage', 'display_name' => 'Manage Orders'],
            ['name' => 'admin.invoices.view', 'display_name' => 'View Invoices'],
            ['name' => 'admin.invoices.manage', 'display_name' => 'Manage Invoices and Payments'],
            ['name' => 'admin.invoices.export', 'display_name' => 'Export Invoice Reports'],
            ['name' => 'admin.hrm.manage', 'display_name' => 'Manage HRM'],
            ['name' => 'admin.production.manage', 'display_name' => 'Manage Production'],
            ['name' => 'admin.quality.manage', 'display_name' => 'Manage Quality'],
            ['name' => 'admin.store.manage', 'display_name' => 'Manage Store'],
            ['name' => 'admin.email.manage', 'display_name' => 'Manage Email Center'],
            ['name' => 'admin.queue.manage', 'display_name' => 'Manage Queue Jobs'],
            ['name' => 'admin.notifications.view', 'display_name' => 'View Notifications'],
            ['name' => 'admin.audit.view', 'display_name' => 'View Audit Logs'],
            ['name' => 'admin.reports.export', 'display_name' => 'Export Reports'],
        ];

        $permissionModels = collect($permissions)->mapWithKeys(function (array $permission) {
            $model = Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['display_name' => $permission['display_name'], 'module' => 2]
            );

            if ($model->display_name !== $permission['display_name']) {
                $model->update(['display_name' => $permission['display_name']]);
            }

            return [$permission['name'] => $model];
        });

        // Keep one permission for every named admin route. This makes newly
        // added admin routes visible in the role-permission screen automatically.
        $routePermissions = collect(Route::getRoutes()->getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter(fn ($name) => is_string($name) && Str::startsWith($name, 'admin.'))
            ->unique()
            ->mapWithKeys(function (string $routeName) {
                $permissionName = 'admin.route.' . Str::replace('.', '_', Str::after($routeName, 'admin.'));
                $model = Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web'],
                    ['display_name' => 'Access ' . Str::headline(Str::after($routeName, 'admin.')), 'module' => 2]
                );
                return [$permissionName => $model];
            });

        $allAdminPermissions = $permissionModels->merge($routePermissions);

        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'user_type' => USER_ROLE_ADMIN, 'guard_name' => 'web'],
            ['display_name' => 'Admin', 'status' => STATUS_ACTIVE]
        );
        $allAdminPermissions->each(fn (Permission $permission) => $adminRole->givePermissionTo($permission));
        User::where('role', USER_ROLE_ADMIN)->get()->each(function (User $user) use ($adminRole) {
            if (!$user->roles()->exists()) {
                $user->assignRole($adminRole);
            }
        });

        $managerRole = Role::firstOrCreate(
            ['name' => 'Manager', 'user_type' => USER_ROLE_ADMIN, 'guard_name' => 'web'],
            ['display_name' => 'Manager', 'status' => STATUS_ACTIVE]
        );
        $managerPermissions = $permissionModels->except([
            'admin.queue.manage', 'admin.audit.view', 'admin.reports.export', 'admin.email.manage',
        ]);
        $managerPermissions->each(fn (Permission $permission) => $managerRole->givePermissionTo($permission));

        $teamMemberRole = Role::firstOrCreate(
            ['name' => 'Team Member', 'user_type' => USER_ROLE_ADMIN, 'guard_name' => 'web'],
            ['display_name' => 'Team Member', 'status' => STATUS_ACTIVE]
        );
        $teamMemberPermissions = $permissionModels->only([
            'admin.dashboard.view', 'admin.buyers.manage', 'admin.orders.manage',
            'admin.invoices.view', 'admin.notifications.view',
        ]);
        $teamMemberPermissions->each(fn (Permission $permission) => $teamMemberRole->givePermissionTo($permission));

        $merchandiserRole = Role::firstOrCreate(
            ['name' => 'Merchandiser', 'user_type' => USER_ROLE_ADMIN, 'guard_name' => 'web'],
            ['display_name' => 'Merchandiser', 'status' => STATUS_ACTIVE]
        );
        $merchandiserPermissions = $permissionModels->only([
            'admin.dashboard.view', 'admin.buyers.manage', 'admin.orders.manage',
            'admin.merchandiser.view', 'admin.merchandiser.manage',
            'admin.merchandiser.tasks.manage', 'admin.merchandiser.communication.manage',
            'admin.merchandiser.insights.view', 'admin.notifications.view',
        ]);
        $merchandiserPermissions->each(fn (Permission $permission) => $merchandiserRole->givePermissionTo($permission));
    }
}
