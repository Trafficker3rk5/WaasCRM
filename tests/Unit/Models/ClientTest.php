<?php

use App\Models\Tenant\Client;
use App\Models\Tenant\Catalog;
use App\Models\Tenant\Address;
use App\Models\Tenant\Budget;
use App\Models\Tenant\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup multi-tenancy context if needed
    // This would depend on your tenancy setup
});

test('client can be created with required fields', function () {
    $client = Client::factory()->create([
        'company_name' => 'Test Company',
        'contact_name' => 'John',
        'contact_lastname' => 'Doe',
        'email' => 'john@test.com',
        'phone' => '123456789',
        'is_client' => true,
    ]);

    expect($client)->toBeInstanceOf(Client::class)
        ->and($client->company_name)->toBe('Test Company')
        ->and($client->email)->toBe('john@test.com')
        ->and($client->is_client)->toBeTrue();
});

test('client full name attribute concatenates contact name and lastname', function () {
    $client = Client::factory()->create([
        'contact_name' => 'John',
        'contact_lastname' => 'Doe',
    ]);

    expect($client->full_name)->toBe('John Doe');
});

test('client logo url returns default avatar when no logo', function () {
    $client = Client::factory()->create([
        'company_name' => 'Test Company',
        'logo' => null,
    ]);

    expect($client->logo_url)
        ->toBeString()
        ->toContain('ui-avatars.com');
});

test('client has origin relationship', function () {
    $origin = Catalog::factory()->create(['type' => 1]);
    $client = Client::factory()->create(['origin_id' => $origin->id]);

    expect($client->origin)->toBeInstanceOf(Catalog::class)
        ->and($client->origin->id)->toBe($origin->id);
});

test('client has status relationship', function () {
    $status = Catalog::factory()->create(['type' => 2]);
    $client = Client::factory()->create(['status_id' => $status->id]);

    expect($client->status)->toBeInstanceOf(Catalog::class)
        ->and($client->status->id)->toBe($status->id);
});

test('client has activity relationship', function () {
    $activity = Catalog::factory()->create(['type' => 5]);
    $client = Client::factory()->create(['activity_id' => $activity->id]);

    expect($client->activity)->toBeInstanceOf(Catalog::class)
        ->and($client->activity->id)->toBe($activity->id);
});

test('client can have multiple addresses', function () {
    $client = Client::factory()->create();
    $address1 = Address::factory()->create();
    $address2 = Address::factory()->create();

    $client->addresses()->attach([$address1->id, $address2->id]);

    expect($client->addresses)->toHaveCount(2);
});

test('client can have multiple budgets', function () {
    $client = Client::factory()->create();
    $budgets = Budget::factory()->count(3)->create(['client_id' => $client->id]);

    expect($client->budgets)->toHaveCount(3);
});

test('client can have multiple tasks', function () {
    $client = Client::factory()->create();
    $tasks = Task::factory()->count(2)->create(['client_id' => $client->id]);

    expect($client->tasks)->toHaveCount(2);
});

test('client budget lights returns correct counts', function () {
    $client = Client::factory()->create();

    // Create budgets with different statuses
    Budget::factory()->create(['client_id' => $client->id, 'status' => 0]); // Pending
    Budget::factory()->create(['client_id' => $client->id, 'status' => 0]); // Pending
    Budget::factory()->create(['client_id' => $client->id, 'status' => 1]); // Approved
    Budget::factory()->create(['client_id' => $client->id, 'status' => 2]); // Rejected

    $lights = $client->budgetsLigths();

    expect($lights['pendings'])->toBe(2)
        ->and($lights['approved'])->toBe(1)
        ->and($lights['rejected'])->toBe(1)
        ->and($lights['total'])->toBe(4);
});

test('client tasks lights returns correct counts', function () {
    $client = Client::factory()->create();

    // Create tasks with different statuses
    Task::factory()->create(['client_id' => $client->id, 'status' => 0]); // Pending
    Task::factory()->create(['client_id' => $client->id, 'status' => 1]); // Approved
    Task::factory()->create(['client_id' => $client->id, 'status' => 1]); // Approved
    Task::factory()->create(['client_id' => $client->id, 'status' => 2]); // Rejected

    $lights = $client->tasksLights();

    expect($lights['pendings'])->toBe(1)
        ->and($lights['approved'])->toBe(2)
        ->and($lights['rejected'])->toBe(1)
        ->and($lights['total'])->toBe(4);
});

test('client is expired returns correct values based on creation date', function () {
    // Recent client (< 30 days)
    $recentClient = Client::factory()->create();
    expect($recentClient->isExpired())->toBe(0);

    // Old client (> 30 days)
    $oldClient = Client::factory()->create([
        'created_at' => now()->subDays(45),
    ]);
    expect($oldClient->isExpired())->toBe(1);

    // Very old client (> 60 days)
    $veryOldClient = Client::factory()->create([
        'created_at' => now()->subDays(75),
    ]);
    expect($veryOldClient->isExpired())->toBe(2);
});

test('client uses soft deletes', function () {
    $client = Client::factory()->create();
    $clientId = $client->id;

    $client->delete();

    expect(Client::find($clientId))->toBeNull()
        ->and(Client::withTrashed()->find($clientId))->not->toBeNull();
});

test('client fillable attributes are correct', function () {
    $fillable = (new Client())->getFillable();

    expect($fillable)->toContain('company_name')
        ->and($fillable)->toContain('email')
        ->and($fillable)->toContain('phone')
        ->and($fillable)->toContain('is_client')
        ->and($fillable)->toContain('status_id')
        ->and($fillable)->toContain('origin_id')
        ->and($fillable)->toContain('activity_id')
        ->and($fillable)->not->toContain('id');
});
