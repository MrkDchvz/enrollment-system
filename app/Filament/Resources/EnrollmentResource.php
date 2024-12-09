<?php

namespace App\Filament\Resources;

use App\Enums\RegistrationStatus;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getDetailsFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema() : array {
        return [
            Forms\Components\Select::make('student_id')
                ->label('Student Number')
                ->relationship('student', 'student_number')
                ->required()
                ->reactive()
                ->preload()
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    $set('student_name', Student::class::find($state)->full_name ?? '');
                    $set('semester', static::getCurrentSemester());
                    // Retrieve the latest/last enrollment of the selected student
                    $latestEnrollment = Student::class::find($state)->enrollments()->latest()->first();

                    if ($latestEnrollment) {
                        $latestSemester = $latestEnrollment->semester;
                        $latestYearLevel = $latestEnrollment->year_level;

                        $newYearLevel = $latestSemester === "2nd Semester" ? static::incrementYearLevel($latestYearLevel) : $latestYearLevel;
                        // Assign default year level based on latest enrollment data of student
                        // Retains the year level if the last record is on 1st semester
                        $set('year_level', $newYearLevel);
                        $set('registration_status', $latestEnrollment->registration_status);
                        $set('old_new_student', 'Old Student');
                        $set('department_id', $latestEnrollment->department_id);
                    }
                    // Assumes that the student is a new first year student
                    else {
                        $set('old_new_student', 'New Student');
                        $set('year_level', "1st Year");
                        $set('registration_status', 'REGULAR');
                    }

                })
                ->searchable()
                ->native(false),

            Forms\Components\TextInput::make('student_name')
                ->label('Student Name')
                ->disabled(),

            Forms\Components\ToggleButtons::make('registration_status')
                ->inline()
                ->label('Registration Status')
                ->options(RegistrationStatus::class)
                ->required(),
            Forms\Components\ToggleButtons::make('year_level')
                ->label('Year Level')
                ->options([
                    '1st Year' => '1st Year',
                    '2nd Year' => '2nd Year',
                    '3rd Year' => '3rd Year',
                    '4th Year' => '4th Year'])
                ->colors([
                    '1st Year' => 'success',
                    '2nd Year' => 'info',
                    '3rd Year' => 'warning',
                    '4th Year' => 'danger',
                ])
                ->inline()
                ->required(),
            Forms\Components\ToggleButtons::make('semester')
            ->label('Semester')
            ->options([
                '1st Semester' => '1st Semester',
                '2nd Semester' => '2nd Semester',
            ])
            ->colors([
                '1st Semester' => 'info',
                '2nd Semester' => 'success',
                ])
            ->inline()
            ->required(),
            Forms\Components\TextInput::make('school_year')
            ->label('School Year')
            ->default(static::generateCurrentSchoolYear())
            ->disabled()
            ->dehydrated()
            ->required(),
            Forms\Components\TextInput::make('old_new_student')
            ->label('Old/New Student')
            ->disabled()
            ->dehydrated(),
            Forms\Components\Select::make('department_id')
                ->label('Department')
                ->options(Department::all()->pluck('department_code', 'id')),
        ];

    }

    public static function generateCurrentSchoolYear() : string {
        $currentYear = Carbon::now()->year;
        $nextYear = Carbon::now()->addYear()->year;
        return "{$currentYear} - {$nextYear}";
    }

    public static function incrementYearLevel($latestYearLevel) : string {
        $yearLevelPipeline = [
            "1st Year" => "2nd Year",
            "2nd Year" => "3rd Year",
            "3rd Year" => "4th Year",
        ];

        return $yearLevelPipeline[$latestYearLevel] ?? $latestYearLevel;
    }

    public static function getCurrentSemester() : string {
        $currentMonth = Carbon::now()->month;
        // 1st Semester is around September to February
        if ($currentMonth >= 9 || $currentMonth <= 2) {
            return "1st Semester";
        // 2nd Semester is around March to June
        } else {
            return "2nd Semester";
        }
    }


}