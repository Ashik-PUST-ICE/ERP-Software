<?php

namespace Database\Seeders;

use App\Models\Garments\GarmentPaymentGateway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GarmentPaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            ['title' => 'Paypal', 'slug' => 'paypal', 'image' => 'assets/images/gateway-icon/paypal.png'],
            ['title' => 'Stripe', 'slug' => 'stripe', 'image' => 'assets/images/gateway-icon/stripe.png'],
            ['title' => 'Razorpay', 'slug' => 'razorpay', 'image' => 'assets/images/gateway-icon/razorpay.png'],
            ['title' => 'Instamojo', 'slug' => 'instamojo', 'image' => 'assets/images/gateway-icon/instamojo.png'],
            ['title' => 'Mollie', 'slug' => 'mollie', 'image' => 'assets/images/gateway-icon/mollie.png'],
            ['title' => 'Paystack', 'slug' => 'paystack', 'image' => 'assets/images/gateway-icon/paystack.png'],
            ['title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'image' => 'assets/images/gateway-icon/sslcommerz.png'],
            ['title' => 'Flutterwave', 'slug' => 'flutterwave', 'image' => 'assets/images/gateway-icon/flutterwave.png'],
            ['title' => 'Mercadopago', 'slug' => 'mercadopago', 'image' => 'assets/images/gateway-icon/mercadopago.png'],
            ['title' => 'Bank', 'slug' => 'bank', 'image' => 'assets/images/gateway-icon/bank.png'],
        ];

        $currencies = [
            'paypal' => ['USD', 1],
            'stripe' => ['USD', 1],
            'razorpay' => ['INR', 80],
            'instamojo' => ['INR', 80],
            'mollie' => ['USD', 1],
            'paystack' => ['NGN', 464],
            'sslcommerz' => ['BDT', 100],
            'flutterwave' => ['NGN', 464],
            'mercadopago' => ['BRL', 5],
            'bank' => ['USD', 1],
        ];

        foreach (DB::table('tenants')->pluck('id') as $tenantId) {
            foreach ($gateways as $gatewayData) {
                $gateway = GarmentPaymentGateway::withoutGlobalScopes()->updateOrCreate(
                    ['tenant_id' => $tenantId, 'slug' => $gatewayData['slug']],
                    array_merge($gatewayData, [
                        'tenant_id' => $tenantId,
                        'status' => ACTIVE,
                        'mode' => GATEWAY_MODE_SANDBOX,
                        'url' => '',
                        'key' => '',
                        'secret' => '',
                    ])
                );

                [$currency, $conversionRate] = $currencies[$gatewayData['slug']];
                $gateway->currencies()->withoutGlobalScopes()->updateOrCreate(
                    ['tenant_id' => $tenantId, 'currency' => $currency],
                    ['tenant_id' => $tenantId, 'conversion_rate' => $conversionRate]
                );
            }
        }
    }
}
