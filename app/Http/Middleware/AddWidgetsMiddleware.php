<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Facades\Filament;
use App\Filament\Resources\CampaignResource\Widgets\CampaignWidget;

class AddWidgetsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->hasRole('Admin')) {
            Filament::getCurrentPanel()->widgets([
                CampaignWidget::class,
            ]);
        }

        return $next($request);
    }
}
