<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void {
        parent::setUp();

        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $role = Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Student']);

        $user->assignRole($role);

        $this->actingAs($user);

    }
    //
}
