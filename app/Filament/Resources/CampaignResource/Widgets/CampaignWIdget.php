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
    const FILTER_7_DAYS = '7days';
    const FILTER_1_MONTH = '1month';
    const FILTER_3_MONTHS = '3months';
    const FILTER_6_MONTHS = '6months';
    const FILTER_1_YEAR = '1year';
    const FILTER_ALL_TIME = 'all';
    public ?string $filter = self::FILTER_7_DAYS;
    protected function getFilters(): ?array
    {
        return [
            self::FILTER_7_DAYS => 'Last 7 Days',
            self::FILTER_1_MONTH => 'Last Month',
            self::FILTER_3_MONTHS => 'Last 3 Months',
            self::FILTER_6_MONTHS => 'Last 6 Months',
            self::FILTER_1_YEAR => 'Last Year',
            self::FILTER_ALL_TIME => 'All Time',
        ];
    }
    protected function getStartDate(): Carbon
    {
        return match ($this->filter) {
            self::FILTER_7_DAYS => now()->subDays(7),
            self::FILTER_1_MONTH => now()->subMonth(),
            self::FILTER_3_MONTHS => now()->subMonths(3),
            self::FILTER_6_MONTHS => now()->subMonths(6),
            self::FILTER_1_YEAR => now()->subYear(),
            self::FILTER_ALL_TIME => Carbon::createFromTimestamp(0),
            default => now()->subDays(7),
        };
    }
    protected function getData(): array
    {
        $startDate = $this->getStartDate();

        if (auth()->user()->hasRole('Admin')) {
            $campaigns = Campaign::where('created_at', '>=', $startDate)
                ->select('created_at')
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });

            $donations = Donation::where('created_at', '>=', $startDate)
                ->select('created_at')
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });
        } else {
            $campaigns = Campaign::where('created_at', '>=', $startDate)
                ->where('user_id', auth()->id())
                ->select('created_at')
                ->get()
                ->groupBy(function ($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });

            $donations = Donation::where('created_at', '>=', $startDate)
                ->whereHas('campaign', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->select('created_at')
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
            $donationData[] = isset($donations[$date]) ? $donations[$date]->sum('amount') : 0;
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
