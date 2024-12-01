<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getDetailsFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight('medium')
                            ->alignLeft(),

                        Tables\Columns\TextColumn::make('department.department_name')
                            ->color('gray')
                            ->alignLeft()
                    ])->space(),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('email')
                            ->icon('heroicon-o-envelope')
                            ->searchable()
                            ->sortable()
                            ->alignLeft(),
                        Tables\Columns\TextColumn::make('contact_number')
                            ->icon('heroicon-o-phone')
                            ->alignLeft()
                    ])->space()
                ])

            ])
            ->filters([
                SelectFilter::make('department')
                    ->relationship('department', 'department_name')
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }

    public static function getDetailsFormSchema(): array {
        return [
            Forms\Components\Select::make('department_id')
                ->label('Department')
                ->relationship('department', 'department_name')
                ->required()
                ->native(false),
            Forms\Components\TextInput::make('name')
                ->required()
                ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required(),
            Forms\Components\TextInput::make('contact_number')
                ->required()
        ];
    }
}
