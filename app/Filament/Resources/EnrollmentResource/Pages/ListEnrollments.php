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
                ->badge(Enrollment::where('year_level', '1st Year')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('year_level', '1st Year'));
            $tabs['2nd Year'] = Tab::make('2nd Year')
                ->badge(Enrollment::where('year_level', '2nd Year')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('year_level', '2nd Year'));
            $tabs['3rd Year'] = Tab::make('3rd Year')
                ->badge(Enrollment::where('year_level', '3rd Year')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('year_level', '3rd Year'));
            $tabs['4th Year'] = Tab::make('4th Year')
                ->badge(Enrollment::where('year_level', '4th Year')->count())
                ->modifyQueryUsing(fn ($query) => $query->where('year_level', '4th Year'));
        }
        if (auth()->user()->hasRole(['Admin'])) {
            $tabs['trashed'] = Tab::make('Trashed')
                ->badge(Enrollment::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed());
        }

        return $tabs;
    }



}
