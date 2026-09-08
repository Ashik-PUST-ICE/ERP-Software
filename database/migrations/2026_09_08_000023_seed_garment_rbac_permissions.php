<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['name' => 'garments.buyers.manage', 'display_name' => 'Manage Buyers'],
            ['name' => 'garments.orders.manage', 'display_name' => 'Manage Orders'],
            ['name' => 'garments.production.manage', 'display_name' => 'Manage Production'],
            ['name' => 'garments.quality.manage', 'display_name' => 'Manage Quality'],
            ['name' => 'garments.store.manage', 'display_name' => 'Manage Store'],
            ['name' => 'garments.export.manage', 'display_name' => 'Manage Export Documents'],
            ['name' => 'garments.finance.manage', 'display_name' => 'Manage Garments Finance'],
            ['name' => 'garments.hrm-extension.manage', 'display_name' => 'Manage Production HR Extension'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'module' => 2,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('permissions')->whereIn('name', [
            'garments.buyers.manage', 'garments.orders.manage', 'garments.production.manage',
            'garments.quality.manage', 'garments.store.manage', 'garments.export.manage',
            'garments.finance.manage', 'garments.hrm-extension.manage',
        ])->delete();
    }
};
