<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Section;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array {
        // Assign the current logged admin/registrar as encoder of the enrollment
        $userId = auth()->id();
        $data['user_id'] = $userId;

        $currentDate = Carbon::now()->toDateString();
        $data['enrollment_date'] = $currentDate;

        $section = Section::find($data['section_id']);

        $departmentId = $section->department->id;
        $year_level = $section->year_level;



        $data['year_level'] = $year_level;
        $data['school_year'] = static::getCurrentSchoolYear();
        $data['department_id'] = $departmentId;


        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
}
