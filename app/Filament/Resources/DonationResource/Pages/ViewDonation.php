<?php

namespace App\Filament\Resources\DonationResource\Pages;

use App\Filament\Resources\DonationResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Fieldset;

class ViewDonation extends ViewRecord
{
    protected static string $resource = DonationResource::class;
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Campaign Details')
                    ->relationship('campaign')
                    ->schema([
                        Infolists\Components\TextEntry::make('title'),
                        Infolists\Components\TextEntry::make('funding_goal'),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull()
                            ->html(),
                    ]),
                    Fieldset::make('Donation Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('donor_name'),
                        Infolists\Components\TextEntry::make('donor_email'),
                        Infolists\Components\TextEntry::make('amount'),
                        Infolists\Components\TextEntry::make('amount')
                        ->money('USD'),
                        Infolists\Components\TextEntry::make('message'),
                        Infolists\Components\IconEntry::make('anonymous')
                        ->boolean(),
                        Infolists\Components\TextEntry::make('tip_percentage'),
                        Infolists\Components\IconEntry::make('opt_out_tip')
                        ->boolean(),
                        Infolists\Components\TextEntry::make('created_at')
                        ->since()
                        ->sinceTooltip(),
                    ]),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

}
