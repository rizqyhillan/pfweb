<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Laporan ringkasan.
     */
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Transaction::query();

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $totalRevenue = (clone $query)->where('status', 'lunas')->sum('total');
        $monthlyRevenue = Transaction::where('status', 'lunas')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->sum('total');
        $totalTransactions = (clone $query)->count();
        $paidTransactions = (clone $query)->where('status', 'lunas')->count();

        return view('admin.reports.index', compact(
            'totalRevenue', 'monthlyRevenue', 'totalTransactions', 'paidTransactions',
            'startDate', 'endDate'
        ));
    }

    /**
     * Export laporan transaksi ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Transaction::with(['pelanggan', 'kasir'])->latest('tanggal');

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $transactions = $query->get();
        $totalTransactions = $transactions->count();
        $paidTransactions = $transactions->where('status', 'lunas')->count();
        $totalRevenue = $transactions->where('status', 'lunas')->sum('total');
        $totalSubtotal = $transactions->where('status', 'lunas')->sum('subtotal');
        $totalDiskon = $transactions->where('status', 'lunas')->sum('diskon');

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'transactions', 'totalTransactions', 'paidTransactions',
            'totalRevenue', 'totalSubtotal', 'totalDiskon',
            'startDate', 'endDate'
        ));

        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-transaksi-'.now()->format('Y-m-d-His').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export laporan transaksi ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $filename = 'laporan-transaksi-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new TransactionExport($startDate, $endDate), $filename);
    }
}
