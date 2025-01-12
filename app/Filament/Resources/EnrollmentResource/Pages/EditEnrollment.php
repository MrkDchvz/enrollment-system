<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\EnrollmentFee;
use App\Models\Fee;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    use  \EightyNine\Approvals\Traits\HasApprovalHeaderActions;

    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array {
//        dd(User::role('Registrar')->get());

        $section = Section::find($data['section_id']);

        $departmentId = $section->department->id;
        $year_level = $section->year_level;

        $data['year_level'] = $year_level;
        $data['department_id'] = $departmentId;

        // Automatically populate fees when faculty finished advising
        if (auth()->user()->hasRole('Faculty')) {
            $fees = static::populateFees($year_level);

            foreach ($fees as $fee) {
                EnrollmentFee::updateOrCreate([
                    'enrollment_id' => $this->record->id,
                    'fee_id' => $fee['id'],
                ],
                [
                    'amount' => $fee['amount'],
                ]);
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $student = Student::find($data['student_id']);
        $data['student_name'] = $student->fullName;

        $courses = Course::selectRaw('id as course_id, course_name, lecture_units, lab_units, lecture_hours, lab_hours')
            ->whereHas('courseEnrollments', function ($query) use ($data)  {
                $query
                    ->where('enrollment_id', $data['id']);
            })
            ->get()
            ->toArray();

        return $data;
    }

    public static function populateFees($year_level) {
        $fees = [];
        switch ($year_level) {
            case '1st Year':
                $fees = Fee::selectRaw('id, amount')
                    ->whereNot('name', 'Late Reg.')
                    ->get()
                    ->toArray();
                break;
            case '3rd Year':
            case '4th Year':
            case '2nd Year':
                $fees  = Fee::selectRaw('id, amount')
                    ->whereNot('name', 'NSTP')
                    ->whereNot('name', 'Late Reg.')
                    ->get()
                    ->toArray();
                break;
            default:
                throw new InvalidArgumentException("Invalid year level: $year_level");
                break;
        }
        return $fees;
    }

}
