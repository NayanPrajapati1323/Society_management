<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\PassbookEntry;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Create a double-entry transaction record.
     */
    public function recordTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the master transaction record
            $transaction = Transaction::create([
                'society_id'    => $data['society_id'],
                'user_id'       => $data['user_id'] ?? null,
                'type'          => $data['type'], // credit or debit
                'amount'        => $data['amount'],
                'source'        => $data['source'],
                'reference_id'  => $data['reference_id'] ?? null,
                'payment_mode'  => $data['payment_mode'] ?? 'Cash',
                'description'   => $data['description'] ?? null,
                'status'        => $data['status'] ?? 'completed',
            ]);

            // 2. Create Passbook Entries
            // Society Passbook Entry
            $this->createPassbookEntry([
                'society_id'     => $data['society_id'],
                'user_id'        => $data['user_id'] ?? null,
                'transaction_id' => $transaction->id,
                'passbook_type'  => 'society',
                'entry_type'     => $data['type'],
                'amount'         => $data['amount'],
                'category'       => $data['source'],
                'description'    => $data['description'],
                'entry_date'     => now(),
            ]);

            // User Passbook Entry (if user is involved)
            if (isset($data['user_id'])) {
                $this->createPassbookEntry([
                    'society_id'     => $data['society_id'],
                    'user_id'        => $data['user_id'],
                    'transaction_id' => $transaction->id,
                    'passbook_type'  => 'user',
                    'entry_type'     => $data['type'] === 'credit' ? 'debit' : 'credit', // User's view is opposite
                    'amount'         => $data['amount'],
                    'category'       => $data['source'],
                    'description'    => $data['description'],
                    'entry_date'     => now(),
                ]);
            }

            return $transaction;
        });
    }

    /**
     * Create a single passbook entry and update balance_after.
     */
    protected function createPassbookEntry(array $data)
    {
        $lastEntry = PassbookEntry::where('society_id', $data['society_id'])
            ->where('passbook_type', $data['passbook_type'])
            ->when($data['passbook_type'] === 'user', function ($q) use ($data) {
                return $q->where('user_id', $data['user_id']);
            })
            ->latest()
            ->first();

        $currentBalance = $lastEntry ? $lastEntry->balance_after : 0;
        
        if ($data['entry_type'] === 'credit') {
            $newBalance = $currentBalance + $data['amount'];
        } else {
            $newBalance = $currentBalance - $data['amount'];
        }

        return PassbookEntry::create(array_merge($data, ['balance_after' => $newBalance]));
    }

    /**
     * Adjust user wallet balance.
     */
    public function adjustWallet($userId, $societyId, $amount, $description = 'Wallet adjustment')
    {
        return DB::transaction(function () use ($userId, $societyId, $amount, $description) {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $userId, 'society_id' => $societyId],
                ['balance' => 0]
            );

            $oldBalance = $wallet->balance;
            $wallet->balance += $amount;
            $wallet->save();

            // Record transaction for wallet change
            $this->recordTransaction([
                'society_id'  => $societyId,
                'user_id'     => $userId,
                'type'        => $amount > 0 ? 'credit' : 'debit',
                'amount'      => abs($amount),
                'source'      => 'advance',
                'description' => $description,
            ]);

            return $wallet;
        });
    }
}
