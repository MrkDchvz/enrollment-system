<?php

namespace Tests\Feature\Livewire;

use App\Filament\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Resources\StudentResource\Pages\EditStudent;
use App\Filament\Resources\StudentResource\Pages\ListStudents;
use App\Livewire\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Actions\DeleteAction;
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
})->with(['student_number', 'full_name', 'user.email', 'gender', 'date_of_birth', 'contact_number']);


it('cannot display trashed student by default', function () {
    $this->actingAsAdmin();

    $students = Student::factory()->count(4)->create();
    $studentPosts = Student::factory()->trashed()->count(6)->create();

//    Ensures that only non-trashed student records are visible in the student list table of student Resource
    livewire(ListStudents::class)
        ->assertCanSeeTableRecords($students)
        ->assertCanNotSeeTableRecords($studentPosts)
        ->assertCountTableRecords(4);
});

it('can sort column', function (string $column) {
    $this->actingAsAdmin();

    $records = Student::factory(5)->create();

    livewire(ListStudents::class)
        ->sortTable($column)
        ->assertCanSeeTableRecords($records->sortBy($column), inOrder: true)
        ->sortTable($column, 'desc')
        ->assertCanSeeTableRecords($records->sortByDesc($column), inOrder: true);
})->with(['full_name','date_of_birth']);


it('can search column', function (string $column) {
    $this->actingAsAdmin();

    $records = Student::factory(5)->create();

    $value = $records->first()->{$column};

    livewire(ListStudents::class)
        ->searchTable($value)
        ->assertCanSeeTableRecords($records->where($column, $value))
        ->assertCanNotSeeTableRecords($records->where($column, '!=', $value));
})->with(['student_number', 'full_name', 'email', 'contact_number']);


it('can create a record', function () {
    // NOTE: 'create' inserts the record to the database while 'make' creates a model instance but not insert it to the database.
    $record = Student::factory()->make();


    livewire(CreateStudent::class)
        ->fillForm([
            'student_number' => $record->student_number,
            'first_name' => $record->first_name,
            'middle_name' => $record->middle_name,
            'last_name' => $record->last_name,
            'gender' => $record->gender,
            'date_of_birth' => $record->date_of_birth,
            'contact_number' => $record->contact_number,
            // Student factory creates a user when its called
            // The create form also creates a new user before the form was submitted (MutateBeforeCreate Hook)
            // if $record->email is entered on the 'email' of this form will result a duplication because
            // an email is created in the factory then is assigned to 'email' of this form which will be assigned
            // on the MutateBeforeCreate Hook that also creates a User base on the input email ($data['email'])
            // for testing purposes a custom email is entered to avoid duplication
            'email' => 'testing@email.com',
            'address' => $record->address,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Student::class, [
        'student_number' => $record->student_number,
        'first_name' => $record->first_name,
        'middle_name' => $record->middle_name,
        'last_name' => $record->last_name,
        'gender' => $record->gender,
        'date_of_birth' => $record->date_of_birth,
        'contact_number' => $record->contact_number,
        'address' => $record->address,
    ]);

    $this->assertDatabaseHas(User::class, [
        'email' => $record->user->email,
    ]);

});

it('can update a record', function () {
    // NOTE: 'create' inserts the record to the database while 'make' creates a model instance but not insert it to the database.
    $record = Student::factory()->create();
    $newRecord = Student::factory()->make();

    livewire(EditStudent::class, ['record' => $record->getRouteKey()])
        ->fillForm([
            'first_name' => $newRecord->first_name,
            'middle_name' => $newRecord->middle_name,
            'last_name' => $newRecord->last_name,
            'gender' => $newRecord->gender,
            'date_of_birth' => $newRecord->date_of_birth,
            'contact_number' => $newRecord->contact_number,
            'address' => $newRecord->address,
        ])
        ->assertActionExists('save')
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Student::class, [
        'first_name' => $newRecord->first_name,
        'middle_name' => $newRecord->middle_name,
        'last_name' => $newRecord->last_name,
        'gender' => $newRecord->gender,
        'date_of_birth' => $newRecord->date_of_birth,
        'contact_number' => $newRecord->contact_number,
        'address' => $newRecord->address,
    ]);
});

it('can soft delete a record', function () {
    $this->actingAsAdmin();
    $record = Student::factory()->create();

    livewire(EditStudent::class, ['record' => $record->getRouteKey()])
        ->assertActionExists('delete')
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('students', [
        'id' => $record->id,
    ]);
});


it('should fail to create a student with a duplicate student number', function () {
    // Create an initial student record
    // NOTE: 'create' inserts the record to the database while 'make' creates a model instance but not insert it to the database.
    $existingStudent = Student::factory()->create();

    $existingStudentNumber = $existingStudent->student_number;
    livewire(CreateStudent::class)
        ->fillForm([
            'student_number' => $existingStudentNumber,
        ])
        ->call('create')
        ->assertHasErrors(); // Ensure the form has a unique validation error for student_number
});

it('fails validation when student_number does not match the regex pattern', function () {
    // Attempt to create a student with an invalid student_number
    livewire(CreateStudent::class)
        ->fillForm([
            'student_number' => '123456789',
        ])
        ->call('create')
        ->assertHasFormErrors();
});

it('passes validation when student_number matches the regex pattern', function () {
    // Attempt to create a student with a valid student_number
    livewire(CreateStudent::class)
        ->fillForm([
            'student_number' => '202211728',
        ])
        ->call('create')
        ->assertHasFormErrors();
});

it('should fail when an existing email is entered upon creation', function () {
    $existingStudent = Student::factory()->create();
    $existingEmail = $existingStudent->email;
    livewire(CreateStudent::class)
        ->fillForm([
            'student_number' => $existingStudent->student_number,
        ])
        ->call('create')
        ->assertHasFormErrors();
});













