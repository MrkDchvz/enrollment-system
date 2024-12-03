<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationGroup = 'Academic Management';

    protected static array $timeSlots = [
'07:00 AM' => '7:00 AM',
'08:00 AM' => '8:00 AM',
'09:00 AM' => '9:00 AM',
'10:00 AM' => '10:00 AM',
'11:00 AM' => '11:00 AM',
'12:00 PM' => '12:00 PM',
'01:00 PM' => '1:00 PM',
'02:00 PM' => '2:00 PM',
'03:00 PM' => '3:00 PM',
'04:00 PM' => '4:00 PM',
'05:00 PM' => '5:00 PM',
'06:00 PM' => '6:00 PM',
'07:00 PM' => '7:00 PM',
'08:00 PM' => '8:00 PM',
'09:00 PM' => '9:00 PM',
];
protected static array $days = [
'MON' => 'Monday',
'TUE' => 'Tuesday',
'WED' => 'Wednesday',
'THU' => 'Thursday',
'FRI' => 'Friday',
'SAT' => 'Saturday',
];

protected static array $rooms = [
    // Floor 1
'101' => 'Room 101', '102' => 'Room 102', '103' => 'Room 103', '104' => 'Room 104', '105' => 'Room 105', '106' => 'Room 106',
    // Floor 2
'201' => 'Room 201', '202' => 'Room 202', '203' => 'Room 203', '204' => 'Room 204', '205' => 'Room 205', '206' => 'Room 206',
    // Floor 3
'301' => 'Room 301', '302' => 'Room 302', '303' => 'Room 303', '304' => 'Room 304', '305' => 'Room 305', '306' => 'Room 306',
    // Floor 4
'401' => 'Room 401', '402' => 'Room 402', '403' => 'Room 403', '404' => 'Room 404', '405' => 'Room 405', '406' => 'Room 406',

    // Computer Labs
'CL1' => 'Computer Lab 1', 'CL2' => 'Computer Lab 2', 'CL3' => 'Computer Lab 3', 'CL4' => 'Computer Lab 4', 'CL5' => 'Computer Lab 5', 'CL6' => 'Computer Lab 6',

    // Special Rooms
'ACRE' => 'Accrediting Room', 'AUDI' => 'Auditorium', 'GYM' => 'Gym'
];

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getDetailsFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.course_code')
                ->label('Course Code')
                ->searchable(),
                TextColumn::make('course.course_name')
                ->label('Course Name')
                ->searchable()
                ->toggleable(),
                TextColumn::make('instructor.name')
                ->label('Instructor')
                ->searchable()
                ->toggleable(),
                TextColumn::make('start_time')
                ->toggleable(),
                TextColumn::make('end_time')
                ->toggleable(),
                TextColumn::make('day')
                ->toggleable()
                ->searchable(),
                TextColumn::make('room')
                ->toggleable(),

            ])
            ->filters([
                SelectFilter::make('course_id')
                    ->label('Course')
                    ->searchable()
                    ->options(Course::all()->pluck('course_code', 'id')),
                SelectFilter::make('instructor_id')
                    ->label('Instructor')
                    ->options(Instructor::all()->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('day')
                    ->label('Day')
                    ->options(static::$days)
                    ->searchable(),
                SelectFilter::make('room')
                ->label('Room')
                ->options(static::$rooms)
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema() : array {


        return [
            Forms\Components\Fieldset::make('Course Information')->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course Code')
                    ->placeholder('Select a course')
                    ->options(Course::all()->pluck('course_code', 'id'))
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('course_name', \App\Models\Course::find($state)?->course_name ?? ''))
                    ->reactive()
                    ->native(false)
                    ->columnSpan([
                        'md' => 1,
                    ]),
                Forms\Components\TextInput::make('course_name')
                    ->label('Course Name')
                    ->required()
                    ->disabled()
                    ->columnSpan([
                        'md' => 1,
                    ])
            ])->columns([
                'md' => 10
            ]),

            Forms\Components\Fieldset::make("Timeslot")->schema([
                Forms\Components\Select::make('start_time')
                    ->label('Start Time')
                    ->reactive()
                    ->options(static::$timeSlots)
                    ->afterStateUpdated(function ($state, callable $set)  {
                        // Get the selected index, will be used as offset when populating the end time
                        $startIndex = array_search($state, array_keys(static::$timeSlots));
                        // Returns an array where the time is greater than the start Time (e.g if 9:00AM is selected, the array will be [10:00AM,..., 9:00PM])
                        $endTimeOptions = array_slice(static::$timeSlots, $startIndex + 1, null, true);

                        $set('end_time_options', $endTimeOptions);
                        // Reset end time everytime a new start time is selected. This ensures that all the options generated in the end time is always after the start time.
                        $set('end_time', null);
                    }),

                Forms\Components\Select::make('end_time')
                    ->label('End Time')
                    ->reactive()
                    ->required()
                    // Get all valid time from $timeSlots
                    ->options(fn (callable $get) => $get('end_time_options'))
                    ->disabled(fn (callable $get) => empty($get('start_time')) || $get('start_time') === '09:00 PM' )
                    ->dehydrated(),

                Forms\Components\Select::make('day')
                    ->label('Day')
                    ->required()
                    ->disabled(fn (callable $get) => empty($get('start_time')) || empty($get('end_time')))
                    ->options(static::$days)
            ])->columns(3),
            Forms\Components\Fieldset::make("Other Information")->schema([
                Forms\Components\Select::make('instructor_id')
                    ->placeholder('Select an instructor')
                    ->relationship('instructor', 'name')
                    ->native(false),
                Forms\Components\Select::make('room')
                ->label('Room')
                ->placeholder('Select a room')
                ->options(static::$rooms)
                ->searchable()
                ])
        ];
    }
}
