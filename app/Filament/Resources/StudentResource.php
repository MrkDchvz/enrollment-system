<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getDetailsFormSchema());
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
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
                TextColumn::make('email')
                    ->toggleable()
                    ->searchable(isIndividual: true),
                TextColumn::make('gender')
                    ->toggleable(),
                TextColumn::make('date_of_birth')
                    ->sortable()
                    ->toggleable()
                    ->date('F j, Y'),
                TextColumn::make('address')
                    ->toggleable(),
                TextColumn::make('contact_number')
                    ->toggleable(),


            ])
            ->filters([
                SelectFilter::make('gender')
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
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
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
                TextEntry::make('email'),
                Fieldset::make('Timestamps')->schema([
                    TextEntry::make('created_at')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->dateTime()
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getDetailsFormSchema(): array {
        return [
            Forms\Components\TextInput::make('student_number')
                ->default(fn() => Student::class::generateUniqueStudentNumber())
                ->readonly()
                ->disabled()
                ->dehydrated()
                ->required()
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
            Forms\Components\Select::make('gender')
                ->options([
                    'MALE' => 'Male',
                    'FEMALE' => 'Female',
                ])
                ->native(false)
                ->required(),
            DatePicker::make('date_of_birth')
                ->required(),
            Forms\Components\TextInput::make('contact_number')
                ->tel()
                ->telRegex('/^(09|\+639)\d{9}$/')
                ->placeholder("+639171234567")
                ->required(),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required(),
        ];
    }
}
