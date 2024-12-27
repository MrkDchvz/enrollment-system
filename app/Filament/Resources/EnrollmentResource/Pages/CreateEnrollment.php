<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array {
        // Assign the current logged admin/registrar as encoder of the enrollment
        $userId = auth()->id();
        $data['user_id'] = $userId;
        return $data;
    }
}
