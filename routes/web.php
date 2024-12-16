<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('share/campaign/{purpose}/{id}', [HomeController::class, 'index'])->name('sahred.campaign');
Route::get('/payment-success',[HomeController::class, 'paymentSuccess'])->name('payment.success');

// Route::get('admin/donations')->name('filament.admin.pages.dashboard');

Route::get('/payment-cancel', function () {
    Notification::make()
        ->title('Payment Unsuccessfull')
        ->body('Some thing bad happened.')
        ->danger()
        ->send();
    return redirect()->back();
})->name('payment.cancel');
