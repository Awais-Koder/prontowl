<?php


use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('share/campaign/{purpose}/{id}', [HomeController::class, 'index'])->name('sahred.campaign');
Route::get('/payment-success', [HomeController::class, 'paymentSuccess'])->name('payment.success');

Route::controller(CampaignController::class)->group(function () {
    Route::get('discover/{category?}', 'discover')->name('campaigns.discover');
    Route::get('show/{id}', 'show')->name('campaign.show');
    Route::get('load/more/donors/{id}', action: 'fetchDonors')->name('campaign.donors');
    Route::get('donate/now/{id}', 'makeDonation')->name('campaign.donate.now');
    Route::post('make/donation/now/{id}', 'payDonation')->name('campaign.make.donation');
});
Route::controller(DepositController::class)->group(function () {
    Route::get('deposit/success', 'depositSuccess')->name('deposit.success');
});


Route::get('/payment-cancel', function () {
    ;
})->name('payment.cancel');
