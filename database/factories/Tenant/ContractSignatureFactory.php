<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\ContractSignature;
use App\Models\Tenant\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractSignatureFactory extends Factory
{
    protected $model = ContractSignature::class;

    public function definition(): array
    {
        return [
            'budget_id' => Budget::factory(),
            'signature_data' => fake()->optional()->imageUrl(200, 100, 'signature', true),
            'signed_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    public function signed(): static
    {
        return $this->state(fn (array $attributes) => [
            'signature_data' => fake()->imageUrl(200, 100, 'signature', true),
            'signed_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function unsigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'signature_data' => null,
            'signed_at' => null,
        ]);
    }
}
