<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $google2fa = app('pragmarx.google2fa');

        // Create two tenants first
        $tenant1Id = DB::table('tenants')->insertGetId([
            'name' => 'Tenant 1 Organization',
            'slug' => 'tenant-1',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tenant2Id = DB::table('tenants')->insertGetId([
            'name' => 'Tenant 2 Organization',
            'slug' => 'tenant-2',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Administrator Doe',
                'mobile' => '0',
                'role' => USER_ROLE_SUPER_ADMIN,
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'status' => USER_STATUS_ACTIVE,
                'tenant_id' => null, // Super admin has no tenant - can see all
                'google2fa_secret' => $google2fa->generateSecretKey(),
                'email_verification_status' => 1,
                'phone_verification_status' => 1,
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Admin',
                'mobile' => '01',
                'role' => USER_ROLE_ADMIN,
                'email' => 'user@gmail.com',
                'password' => Hash::make('123456'),
                'status' => USER_STATUS_ACTIVE,
                'tenant_id' => $tenant1Id, // Admin belongs to tenant 1
                'google2fa_secret' => $google2fa->generateSecretKey(),
                'email_verification_status' => 1,
                'phone_verification_status' => 1,
            ]
        ]);
    }
}