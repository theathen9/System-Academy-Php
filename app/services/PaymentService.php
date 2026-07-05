<?php

namespace App\Services;
use App\Models\Payment;

class PaymentService
{
    public static function totalRevenue(): float
    {
        return Payment::sum('amount');
    }

    public static function todayRevenue(): float
    {
        return Payment::whereDate(
                'payment_date',
                date('Y-m-d')
            )
            ->sum('amount');
    }

    public static function monthlyRevenue(): float
    {
        return Payment::whereMonth(
                'payment_date',
                date('m')
            )
            ->sum('amount');
    }

    public static function revenueTrend(): array
    {
        return Payment::select([
                'DATE(payment_date) as date',
                'SUM(amount) as total'
            ])
            ->groupBy('DATE(payment_date)')
            ->get();
    }
}