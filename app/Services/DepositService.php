<?php
namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Deposit;
use Illuminate\Support\Facades\DB;

class DepositService
{
    public function deposit($userId, $amount)
    {
        DB::transaction(function () use ($userId, $amount) {
            // Record the deposit in the deposits table
            $deposit = Deposit::create([
                'user_id' => $userId,
                'amount' => $amount,
            ]);

            // Fetch or create the user's account
            $account = Account::firstOrCreate(
                ['user_id' => $userId],
                ['balance' => 0]
            );

            // Update the account balance
            $account->balance += $amount;
            $account->save();

            // Record the transaction in the transactions table
            Transaction::create([
                'account_id' => $account->id,
                'user_id' => auth()->id(),
                'type' => 'deposit',
                'amount' => $amount,
                'balance_after' => $account->balance,
                'description' => "Deposit ID: {$deposit->id}",
            ]);
        });
    }
}
