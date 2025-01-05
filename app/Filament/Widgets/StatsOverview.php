<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Student;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

// WIDGET FOR ADMINS
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Enrollments', Enrollment::query()->count())
            ->description('Total Number of Enrollments')
            ->descriptionIcon('heroicon-s-academic-cap', IconPosition::Before)
            ->chart([1,3,5,10,20,40])
            ->color('primary'),
            Stat::make('Students', Student::query()->count())
                ->description('Total Number of Students')
                ->descriptionIcon('heroicon-s-user-group', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('warning'),
            Stat::make('Instructors', Instructor::query()->count())
                ->description('Total Number of Instructors')
                ->descriptionIcon('heroicon-s-briefcase', IconPosition::Before)
                ->chart([1,3,5,10,20,40])
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['Admin']);
    }
}
