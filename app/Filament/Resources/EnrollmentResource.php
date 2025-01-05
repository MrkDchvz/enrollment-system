<?php

namespace App\Filament\Resources;

use App\Enums\RegistrationStatus;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Livewire\ListEnrollmentCourses;
use App\Livewire\ListEnrollmentFees;
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
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use phpDocumentor\Reflection\Types\Integer;
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
            ->recordAction(null)
            ->searchable(false)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('student.student_number')
                    ->label('Student Number')
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('student.fullName')
                    ->label('Full Name')
                    ->searchable(['first_name', 'middle_name', 'last_name'], isIndividual: true),
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
                Tables\Columns\TextColumn::make('department.department_code')
                    ->label('Department')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'BSIT' => 'success',
                        'BSCS' => 'danger',
                    })
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
                    ->searchable(isIndividual: true)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('semester')
                    // Key (in the database) => Display in the forms
                    ->options([
                        '1st Semester' => '1st Semester',
                        '2nd Semester' => '2nd Semester',
                    ]),
                SelectFilter::make('school_year')
                    ->label('School Year')
                    ->options(function () {
                        return Enrollment::distinct()
                            ->pluck('school_year', 'school_year')
                            ->toArray();
                }),
                SelectFilter::make('department_id')
                    ->label('Department')
                    ->options(function () {
                        return Department::all()
                            ->pluck('department_code', 'id')
                            ->toArray();
                }),
                SelectFilter::make('registration_status')
                ->label('Registration Status')
                ->options([
                    'IRREGULAR' => 'IRREGULAR',
                    'REGULAR' => 'REGULAR',
                ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn($record) => $record->trashed()),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->trashed()),
                Tables\Actions\Action::make('pdf')
                    ->visible(fn() => auth()->user()->hasRole(['Admin', 'Registrar']))
                    ->label('Download COR')
                    ->color('danger')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn (Enrollment $record) => route('pdf', $record))
                    ->openUrlInNewTab(),
            ], position: ActionsPosition::BeforeColumns);
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

    public static function infolist(Infolist $infolist): Infolist
    {
        $enrollmentId = $infolist->record->id;
        return $infolist
            ->schema([
                Fieldset::make('Student Information')->schema([
                    TextEntry::make('student.student_number')
                        ->label('Student Number'),
                    TextEntry::make('student.full_name')
                        ->label('Full Name'),
                    TextEntry::make('student.gender')
                        ->label('Gender'),
                ])->columns(3),
                Fieldset::make('Enrollment Information')->schema([
                    TextEntry::make('department.department_code')
                        ->label('Department')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'BSCS' => 'danger',
                            'BSIT' => 'success',
                        }),
                    TextEntry::make('year_level')
                        ->label('Year Level'),
                    TextEntry::make('semester')
                        ->label('Semester'),
                    TextEntry::make('section.fullName')
                        ->label('Section'),
                    TextEntry::make('registration_status')
                        ->label('Registration Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'IRREGULAR' => 'info',
                            'REGULAR' => 'success',
                        }),
                    TextEntry::make('old_new_student')
                        ->label('Old/New Student'),
                    TextEntry::make('enrollment_date')
                        ->label('Enrollment Date')
                        ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('F j, Y')),
                    TextEntry::make('school_year')
                        ->label('School Year'),
                    TextEntry::make('user.name')
                        ->label('Encoder'),
                ])->columns(3),
                Fieldset::make('Courses')->schema([
                    Livewire::make(ListEnrollmentCourses::class, ['enrollmentId' => $enrollmentId])
                        ->columnSpanFull(),
                ]),
                Fieldset::make('Fees')->schema([
                    Livewire::make(ListEnrollmentFees::class, ['enrollmentId' => $enrollmentId])
                        ->columnSpanFull(),
                ])
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
            'view' => Pages\ViewEnrollment::route('/{record}'),
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
                                $lastDepartment = $lastEnrollment->section->department_id;
                                $lastRegistrationStatus = $lastEnrollment->registration_status;
                                $lastSectionId = $lastEnrollment->section_id;


                                // If the last/latest enrollment is 2nd semester move the year level up by 1
                                $newYearLevel = self::incrementYearLevel($lastYearLevel, $lastSemester);
                                // Get new section
                                $newSectionId = self::getNewSection($lastSectionId, $lastSemester);
                                // Assign default year level based on latest enrollment data of student
                                // Retains the year level if the last record is on 1st semester
                                $set('registration_status', $lastRegistrationStatus);
                                $set('old_new_student', 'Old Student');
                                $set('department_id', $lastDepartment);
                                $set('section_id', $newSectionId);
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
                                $set('registration_status', 'REGULAR');
                            }
                            // Autofill fees base on year level
                            $set('enrollmentFees', static::populateFees($newYearLevel));

                        // clear fields if the student number is cleared
                        } else {
                            $set('student_name', '');
                            $set('semester', '');
                            $set('registration_status', '');
                            $set('old_new_student', '');
                            $set('section_id', null);

                        }

                    })
                    ->native(false),
                Forms\Components\TextInput::make('student_name')
                    ->label('Student Name')
                    ->disabled(),
            ]),
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\ToggleButtons::make('registration_status')
                    ->inline()
                    ->label('Registration Status')
                    ->options(RegistrationStatus::class)
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
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\get $get) {
                        if($get('section_id') && $state) {
                            $section = Section::find($get('section_id'));
                            $departmentId = $section->department->id;
                            $yearLevel = $section->year_level;

                            $set('courseEnrollments', static::populateCourse(
                                $get('semester'),
                                $departmentId,
                                $yearLevel
                            ));
                        }
                    }),
            ]),
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\Select::make('section_id')
                    ->label('Section')
                    ->options(Section::all()->pluck('sectionName', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterstateUpdated(function ($state, Forms\Set $set, Forms\get $get) {
                        if ($state) {
                            $section = Section::find($get('section_id'));
                            $departmentId = $section->department->id;
                            $yearLevel = $section->year_level;
                            if ($get('semester')) {
                                $set('courseEnrollments', static::populateCourse(
                                    $get('semester'),
                                    $departmentId,
                                    $yearLevel
                                ));
                            }
                            $set('enrollmentFees', static::populateFees($yearLevel));
                        }

                    })
                    ->required(),

                Forms\Components\Select::make('old_new_student')
                    ->label('Old/New Student')
                    ->options([
                        'Old Student' => 'Old Student',
                        'New Student' => 'New Student',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('school_year')
                    ->label('School Year')
                    ->disabled()
                    ->default(static::getCurrentSchoolYear()),
            ]),

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
                    ->afterStateUpdated(function ($state, Forms\set $set, Forms\get $get) {
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
                            $set('lecture_hours', '');
                            $set('lab_hours', '');
                        }
                    })
                    ->options(Course::all()->pluck('course_code', 'id'))
                    ->columnSpan([
                        'md' => 2
                    ]),
                Forms\Components\TextInput::make('course_name')
                    ->label('Course Description')
                    ->readOnly()
                    ->required()
                    ->columnSpan([
                        'md' => 4
                    ]),
                Forms\Components\TextInput::make('lecture_units')
                    ->label('Lec')
                    ->readOnly()
                    ->required()
                    ->columnSpan([
                        'md' => 1
                    ]),
                Forms\Components\TextInput::make('lab_units')
                    ->label('Lab')
                    ->readOnly()
                    ->required()
                    ->columnSpan([
                        'md' => 1
                    ]),
                Forms\Components\TextInput::make('lecture_hours')
                    ->label('Lec')
                    ->readOnly()
                    ->required()
                    ->columnSpan([
                        'md' => 1
                    ]),
                Forms\Components\TextInput::make('lab_hours')
                    ->label('Lec')
                    ->readOnly()
                    ->required()
                    ->columnSpan([
                        'md' => 1
                    ]),
            ])
                ->required()
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
                Header::make('Amount'),
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
                    ->numeric()
                    ->inputMode('decimal')
                    ->minValue(1)
                    ->required()
                    ->prefix("₱")
            ])
            ->required()
            ->addActionLabel('Add Fee')
            ->hiddenLabel()
            ->defaultItems(0);
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

    // This retuns a new section ID
    public static function getNewSection($sectionId, $lastSemester) : int {
        $yearLevelPipeline = [
            "1st Year" => "2nd Year",
            "2nd Year" => "3rd Year",
            "3rd Year" => "4th Year",
        ];

        $section = Section::find($sectionId);
        $classNumber = $section->class_number;
        $departmentId = $section->department->id;
        $yearLevel = $section->year_level;

        if ($lastSemester == "2nd Semester" && $yearLevel !== "4th Year") {
            // Increment Year level by 1 if the previous semester is 2nd semester
            $yearLevel = $yearLevelPipeline[$yearLevel];
        }

        $newSection =
            Section::where('department_id', $departmentId)
                ->where('year_level', $yearLevel)
                ->where('class_number', $classNumber)
                ->first();
        return $newSection->id;
    }

    public static function getCurrentSchoolYear() : string {
        // Current Date
        $date = Carbon::now();
        // Current Year $ Month
        $year = $date->year;
        $month = $date->month;
        // Set a new school year if the enrollment is in around august
        // If the year is 2024 and the student enrolled around august 2024
        // Then the school year will be 2024 - 2024
        if ($month >= 8) {
            $startYear = $date->year;
            $endYear = $date->year + 1;
        }
        // Retain the current school year if the enrollment is around february
        // If the year is 2024 and the student enrolled around february 2024
        // Then the school year is 2023-2024
        else {
            $startYear = $date->year - 1;
            $endYear = $date->year;
        }
        return trim(
            sprintf('%s-%s', $startYear, $endYear)
        );
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
        if (!empty($semester) && $semester !== null &&
            !empty($departmentId) && $departmentId !== null &&
            !empty($yearLevel) && $yearLevel !== null) {
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
                    ->whereNot('name', 'Late Reg.')
                    ->get()
                        ->toArray();
                break;
            case '3rd Year':
            case '4th Year':
            case '2nd Year':
                $fees  = Fee::selectRaw('id as fee_id, amount')
                    ->whereNot('name', 'NSTP')
                    ->whereNot('name', 'Late Reg.')
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
