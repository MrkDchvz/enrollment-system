<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;



    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->formId('form'),
        ];
    }


    protected function mutateFormDataBeforeCreate(array $data): array {
        $userId = auth()->id();
        $school_year = static::getCurrentSchoolYear();
        $semester = static::getCurrentSemester();
        // Assign the current logged admin/registrar as encoder of the enrollment
        if (auth()->user()->HasRole('Admin')) {

            $data['user_id'] = $userId;

            $section = Section::find($data['section_id']);

            $departmentId = $section->department->id;
            $year_level = $section->year_level;


            $data['year_level'] = $year_level;
            $data['department_id'] = $departmentId;
        }

        if (auth()->user()->HasRole('Student')) {
            $studentId = Student::where('user_id', $userId)->first()->id;
            $data['student_id'] = $studentId;
            $onGoingEnrollment = Enrollment::where('student_id', $userId)
                ->where('school_year', $school_year)
                ->where('semester', $semester)
                ->whereHas('approvalStatus', function ($query) {
                    $query->whereIn('status', ['Approved', 'Created', 'Submitted']);
                })
                ->exists();
//            dd(Enrollment::where('student_id', $userId)
//                ->where('school_year', $school_year)
//                ->where('semester', $semester)
//                ->whereHas('approvalStatus', function ($query) {
//                    $query->whereIn('status', ['Approved', 'Created', 'Submitted']);
//                })
//                ->first()->approvals);
            if ($onGoingEnrollment) {
                Notification::make()
                    ->warning()
                    ->title('Unable to create a new enrollment.')
                    ->body('Your enrollment is already being processed.')
                    ->persistent()
                    ->send();
                $this->halt();
            }
        }



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

    public static function getCurrentSemester() : string {
        $currentMonth = Carbon::now()->month;
        // 1st Semester is around September to February
        if ($currentMonth >= 9 || $currentMonth <= 2) {
            return "1st Semester";
            // 2nd Semester is around March to June
        } else {
            return "2nd Semester";
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }





}
