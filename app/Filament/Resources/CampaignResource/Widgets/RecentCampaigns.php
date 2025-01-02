<?php

namespace App\Filament\Resources\CampaignResource\Widgets;

use Filament\Widgets\Widget;

class RecentCampaigns extends Widget
{
    protected int | string | array $columnSpan = '1';
    protected static ?int $sort = 3;
    protected static string $view = 'filament.resources.campaign-resource.widgets.recent-campaigns';
}
