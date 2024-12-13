<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Crypt;
class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Campaigns';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')->default(auth()->id())
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->placeholder('Enter Your Campaign Title')
                    ->required()
                    ->rules(['required'])
                    ->label('Title')
                    ->maxLength(255),
                Forms\Components\Select::make('purpose')
                    ->label('Campaign Purpose')
                    ->required()
                    ->rules(['required'])
                    ->placeholder('Select Purpose')
                    ->options([
                        'online_course' => 'Online Course',
                        'training' => 'Training',
                        'tution' => 'Tution',
                    ])
                    ->native(false)
                    ->reactive(),
                Forms\Components\Select::make('used_in')
                    ->placeholder('Select Platform')
                    ->label('Platform')
                    ->options([
                        'courseera' => 'Courseera',
                        'udemy' => 'Udemy',
                        'get_smarter' => 'Get Smarter',
                        'other' => 'Other',
                    ])
                    ->native(false)
                    ->hidden(fn(callable $get) => $get('purpose') !== 'online_course') // Show only for 'online_course'
                    ->reactive(),
                Forms\Components\TextInput::make('used_in_other')
                    ->label('Specify Other Platform')
                    ->hidden(fn(callable $get) => $get('used_in') !== 'other') // Show only if 'Other' is selected
                    ->placeholder('Specify Other Platform Here')
                    ->reactive(),
                Forms\Components\TextInput::make('used_in')
                    ->label('Details')
                    ->hidden(fn(callable $get) => !in_array($get('purpose'), ['training', 'tution'])) // Show for 'training' or 'tution'
                    ->placeholder('Enter Details Here')
                    ->reactive(),
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->required()
                    ->rules(['required'])
                    ->placeholder('Enter Description Here')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('feature_image')
                    ->required()
                    ->rules(['required'])
                    ->imageEditor()
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->openable()
                    ->downloadable()
                    ->uploadingMessage('Uploading attachment...')
                    ->image(),
                Forms\Components\FileUpload::make('gallary_images')
                    ->multiple()
                    ->uploadingMessage('Uploading attachment...')
                    ->imageEditor()
                    ->reorderable()
                    ->openable()
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->downloadable()
                    ->panelLayout('grid')
                    ->formatStateUsing(function ($record) {
                        if (!empty($record->gallary_images))
                            return json_decode($record->gallary_images, true); // Decode and show the images
                    })
                    ->image(),
                Forms\Components\TextInput::make('video_url')
                    ->placeholder('Enter Video Url Here')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('starting_date')
                    ->placeholder('Enter Starting Date Here')
                    ->native(false)
                    ->required(),
                Forms\Components\DateTimePicker::make('ending_date')
                    ->placeholder('Enter Ending Date Here')
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('funding_goal')
                    ->placeholder('Enter Funding Goal Here')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->placeholder('Enter Location Here')
                    ->maxLength(255),
                Forms\Components\Toggle::make('term_and_conditions')
                    ->label('Accept Terms and Conditions')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->limit(20)
                    ->formatStateUsing(function ($state) {
                        $state = ucfirst(strtolower($state));
                        if ($state === 'Online_course') {
                            return 'Online Course';
                        }
                        return $state;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('used_in')
                    ->limit(20)
                    ->formatStateUsing(function ($state) {
                        $state = ucfirst(strtolower($state));
                        if ($state === 'Get_smarter') {
                            return 'Get Smarter';
                        }
                        return $state;
                    })
                    ->searchable(),
                Tables\Columns\ImageColumn::make('feature_image')
                    ->circular()
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('starting_date')
                    ->dateTime()
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ending_date')
                    ->dateTime()
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                Tables\Columns\TextColumn::make('funding_goal')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\IconColumn::make('term_and_conditions')
                    ->boolean(),
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
                Tables\Actions\Action::make('Share')
                    ->label('Share')
                    ->icon('heroicon-o-share')
                    ->color('danger')
                    ->modalHeading('Share Campaign')
                    ->modalSubmitAction(false)
                    ->modalButton('Copy')
                    ->modalDescription('Boost your campaign by sharing this link on social media and inspire more support!')
                    ->form([
                        Forms\Components\TextInput::make('Link')
                        // ->default(fn(Campaign $record) =>route('campaign.share', ['purpose' => $record->purpose , 'id'=> base64_encode($record->id)])),
                        ->default(fn(Campaign $record) => config('app.url') . 'share/campaign/' . urlencode($record->purpose) . '/' . base64_encode( $record->id))

                    ])
                ,
                Tables\Actions\Action::make('Video Url')
                    ->label('Video')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->url(fn($record) => $record->video_url)
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
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
    public static function canViewAny(): bool
    {
        return auth()->check();
    }
}
