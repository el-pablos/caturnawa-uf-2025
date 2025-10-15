<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

/**
 * Controller for global search functionality
 */
class SearchController extends Controller
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->middleware(['auth']);
        $this->searchService = $searchService;
    }

    /**
     * Display global search page
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $entity = $request->input('entity', 'all');
        
        $results = [];
        $statistics = [];

        if ($query && strlen($query) >= 2) {
            $filters = [
                'entity' => $entity !== 'all' ? $entity : null,
                'competition_id' => $request->input('competition_id'),
                'status' => $request->input('status'),
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ];

            // Remove null filters
            $filters = array_filter($filters, function($value) {
                return $value !== null && $value !== '';
            });

            $results = $this->searchService->globalSearch($query, $filters);
            $statistics = $this->searchService->getSearchStatistics($query);
        }

        return view('admin.search.index', compact('query', 'entity', 'results', 'statistics'));
    }

    /**
     * AJAX search endpoint
     */
    public function ajax(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query must be at least 2 characters',
            ]);
        }

        $filters = [
            'entity' => $request->input('entity'),
        ];

        $filters = array_filter($filters);

        $results = $this->searchService->globalSearch($query, $filters);
        $statistics = $this->searchService->getSearchStatistics($query);

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $results,
            'statistics' => $statistics,
        ]);
    }
}

