<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEnrollment extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(Enrollment::query())
            ->defaultSort('created_at','desc')
            ->columns([
                TextColumn::make('student.student_number'),
                TextColumn::make('student.fullName'),
                TextColumn::make('section.sectionName'),
                TextColumn::make('semester'),
                TextColumn::make('school_year'),
                TextColumn::make('registration_status'),
                TextColumn::make('enrollment_date'),
            ]);
    }
    public static function canView(): bool
    {
        return auth()->user()->hasRole(['Admin', 'Registrar']);
    }
}
