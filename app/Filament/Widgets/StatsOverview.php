<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Machine;
use App\Models\Worker;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        
        // Base queries scoped to the user's refinery
        $transactionQuery = Transaction::query();
        $machineQuery = Machine::query();
        $workerQuery = Worker::query();

        if (! $user->isSystemAdmin()) {
            $transactionQuery->where('refinery_id', $user->refinery_id);
            $machineQuery->where('refinery_id', $user->refinery_id);
            $workerQuery->where('refinery_id', $user->refinery_id);
        }

        // Calculate Totals
        $totalRevenue = $transactionQuery->where('status', 'completed')->sum('total_amount');
        $activeMachines = $machineQuery->where('is_active', true)->count();
        $totalWorkers = $workerQuery->count();
        $pendingTransactions = (clone $transactionQuery)->where('status', 'pending')->count();

        return [
            Stat::make('إجمالي الإيرادات', number_format($totalRevenue, 2) . ' SDG')
                ->description('المعاملات المكتملة فقط')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Dummy data for the sparkline
                ->color('success'),

            Stat::make('الآلات النشطة', $activeMachines)
                ->description('جاهزة للعمل حالياً')
                ->descriptionIcon('heroicon-m-cpu-chip')
                ->color('info'),

            Stat::make('المعاملات المعلقة', $pendingTransactions)
                ->description('تحتاج إلى مراجعة')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingTransactions > 0 ? 'warning' : 'gray'),
                
            Stat::make('إجمالي العمال', $totalWorkers)
                ->description('القوى العاملة المسجلة')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}