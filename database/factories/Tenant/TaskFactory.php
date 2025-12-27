<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Task;
use App\Models\Tenant\Client;
use App\Models\Tenant\TenantUser;
use App\Models\Tenant\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'created_by' => TenantUser::factory(),
            'assigned_to' => TenantUser::factory(),
            'client_id' => Client::factory(),
            'date' => fake()->dateTimeBetween('now', '+1 month'),
            'date_end' => fake()->dateTimeBetween('+1 month', '+2 months'),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->numberBetween(0, 2),
            'type_id' => Catalog::factory()->create(['type' => 3])->id,
            'appreciation_id' => fake()->optional()->randomElement([
                Catalog::factory()->create(['type' => 4])->id,
                null,
            ]),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2,
        ]);
    }

    public function withoutClient(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_id' => null,
        ]);
    }
}
