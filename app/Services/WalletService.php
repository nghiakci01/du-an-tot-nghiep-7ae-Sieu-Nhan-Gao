<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Credit (add) money to user's wallet.
     */
    public function credit(User $user, float $amount, string $description, string $refType = null, int $refId = null): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $description, $refType, $refId) {
            $user->increment('wallet_balance', $amount);
            $user->refresh();

            return WalletTransaction::create([
                'user_id'        => $user->id,
                'type'           => 'credit',
                'amount'         => $amount,
                'balance_after'  => $user->wallet_balance,
                'description'    => $description,
                'reference_type' => $refType,
                'reference_id'   => $refId,
            ]);
        });
    }

    /**
     * Debit (subtract) money from user's wallet.
     * Returns false if insufficient balance.
     */
    public function debit(User $user, float $amount, string $description, string $refType = null, int $refId = null): WalletTransaction|bool
    {
        if ($user->wallet_balance < $amount) {
            return false;
        }

        return DB::transaction(function () use ($user, $amount, $description, $refType, $refId) {
            $user->decrement('wallet_balance', $amount);
            $user->refresh();

            return WalletTransaction::create([
                'user_id'        => $user->id,
                'type'           => 'debit',
                'amount'         => $amount,
                'balance_after'  => $user->wallet_balance,
                'description'    => $description,
                'reference_type' => $refType,
                'reference_id'   => $refId,
            ]);
        });
    }
}
