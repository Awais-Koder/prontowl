<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelResource\Pages;
use App\Filament\Resources\LevelResource\RelationManagers;
use App\Models\Level;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')->label('User Name')
                ->options(User::pluck('name', 'id')->toArray())
                ->searchable()
                ->optionsLimit(10)
                    // ->visible(fn() => request()->routeIs('filament.admin.resources.levels.create'))
                    ->required(),
                Forms\Components\Select::make('level')->label('Level')
                    ->options([
                        '1' => 'Level 1',
                        '2' => 'Level 2',
                        '3' => 'Level 3'
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')->label('Current Level'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Upgrade Level')
                    ->visible(fn() => !auth()->user()->hasRole('Admin')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole('Admin')),
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
            'index' => Pages\ListLevels::route('/'),
            'create' => Pages\CreateLevel::route('/create'),
            'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Admin');
    }
    public static function canEdit($record): bool
    {
        return auth()->check() && auth()->user()->hasRole('Admin');
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('Admin')) {
            // Admins can see all records
            return parent::getEloquentQuery();
        }
        // Non-admin users can only see their own records
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('Admin');
    }
}
