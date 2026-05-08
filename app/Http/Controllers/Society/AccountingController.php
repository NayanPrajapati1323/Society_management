<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use App\Models\PassbookEntry;
use App\Models\SocietyExpense;
use App\Models\User;
use App\Models\MaintenanceBill;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingController extends Controller
{
    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    /**
     * Get Society Passbook.
     */
    public function getSocietyPassbook(Request $request)
    {
        $societyId = Auth::user()->society_id;
        $entries = PassbookEntry::where('society_id', $societyId)
            ->where('passbook_type', 'society')
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $entries]);
    }

    /**
     * Get User Passbook.
     */
    public function getUserPassbook(Request $request)
    {
        $userId = $request->user_id ?? Auth::id();
        $societyId = Auth::user()->society_id;
        
        $entries = PassbookEntry::where('society_id', $societyId)
            ->where('user_id', $userId)
            ->where('passbook_type', 'user')
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $entries]);
    }

    /**
     * Add Society Expense.
     */
    public function addExpense(Request $request)
    {
        $request->validate([
            'title'    => 'required|string',
            'amount'   => 'required|numeric',
            'category' => 'required|string',
            'date'     => 'required|date',
        ]);

        $expense = SocietyExpense::create([
            'society_id'   => Auth::user()->society_id,
            'title'        => $request->title,
            'amount'       => $request->amount,
            'category'     => $request->category,
            'description'  => $request->description,
            'expense_date' => $request->date,
            'created_by'   => Auth::id(),
        ]);

        // Record in accounting
        $this->accountingService->recordTransaction([
            'society_id'  => Auth::user()->society_id,
            'type'        => 'debit',
            'amount'      => $request->amount,
            'source'      => 'expense',
            'reference_id' => $expense->id,
            'description' => "Expense: {$request->title}",
        ]);

        // If it's a shared expense, create dues for all users
        if ($request->split_among_users) {
            $users = User::where('society_id', Auth::user()->society_id)->where('is_active', true)->get();
            if ($users->count() > 0) {
                $perUserAmount = $request->amount / $users->count();
                foreach ($users as $user) {
                    MaintenanceBill::create([
                        'society_id'   => Auth::user()->society_id,
                        'user_id'      => $user->id,
                        'title'        => "Share: {$request->title}",
                        'total_amount' => $perUserAmount,
                        'month'        => now()->format('F'),
                        'year'         => now()->format('Y'),
                        'due_date'     => now()->addDays(7),
                        'status'       => 'unpaid',
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'data' => $expense]);
    }

    /**
     * Deposit Advance Maintenance (Add to Wallet).
     */
    public function depositAdvance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount'  => 'required|numeric|min:1',
        ]);

        $this->accountingService->adjustWallet(
            $request->user_id,
            Auth::user()->society_id,
            $request->amount,
            'Advance maintenance deposit'
        );

        return response()->json(['success' => true, 'message' => 'Advance deposited successfully.']);
    }
}
