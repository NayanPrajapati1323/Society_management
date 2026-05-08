<?php

namespace App\Services;

use App\Models\MaintenanceBill;
use App\Models\MaintenanceSetting;
use App\Models\User;
use App\Models\Society;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MaintenanceService
{
    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    /**
     * Generate maintenance bills for all users in a society.
     */
    public function generateMonthlyBills($societyId, $month = null, $year = null)
    {
        $month = $month ?? now()->format('F');
        $year = $year ?? now()->format('Y');
        
        $settings = MaintenanceSetting::where('society_id', $societyId)->where('is_active', true)->first();
        if (!$settings) return;

        $users = User::where('society_id', $societyId)->where('is_active', true)->get();

        foreach ($users as $user) {
            $this->generateBillForUser($user, $settings, $month, $year);
        }
    }

    /**
     * Generate a single bill for a user.
     */
    public function generateBillForUser(User $user, MaintenanceSetting $settings, $month, $year)
    {
        // Check if bill already exists
        $exists = MaintenanceBill::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->exists();
        
        if ($exists) return;

        $dueDate = Carbon::now()->day($settings->due_date_day);
        if ($dueDate->isPast()) {
            $dueDate->addMonth();
        }

        $bill = MaintenanceBill::create([
            'society_id'   => $user->society_id,
            'user_id'      => $user->id,
            'unit_id'      => $user->unit_id ?? null,
            'title'        => $settings->title,
            'total_amount' => $settings->amount,
            'month'        => $month,
            'year'         => $year,
            'due_date'     => $dueDate,
            'status'       => 'unpaid',
        ]);

        // Send Notification
        $user->notify(new \App\Notifications\MaintenanceGeneratedNotification($bill));

        // Attempt auto-pay from wallet
        $this->attemptAutoPayment($bill);

        return $bill;
    }

    /**
     * Attempt to pay bill using advance balance.
     */
    public function attemptAutoPayment(MaintenanceBill $bill)
    {
        $wallet = Wallet::where('user_id', $bill->user_id)->first();
        
        if ($wallet && $wallet->balance >= $bill->total_amount) {
            DB::transaction(function () use ($bill, $wallet) {
                $amount = $bill->total_amount;
                
                // 1. Deduct from wallet
                $wallet->balance -= $amount;
                $wallet->save();

                // 2. Mark bill as paid
                $bill->update([
                    'status'         => 'paid',
                    'paid_amount'    => $amount,
                    'paid_at'        => now(),
                    'payment_method' => 'Wallet',
                ]);

                // 3. Record transaction
                $this->accountingService->recordTransaction([
                    'society_id'   => $bill->society_id,
                    'user_id'      => $bill->user_id,
                    'type'         => 'debit', // User debit
                    'amount'       => $amount,
                    'source'       => 'maintenance',
                    'reference_id' => $bill->id,
                    'payment_mode' => 'Wallet',
                    'description'  => "Auto-debit for {$bill->month} {$bill->year} maintenance",
                ]);
            });
        }
    }

    /**
     * Apply penalties to overdue bills.
     */
    public function applyPenalties()
    {
        $overdueBills = MaintenanceBill::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->get();

        foreach ($overdueBills as $bill) {
            $settings = MaintenanceSetting::where('society_id', $bill->society_id)->first();
            if (!$settings || $settings->penalty_value <= 0) continue;

            // Check grace days
            $penaltyDate = $bill->due_date->addDays($settings->grace_days);
            if (now()->greaterThan($penaltyDate)) {
                $penalty = 0;
                if ($settings->penalty_type === 'fixed') {
                    $penalty = $settings->penalty_value;
                } else {
                    $penalty = ($bill->total_amount * $settings->penalty_value) / 100;
                }

                if ($bill->penalty_amount < $penalty) {
                    $bill->update([
                        'penalty_amount' => $penalty,
                        'status'         => 'overdue'
                    ]);
                }
            }
        }
    }
}
