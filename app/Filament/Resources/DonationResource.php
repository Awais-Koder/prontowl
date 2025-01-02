<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Campaign;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use App\Services\StripePaymentService;
use App\Exceptions\CampaignNotFoundException;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $canCreateAnother = false;
    public static function form(Form $form): Form
    {
        $campaignId = 0;
        if (Session::has('campaign_data')) { // Check if the session key exists
            $campaignId = Session::get('campaign_data'); // Retrieve the campaign data
            if (empty($campaignId)) { // Check if the value is empty
                throw new CampaignNotFoundException('Campaign not found.');
            }
        }
        $campaign = Campaign::find($campaignId, ['title', 'purpose', 'id', 'used_in', 'description', 'funding_goal']);

        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Campaign Details')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->icon('heroicon-o-megaphone')
                        ->schema([
                            Fieldset::make('Campaign Details')
                                ->relationship('campaign')
                                ->schema([
                                    Forms\Components\TextInput::make('title'),
                                    Forms\Components\TextInput::make('funding_goal'),
                                    Forms\Components\Textarea::make('description')
                                    ->formatStateUsing(fn ($state) => strip_tags($state))
                                    ->rows(10)
                                    ->columnSpanFull(),
                                ])
                                ->hidden(fn() => Session::has('campaign_data')),
                            Forms\Components\View::make('text')
                                ->viewData([
                                    'text' => $campaign->title ?? 'Not Found', // Dynamically fetch the campaign title
                                    'label' => 'Campaign Title', // Static label
                                ])
                                ->hidden(fn() => !Session::has('campaign_data')),
                            Forms\Components\View::make('text')
                                ->viewData([
                                    'text' => $campaign->funding_goal ?? 'Not Found',
                                    'label' => 'Campaign Goal' ?? 'Not Found',
                                ])
                                ->hidden(fn() => !Session::has('campaign_data')),
                            Forms\Components\View::make('text')
                                ->viewData([
                                    'text' => $campaign->description ?? 'Not Found',
                                    'label' => 'Campaign Description' ?? 'Not Found',
                                ])
                                ->hidden(fn() => !Session::has('campaign_data'))
                                ->columnSpanFull()

                        ])->columns(2),
                    Wizard\Step::make('Donor Information')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->icon('heroicon-o-users')
                        ->schema([
                            Forms\Components\TextInput::make('donor_name')
                                ->placeholder('Enter Your Name Here')
                                ->required(fn($get) => !$get('anonymous')) // Make required if 'anonymous' is false
                                ->maxLength(255),
                            Forms\Components\TextInput::make('donor_email')
                                ->placeholder('Enter Your Email Here')
                                ->email()
                                ->required(fn($get) => !$get('anonymous')) // Make required if 'anonymous' is false
                                ->maxLength(255),
                            Forms\Components\Select::make('donation_type')
                                ->label('Choose Donation Amount')
                                ->options([
                                    '10' => '$10',
                                    '25' => '$25',
                                    '50' => '$50',
                                    'custom' => 'Custom Amount',
                                ])
                                ->native(false)
                                ->reactive() // Makes the component dynamic
                                ->required(),
                            Forms\Components\TextInput::make('donation_amount')
                                ->label('Donation Amount')
                                ->numeric()
                                ->reactive()
                                ->placeholder('Enter your custom amount here')
                                ->required(fn($get) => $get('donation_type') === 'custom') // Required only for custom
                                ->hidden(fn($get) => $get('donation_type') !== 'custom'), // Show only if custom selected
                            Forms\Components\Textarea::make('message')
                                ->label('Message (optional)')
                                ->placeholder('Enter Your Message Here')
                                ->columnSpanFull(),
                            Forms\Components\Toggle::make('anonymous')
                                ->label('Donate Anonymously')
                                ->reactive(), // Enables the toggle to update the form dynamically

                        ])->columns(2),
                    Wizard\Step::make('Voluntary Tip')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        ->icon('heroicon-o-gift')
                        ->schema([
                            Forms\Components\Select::make('tip_percentage')
                                ->label('Choose Donation Amount')
                                ->options([
                                    '5' => '5%',
                                    '10' => '10%',
                                    '15' => '15%',
                                    'custom' => 'Custom Percentage',
                                ])
                                ->native(false)
                                ->reactive() // Makes the component dynamic
                                ->hidden(fn($get) => $get('opt_out_tip') !== true)
                                ->required(),
                            Forms\Components\TextInput::make('tip_percentage_other')
                                ->label('Tip Percentage')
                                ->numeric()
                                ->reactive()
                                ->placeholder('Enter your tip percentage here eg: 5 , 10 , 15')
                                ->required(fn($get) => $get('tip_percentage') === 'custom') // Required only for custom
                                ->hidden(fn($get) => $get('tip_percentage') !== 'custom' || $get('opt_out_tip') !== true),
                            Forms\Components\Toggle::make('opt_out_tip')
                                ->reactive(),
                        ])->columns(2),
                    Wizard\Step::make('Donate Now')
                        ->icon('heroicon-o-banknotes')
                        ->completedIcon('heroicon-m-hand-thumb-up')
                        // for review
                        ->schema([
                            Actions::make([
                                Action::make('Pay')
                                    ->icon('heroicon-o-credit-card')
                                    ->action(function (StripePaymentService $stripePaymentService, $get) {
                                        $data = [
                                            'tip_percentage_other' => $get('tip_percentage_other'),
                                            'tip_percentage' => $get('tip_percentage'),
                                            'message' => $get('message'),
                                            'donation_amount' => $get('donation_amount'),
                                            'donation_type' => $get('donation_type'),
                                            'donor_email' => $get('donor_email'),
                                            'donor_name' => $get('donor_name'),
                                            'opt_out_tip' => $get('opt_out_tip'),
                                            'anonymous' => $get('anonymous'),
                                        ];
                                        Session::put('donation_user_data', $data);
                                        // Get the selected donation type
                                        $donationType = $get('donation_type');

                                        // Determine the amount based on donation type
                                        $amount = match ($donationType) {
                                            '10', '25', '50' => intval($donationType), // Use fixed donation type
                                            'custom' => intval($get('donation_amount')), // Use custom amount
                                            default => null,

                                        };
                                        if ($amount === null) {
                                            Notification::make()
                                                ->title('Donation Error')
                                                ->body('Invalid donation type selected')
                                                ->danger()
                                                ->send();

                                            return; // Stop further execution
                                        }
                                        // Validate that the amount is valid (greater than 0)
                                        if ($amount <= 0) {
                                            Notification::make()
                                                ->title('Donation Error')
                                                ->body('Donation amount must be greater than 0.')
                                                ->danger()
                                                ->send();

                                            return; // Stop further execution
                                        }

                                        // Convert to smallest currency unit (cents for USD)
                                        $session = $stripePaymentService->createCheckoutSession($amount * 100, 'usd');
                                        // Redirect to Stripe payment page
                                        return redirect($session->url);
                                    })
                                    ->requiresConfirmation(),

                            ]),
                        ])->columns(2),
                ])
                    ->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('campaign.title')
                    ->limit(10)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('donor_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('donor_email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('anonymous')
                    ->boolean(),
                Tables\Columns\TextColumn::make('tip_percentage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('opt_out_tip')
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'view' => Pages\ViewDonation::route('/{record}'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Admin');
    }
}
