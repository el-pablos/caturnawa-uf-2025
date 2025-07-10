<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Payment - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Test Payment System</h4>
                    </div>
                    <div class="card-body">
                        <div id="status" class="alert alert-info">
                            Ready to test payment...
                        </div>
                        
                        <div class="mb-3">
                            <label for="registrationId" class="form-label">Registration ID:</label>
                            <input type="number" class="form-control" id="registrationId" value="2">
                        </div>
                        
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method:</label>
                            <select class="form-control" id="paymentMethod">
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="gopay">GoPay</option>
                                <option value="shopeepay">ShopeePay</option>
                            </select>
                        </div>
                        
                        <button type="button" class="btn btn-primary" id="testPaymentBtn">
                            Test Payment Process
                        </button>
                        
                        <div class="mt-4">
                            <h5>Debug Information:</h5>
                            <pre id="debugInfo"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ \App\Helpers\MidtransHelper::getSnapJsUrl() }}" data-client-key="{{ \App\Helpers\MidtransHelper::getClientKey() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusDiv = document.getElementById('status');
            const debugInfo = document.getElementById('debugInfo');
            const testBtn = document.getElementById('testPaymentBtn');
            
            function updateStatus(message, type = 'info') {
                statusDiv.className = `alert alert-${type}`;
                statusDiv.textContent = message;
            }
            
            function addDebugInfo(info) {
                debugInfo.textContent += new Date().toLocaleTimeString() + ': ' + info + '\n';
            }
            
            // Check if Midtrans Snap is loaded
            if (typeof snap !== 'undefined') {
                addDebugInfo('✅ Midtrans Snap loaded successfully');
            } else {
                addDebugInfo('❌ Midtrans Snap not loaded');
            }
            
            // Check CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                addDebugInfo('✅ CSRF token found: ' + csrfToken.content.substring(0, 10) + '...');
            } else {
                addDebugInfo('❌ CSRF token not found');
            }
            
            // Check Midtrans configuration
            addDebugInfo('Midtrans Client Key: {{ \App\Helpers\MidtransHelper::getClientKey() }}');
            addDebugInfo('Midtrans Environment: {{ \App\Helpers\MidtransHelper::isProduction() ? "Production" : "Sandbox" }}');
            addDebugInfo('Snap JS URL: {{ \App\Helpers\MidtransHelper::getSnapJsUrl() }}');
            
            testBtn.addEventListener('click', function() {
                const registrationId = document.getElementById('registrationId').value;
                const paymentMethod = document.getElementById('paymentMethod').value;
                
                if (!registrationId) {
                    updateStatus('Please enter a registration ID', 'danger');
                    return;
                }
                
                updateStatus('Processing payment...', 'warning');
                testBtn.disabled = true;
                testBtn.textContent = 'Processing...';
                
                addDebugInfo('Starting payment process for registration: ' + registrationId);
                addDebugInfo('Payment method: ' + paymentMethod);
                
                // Create form data
                const formData = new FormData();
                formData.append('payment_method', paymentMethod);
                
                // Make request to payment process endpoint
                fetch(`/payment/process/${registrationId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                })
                .then(response => {
                    addDebugInfo('Response status: ' + response.status);
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    addDebugInfo('Response data: ' + JSON.stringify(data, null, 2));
                    
                    if (data.success && data.snap_token) {
                        updateStatus('Payment token received, opening Snap...', 'success');
                        addDebugInfo('✅ Snap token received: ' + data.snap_token.substring(0, 20) + '...');
                        
                        // Open Midtrans Snap
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                addDebugInfo('✅ Payment success: ' + JSON.stringify(result));
                                updateStatus('Payment successful!', 'success');
                            },
                            onPending: function(result) {
                                addDebugInfo('⏳ Payment pending: ' + JSON.stringify(result));
                                updateStatus('Payment pending...', 'warning');
                            },
                            onError: function(result) {
                                addDebugInfo('❌ Payment error: ' + JSON.stringify(result));
                                updateStatus('Payment error occurred', 'danger');
                            },
                            onClose: function() {
                                addDebugInfo('Payment popup closed by user');
                                updateStatus('Payment popup closed', 'info');
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Failed to get payment token');
                    }
                })
                .catch(error => {
                    addDebugInfo('❌ Error: ' + error.message);
                    updateStatus('Error: ' + error.message, 'danger');
                })
                .finally(() => {
                    testBtn.disabled = false;
                    testBtn.textContent = 'Test Payment Process';
                });
            });
        });
    </script>
</body>
</html>
