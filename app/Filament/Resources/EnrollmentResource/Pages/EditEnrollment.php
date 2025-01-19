<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\EnrollmentFee;
use App\Models\Fee;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentNumberGenerator;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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

    protected function handleRecordUpdate(Model $record , array $data): Model {
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


        if (auth()->user()->hasRole('Registrar')) {
            $student = Student::find($record->student_id);
            $generator = StudentNumberGenerator::where('school_year', static::getCurrentSchoolYear())->firstOrCreate();
            if (!$student->student_number) {
                $student->student_number = $generator->studentNumber;
                $student->save();
                $data['old_new_student'] = 'New Student';

                $generator->iteration += 1;
                $generator->save();
            } else {
                $data['old_new_student'] = 'Old Student';
            }
        }


        $record->update($data);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $student = Student::find($data['student_id']);


        $data['student_name'] = $student->fullName;
        if (auth()->user()->hasRole('Registrar')) {
            if ($student->student_number) {
                $data['student_number'] = $student->student_number;
                $data['old_new_student'] = 'Old Student';
            } else {
                $data['student_number'] = StudentNumberGenerator::where('school_year', static::getCurrentSchoolYear())->firstOrCreate()->studentNumber;
                $data['old_new_student'] = 'New Student';

            }

        }



        $courses = Course::selectRaw('id as course_id, course_name, lecture_units, lab_units, lecture_hours, lab_hours')
            ->whereHas('courseEnrollments', function ($query) use ($data)  {
                $query
                    ->where('enrollment_id', $data['id']);
            })
            ->get()
            ->toArray();


        return $data;
    }

    public static function getCurrentSchoolYear() : string {
        // Current Date
        $date = Carbon::now();
        // Current Year $ Month
        $year = $date->year;
        $month = $date->month;
        // Set a new school year if the enrollment is in around august
        // If the year is 2024 and the student enrolled around august 2024
        // Then the school year will be 2024 - 2024
        if ($month >= 8) {
            $startYear = $date->year;
            $endYear = $date->year + 1;
        }
        // Retain the current school year if the enrollment is around february
        // If the year is 2024 and the student enrolled around february 2024
        // Then the school year is 2023-2024
        else {
            $startYear = $date->year - 1;
            $endYear = $date->year;
        }
        return trim(
            sprintf('%s-%s', $startYear, $endYear)
        );
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
