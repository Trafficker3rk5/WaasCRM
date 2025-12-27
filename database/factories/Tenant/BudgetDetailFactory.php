<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\BudgetDetail;
use App\Models\Tenant\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetDetailFactory extends Factory
{
    protected $model = BudgetDetail::class;

    public function definition(): array
    {
        return [
            'budget_id' => Budget::factory(),
            'product_id' => fake()->numberBetween(1, 100),
            'quantity' => fake()->numberBetween(1, 50),
            'price' => fake()->randomFloat(2, 10, 1000),
            'discount' => fake()->optional()->randomFloat(2, 0, 20),
            'total' => fake()->randomFloat(2, 100, 5000),
        ];
    }
}
