<?php

namespace App\Filament\Resources;

//Plugin
use App\Livewire\StudentChecklist;
use App\Models\Instructor;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $pluralModelLabel = 'Student Information';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema(static::getDetailsFormSchema()),
                Forms\Components\Section::make('Student Checklist')
                    ->schema([static::getItemsRepeater()])
                    ->hiddenOn('create')
               ]);
    }



    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        $filters = [];

        if (auth()->user()->hasRole(['Admin', 'Officer'])) {
            $filters = [
                SelectFilter::make('gender')
                        // Key (in the database) => Display in the forms
                        //  MALE is in the database and Male is what would be shown in form
                    ->options([
                        'MALE' => 'Male',
                        'FEMALE' => 'Female',
                    ]),
                Filter::make('date_of_birth')
                    ->form([
                        DatePicker::make('date_of_birth')
                    ])
                    ->query(function (Builder $query, array $data) : Builder {
                        return $query
                            ->when(
                                $data['date_of_birth'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date_of_birth', $data['date_of_birth'])
                            );
                    }),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ];
        }

        return $table
//            ->modifyQueryUsing(function (Builder $query) {
//                if (auth()->user()->hasRole('Admin')) {
//                    return $query;
//                }
//                else {
//                    return $query->where('user_id', auth()->id());
//                }}
//            )
            ->defaultSort('created_at', 'desc')
            ->searchable(false)
            ->columns([
                TextColumn::make('student_number')

                    ->searchable(isIndividual: true),
                TextColumn::make('full_name')
                    ->label('Full Name')

                    ->getStateUsing(function ($record) {
                //  Display The full name of student
                    $fullname = $record->first_name;
                    if ($record->middle_name) {
                        $fullname .= ' ' . $record->middle_name;
                    }
                    $fullname .= ' ' . $record->last_name;
                    return $fullname;
                })
                    ->sortable(['first_name'])
                    ->searchable(['first_name', 'middle_name', 'last_name'], isIndividual: true),
                // This is an attribute
                TextColumn::make('user.email')
                    ->label('email')
                    ->toggleable()
                    ->searchable(isIndividual: true),
                TextColumn::make('gender')
                    ->toggleable(),
                TextColumn::make('date_of_birth')
                    ->sortable()
                    ->toggleable()
                    ->date('F j, Y'),
                TextColumn::make('address')
                    ->toggleable()
                    ->searchable(isIndividual: true),
                TextColumn::make('contact_number')
                    ->toggleable()
                    ->searchable(isIndividual: true),
                TextColumn::make('deleted_at')
                    ->toggleable()
                    ->visible(fn($record) => $record && $record->trashed()),

            ])
            ->filters($filters)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn($record) => $record->trashed()),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->trashed()),
            ], position: ActionsPosition::BeforeColumns);

    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('Student')) {
            return parent::getEloquentQuery()->where('user_id', auth()->id());
        } else {
            return parent::getEloquentQuery();
        }

    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $student_id = $infolist->record->id;
        return $infolist
            ->schema([
                TextEntry::make('student_number')
                ->columnSpanFull(),
                Fieldset::make("Name")->schema([
                    TextEntry::make('first_name'),
                    TextEntry::make('middle_name'),
                    TextEntry::make('last_name'),
                    ])->columns(3),
                TextEntry::make('gender'),
                TextEntry::make('date_of_birth')
                    ->date('F j, Y'),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('contact_number'),
                // This is an attribute
                TextEntry::make('email'),
                Fieldset::make('Timestamps')->schema([
                    TextEntry::make('created_at')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->dateTime()
                ])
                ->visible(auth()->user()->hasRole('Admin')),
                Fieldset::make('Checklist')->schema([
                    Livewire::make(StudentChecklist::class, ['studentId' =>$student_id])
                    ->columnSpanFull(),
                ])
                ->columnSpanFull(),

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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}'),
        ];
    }

    public static function getDetailsFormSchema(): array {
        return [
            Forms\Components\TextInput::make('student_number')
                ->required()
                ->unique()
                ->placeholder('E.g. 20xxxxxxx')
                ->regex('/^20\d{7}$/')
                ->hiddenOn('edit')
                ->columnSpanFull(),
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\TextInput::make('first_name')
                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
                    ->required(),
                Forms\Components\TextInput::make('middle_name')
                    ->label('Middle Name (Optional)')
                    ->placeholder('Leave blank if not applicable')
                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                Forms\Components\TextInput::make('last_name')
                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
                    ->required()
            ]),

            Forms\Components\TextInput::make('address')
                ->placeholder('B9 L8 MANCHESTER ST. MOLINO 3, BACOOR CAVITE')
                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
                ->required()
                ->columnSpanFull(),

            Forms\Components\Grid::make(3)->schema([
                Forms\Components\Select::make('gender')
                    ->options([
                        'MALE' => 'Male',
                        'FEMALE' => 'Female',
                    ])
                    ->native(false)
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->required()
                    ->before(Carbon::today()->toString()),
                Forms\Components\TextInput::make('contact_number')
                    ->tel()
                    ->prefix('+63')
                    ->telRegex('/^9\d{9}$/')
                    // Removed Users as table here Check for bugs
                    ->unique(ignoreRecord: true)
                    ->placeholder("9171234567")
                    ->required(),
            ]),

                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->rules([
                        static function (Forms\Components\Component $component) : Closure {
                            return static function (string $attribute, $value, Closure $fail ) use ($component)  {
                                $existingUser = User::withTrashed()
                                    ->where('email', Str::lower($value))
                                    ->first();

                                // Fails Validation if email already exists on the database
                                if ($existingUser && $existingUser->id !== $component->getRecord()?->user_id) {
                                    $fail('Email already exists.');
                                }
                            };
                        }
                    ]),
        ];
    }
    public static function getItemsRepeater(): TableRepeater {
        return TableRepeater::make('courseStudents')
            ->headers([
                Header::make('Course Code')
                    ->markAsRequired(),
                Header::make('Course Name'),
                Header::make('grade')
                    ->markAsRequired(),
                Header::make('Instructor')
                    ->markAsRequired(),
            ])
            ->streamlined()
            ->relationship()
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(Course::all()->pluck('course_code', 'id'))
                    ->required()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('course_name', \App\Models\Course::find($state)?->course_name ?? ''))
                    ->reactive()
                    ->distinct()
                    ->searchable()
                    ->columnSpan([
                        'md' => 2,
                    ]),
                Forms\Components\TextInput::make('course_name')
                    ->label('Course Name')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->columnSpan([
                        'md' => 6,
                    ]),

                Forms\Components\Select::make('grade')
                    ->options([
                        '1.00' => '1.00',
                        '1.25' => '1.25',
                        '1.50' => '1.50',
                        '1.75' => '1.75',
                        '2.00' => '2.00',
                        '2.25' => '2.25',
                        '2.50' => '2.50',
                        '2.75' => '2.75',
                        '3.00' => '3.00',
                        '4.00' => '4.00',
                        '5.00' => '5.00',
                        'INC' => 'INC',
                        'DRP' => 'DRP',
                    ])
                    ->searchable()
                    ->required()
                    ->columnSpan([
                        'md' => 2,
                    ]),
                Forms\Components\Select::make('instructor_id')
                    ->options(Instructor::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->native(false)

            ])
            ->columns([
                'md' => 10,
            ])
            ->addActionLabel('Add grade')
            ->hiddenLabel()
            ->defaultItems(0);
    }

}
