<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use App\Services\DepositService;
class DepositController extends Controller
{
    public $depositService;
    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    public function depositSuccess()
    {
        $deposit_amount = Session::get('deposit_amount');
        $this->depositService->deposit(auth()->id(), $deposit_amount);
        Notification::make()
            ->title('Deposit Successful')
            ->body('Your deposit of $' . number_format($deposit_amount, 2) . ' has been successfully processed.')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->iconColor('green')
            ->duration(5000)
            ->send();
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
