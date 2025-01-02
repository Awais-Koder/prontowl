<?php

namespace App\Filament\Resources\CampaignResource\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;

class CampaignWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Campaigns & Donations';
    protected int|string|array $columnSpan = '1';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $labels = [];
        $campaignData = [];
        $donationData = [];

        if (auth()->user()->hasRole('Admin')) {
            // Fetch campaign data
            $campaigns = Campaign::select('created_at')
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });

            // Fetch donation data
            $donations = Donation::select('created_at')
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });
        } else {
            // Fetch user specific campaign data
            $campaigns = Campaign::select('created_at')
                ->where('user_id', auth()->id())
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });

            // Fetch user specific donation data
            $donations = Donation::select('created_at')
                ->where('campaign_id', function ($query) {
                    $query->select('id')
                        ->from('campaigns')
                        ->where('user_id', auth()->id());
                })
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });
        }

        // Combine all dates
        $allDates = collect(array_merge(
            array_keys($campaigns->toArray()),
            array_keys($donations->toArray())
        ))->unique()->sort();

        // Prepare data arrays
        foreach ($allDates as $date) {
            $labels[] = $date;
            $campaignData[] = isset($campaigns[$date]) ? $campaigns[$date]->count() : 0;
            $donationData[] = isset($donations[$date]) ? $donations[$date]->count() : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Campaigns Created',
                    'data' => $campaignData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Donations Received',
                    'data' => $donationData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
