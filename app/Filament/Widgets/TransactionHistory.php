<?php

namespace App\Filament\Widgets;

use App\Models\Campaign;
use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Collection;
use Faker\Factory as Faker;
use Filament\Tables\Columns\TextColumn;

class TransactionHistory extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = Transaction::latest();

                if (!auth()->user()->hasRole('Admin')) {
                    $query->where('user_id', auth()->id());
                }

                return $query;
            })
            ->columns([
                TextColumn::make('user.name')->label('Name'),
                TextColumn::make('user.email')->label('Email')->limit(10),
                TextColumn::make('amount')->label('Amount')->icon('heroicon-s-currency-dollar')->iconColor('green'),
                TextColumn::make('type')->label('Type')->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('created_at')->label('Timestamp')->dateTime(),
            ]);
    }
}
