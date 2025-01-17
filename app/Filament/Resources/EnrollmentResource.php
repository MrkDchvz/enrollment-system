<?php

namespace App\Filament\Resources;

use App\Enums\RegistrationStatus;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Livewire\ListEnrollmentCourses;
use App\Livewire\ListEnrollmentFees;
use App\Livewire\StudentChecklist;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentNumberGenerator;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
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
        $id = request()->route()->parameter('record');
        return $form
            ->schema([
                Forms\Components\Section::make('Student Information')
                    ->schema(static::getDetailsFormSchema()),
                Forms\Components\Section::make('Courses')
                    ->schema([static::getCourseRepeater()])
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Faculty', 'Registrar'])),
                Forms\Components\Section::make('Fees')
                    ->schema([static::getFeeRepeater()])
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        $filters = [];

        if (auth()->user()->hasRole(['Admin', 'Registrar'])) {
           $filters = [
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
                    ]),
              ];
        }

        return $table
            ->recordUrl(null)
            ->searchable(false)
            ->defaultSort('created_at', 'desc')
            ->columns([
                \EightyNine\Approvals\Tables\Columns\ApprovalStatusColumn::make("approvalStatus.status"),
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
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label('Enrollment Date')
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\TextColumn::make('department.department_code')
                    ->label('Department')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'BSIT' => 'success',
                        'BSCS' => 'danger',
                    })
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\TextColumn::make('year_level')
                    ->label('Year Level')
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\TextColumn::make('semester')
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\TextColumn::make('section.sectionName')
                    ->label('Section')
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\TextColumn::make('school_year')
                    ->label('School Year')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('old_new_student')
                    ->label('Old/New Student')
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Encoder')
                    ->searchable(isIndividual: true)
                    ->toggleable()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar'])),
                Tables\Columns\ImageColumn::make('requirements')
                    ->simpleLightbox()
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar', 'Officer'])),
                Tables\Columns\TextColumn::make('student_type')
                    ->label('Student Type')
                    ->hidden(fn () => !auth()->user()->hasRole(['Admin', 'Registrar', 'Officer', 'Faculty']))



            ])
            ->filters($filters)
            ->actions(
                \EightyNine\Approvals\Tables\Actions\ApprovalActions::make(
                    Tables\Actions\Action::make('pdf')
                        // Negate if the enrollment is valid
                        // because the logic of hidden is it hides the component if it returns true
                        // I don't want to hide the enrollment if its valid
                        // Since the function isEnrollmentValid returns true if enrollment valid
                        //  hidden function hides the component if its true
                        // I have to negate it so it wont hide the COR if the enrollment is valid
                        ->hidden(function ($record) {
                            if (auth()->user()->hasRole(['Registrar'])) {
                                return !static::isEnrollmentValid($record->id);
                            }
                            if (auth()->user()->hasRole(['Admin'])) {
                                return false;
                            }
                            // Hide the button for Student, Officer, Faculty.
                            return true;
                        })
                        ->label('Download COR')
                        ->color('danger')
                        ->icon('heroicon-o-document-arrow-down')
                        ->url(fn (Enrollment $record) => route('pdf', $record))
                        ->openUrlInNewTab(),
                    [
                        Tables\Actions\ForceDeleteAction::make(),
                        Tables\Actions\RestoreAction::make(),
                        Tables\Actions\EditAction::make()
                            ->hidden(fn($record) => $record->trashed()),
                    ],
                ),
                 position: ActionsPosition::BeforeColumns,
            );
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('Student')) {
            $student = Student::where('user_id', auth()->id())->firstOrFail();
            return parent::getEloquentQuery()->where('student_id', $student->id);
        }
        if (auth()->user()->hasRole(['Registrar', 'Faculty', 'Officer'])) {
            return parent::getEloquentQuery()->whereHas('approvalStatus', function ($query) {
                $query->whereIn('status', ['Approved', 'Created', 'Submitted']);
            });
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
                    TextEntry::make('section.sectionName')
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
                Fieldset::make('Checklist')->schema([
                    Livewire::make(StudentChecklist::class, ['studentId' => Enrollment::find($enrollmentId)->student_id])
                        ->columnSpanFull(),
                ])
                ->hidden(fn() => !auth()->user()->hasRole(['Admin', 'Faculty'])),
                Fieldset::make('Courses')->schema([
                    Livewire::make(ListEnrollmentCourses::class, ['enrollmentId' => $enrollmentId])
                        ->columnSpanFull(),
                ]),
                Fieldset::make('Fees')->schema([
                    Livewire::make(ListEnrollmentFees::class, ['enrollmentId' => $enrollmentId])
                        ->columnSpanFull(),
                ]),
                TextEntry::make('comments')
                    ->default(function ($record) {
                        return $record->approvals->last()->comment;
                    })
                    ->label('Comments'),
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
                Forms\Components\TextInput::make('student_number')
                ->label('Student Number')
                    ->default(function () {
                        // If the user is student then set the user's student name  by default
                        if (auth()->user()->hasRole('Student')) {
                            $userId = auth()->id();
                            return Student::where('user_id', $userId)->firstOrFail()->student_number;
                        }
                        else {
                            return null;
                        }
                    })
                ->live()
                ->reactive()
                ->disabled(),
                Forms\Components\TextInput::make('student_name')
                    ->label('Student Name')
                    ->default(function () {
                        // If the user is student then set the user's student name  by default
                        if (auth()->user()->hasRole('Student')) {
                            $userId = auth()->id();
                            return Student::where('user_id', $userId)->firstOrFail()->fullName;
                        }
                        else {
                            return null;
                        }
                    })
                    ->disabled(),
            ]),
            // Only Visible to student
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        '1st Semester' => '1st Semester',
                        '2nd Semester' => '2nd Semester',
                    ])
                    ->required()
                    ->default(fn () => static::getCurrentSemester())
                    ->disabled(fn () => !auth()->user()->hasRole('Admin'))
                    ->selectablePlaceholder(false)
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
                Forms\Components\TextInput::make('school_year')
                    ->label('School Year')
                    ->disabled()
                    ->default(static::getCurrentSchoolYear()),


            ]),
            Forms\Components\Grid::make(2)->schema([
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
                            $sectionPoupulation = Enrollment::where('section_id', $get('section_id'))
                            ->where('school_year', static::getCurrentSchoolYear())
                            ->where('semester', $get('semester'))
                            ->whereHas('approvalStatus', function ($query) {
                                $query->where('status', 'Approved');
                            })
                            ->count();
                            $set('section_population', $sectionPoupulation);
                        }

                    })
                    ->required(),
                Forms\Components\TextInput::make('section_population')
                    ->label('Section Population')

                    ->disabled()
            ])
                    ->hidden(fn ($state) => !auth()->user()->hasRole(['Admin','Faculty','Registrar'])),
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\ToggleButtons::make('registration_status')
                    ->inline()
                    ->label('Registration Status')
                    ->options(RegistrationStatus::class)
                    ->required(),


                Forms\Components\TextInput::make('old_new_student')
                    ->label('Old/New Student')
                    ->readOnly(),
            ])
            ->hidden(fn() => auth()->user()->hasRole('Student')),
            Forms\Components\Select::make('user_id')
            ->label('Encoder')
            ->options(function () {
                return User::role('Registrar')->pluck('name', 'id')->toArray();
            })
            ->hidden(fn() => !auth()->user()->hasRole(['Admin', 'Registrar']))
            ->required()
            ->searchable(),

            Forms\Components\ToggleButtons::make('student_type')
            ->label('Student Type')
            ->options(function () {
                if (auth()->user()->hasRole('Student')) {
                    $loggedStudent = Student::where('user_id', auth()->user()->id)->firstOrFail();
                    if (is_null($loggedStudent->student_number) || $loggedStudent->student_number === '') {
                        return [
                            'New' => 'New',
                            'Transferee' => 'Transferee'
                        ];
                    } else {
                        return [
                            'Regular' => 'Regular',
                            'Irregular' => 'Irregular',
                        ];
                    }
                }
                else {
                    return [
                        'New' => 'New',
                        'Transferee' => 'Transferee',
                         'Regular' => 'Regular',
                        'Irregular' => 'Irregular',
                    ];
                }
            })
            ->icons([
                    'Regular' => 'heroicon-o-check-circle',
                    'Irregular' => 'heroicon-o-arrow-path-rounded-square',
                    'Transferee' => 'heroicon-o-paper-airplane',
                    'New' => 'heroicon-o-sparkles',
                ])
            ->inline()
            ->disabled(fn() => !auth()->user()->hasRole(['Student']))
            ->required(),

            Forms\Components\FileUpload::make('requirements')
            ->label('Requirements')
            ->image()
            ->imageEditor()
            ->imagePreviewHeight('250')
            ->panelLayout('grid')
            ->disk('public')
            ->directory('requirements')
            ->multiple()
            ->disabled(fn () => !auth()->user()->hasRole('Student'))
            ->hidden(fn() => !auth()->user()->hasRole(['Admin', 'Registrar', 'Student']))
            ->required()
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

    public static function isEnrollmentValid ($enrollmentId) {

        $enrollmentModel = Enrollment::find($enrollmentId);
//        dd($enrollmentModel->approvalStatus);
        $nullableFields = ['user_id', 'department_id', 'section_id'];
        // Check if enrollment user_id, department_id, section_id because enrollment needs it.
        $allNullableFieldsHaveValues = collect($nullableFields)
            ->every(fn($field) => !is_null($enrollmentModel->$field));

        return $allNullableFieldsHaveValues &&
            !$enrollmentModel->trashed() &&
            $enrollmentModel->fees()->exists() &&
            $enrollmentModel->courses()->exists();
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
