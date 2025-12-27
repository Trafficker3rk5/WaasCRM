<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Budget;
use App\Models\Tenant\Client;
use App\Models\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'created_by' => TenantUser::factory(),
            'products' => implode(',', [1, 2, 3]),
            'quantities' => implode(',', [
                fake()->numberBetween(1, 10),
                fake()->numberBetween(1, 10),
                fake()->numberBetween(1, 10),
            ]),
            'status' => fake()->numberBetween(0, 2),
            'rejection_reason' => null,
            'is_horeca' => fake()->boolean(30),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
            'rejection_reason' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
            'rejection_reason' => null,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
            'rejection_reason' => fake()->sentence(),
        ]);
    }

    public function horeca(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_horeca' => true,
        ]);
    }
}
