<?php

namespace App\Filament\Resources\DonationResource\Widgets;

use App\Models\Campaign;
use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use App\Models\Donation;
use Carbon\Carbon;

class DonationWidget extends AdvancedChartWidget
{
    protected static ?string $heading = 'Donations';

    protected function getData(): array
    {
        if (auth()->user()->hasRole('Admin')) {
            // Fetch donation data for the last 30 days
            $donations = Donation::select('amount', 'created_at')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d'); // Group by dates
                });

            $labels = [];
            $data = [];

            foreach ($donations as $date => $donation) {
                $labels[] = $date;
                $data[] = $donation->sum('amount');
            }
        } elseif (auth()->check()) {

            $campaigns = Campaign::with('donations')
                ->where('user_id', auth()->id())
                ->get();

            $labels = [];
            $data = [];

            // Iterate over each campaign and its donations
            foreach ($campaigns as $campaign) {
                foreach ($campaign->donations as $donation) {
                    $date = Carbon::parse($donation->created_at)->format('Y-m-d');
                    if (!isset($data[$date])) {
                        $data[$date] = 0;
                    }
                    $data[$date] += $donation->amount;
                }
            }

            // Prepare labels and data for the chart
            $labels = array_keys($data);
            $data = array_values($data);
        }
        else {
            $labels = [];
            $data = [];
        }
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Donations',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
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
