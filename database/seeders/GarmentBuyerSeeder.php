<?php

namespace Database\Seeders;

use App\Models\Garments\Buyer;
use Illuminate\Database\Seeder;

class GarmentBuyerSeeder extends Seeder
{
    public function run(): void
    {
        $buyers = [
            ['buyer_code' => 'BUY-001', 'company_name' => 'Northstar Fashion Ltd.', 'contact_person' => 'Emily Carter', 'email' => 'emily@northstar.example', 'phone' => '+1 212 555 0141', 'country' => 'United States', 'currency' => 'USD'],
            ['buyer_code' => 'BUY-002', 'company_name' => 'Moda Europa GmbH', 'contact_person' => 'Lukas Weber', 'email' => 'lukas@moda-europa.example', 'phone' => '+49 30 555 0182', 'country' => 'Germany', 'currency' => 'EUR'],
            ['buyer_code' => 'BUY-003', 'company_name' => 'Urban Thread Co.', 'contact_person' => 'Sofia Rahman', 'email' => 'sofia@urbanthread.example', 'phone' => '+44 20 555 0193', 'country' => 'United Kingdom', 'currency' => 'GBP'],
        ];

        foreach ($buyers as $buyer) {
            Buyer::updateOrCreate(['buyer_code' => $buyer['buyer_code']], array_merge($buyer, ['status' => STATUS_ACTIVE]));
        }
    }
}
