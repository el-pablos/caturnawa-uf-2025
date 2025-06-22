<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CompetitionReportExport;
use App\Exports\RegistrationReportExport;
use App\Exports\PaymentReportExport;

/**
 * Controller untuk laporan dan analytics
 * 
 * Menyediakan berbagai laporan untuk admin
 */
class ReportController extends Controller
{
    /**
     * Dashboard laporan utama
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Overview statistics
        $stats = [
            'total_competitions' => Competition::count(),
            'active_competitions' => Competition::where('status', 'active')->count(),
            'total_registrations' => Registration::count(),
            'confirmed_registrations' => Registration::where('status', 'confirmed')->count(),
            'total_revenue' => Payment::whereIn('transaction_status', ['settlement', 'capture'])->sum('gross_amount'),
            'total_users' => User::count(),
        ];

        // Monthly registration trend
        $monthlyRegistrations = Registration::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Revenue trend
        $monthlyRevenue = Payment::select(
            DB::raw('MONTH(paid_at) as month'),
            DB::raw('YEAR(paid_at) as year'),
            DB::raw('SUM(gross_amount) as total')
        )
        ->whereIn('transaction_status', ['settlement', 'capture'])
        ->where('paid_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Top competitions by registrations
        $topCompetitions = Competition::withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'stats', 
            'monthlyRegistrations', 
            'monthlyRevenue', 
            'topCompetitions'
        ));
    }

    /**
     * Laporan kompetisi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function competitions(Request $request)
    {
        $query = Competition::withCount(['registrations', 'confirmedRegistrations']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $competitions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Summary statistics
        $summary = [
            'total_competitions' => $query->count(),
            'total_registrations' => $competitions->sum('registrations_count'),
            'total_confirmed' => $competitions->sum('confirmed_registrations_count'),
        ];

        return view('admin.reports.competitions', compact('competitions', 'summary'));
    }

    /**
     * Laporan registrasi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function registrations(Request $request)
    {
        $query = Registration::with(['user', 'competition', 'payment']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kompetisi
        if ($request->filled('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(20);
        $competitions = Competition::orderBy('name')->get();

        // Summary statistics
        $summary = [
            'total_registrations' => $query->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'confirmed' => $query->where('status', 'confirmed')->count(),
            'cancelled' => $query->where('status', 'cancelled')->count(),
        ];

        return view('admin.reports.registrations', compact('registrations', 'competitions', 'summary'));
    }

    /**
     * Laporan pembayaran
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['registration.user', 'registration.competition']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Summary statistics
        $summary = [
            'total_payments' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'paid_amount' => $query->where('status', 'paid')->sum('amount'),
            'pending_amount' => $query->where('status', 'pending')->sum('amount'),
        ];

        return view('admin.reports.payments', compact('payments', 'summary'));
    }

    /**
     * Export laporan
     * 
     * @param string $type
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function export($type, Request $request)
    {
        $format = $request->get('format', 'excel'); // excel or pdf

        switch ($type) {
            case 'competitions':
                return $this->exportCompetitions($format, $request);
            case 'registrations':
                return $this->exportRegistrations($format, $request);
            case 'payments':
                return $this->exportPayments($format, $request);
            default:
                return back()->with('error', 'Tipe laporan tidak valid.');
        }
    }

    /**
     * Export laporan kompetisi
     */
    private function exportCompetitions($format, $request)
    {
        $competitions = Competition::withCount(['registrations', 'confirmedRegistrations'])->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.exports.competitions-pdf', compact('competitions'));
            return $pdf->download('laporan-kompetisi.pdf');
        }

        // Generate CSV file manually for compatibility
        $export = new CompetitionReportExport($competitions);
        $data = $export->export();

        $filename = 'laporan-kompetisi-' . date('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export laporan registrasi
     */
    private function exportRegistrations($format, $request)
    {
        $registrations = Registration::with(['user', 'competition', 'payment'])->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.exports.registrations-pdf', compact('registrations'));
            return $pdf->download('laporan-registrasi.pdf');
        }

        // Generate CSV file manually for compatibility
        $export = new RegistrationReportExport($registrations);
        $data = $export->export();

        $filename = 'laporan-registrasi-' . date('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export laporan pembayaran
     */
    private function exportPayments($format, $request)
    {
        $payments = Payment::with(['registration.user', 'registration.competition'])->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.exports.payments-pdf', compact('payments'));
            return $pdf->download('laporan-pembayaran.pdf');
        }

        // Generate CSV file manually for compatibility
        $export = new PaymentReportExport($payments);
        $data = $export->export();

        $filename = 'laporan-pembayaran-' . date('Y-m-d-H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get competition distribution data
     */
    public function getCompetitionDistribution()
    {
        try {
            $distribution = Competition::select('category', DB::raw('COUNT(*) as count'))
                ->where('is_active', true)
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $distribution
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat distribusi kompetisi'
            ]);
        }
    }
}
