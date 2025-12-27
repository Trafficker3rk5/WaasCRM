<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogFactory extends Factory
{
    protected $model = Catalog::class;

    public function definition(): array
    {
        return [
            'type' => fake()->numberBetween(1, 5),
            'name' => fake()->words(2, true),
            'description' => fake()->optional()->sentence(),
            'extra_1' => fake()->optional()->word(),
        ];
    }

    public function origin(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 1,
            'name' => fake()->randomElement([
                'Web', 'Referral', 'Cold Call', 'Social Media', 'Event'
            ]),
        ]);
    }

    public function status(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 2,
            'name' => fake()->randomElement([
                'New', 'Contacted', 'Qualified', 'Negotiation', 'Closed'
            ]),
        ]);
    }

    public function taskType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 3,
            'name' => fake()->randomElement([
                'Call', 'Email', 'Meeting', 'Follow-up', 'Demo'
            ]),
        ]);
    }

    public function appreciation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 4,
            'name' => fake()->randomElement([
                'High', 'Medium', 'Low', 'Very High', 'Very Low'
            ]),
        ]);
    }

    public function activity(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 5,
            'name' => fake()->randomElement([
                'Technology', 'Healthcare', 'Finance', 'Retail', 'Manufacturing'
            ]),
        ]);
    }
}
