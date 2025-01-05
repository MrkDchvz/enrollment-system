<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrarStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Enrollments', Enrollment::query()->count())
                ->description('Total Number of Enrollments')
                ->descriptionIcon('heroicon-s-academic-cap', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('primary'),
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->hasRole(['Registrar']);
    }
}
