<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Maintenance Settings per Society
        if (!Schema::hasTable('maintenance_settings')) {
            Schema::create('maintenance_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->string('title');
                $table->decimal('amount', 12, 2);
                $table->text('description')->nullable();
                $table->integer('due_date_day')->default(10); // Day of month
                $table->integer('grace_days')->default(0);
                $table->enum('penalty_type', ['fixed', 'percentage'])->default('fixed');
                $table->decimal('penalty_value', 12, 2)->default(0);
                $table->enum('calculation_type', ['flat_wise', 'area_wise', 'common'])->default('common');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Update Maintenance Bills
        Schema::table('maintenance_bills', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenance_bills', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('unit_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('maintenance_bills', 'title')) {
                $table->string('title')->after('user_id')->nullable();
            }
            if (!Schema::hasColumn('maintenance_bills', 'penalty_amount')) {
                $table->decimal('penalty_amount', 12, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('maintenance_bills', 'due_date')) {
                $table->date('due_date')->nullable()->after('year');
            }
            if (!Schema::hasColumn('maintenance_bills', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('maintenance_bills', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('paid_amount');
            }
            if (!Schema::hasColumn('maintenance_bills', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('maintenance_bills', 'transaction_id')) {
                $table->unsignedBigInteger('transaction_id')->nullable()->after('payment_method');
            }
            
            // Change status to include partial and overdue
            $table->string('status')->default('unpaid')->change();
        });

        // Wallets for Advance Balance
        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->decimal('balance', 12, 2)->default(0);
                $table->timestamps();
                $table->unique(['user_id', 'society_id']);
            });
        }

        // Society Expenses
        if (!Schema::hasTable('society_expenses')) {
            Schema::create('society_expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->string('title');
                $table->decimal('amount', 12, 2);
                $table->string('category');
                $table->text('description')->nullable();
                $table->date('expense_date');
                $table->string('attachment_path')->nullable();
                $table->foreignId('created_by')->constrained('users');
                $table->timestamps();
            });
        }

        // Core Transactions Ledger
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('transaction_no')->unique();
                $table->enum('type', ['credit', 'debit']); // credit = money in, debit = money out
                $table->decimal('amount', 12, 2);
                $table->string('source'); // maintenance, penalty, advance, expense, refund
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('payment_mode')->nullable(); // Cash, Online, UPI, Wallet
                $table->text('description')->nullable();
                $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
                $table->timestamps();
            });
        }

        // Refine Passbook Entries
        Schema::table('passbook_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('passbook_entries', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('society_id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('passbook_entries', 'transaction_id')) {
                $table->foreignId('transaction_id')->nullable()->after('user_id')->constrained('transactions')->onDelete('cascade');
            }
            if (!Schema::hasColumn('passbook_entries', 'passbook_type')) {
                $table->enum('passbook_type', ['society', 'user'])->after('transaction_id')->default('society');
            }
            if (!Schema::hasColumn('passbook_entries', 'balance_after')) {
                $table->decimal('balance_after', 12, 2)->default(0)->after('amount');
            }
            
            // Check if column exists before renaming
            if (Schema::hasColumn('passbook_entries', 'type') && !Schema::hasColumn('passbook_entries', 'entry_type')) {
                DB::statement("ALTER TABLE passbook_entries CHANGE type entry_type ENUM('credit', 'debit') NOT NULL");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('passbook_entries')) {
            Schema::table('passbook_entries', function (Blueprint $table) {
                if (Schema::hasColumn('passbook_entries', 'entry_type')) {
                    DB::statement("ALTER TABLE passbook_entries CHANGE entry_type type ENUM('credit', 'debit') NOT NULL");
                }
                $table->dropColumn(['user_id', 'transaction_id', 'passbook_type', 'balance_after']);
            });
        }
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('society_expenses');
        Schema::dropIfExists('wallets');
        if (Schema::hasTable('maintenance_bills')) {
            Schema::table('maintenance_bills', function (Blueprint $table) {
                $table->dropColumn(['user_id', 'title', 'penalty_amount', 'due_date', 'paid_amount', 'paid_at', 'payment_method', 'transaction_id']);
            });
        }
        Schema::dropIfExists('maintenance_settings');
    }
};
