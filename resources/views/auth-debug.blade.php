<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Auth Debug - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Authentication Debug</h4>
                    </div>
                    <div class="card-body">
                        <h5>Authentication Status:</h5>
                        <ul>
                            <li><strong>Authenticated:</strong> {{ Auth::check() ? 'YES' : 'NO' }}</li>
                            @if(Auth::check())
                                <li><strong>User ID:</strong> {{ Auth::id() }}</li>
                                <li><strong>User Email:</strong> {{ Auth::user()->email }}</li>
                                <li><strong>User Name:</strong> {{ Auth::user()->name }}</li>
                                <li><strong>User Role:</strong> {{ Auth::user()->roles->pluck('name')->implode(', ') }}</li>
                            @endif
                        </ul>
                        
                        <h5>CSRF Token:</h5>
                        <p><code>{{ csrf_token() }}</code></p>
                        
                        <h5>Session Info:</h5>
                        <ul>
                            <li><strong>Session ID:</strong> {{ session()->getId() }}</li>
                            <li><strong>Session Driver:</strong> {{ config('session.driver') }}</li>
                        </ul>
                        
                        @if(Auth::check())
                            <h5>User Registrations:</h5>
                            @php
                                $registrations = Auth::user()->registrations;
                            @endphp
                            @if($registrations->count() > 0)
                                <ul>
                                    @foreach($registrations as $reg)
                                        <li>
                                            <strong>ID:</strong> {{ $reg->id }} | 
                                            <strong>Competition:</strong> {{ $reg->competition->name }} | 
                                            <strong>Status:</strong> {{ $reg->status }} | 
                                            <strong>Amount:</strong> Rp {{ number_format($reg->amount) }}
                                            <br>
                                            <a href="{{ route('payment.checkout', $reg) }}" class="btn btn-sm btn-primary mt-1">
                                                Go to Payment
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No registrations found.</p>
                            @endif
                        @endif
                        
                        <div class="mt-4">
                            <h5>Test Payment API:</h5>
                            <button type="button" class="btn btn-warning" id="testApiBtn">
                                Test Payment API Call
                            </button>
                            <div class="mt-3">
                                <pre id="apiResult"></pre>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            @if(Auth::check())
                                <a href="{{ route('logout') }}" class="btn btn-danger"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('testApiBtn').addEventListener('click', function() {
            const resultDiv = document.getElementById('apiResult');
            resultDiv.textContent = 'Testing...';

            @if(Auth::check() && Auth::user()->registrations->count() > 0)
                const registrationId = {{ Auth::user()->registrations->first()->id }};

                // Add more debugging
                console.log('Starting payment test...');
                console.log('Registration ID:', registrationId);
                console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').content);

                fetch(`/payment/process/${registrationId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin', // Important for session cookies
                    body: 'payment_method=bank_transfer'
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response ok:', response.ok);
                    console.log('Response type:', response.type);
                    console.log('Response url:', response.url);

                    // Log response headers
                    for (let [key, value] of response.headers.entries()) {
                        console.log(`${key}: ${value}`);
                    }

                    return response.text(); // Get as text first to see what we're getting
                })
                .then(text => {
                    console.log('Response text:', text);

                    // Try to parse as JSON
                    try {
                        const data = JSON.parse(text);
                        resultDiv.textContent = `Status: Success\nJSON Response:\n${JSON.stringify(data, null, 2)}`;
                        console.log('Parsed JSON:', data);
                    } catch (e) {
                        resultDiv.textContent = `Status: Error - Not JSON\nResponse: ${text.substring(0, 500)}...`;
                        console.error('JSON parse error:', e);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    resultDiv.textContent = `Status: Error\nError: ${error.message}`;
                });
            @else
                resultDiv.textContent = 'No registration found or not authenticated';
            @endif
        });
    </script>
</body>
</html>
