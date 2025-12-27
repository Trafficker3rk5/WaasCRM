<?php

use App\Models\Tenant\Catalog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('catalog can be created with required fields', function () {
    $catalog = Catalog::factory()->create([
        'type' => 1,
        'name' => 'Web Campaign',
        'description' => 'Client found us through website',
    ]);

    expect($catalog)->toBeInstanceOf(Catalog::class)
        ->and($catalog->type)->toBe(1)
        ->and($catalog->name)->toBe('Web Campaign');
});

test('catalog type can represent different catalog types', function () {
    $origin = Catalog::factory()->create(['type' => 1]);
    $status = Catalog::factory()->create(['type' => 2]);
    $taskType = Catalog::factory()->create(['type' => 3]);
    $appreciation = Catalog::factory()->create(['type' => 4]);
    $activity = Catalog::factory()->create(['type' => 5]);

    expect($origin->type)->toBe(1)
        ->and($status->type)->toBe(2)
        ->and($taskType->type)->toBe(3)
        ->and($appreciation->type)->toBe(4)
        ->and($activity->type)->toBe(5);
});

test('catalog has name and description', function () {
    $catalog = Catalog::factory()->create([
        'name' => 'Cold Call',
        'description' => 'First contact via phone call',
    ]);

    expect($catalog->name)->toBe('Cold Call')
        ->and($catalog->description)->toBe('First contact via phone call');
});

test('catalog can have extra field', function () {
    $catalog = Catalog::factory()->create([
        'extra_1' => 'Additional information',
    ]);

    expect($catalog->extra_1)->toBe('Additional information');
});

test('catalog uses soft deletes', function () {
    $catalog = Catalog::factory()->create();
    $catalogId = $catalog->id;

    $catalog->delete();

    expect(Catalog::find($catalogId))->toBeNull()
        ->and(Catalog::withTrashed()->find($catalogId))->not->toBeNull();
});

test('catalog does not have timestamps', function () {
    $catalog = new Catalog();

    expect($catalog->timestamps)->toBeFalse();
});

test('catalog fillable attributes are correct', function () {
    $fillable = (new Catalog())->getFillable();

    expect($fillable)->toContain('type')
        ->and($fillable)->toContain('name')
        ->and($fillable)->toContain('description')
        ->and($fillable)->toContain('extra_1')
        ->and($fillable)->not->toContain('id');
});

test('catalog can be queried by type', function () {
    Catalog::factory()->create(['type' => 1, 'name' => 'Origin 1']);
    Catalog::factory()->create(['type' => 1, 'name' => 'Origin 2']);
    Catalog::factory()->create(['type' => 2, 'name' => 'Status 1']);

    $origins = Catalog::where('type', 1)->get();
    $statuses = Catalog::where('type', 2)->get();

    expect($origins)->toHaveCount(2)
        ->and($statuses)->toHaveCount(1);
});
