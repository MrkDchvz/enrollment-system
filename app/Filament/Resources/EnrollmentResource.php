<?php

namespace App\Filament\Resources;

use App\Enums\RegistrationStatus;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\Section;
use App\Models\Student;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Symfony\Component\Yaml\Tag\TaggedValue;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Student Information')
                ->schema(static::getDetailsFormSchema()),
                Forms\Components\Section::make('Courses')
                ->schema([static::getCourseRepeater()]),
                Forms\Components\Section::make('Fees')
                    ->schema([static::getFeeRepeater()]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.student_number')
                    ->label('Student Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Full Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.department_code')
                    ->label('Department')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'BSCS' => 'danger',
                        'BSIT' => 'success',

                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('registration_status')
                    ->label('Registration Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'IRREGULAR' => 'info',
                        'REGULAR' => 'success',
                    })
                    ->icon(fn (string $state): ?string => match ($state) {
                        'IRREGULAR' => 'heroicon-o-arrow-path-rounded-square', // Icon for irregular
                        'REGULAR' => 'heroicon-o-check',        // Icon for regular
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label('Enrollment Date')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('year_level')
                ->label('Year Level')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('semester')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('section.sectionName')
                    ->label('Section')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('school_year')
                    ->label('School Year')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('old_new_student')
                    ->label('Old/New Student')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Encoder')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('semester')
                    // Key (in the database) => Display in the forms
                    ->options([
                        '1st Semester' => '1st Semester',
                        '2nd Semester' => '2nd Semester',
                    ]),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'department_code')
                    ->preload(),
                SelectFilter::make('school_year')
                    ->label('School Year')
                    ->options(function () {
                        return Enrollment::select('school_year')
                                ->distinct()
                                ->pluck('school_year', 'school_year');
                    })
                    ->searchable(),
                SelectFilter::make('old_new_student')
                    ->label('Old /New Student')


            ])
            ->actions([
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->trashed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('Student')) {
            $student = Student::where('user_id', auth()->id())->firstOrFail();
            return parent::getEloquentQuery()->where('student_id', $student->id);
        } else {
            return parent::getEloquentQuery();
        }

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
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Student Number')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search): array => Student::where('student_number', 'like', "%{$search}%")->limit(5)->pluck('student_number', 'id')->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => Student::find($value)?->student_number)
                    ->required()
                    ->reactive()
                    ->preload()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\get $get) {
                        if($state) {
                            // Default Values
                            $currentSemester =  static::getCurrentSemester();
                            $newYearLevel = "1st Year";
                            // Initialize read-only fields
                            $set('student_name', Student::class::find($state)->full_name ?? '');
                            $set('semester', $currentSemester);

                            // Retrieve the latest/last enrollment of the selected student
                            $lastEnrollment = Student::class::find($state)->enrollments()->latest()->first();

                            if ($lastEnrollment) {
                                // This is used to calculate the newyearlevel ONLY
                                $lastSemester = $lastEnrollment->semester;
                                $lastYearLevel = $lastEnrollment->year_level;
                                $lastDepartment = $lastEnrollment->department_id;
                                $lastRegistrationStatus = $lastEnrollment->registration_status;

                                // If the last/latest enrollment is 2nd semester move the year level up by 1
                                $newYearLevel = self::incrementYearLevel($lastYearLevel, $lastSemester);
                                // Assign default year level based on latest enrollment data of student
                                // Retains the year level if the last record is on 1st semester
                                $set('year_level', $newYearLevel);
                                $set('registration_status', $lastRegistrationStatus);
                                $set('old_new_student', 'Old Student');
                                $set('department_id', $lastDepartment);
                                // Autofill courses base on Department, Year_level, and semester
                                $set('courseEnrollments', static::populateCourse(
                                    $currentSemester,
                                    $lastDepartment,
                                    $newYearLevel
                                ));

                            }
                            // Assumes that the student is a new first year student if last enrollment is not found
                            // In other words if a student has no enrollment data the system will assume that the student is 1st year
                            else {
                                $set('old_new_student', 'New Student');
                                $set('year_level', $newYearLevel);
                                $set('registration_status', 'REGULAR');
                            }
                            // Autofill fees base on semester
                            $set('enrollmentFees', static::populateFees($newYearLevel));

                        // clear fields if the student number is cleared
                        } else {
                            $set('student_name', '');
                            $set('semester', '');
                            $set('year_level', '');
                            $set('registration_status', '');
                            $set('old_new_student', '');
                            $set('department_id', null);
                        }

                    })
                    ->native(false),
                Forms\Components\TextInput::make('student_name')
                    ->label('Student Name')
                    ->disabled(),
            ]),
            Forms\Components\Grid::make(3)->schema([
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
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\get $get) {
                        $set('courseEnrollments', static::populateCourse(
                            $get('semester'),
                            $get('department_id'),
                            $get('year_level')
                        ));
                        $set('enrollmentFees', static::populateFees($state));

                    }),

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
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\get $get) {
                        $set('courseEnrollments', static::populateCourse(
                            $get('semester'),
                            $get('department_id'),
                            $get('year_level')
                        ));
                    }),
            ]),

            Forms\Components\Select::make('department_id')
                ->label('Department')
                ->options(Department::all()->pluck('department_code', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\get $get) {
                    $set('courseEnrollments', static::populateCourse(
                        $get('semester'),
                        $get('department_id'),
                        $get('year_level')
                    ));
                }),
        Forms\Components\TextInput::make('school_year')
            ->label('School Year')
            ->default(static::generateCurrentSchoolYear())
            ->readOnly()
            ->dehydrated(false)
            ->required(),
            Forms\Components\TextInput::make('old_new_student')
            ->label('Old/New Student')
            ->readOnly()
            ->dehydrated(false)
            ->required(),
        ];
    }

    public static function getCourseRepeater(): TableRepeater {
        return TableRepeater::make('courseEnrollments')
            ->headers([
                Header::make('Course Code')
                    ->markAsRequired(),
                Header::make('Course Description')
                ->width('40%'),
                Header::make('Lecture Units')
                ->width('10%'),
                Header::make('Lab Units')
                ->width('10%'),
                Header::make('Lecture Hours')
                    ->width('10%'),
                Header::make('Lab Hours')
                    ->width('10%'),
            ])
            ->streamlined()
            ->relationship()
            ->schema([
                Forms\Components\Select::make('course_id')
                ->label('Course')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select Course')
                    ->required()
                    ->reactive()
                    ->distinct()
                    ->afterStateUpdated(function ($state, Forms\set $set) {
                        if(!empty($state)) {
                            $set('course_name', Course::find($state)->course_name ?? '');
                            $set('lecture_units', Course::find($state)->lecture_units ?? '');
                            $set('lab_units', Course::find($state)->lab_units ?? '');
                            $set('lecture_hours', Course::find($state)->lecture_hours ?? '');
                            $set('lab_hours', Course::find($state)->lab_hours ?? '');
                        } else {
                            $set('course_name', '');
                            $set('lecture_units', '');
                            $set('lab_units', '');
                        }
                    })
                    ->options(Course::all()->pluck('course_code', 'id'))
                    ->columnSpan([
                        'md' => 2
                    ]),
                Forms\Components\TextInput::make('course_name')
                    ->label('Course Description')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan([
                        'md' => 4
                    ]),
                Forms\Components\TextInput::make('lecture_units')
                    ->label('Lec')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan([
                        'md' => 1
                    ]),
                Forms\Components\TextInput::make('lab_units')
                    ->label('Lab')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan([
                        'md' => 1
                    ]),
                Forms\Components\TextInput::make('lecture_hours')
                    ->label('Lec')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan([
                        'md' => 1
                    ]),
                Forms\Components\TextInput::make('lab_hours')
                    ->label('Lec')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan([
                        'md' => 1
                    ]),
            ])
                ->addActionLabel('Add course')
                ->hiddenLabel()
                ->columns([
                    'md' => 10
                ])
                ->defaultItems(0);
    }
    public static function getFeeRepeater(): TableRepeater {
        return TableRepeater::make('enrollmentFees')
            ->headers([
                Header::make('Name')
                    ->markAsRequired(),
                Header::make('Amount (in Pesos)'),
            ])
            ->streamlined()
            ->relationship()
            ->schema([
                Forms\Components\Select::make('fee_id')
                    ->label('Fee')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select Fee')
                    ->required()
                    ->reactive()
                    ->distinct()
                    ->afterStateUpdated(function ($state, Forms\set $set) {
                        if(!empty($state)) {
                            $amount = Fee::find($state)?->amount ?? '';
                            $set('amount', $amount);
                        } else {
                            $set('amount', '');

                        }
                    })
                    ->options(Fee::all()->pluck('name', 'id')),

                Forms\Components\TextInput::make('amount')
                    ->readOnly(),
            ])
            ->addActionLabel('Add Fee')
            ->hiddenLabel()
            ->defaultItems(0);
    }


    public static function generateCurrentSchoolYear() : string {
        $currentYear = Carbon::now()->year;
        $nextYear = Carbon::now()->addYear()->year;
        return "{$currentYear} - {$nextYear}";
    }

    public static function incrementYearLevel($yearLevel, $semester) : string {
        $yearLevelPipeline = [
            "1st Year" => "2nd Year",
            "2nd Year" => "3rd Year",
            "3rd Year" => "4th Year",
        ];
        // Retains year level if its in 1st semester
        if ($semester == "1st Semester") {
            return $yearLevel;
        } else {
            // advances year level by 1 if its 2nd semester
            // If its 4th year retain year
            return $yearLevelPipeline[$yearLevel] ?? $yearLevel;
        }

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

    public static function populateCourse($semester, $departmentId, $yearLevel) : array {
        $courses = [];
        if (!empty($semester) && !empty($departmentId) && !empty($yearLevel)) {
            $departmentCode = Department::find($departmentId)->department_code;
            $courses = Course::selectRaw('id as course_id, course_name, lecture_units, lab_units, lecture_hours, lab_hours')
                ->whereHas('populators', function ($query) use ($semester, $departmentCode, $yearLevel) {
                    $query
                        ->where('semester', $semester)
                        ->where('program', $departmentCode)
                        ->where('year_level', $yearLevel);
                })
//            ->with('populators')
                ->get()
                ->toArray();
        }

        return $courses;
    }
    public static function populateFees($year_level) {
        $fees = [];
        switch ($year_level) {
            case '1st Year':
                $fees = Fee::selectRaw('id as fee_id, amount')
                        ->get()
                        ->toArray();
                break;
            case '3rd Year':
            case '4th Year':
            case '2nd Year':
                $fees  = Fee::selectRaw('id as fee_id, amount')
                    ->whereNot('name', 'NSTP')
                    ->get()
                    ->toArray();
                break;
            default:
                throw new InvalidArgumentException("Invalid year level: $year_level");
                break;
        }
        return $fees;
    }




}
