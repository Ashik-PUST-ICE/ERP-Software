<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition()
    {
        $startDate = now()->subDays(1);
        $endDate = now()->addDays(30);

        return [
            'name' => $this->faker->words(3, true),
            'code' => $this->faker->unique()->bothify('???-####'),
            'discount_type' => $this->faker->randomElement(['fixed', 'percentage']),
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'minimum_spend' => $this->faker->randomFloat(2, 0, 500),
            'usage_limit_per_customer' => $this->faker->optional()->numberBetween(1, 10),
            'usage_limit_per_coupon' => $this->faker->optional()->numberBetween(1, 100),
            'used_count' => 0,
            'is_active' => true,
        ];
    }
}
