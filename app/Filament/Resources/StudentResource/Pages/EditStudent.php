<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($record->email !== $data['email']) {
            $user = $record->user;
            $user->email = $data['email'];
            $user->save();
        }
        unset($data['email']);

        $data['last_edited_by_id'] = auth()->id();
        $record->update($data);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        $student = Student::where('student_number', $data['student_number'])->first();
        $data['email'] = $student->email;
        return $data;
    }

//    Redirect to table
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
