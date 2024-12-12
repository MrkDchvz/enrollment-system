<?php

namespace Tests\Feature\Livewire;

use App\Filament\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Resources\StudentResource\Pages\ListStudents;
use App\Livewire\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Pages\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

use function Pest\Livewire\livewire;
use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
    /* The TestCase setup generates a user before each test, so we need to clear the table to make sure we have a clean slate. */
    DB::table('users')->truncate();
});
// Admin Login w/ Valid Credentials
it ('can login admin with valid credentials', function () {
    auth()->logout();
    $adminUser = User::factory()->create([
        'password' => Hash::make('password')
    ]);


    $adminUser->assignRole('Admin');

    livewire(Login::class)
        ->fillForm([
            'email' => $adminUser->email,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors();

    assertEquals($adminUser->id, auth()->id());
});

// Admin Login w/ Invalid Credentials
it ('cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password')
    ]);
    $role = Role::create(['name' => 'Admin']);

    $user->assignRole($role);

    livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'incorrect-password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors();

});
// Admin Login w/ Soft Delete
// Student Login w/ Valid Credentials
// Student Login w/ Invalid Credentials
// Student Login w/ Soft Delete


// Form Existence
it('student resource has a create form', function () {
    livewire(CreateStudent::class)
        ->assertFormExists();
});

it('can render page', function () {
    livewire(ListStudents::class)->assertSuccessful();
});








