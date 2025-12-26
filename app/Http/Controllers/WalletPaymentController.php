<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WalletPaymentController extends AppBaseController
{
    /**
     * Display a listing of wallet payments
     */
    public function index()
    {
        $walletPayments = Transaction::where('meta->is_wallet_addon', true)
            ->with(['user', 'tenant'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalRevenue = Transaction::where('meta->is_wallet_addon', true)
            ->where('status', Transaction::SUCCESS)
            ->sum('amount');

        $totalPayments = Transaction::where('meta->is_wallet_addon', true)
            ->where('status', Transaction::SUCCESS)
            ->count();

        $monthlyRevenue = Transaction::where('meta->is_wallet_addon', true)
            ->where('status', Transaction::SUCCESS)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');

        return view('sadmin.wallet_payments.index', compact(
            'walletPayments', 
            'totalRevenue', 
            'totalPayments', 
            'monthlyRevenue'
        ));
    }

    /**
     * Show wallet payment details
     */
    public function show($id)
    {
        $payment = Transaction::where('id', $id)
            ->where('meta->is_wallet_addon', true)
            ->with(['user', 'tenant'])
            ->firstOrFail();

        return view('sadmin.wallet_payments.show', compact('payment'));
    }

    /**
     * Get wallet payment statistics
     */
    public function getStatistics()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthRevenue = Transaction::where('meta->is_wallet_addon', true)
            ->where('status', Transaction::SUCCESS)
            ->whereMonth('created_at', $currentMonth->month)
            ->sum('amount');

        $lastMonthRevenue = Transaction::where('meta->is_wallet_addon', true)
            ->where('status', Transaction::SUCCESS)
            ->whereMonth('created_at', $lastMonth->month)
            ->sum('amount');

        $growthPercentage = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        $paymentMethods = Transaction::where('meta->is_wallet_addon', true)
            ->where('status', Transaction::SUCCESS)
            ->selectRaw('payment_type, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_type')
            ->get();

        return response()->json([
            'current_month_revenue' => $currentMonthRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'growth_percentage' => round($growthPercentage, 2),
            'payment_methods' => $paymentMethods
        ]);
    }

    /**
     * Export wallet payments
     */
    public function export(Request $request)
    {
        $payments = Transaction::where('meta->is_wallet_addon', true)
            ->with(['user', 'tenant'])
            ->when($request->date_from, function($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        $filename = 'wallet_payments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'User', 'Email', 'Amount', 'Currency', 'Payment Method', 
                'Status', 'Date', 'Transaction ID'
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->user->full_name ?? 'N/A',
                    $payment->user->email ?? 'N/A',
                    $payment->amount,
                    $payment->currency ?? 'USD',
                    $payment->payment_type,
                    $payment->status == Transaction::SUCCESS ? 'Success' : 'Failed',
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->transaction_id ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 