<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'image' => $this->faker->imageUrl(400, 300),
            'description' => $this->faker->paragraphs(3, true),
            'monthly_total_channel' => $this->faker->numberBetween(1, 100),
            'yearly_total_channel' => $this->faker->numberBetween(1, 100),
            'monthly_total_post_schedule' => $this->faker->numberBetween(10, 1000),
            'yearly_total_post_schedule' => $this->faker->numberBetween(10, 1000),
            'old_monthly_price' => $this->faker->randomFloat(2, 5, 50),
            'monthly_price' => $this->faker->randomFloat(2, 10, 100),
            'yearly_price' => $this->faker->randomFloat(2, 50, 500),
            'old_yearly_price' => $this->faker->randomFloat(2, 25, 250),
            'allow_ai' => $this->faker->boolean,
            'features' => ['Feature 1', 'Feature 2', 'Feature 3'],
            'status' => true,
        ];
    }
}
