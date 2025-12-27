<?php

use App\Models\Tenant\TenantUser;
use App\Helpers\Lerph;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('tenant user can be created with required fields', function () {
    $user = TenantUser::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'rol_id' => 1,
    ]);

    expect($user)->toBeInstanceOf(TenantUser::class)
        ->and($user->name)->toBe('John')
        ->and($user->last_name)->toBe('Doe')
        ->and($user->email)->toBe('john@test.com');
});

test('tenant user full name attribute concatenates name and lastname', function () {
    $user = TenantUser::factory()->create([
        'name' => 'John',
        'last_name' => 'Doe',
    ]);

    expect($user->full_name)->toBe('John Doe');
});

test('tenant user avatar url returns default avatar when no picture', function () {
    $user = TenantUser::factory()->create([
        'name' => 'John Doe',
        'picture' => null,
    ]);

    expect($user->avatar_url)
        ->toBeString()
        ->toContain('ui-avatars.com')
        ->toContain('John+Doe');
});

test('tenant user rol tenant returns correct role name', function () {
    $adminUser = TenantUser::factory()->create(['rol_id' => 1]);
    $commercialUser = TenantUser::factory()->create(['rol_id' => 4]);

    expect($adminUser->rolTenant())->toBe('Administrador')
        ->and($commercialUser->rolTenant())->toBe('Comerciales');
});

test('tenant user uses soft deletes', function () {
    $user = TenantUser::factory()->create();
    $userId = $user->id;

    $user->delete();

    expect(TenantUser::find($userId))->toBeNull()
        ->and(TenantUser::withTrashed()->find($userId))->not->toBeNull();
});

test('tenant user fillable attributes are correct', function () {
    $fillable = (new TenantUser())->getFillable();

    expect($fillable)->toContain('name')
        ->and($fillable)->toContain('email')
        ->and($fillable)->toContain('password')
        ->and($fillable)->toContain('rol_id')
        ->and($fillable)->toContain('last_name')
        ->and($fillable)->toContain('phone');
});

test('tenant user email must be unique', function () {
    TenantUser::factory()->create(['email' => 'test@test.com']);

    expect(function () {
        TenantUser::factory()->create(['email' => 'test@test.com']);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

test('tenant user password is hidden in array', function () {
    $user = TenantUser::factory()->create(['password' => 'secret123']);
    $array = $user->toArray();

    expect($array)->not->toHaveKey('password');
});
