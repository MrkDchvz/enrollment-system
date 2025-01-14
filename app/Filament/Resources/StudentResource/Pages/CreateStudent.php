<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $password = $data['last_name'] . Carbon::now()->year;
        $user = User::create([
            'name' => trim(
                "{$data['first_name']} " .
                ($data['middle_name'] ? "{$data['middle_name']} " : "") .
                "{$data['last_name']}"),
            'email' => $data['email'],
            'password' => Hash::make($password),
            ]);
        $user->assignRole('Student');

        $user->email_verified_at = Date::now();
        $user->force_renew_password = true;
        $user->save();


        unset($data['email']);
        unset($data['password']);
        unset($data['password_confirmation']);

        $data['user_id'] = $user->id;

        return $data;
    }

}
