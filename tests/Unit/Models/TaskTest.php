<?php

use App\Models\Tenant\Task;
use App\Models\Tenant\Client;
use App\Models\Tenant\TenantUser;
use App\Models\Tenant\Catalog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('task can be created with required fields', function () {
    $creator = TenantUser::factory()->create();
    $assignee = TenantUser::factory()->create();
    $client = Client::factory()->create();

    $task = Task::factory()->create([
        'created_by' => $creator->id,
        'assigned_to' => $assignee->id,
        'client_id' => $client->id,
        'title' => 'Follow up call',
        'description' => 'Call client about budget',
        'status' => 0,
    ]);

    expect($task)->toBeInstanceOf(Task::class)
        ->and($task->title)->toBe('Follow up call')
        ->and($task->status)->toBe(0);
});

test('task belongs to creator user', function () {
    $creator = TenantUser::factory()->create();
    $task = Task::factory()->create(['created_by' => $creator->id]);

    expect($task->createdBy)->toBeInstanceOf(TenantUser::class)
        ->and($task->createdBy->id)->toBe($creator->id);
});

test('task belongs to assigned user', function () {
    $assignee = TenantUser::factory()->create();
    $task = Task::factory()->create(['assigned_to' => $assignee->id]);

    expect($task->assignedTo)->toBeInstanceOf(TenantUser::class)
        ->and($task->assignedTo->id)->toBe($assignee->id);
});

test('task belongs to client', function () {
    $client = Client::factory()->create();
    $task = Task::factory()->create(['client_id' => $client->id]);

    expect($task->client)->toBeInstanceOf(Client::class)
        ->and($task->client->id)->toBe($client->id);
});

test('task belongs to type catalog', function () {
    $type = Catalog::factory()->create(['type' => 3]);
    $task = Task::factory()->create(['type_id' => $type->id]);

    expect($task->type)->toBeInstanceOf(Catalog::class)
        ->and($task->type->id)->toBe($type->id);
});

test('task belongs to appreciation catalog', function () {
    $appreciation = Catalog::factory()->create(['type' => 4]);
    $task = Task::factory()->create(['appreciation_id' => $appreciation->id]);

    expect($task->appreciation)->toBeInstanceOf(Catalog::class)
        ->and($task->appreciation->id)->toBe($appreciation->id);
});

test('task can have date and date end', function () {
    $startDate = now()->addDay();
    $endDate = now()->addDays(3);

    $task = Task::factory()->create([
        'date' => $startDate,
        'date_end' => $endDate,
    ]);

    expect($task->date)->toBeInstanceOf(DateTime::class)
        ->and($task->date_end)->toBeInstanceOf(DateTime::class);
});

test('task status can be pending completed or cancelled', function () {
    $pending = Task::factory()->create(['status' => 0]);
    $completed = Task::factory()->create(['status' => 1]);
    $cancelled = Task::factory()->create(['status' => 2]);

    expect($pending->status)->toBe(0)
        ->and($completed->status)->toBe(1)
        ->and($cancelled->status)->toBe(2);
});

test('task fillable attributes are correct', function () {
    $fillable = (new Task())->getFillable();

    expect($fillable)->toContain('created_by')
        ->and($fillable)->toContain('assigned_to')
        ->and($fillable)->toContain('date')
        ->and($fillable)->toContain('date_end')
        ->and($fillable)->toContain('title')
        ->and($fillable)->toContain('description')
        ->and($fillable)->toContain('client_id')
        ->and($fillable)->toContain('status')
        ->and($fillable)->toContain('type_id')
        ->and($fillable)->toContain('appreciation_id');
});

test('task can exist without client', function () {
    $task = Task::factory()->create(['client_id' => null]);

    expect($task->client_id)->toBeNull()
        ->and($task->client)->toBeNull();
});

test('task title and description can be set', function () {
    $task = Task::factory()->create([
        'title' => 'Important meeting',
        'description' => 'Discuss contract terms with client',
    ]);

    expect($task->title)->toBe('Important meeting')
        ->and($task->description)->toBe('Discuss contract terms with client');
});
