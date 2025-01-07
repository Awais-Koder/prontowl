<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KycRequestResource\Pages;
use App\Filament\Resources\KycRequestResource\RelationManagers;
use App\Models\KycRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
class KycRequestResource extends Resource
{
    protected static ?string $model = KycRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User Name')
                    ->options(User::pluck('name', 'id')->toArray()),
                Forms\Components\Select::make('reuest_for_level')
                    ->required()
                    ->options([
                        '1' => 'Level 1',
                        '2' => 'Level 2',
                        '3' => 'Level 3',
                    ]),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'declined' => 'Declined',
                    ])->columnSpanFull(),
                    Forms\Components\FileUpload::make('driving_license')
                    ->imageEditor()
                    ->image()
                    ->required(),
                    Forms\Components\FileUpload::make('passport')
                    ->imageEditor()
                    ->image()
                    ->required(),
                    Forms\Components\FileUpload::make('org_document')
                    ->imageEditor()
                    ->image()
                    ->required()
                    ->visible(function ($record) {
                        // Get the user associated with this record
                        $user = $record ? $record->user : null;
                        // Check if user exists and has Organization role
                        return $user && $user->hasRole('Organization');
                    })
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reuest_for_level')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\ImageColumn::make('driving_license')->label('Driving License'),
                Tables\Columns\ImageColumn::make('passport')->label('Passport'),
                Tables\Columns\ImageColumn::make('org_document')->label('Organization Document'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListKycRequests::route('/'),
            'create' => Pages\CreateKycRequest::route('/create'),
            'edit' => Pages\EditKycRequest::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Admin');
    }
}
