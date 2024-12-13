<?php

namespace Tests\Feature\Livewire;

use App\Filament\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Resources\StudentResource\Pages\EditStudent;
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

// Form Existence
it('student resource has a create form', function () {
    livewire(CreateStudent::class)
        ->assertFormExists();
});

it('can render page', function () {
    livewire(ListStudents::class)->assertSuccessful();
});

it('can render the edit page', function () {
    $record = Student::factory()->create();

    livewire(EditStudent::class, ['record' => $record->getRouteKey()])
        ->assertSuccessful();
});

it('can render column', function (string $column) {
    livewire(ListStudents::class)
        ->assertCanRenderTableColumn($column);
})->with(['student_number', 'full_name', 'email', 'gender', 'date_of_birth', 'contact_number', 'email']);


it('cannot display trashed student by default', function () {
    $newAdmin = User::factory()->create();
    $newAdmin->hasRole('Admin');
    $this->actingAs($newAdmin);

    $students = Student::factory()->count(4)->create();
    $studentPosts = Student::factory()->trashed()->count(6)->create();

    livewire(ListStudents::class)
        ->assertCanSeeTableRecords($students)
        ->assertCanNotSeeTableRecords($studentPosts)
        ->assertCountTableRecords(4);
});







