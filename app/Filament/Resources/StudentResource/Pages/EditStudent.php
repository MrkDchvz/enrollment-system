<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['email'])) {
            $student = Student::where('student_number', $data['student_number'])->first();
            if ($student->user) {
                $student->email = $data['email'];
            }
            unset($data['email']);
        }
        $data['last_edited_by_id'] = auth()->id();
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $student = Student::where('student_number', $data['student_number'])->first();
        $data['email'] = $student->email;
        return $data;
    }
}
