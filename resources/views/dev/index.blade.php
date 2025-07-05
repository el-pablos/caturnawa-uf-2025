@extends('layouts.app')

@section('title', 'Development Tools')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">🛠️ Development Tools</h1>
                <div class="badge bg-warning text-dark">{{ config('app.env') }} Environment</div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Overview -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $stats['total_users'] }}</h5>
                            <p class="card-text small">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">{{ $stats['total_registrations'] }}</h5>
                            <p class="card-text small">Total Registrations</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning">{{ $stats['pending_registrations'] }}</h5>
                            <p class="card-text small">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ $stats['confirmed_registrations'] }}</h5>
                            <p class="card-text small">Confirmed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">{{ $stats['total_payments'] }}</h5>
                            <p class="card-text small">Total Payments</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">{{ $stats['paid_payments'] }}</h5>
                            <p class="card-text small">Paid</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="row mb-4">
                <!-- Reset Payments -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">🔄 Reset Payment Data</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dev.reset-payments') }}" method="POST" onsubmit="return confirm('Are you sure? This will delete payment data!')">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Reset Scope:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="scope" value="user" id="scope_user">
                                        <label class="form-check-label" for="scope_user">Specific User</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="scope" value="registration" id="scope_registration">
                                        <label class="form-check-label" for="scope_registration">Specific Registration</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="scope" value="all" id="scope_all">
                                        <label class="form-check-label" for="scope_all">All Data (DANGEROUS)</label>
                                    </div>
                                </div>
                                <div class="mb-3" id="user_input" style="display: none;">
                                    <input type="number" class="form-control" name="user_id" placeholder="User ID">
                                </div>
                                <div class="mb-3" id="registration_input" style="display: none;">
                                    <input type="number" class="form-control" name="registration_id" placeholder="Registration ID">
                                </div>
                                <input type="hidden" name="reset_all" value="0" id="reset_all_input">
                                <button type="submit" class="btn btn-danger">Reset Data</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Regenerate QR -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">📱 Regenerate QR Codes</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dev.regenerate-qr') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">User ID (optional):</label>
                                    <input type="number" class="form-control" name="user_id" placeholder="Leave empty for all confirmed">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Registration ID (optional):</label>
                                    <input type="number" class="form-control" name="registration_id" placeholder="Specific registration">
                                </div>
                                <button type="submit" class="btn btn-info">Regenerate QR</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Test Payment -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">💳 Test Payment</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dev.test-payment') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Registration ID:</label>
                                    <input type="number" class="form-control" name="registration_id" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Status:</label>
                                    <select class="form-select" name="status" required>
                                        <option value="pending">Pending</option>
                                        <option value="settlement">Settlement (Paid)</option>
                                        <option value="cancel">Cancel</option>
                                        <option value="expire">Expire</option>
                                        <option value="failure">Failure</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-warning">Create Test Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Data Tables -->
            <div class="row">
                <!-- Recent Registrations -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">📋 Recent Registrations</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Competition</th>
                                            <th>Status</th>
                                            <th>Payments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_registrations as $reg)
                                        <tr>
                                            <td>{{ $reg->id }}</td>
                                            <td>{{ $reg->user->name }}</td>
                                            <td>{{ Str::limit($reg->competition->name, 20) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $reg->status === 'confirmed' ? 'success' : ($reg->status === 'paid' ? 'primary' : 'warning') }}">
                                                    {{ $reg->status }}
                                                </span>
                                            </td>
                                            <td>{{ $reg->payments->count() }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">💰 Recent Payments</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Method</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->registration->user->name }}</td>
                                            <td>Rp {{ number_format($payment->gross_amount) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payment->transaction_status === 'settlement' ? 'success' : 'warning' }}">
                                                    {{ $payment->transaction_status }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scopeRadios = document.querySelectorAll('input[name="scope"]');
    const userInput = document.getElementById('user_input');
    const registrationInput = document.getElementById('registration_input');
    const resetAllInput = document.getElementById('reset_all_input');

    scopeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            userInput.style.display = 'none';
            registrationInput.style.display = 'none';
            resetAllInput.value = '0';

            if (this.value === 'user') {
                userInput.style.display = 'block';
            } else if (this.value === 'registration') {
                registrationInput.style.display = 'block';
            } else if (this.value === 'all') {
                resetAllInput.value = '1';
            }
        });
    });
});
</script>
@endsection
