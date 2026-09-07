<?php

namespace Database\Seeders;

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['title' => 'Paypal', 'slug' => 'paypal', 'image' => 'assets/images/gateway-icon/paypal.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Stripe', 'slug' => 'stripe', 'image' => 'assets/images/gateway-icon/stripe.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Razorpay', 'slug' => 'razorpay', 'image' => 'assets/images/gateway-icon/razorpay.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Instamojo', 'slug' => 'instamojo', 'image' => 'assets/images/gateway-icon/instamojo.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Mollie', 'slug' => 'mollie', 'image' => 'assets/images/gateway-icon/mollie.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Paystack', 'slug' => 'paystack', 'image' => 'assets/images/gateway-icon/paystack.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'image' => 'assets/images/gateway-icon/sslcommerz.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Flutterwave', 'slug' => 'flutterwave', 'image' => 'assets/images/gateway-icon/flutterwave.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Mercadopago', 'slug' => 'mercadopago', 'image' => 'assets/images/gateway-icon/mercadopago.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['title' => 'Bank', 'slug' => 'bank', 'image' => 'assets/images/gateway-icon/bank.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
        ];
        Gateway::insert($data);

        GatewayCurrency::insert([
            ['gateway_id' => 1, 'currency' => 'USD', 'conversion_rate' => 1],
            ['gateway_id' => 2, 'currency' => 'USD', 'conversion_rate' => 1],
            ['gateway_id' => 3, 'currency' => 'INR', 'conversion_rate' => 80],
            ['gateway_id' => 4, 'currency' => 'INR', 'conversion_rate' => 80],
            ['gateway_id' => 5, 'currency' => 'USD', 'conversion_rate' => 1],
            ['gateway_id' => 6, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['gateway_id' => 7, 'currency' => 'BDT', 'conversion_rate' => 100],
            ['gateway_id' => 8, 'currency' => 'NGN', 'conversion_rate' => 464],
            ['gateway_id' => 9, 'currency' => 'BRL', 'conversion_rate' => 5],
            ['gateway_id' => 10, 'currency' => 'USD', 'conversion_rate' => 1],
        ]);
        
        // Seed roles and permissions
        // $this->seedRolesAndPermissions();
    }
    
    // private function seedRolesAndPermissions()
    // {
    //     // Create permissions with proper format (module.action.name)
    //     $permissions = [
    //         ['name' => 'dashboard.manage.view', 'display_name' => 'View Dashboard', 'module' => 1],
    //         ['name' => 'user.manage.view', 'display_name' => 'View Users', 'module' => 2],
    //         ['name' => 'user.manage.create', 'display_name' => 'Create Users', 'module' => 2],
    //         ['name' => 'user.manage.edit', 'display_name' => 'Edit Users', 'module' => 2],
    //         ['name' => 'user.manage.delete', 'display_name' => 'Delete Users', 'module' => 2],
    //         ['name' => 'post.manage.view', 'display_name' => 'View Posts', 'module' => 3],
    //         ['name' => 'post.manage.create', 'display_name' => 'Create Posts', 'module' => 3],
    //         ['name' => 'post.manage.edit', 'display_name' => 'Edit Posts', 'module' => 3],
    //         ['name' => 'post.manage.delete', 'display_name' => 'Delete Posts', 'module' => 3],
    //         ['name' => 'setting.manage.view', 'display_name' => 'View Settings', 'module' => 4],
    //         ['name' => 'setting.manage.update', 'display_name' => 'Update Settings', 'module' => 4],
    //         ['name' => 'report.manage.view', 'display_name' => 'View Reports', 'module' => 5],
    //         ['name' => 'payment.manage.view', 'display_name' => 'View Payments', 'module' => 6],
    //         ['name' => 'payment.manage.approve', 'display_name' => 'Approve Payments', 'module' => 6],
    //         ['name' => 'package.manage.view', 'display_name' => 'View Packages', 'module' => 7],
    //         ['name' => 'package.manage.create', 'display_name' => 'Create Packages', 'module' => 7],
    //         ['name' => 'package.manage.edit', 'display_name' => 'Edit Packages', 'module' => 7],
    //         ['name' => 'package.manage.delete', 'display_name' => 'Delete Packages', 'module' => 7],
    //         ['name' => 'template.manage.view', 'display_name' => 'View Templates', 'module' => 8],
    //         ['name' => 'template.manage.create', 'display_name' => 'Create Templates', 'module' => 8],
    //         ['name' => 'template.manage.edit', 'display_name' => 'Edit Templates', 'module' => 8],
    //         ['name' => 'category.manage.view', 'display_name' => 'View Categories', 'module' => 9],
    //         ['name' => 'category.manage.create', 'display_name' => 'Create Categories', 'module' => 9],
    //         ['name' => 'category.manage.edit', 'display_name' => 'Edit Categories', 'module' => 9],
    //         ['name' => 'campaign.manage.view', 'display_name' => 'View Campaigns', 'module' => 10],
    //         ['name' => 'campaign.manage.create', 'display_name' => 'Create Campaigns', 'module' => 10],
    //         ['name' => 'ticket.manage.view', 'display_name' => 'View Tickets', 'module' => 11],
    //         ['name' => 'ticket.manage.reply', 'display_name' => 'Reply Tickets', 'module' => 11],
    //         ['name' => 'social.manage.view', 'display_name' => 'View Social Media', 'module' => 12],
    //         ['name' => 'social.manage.configure', 'display_name' => 'Configure Social Media', 'module' => 12],
    //     ];
        
    //     foreach ($permissions as $perm) {
    //         Permission::firstOrCreate(
    //             ['name' => $perm['name']],
    //             ['display_name' => $perm['display_name'], 'module' => $perm['module'], 'guard_name' => 'web']
    //         );
    //     }
        
    //     // Create roles for Super Admin (user_type = 1)
    //     $superAdminRoles = [
    //         ['name' => 'Super Admin', 'display_name' => 'Super Admin'],
    //         ['name' => 'Super Manager', 'display_name' => 'Super Manager'],
    //     ];
    //     foreach ($superAdminRoles as $roleData) {
    //         $role = Role::firstOrCreate(
    //             ['name' => $roleData['name']],
    //             ['display_name' => $roleData['display_name'], 'user_type' => 1, 'status' => 1, 'guard_name' => 'web']
    //         );
    //         $role->givePermissionTo(Permission::all());
    //     }
        
    //     // Create roles for Admin (user_type = 2)
    //     $adminRoles = [
    //         ['name' => 'Admin', 'display_name' => 'Admin'],
    //         ['name' => 'Manager', 'display_name' => 'Manager'],
    //         ['name' => 'Editor', 'display_name' => 'Editor'],
    //         ['name' => 'Moderator', 'display_name' => 'Moderator'],
    //     ];
    //     foreach ($adminRoles as $roleData) {
    //         Role::firstOrCreate(
    //             ['name' => $roleData['name']],
    //             ['display_name' => $roleData['display_name'], 'user_type' => 2, 'status' => 1, 'guard_name' => 'web']
    //         );
    //     }
        
    //     // Create roles for regular users (user_type = 3)
    //     $userRoles = [
    //         ['name' => 'User', 'display_name' => 'User'],
    //         ['name' => 'Premium User', 'display_name' => 'Premium User'],
    //         ['name' => 'Trial User', 'display_name' => 'Trial User'],
    //     ];
    //     foreach ($userRoles as $roleData) {
    //         Role::firstOrCreate(
    //             ['name' => $roleData['name']],
    //             ['display_name' => $roleData['display_name'], 'user_type' => 3, 'status' => 1, 'guard_name' => 'web']
    //         );
    //     }
    // }
}