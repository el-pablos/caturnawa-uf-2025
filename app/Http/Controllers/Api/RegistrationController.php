<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Registration::with(['competition', 'payment'])
            ->where('user_id', $user->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by competition
        if ($request->has('competition_id')) {
            $query->where('competition_id', $request->competition_id);
        }

        $registrations = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function($registration) {
                return [
                    'id' => $registration->id,
                    'registration_number' => $registration->registration_number,
                    'competition' => [
                        'id' => $registration->competition->id,
                        'name' => $registration->competition->name,
                        'category' => $registration->competition->category,
                    ],
                    'status' => $registration->status,
                    'team_name' => $registration->team_name,
                    'payment_status' => $registration->payment?->status,
                    'created_at' => $registration->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    public function datatable(Request $request)
    {
        $user = Auth::user();
        
        $query = Registration::with(['competition', 'payment'])
            ->where('user_id', $user->id);

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('team_name', 'like', "%{$search}%")
                  ->orWhereHas('competition', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Total records
        $totalRecords = $query->count();

        // Order
        if ($request->has('order')) {
            $orderColumn = $request->columns[$request->order[0]['column']]['data'] ?? 'created_at';
            $orderDirection = $request->order[0]['dir'] ?? 'desc';
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        if ($request->has('start') && $request->has('length')) {
            $query->offset($request->start)->limit($request->length);
        }

        $registrations = $query->get()->map(function($registration) {
            return [
                'id' => $registration->id,
                'registration_number' => $registration->registration_number,
                'competition_name' => $registration->competition->name,
                'competition_category' => $registration->competition->category,
                'status' => $registration->status,
                'team_name' => $registration->team_name ?? '-',
                'payment_status' => $registration->payment?->status ?? 'unpaid',
                'created_at' => $registration->created_at->format('d/m/Y H:i'),
                'actions' => view('peserta.registrations.actions', compact('registration'))->render(),
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $registrations
        ]);
    }
}
