<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestStudent extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query())
            ->defaultSort('created_at','desc')
            ->columns([
                TextColumn::make('student_number'),
                TextColumn::make('fullName'),
                TextColumn::make('gender'),
                TextColumn::make('date_of_birth')
                    ->date('F j, Y'),
                TextColumn::make('email'),
                TextColumn::make('contact_number'),
                TextColumn::make('address'),
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['Admin', 'Officer']);
    }
}
