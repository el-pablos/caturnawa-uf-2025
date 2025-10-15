@extends('layouts.admin')

@section('title', 'Export Data')

@section('page-title', 'Export Data & Reports')

@section('content')
<div class="row">
    <!-- Export Registrations -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check me-2"></i>Export Registrations
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.export.registrations') }}">
                    <div class="mb-3">
                        <label for="reg_competition_id" class="form-label">Competition (Optional)</label>
                        <select name="competition_id" id="reg_competition_id" class="form-select">
                            <option value="">All Competitions</option>
                            @foreach($competitions as $competition)
                                <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reg_status" class="form-label">Status (Optional)</label>
                        <select name="status" id="reg_status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="reg_date_from" class="form-label">Date From</label>
                            <input type="date" name="date_from" id="reg_date_from" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="reg_date_to" class="form-label">Date To</label>
                            <input type="date" name="date_to" id="reg_date_to" class="form-control">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-download me-1"></i>Export to CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Payments -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cash-coin me-2"></i>Export Payments
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.export.payments') }}">
                    <div class="mb-3">
                        <label for="pay_competition_id" class="form-label">Competition (Optional)</label>
                        <select name="competition_id" id="pay_competition_id" class="form-select">
                            <option value="">All Competitions</option>
                            @foreach($competitions as $competition)
                                <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pay_status" class="form-label">Status (Optional)</label>
                        <select name="status" id="pay_status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="settlement">Settlement</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pay_date_from" class="form-label">Date From</label>
                            <input type="date" name="date_from" id="pay_date_from" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pay_date_to" class="form-label">Date To</label>
                            <input type="date" name="date_to" id="pay_date_to" class="form-control">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-download me-1"></i>Export to CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Submissions -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>Export Submissions
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.export.submissions') }}">
                    <div class="mb-3">
                        <label for="sub_competition_id" class="form-label">Competition (Optional)</label>
                        <select name="competition_id" id="sub_competition_id" class="form-select">
                            <option value="">All Competitions</option>
                            @foreach($competitions as $competition)
                                <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sub_is_final" class="form-label">Status (Optional)</label>
                        <select name="is_final" id="sub_is_final" class="form-select">
                            <option value="">All Submissions</option>
                            <option value="1">Final Only</option>
                            <option value="0">Draft Only</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sub_date_from" class="form-label">Date From</label>
                            <input type="date" name="date_from" id="sub_date_from" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sub_date_to" class="form-label">Date To</label>
                            <input type="date" name="date_to" id="sub_date_to" class="form-control">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-download me-1"></i>Export to CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Export Scores -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-star me-2"></i>Export Scores
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.export.scores') }}">
                    <div class="mb-3">
                        <label for="score_competition_id" class="form-label">Competition (Optional)</label>
                        <select name="competition_id" id="score_competition_id" class="form-select">
                            <option value="">All Competitions</option>
                            @foreach($competitions as $competition)
                                <option value="{{ $competition->id }}">{{ $competition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="score_is_final" class="form-label">Status (Optional)</label>
                        <select name="is_final" id="score_is_final" class="form-select">
                            <option value="">All Scores</option>
                            <option value="1">Final Only</option>
                            <option value="0">Draft Only</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Scores will be exported with judge names and criteria breakdown.
                    </div>
                    
                    <button type="submit" class="btn btn-info w-100">
                        <i class="bi bi-download me-1"></i>Export to CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Export Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>Quick Export (All Data)
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.export.registrations') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-clipboard-check me-1"></i>All Registrations
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.export.payments') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-cash-coin me-1"></i>All Payments
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.export.submissions') }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-file-earmark-text me-1"></i>All Submissions
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.export.scores') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-star me-1"></i>All Scores
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Instructions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-question-circle me-2"></i>Export Instructions
                </h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>All exports are in CSV format with UTF-8 encoding</li>
                    <li>Use filters to narrow down the data you want to export</li>
                    <li>Leave filters empty to export all data</li>
                    <li>CSV files can be opened in Excel, Google Sheets, or any spreadsheet application</li>
                    <li>For large datasets, the export may take a few seconds</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

