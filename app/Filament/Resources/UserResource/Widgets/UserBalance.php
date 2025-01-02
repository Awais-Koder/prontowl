<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Account;
class UserBalance extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        // for user balance
        $totalBalance = 0;
        // Fetch the total balance of the current user

        $totalBalance = Account::where('user_id', auth()->id())->first();
        if (!empty($totalBalance)) {
            $totalBalance = $totalBalance->balance;
        }

        $previousTotalBalance = Transaction::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->where(['type' => 'deposit', 'user_id' => auth()->id()])
            ->sum('amount');

        // Calculate the change in balance
        $balanceChange = $totalBalance - $previousTotalBalance;
        $percentageChange = $previousTotalBalance > 0 ? ($balanceChange / $previousTotalBalance) * 100 : 0;

        // Determine the arrow direction and color
        $arrow = $balanceChange >= 0 ? '↑' : '↓';
        $color = $balanceChange >= 0 ? 'green' : 'red';

        // campaign data
        if (auth()->user()->hasRole('Admin')) {
            $totalCampaigns = Campaign::count();
            $previousCampaigns = Campaign::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        } else {
            $totalCampaigns = Campaign::with('donations')->where('user_id', auth()->id())->count();
            $previousCampaigns = Campaign::where('user_id', auth()->id())->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        }

        $campaignCountChange = $totalCampaigns - $previousCampaigns;
        $campaignPercentageChange = $previousCampaigns > 0 ? ($campaignCountChange / $previousCampaigns) * 100 : 0;
        $campaignArrow = $campaignCountChange >= 0 ? '↑' : '↓';
        $campaignColor = $campaignCountChange >= 0 ? 'green' : 'red';


        // for total received donations

        $totalDonations = Campaign::where('user_id', auth()->id())->with('donations')->get()->sum(function (Campaign $campaign): mixed {
            return $campaign->donations->sum('amount');
        });
        $previousDonations = Campaign::where('user_id', auth()->id())->with('donations')->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->get()->sum(function (Campaign $campaign): mixed {
            return $campaign->donations->sum('amount');
        });

        $donationChange = $totalDonations - $previousDonations;
        $donationChangePercentage = $previousDonations > 0 ? ($donationChange / $previousDonations) * 100 : 0;
        $donationArrow = $donationChange >= 0 ? '↑' : '↓';
        $donationColor = $donationChange >= 0 ? 'green' : 'red';

        return [
            Stat::make('Your Balance', 'heroicon-o-currency-dollar')
                ->value('$' . number_format($totalBalance, 2))
                ->description($arrow . ' ' . number_format(abs($percentageChange), 2) . '%')
                ->descriptionIcon($balanceChange >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($color),
            // total received
            Stat::make('Total Received', 'heroicon-o-currency-dollar')
                ->value('$' . number_format($totalDonations, 2))
                ->description($donationArrow . ' ' . number_format(abs($donationChangePercentage), 2) . '%')
                ->descriptionIcon($donationChange >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($donationColor),
            // total campaigns
            Stat::make('Campaign Seats', 'heroicon-o-currency-dollar')
                ->value(number_format($totalCampaigns, 2))
                ->description($campaignArrow . ' ' . number_format(abs($campaignPercentageChange), 2) . '%')
                ->descriptionIcon($campaignCountChange >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->descriptionColor($campaignColor),
        ];
    }
}
