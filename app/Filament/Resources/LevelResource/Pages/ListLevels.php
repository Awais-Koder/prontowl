<?php

namespace App\Filament\Resources\LevelResource\Pages;

use App\Filament\Resources\LevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListLevels extends ListRecords
{
    protected static string $resource = LevelResource::class;
    public function render(): View
    {
        if (!auth()->user()->hasRole('Admin')) {
            static::$view = 'custom-components.list-levels';
        } else {
            static::$view = parent::$view; // Use default view
        }
        return parent::render();
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
