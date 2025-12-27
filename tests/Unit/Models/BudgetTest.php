<?php

use App\Models\Tenant\Budget;
use App\Models\Tenant\Client;
use App\Models\Tenant\TenantUser;
use App\Models\Tenant\BudgetDetail;
use App\Models\Tenant\ContractSignature;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('budget can be created with required fields', function () {
    $client = Client::factory()->create();

    $budget = Budget::factory()->create([
        'client_id' => $client->id,
        'products' => '1,2,3',
        'quantities' => '10,20,30',
        'status' => 0,
    ]);

    expect($budget)->toBeInstanceOf(Budget::class)
        ->and($budget->client_id)->toBe($client->id)
        ->and($budget->status)->toBe(0);
});

test('budget belongs to client', function () {
    $client = Client::factory()->create();
    $budget = Budget::factory()->create(['client_id' => $client->id]);

    expect($budget->client)->toBeInstanceOf(Client::class)
        ->and($budget->client->id)->toBe($client->id);
});

test('budget belongs to user creator', function () {
    $user = TenantUser::factory()->create();
    $budget = Budget::factory()->create(['created_by' => $user->id]);

    expect($budget->user)->toBeInstanceOf(TenantUser::class)
        ->and($budget->user->id)->toBe($user->id);
});

test('budget has many details', function () {
    $budget = Budget::factory()->create();
    $details = BudgetDetail::factory()->count(3)->create(['budget_id' => $budget->id]);

    expect($budget->details)->toHaveCount(3);
});

test('budget has many contract signatures', function () {
    $budget = Budget::factory()->create();
    $signatures = ContractSignature::factory()->count(2)->create(['budget_id' => $budget->id]);

    expect($budget->contractSignatures)->toHaveCount(2);
});

test('budget get status returns correct text for pending', function () {
    $budget = Budget::factory()->create(['status' => 0]);

    expect($budget->getStatus())->toBe('Pendiente');
});

test('budget get status returns correct text for approved', function () {
    $budget = Budget::factory()->create(['status' => 1]);

    expect($budget->getStatus())->toBe('Aprobado');
});

test('budget get status returns correct text for rejected', function () {
    $budget = Budget::factory()->create(['status' => 2]);

    expect($budget->getStatus())->toBe('Rechazado');
});

test('budget uses soft deletes', function () {
    $budget = Budget::factory()->create();
    $budgetId = $budget->id;

    $budget->delete();

    expect(Budget::find($budgetId))->toBeNull()
        ->and(Budget::withTrashed()->find($budgetId))->not->toBeNull();
});

test('budget fillable attributes are correct', function () {
    $fillable = (new Budget())->getFillable();

    expect($fillable)->toContain('client_id')
        ->and($fillable)->toContain('created_by')
        ->and($fillable)->toContain('products')
        ->and($fillable)->toContain('quantities')
        ->and($fillable)->toContain('status')
        ->and($fillable)->toContain('rejection_reason')
        ->and($fillable)->toContain('is_horeca');
});

test('budget status can be pending approved or rejected', function () {
    $pending = Budget::factory()->create(['status' => 0]);
    $approved = Budget::factory()->create(['status' => 1]);
    $rejected = Budget::factory()->create(['status' => 2]);

    expect($pending->status)->toBe(0)
        ->and($approved->status)->toBe(1)
        ->and($rejected->status)->toBe(2);
});

test('budget can have rejection reason when rejected', function () {
    $budget = Budget::factory()->create([
        'status' => 2,
        'rejection_reason' => 'Price too high',
    ]);

    expect($budget->rejection_reason)->toBe('Price too high')
        ->and($budget->status)->toBe(2);
});

test('budget can be horeca type', function () {
    $horecaBudget = Budget::factory()->create(['is_horeca' => true]);
    $regularBudget = Budget::factory()->create(['is_horeca' => false]);

    expect($horecaBudget->is_horeca)->toBeTrue()
        ->and($regularBudget->is_horeca)->toBeFalse();
});
