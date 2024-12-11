<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];
        if (auth()->user()->hasRole('Admin')) {
            $tabs['all'] = Tab::make('All Students')
                ->badge(Student::count());

            $tabs['trashed'] = Tab::make('Trashed')
                ->badge(Student::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }
        return $tabs;
    }
}
