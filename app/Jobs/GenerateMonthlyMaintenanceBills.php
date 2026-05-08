<?php

namespace App\Jobs;

use App\Models\Society;
use App\Services\MaintenanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMonthlyMaintenanceBills implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(MaintenanceService $maintenanceService)
    {
        $societies = Society::where('is_active', true)->get();

        foreach ($societies as $society) {
            $maintenanceService->generateMonthlyBills($society->id);
        }
    }
}
