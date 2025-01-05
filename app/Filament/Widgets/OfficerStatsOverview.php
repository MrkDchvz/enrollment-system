<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OfficerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Students', Student::query()->count())
                ->description('Total Number of Students')
                ->descriptionIcon('heroicon-s-user-group', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('warning'),
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->hasRole(['Officer']);
    }
}
