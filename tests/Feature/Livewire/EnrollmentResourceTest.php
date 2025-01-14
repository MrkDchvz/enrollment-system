<?php
namespace Tests\Feature\Livewire;
use App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments;
use App\Filament\Resources\EnrollmentResource\Pages\CreateEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Models\Enrollment;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use function Pest\Livewire\livewire;

beforeEach(function () {
    /* The TestCase setup generates a user before each test, so we need to clear the table to make sure we have a clean slate. */
    DB::table('users')->truncate();
    Role::firstOrCreate(
        ['name' => 'Student'],
        ['guard_name' => 'web']
    );
    Role::firstOrCreate(
        ['name' => 'Registrar'],
        ['guard_name' => 'web']
    );
    Role::firstOrCreate(
        ['name' => 'Officer'],
        ['guard_name' => 'web']
    );
    Role::firstOrCreate(
        ['name' => 'Faculty'],
        ['guard_name' => 'web']
    );
});

it('enrollment resource has a create form', function () {

    livewire(CreateEnrollment::class)
        ->assertFormExists();
});

//it ('can render list of enrollments', function () {
//    $this->actingAsAdmin();
//    livewire(ListEnrollments::class)->assertSuccessful();
//});

it ('can render the edit page', function () {
    $record = Enrollment::factory()->create();
    livewire(EditEnrollment::class, ['record' => $record->getRouteKey()])
        ->assertSuccessful();
});

//it ('can render column', function (string $column) {
//    livewire(ListEnrollments::class)
//        ->assertCanRenderTableColumn($column);
//})->with([  'student.student_number',
//            'student.fullName',
//            'registration_status',
//            'enrollment_date',
//            'department.department_code',
//            'year_level',
//            'semester',
//            'section.sectionName',
//            'school_year',
//            'old_new_student',
//            'user.name']);

//it('cannot display trashed enrollment by default', function () {
//    $this->actingAsAdmin();
//
//    $enrollments = Enrollment::factory()->count(4)->create();
//    $trashedEnrollments = Enrollment::factory()->trashed()->count(6)->create();
//
//    livewire(ListEnrollments::class)
//        ->assertCanSeeTableRecords($enrollments)
//        ->assertCanNotSeeTableRecords($trashedEnrollments)
//        ->assertCountTableRecords(4);
//});

//it('can search column', function (string $column) {
//   $this->actingAsAdmin();
//
//   $records = Enrollment::factory(5)->create();
//
//   $value = $records->first()->{$column};
//
//   livewire(ListEnrollments::class)
//       ->searchTable($value)
//       ->assertCanSeeTableRecords($records->where($column, $value))
//       ->assertCanNotSeeTableRecords($records->where($column, '!=' ,$value));
//})->with(['student_number', 'fullName', 'name']);

it('can search records', function () {
    // NOTE: 'create' inserts the record to the database while 'make' creates a model instance but not insert it to the database.
    $record = enrollment::factory()->make();

    livewire(CreateEnrollment::class)
        ->fillForm([
            'student_id' => $record->student_id,
            'registration_status' => $record->registration_status,
            'semester' => $record->semester,
            'section_id' => $record->section_id,
            'old_new_student' => $record->old_new_student,
            'school_year' => $record->school_year,
        ]);
});

//it('can update record', function () {
//    $record = Enrollment::factory()->create();
//    $newRecord = Enrollment::factory()->make();
//
//    $attributes = $newRecord->getAttributes();
//
//
//
//    livewire(EditEnrollment::class, ['record' => $record->getRouteKey()])
//        ->fillForm([
//            'student_id' => $newRecord->student_id,
//            'registration_status' => $newRecord->registration_status,
//            'semester' => $newRecord->semester,
//            'section_id' => $newRecord->section_id,
//            'old_new_student' => $record->old_new_student,
//            'school_year' => $record->school_year,
//        ])
//        ->assertActionExists('save')
//        ->call('save')
//        ->assertHasNoFormErrors();
//
//    $this->assertDatabaseHas('enrollments', $filteredAttributes);
//});

it('can soft delete a record', function () {
    $this->actingAsAdmin();
    $record = Enrollment::factory()->create();

    livewire(EditEnrollment::class, ['record' => $record->getRouteKey()])
        ->assertActionExists('delete')
        ->callAction(DeleteAction::class);

    $this->assertSoftDeleted('enrollments', [
        'id' => $record->id,
    ]);
});

it('can validate required', function(string $column) {
    livewire(CreateEnrollment::class)
        ->fillForm([$column => null])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasFormErrors([$column => ['required']]);
})->with(['student_id', 'registration_status', 'semester', 'old_new_student', 'section_id']);

