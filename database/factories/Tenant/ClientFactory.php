<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Client;
use App\Models\Tenant\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'external_id' => fake()->unique()->numerify('EXT-####'),
            'company_name' => fake()->company(),
            'contact_name' => fake()->firstName(),
            'contact_lastname' => fake()->lastName(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'phone_2' => fake()->optional()->phoneNumber(),
            'web' => fake()->optional()->url(),
            'cif' => fake()->optional()->numerify('B########'),
            'logo' => null,
            'is_client' => fake()->boolean(70),
            'origin_id' => Catalog::factory()->create(['type' => 1])->id,
            'status_id' => Catalog::factory()->create(['type' => 2])->id,
            'activity_id' => Catalog::factory()->create(['type' => 5])->id,
            'appreciation_id' => null,
            'assigned_id' => null,
        ];
    }

    public function isClient(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_client' => true,
        ]);
    }

    public function isLead(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_client' => false,
        ]);
    }
}
