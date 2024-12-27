<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];
        if (auth()->user()->hasRole(['Admin','Registrar'])) {
            $tabs['all'] = Tab::make('All Enrollments')
                ->badge(Enrollment::count());
            $tabs['1st Year'] = Tab::make('1st Year')
                ->badge(Enrollment::whereHas('section', fn($query) => $query->where('year_level', 1))->count())
                ->modifyQueryUsing(fn ($query) => $query->whereHas('section', fn($query) => $query->where('year_level', 1)));
            $tabs['2nd Year'] = Tab::make('2nd Year')
                ->badge(Enrollment::whereHas('section', fn($query) => $query->where('year_level', 2))->count())
                ->modifyQueryUsing(fn ($query) => $query->whereHas('section', fn($query) => $query->where('year_level', 2)));
            $tabs['3rd Year'] = Tab::make('3rd Year')
                ->badge(Enrollment::whereHas('section', fn($query) => $query->where('year_level', 3))->count())
                ->modifyQueryUsing(fn ($query) => $query->whereHas('section', fn($query) => $query->where('year_level', 3)));
            $tabs['4th Year'] = Tab::make('4th Year')
                ->badge(Enrollment::whereHas('section', fn($query) => $query->where('year_level', 4))->count())
                ->modifyQueryUsing(fn ($query) => $query->whereHas('section', fn($query) => $query->where('year_level', 4)));
            $tabs['trashed'] = Tab::make('Trashed')
                ->badge(Enrollment::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }

}
