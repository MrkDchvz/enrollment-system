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
        $school_year = $section->school_year;
        $year_level = $section->year_level;

        $data['school_year'] = $school_year;
        $data['year_level'] = $year_level;
        $data['department_id'] = $departmentId;

        return $data;
    }
}
