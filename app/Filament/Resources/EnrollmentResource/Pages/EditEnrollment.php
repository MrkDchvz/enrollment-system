<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Course;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
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
}
