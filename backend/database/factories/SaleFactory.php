<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'seller_id' => Seller::inRandomOrder()->first()?->id ?? Seller::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 1000),
            'sale_date' => now()->toDateString(),
        ];
    }
}