@extends('layouts.admin')

@section('title', 'Invoice Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Invoice Management</h1>
            <p class="text-muted">Kelola data invoice dan pembayaran untuk Finance Department</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" onclick="refreshInvoices()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
            <button class="btn btn-success" onclick="exportInvoices()">
                <i class="bi bi-download me-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4" id="summary-cards">
        <!-- Will be populated by JavaScript -->
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="status-filter" class="form-label">Status</label>
                    <select id="status-filter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date-from" class="form-label">Tanggal Dari</label>
                    <input type="date" id="date-from" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="date-to" class="form-label">Tanggal Sampai</label>
                    <input type="date" id="date-to" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="search-input" class="form-label">Pencarian</label>
                    <input type="text" id="search-input" class="form-control" placeholder="Cari nama atau email...">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button class="btn btn-primary" onclick="applyFilters()">
                        <i class="bi bi-funnel me-2"></i>Terapkan Filter
                    </button>
                    <button class="btn btn-secondary" onclick="clearFilters()">
                        <i class="bi bi-x-circle me-2"></i>Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Invoice</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="invoiceTable">
                    <thead>
                        <tr>
                            <th>Invoice Number</th>
                            <th>Order ID</th>
                            <th>Peserta</th>
                            <th>Kompetisi</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invoice-tbody">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <nav aria-label="Invoice pagination" class="mt-3">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Will be populated by JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Invoice Detail Modal -->
<div class="modal fade" id="invoiceDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="invoice-detail-content">
                <!-- Will be populated by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="downloadInvoiceJSON()">
                    <i class="bi bi-download me-2"></i>Download JSON
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentInvoiceData = null;
let currentPage = 1;
let currentFilters = {};

document.addEventListener('DOMContentLoaded', function() {
    loadInvoices();
});

async function loadInvoices(page = 1) {
    try {
        const params = new URLSearchParams({
            page: page,
            per_page: 20,
            ...currentFilters
        });

        const response = await fetch(`/api/invoices?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Failed to load invoices');
        }

        const data = await response.json();
        
        if (data.success) {
            displayInvoices(data.data.invoices);
            displaySummary(data.data.summary);
            displayPagination(data.data.pagination);
            currentPage = page;
        }
    } catch (error) {
        console.error('Error loading invoices:', error);
        showAlert('error', 'Gagal memuat data invoice');
    }
}

function displayInvoices(invoices) {
    const tbody = document.getElementById('invoice-tbody');
    
    if (invoices.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data invoice</td></tr>';
        return;
    }

    tbody.innerHTML = invoices.map(invoice => `
        <tr>
            <td><code>${invoice.invoice_number}</code></td>
            <td><small>${invoice.order_id}</small></td>
            <td>
                <strong>${invoice.user_name}</strong><br>
                <small class="text-muted">${invoice.user_email}</small>
            </td>
            <td>${invoice.competition_name}</td>
            <td><strong>${invoice.formatted_amount}</strong></td>
            <td>${getStatusBadge(invoice.status)}</td>
            <td><small>${formatDate(invoice.created_at)}</small></td>
            <td><small>${formatDate(invoice.due_date)}</small></td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="viewInvoiceDetail('${invoice.payment_id}')" title="View Detail">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="downloadInvoice('${invoice.payment_id}')" title="Download">
                        <i class="bi bi-download"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function displaySummary(summary) {
    const container = document.getElementById('summary-cards');
    container.innerHTML = `
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Invoices</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${summary.total_invoices}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Paid Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp ${summary.paid_amount.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp ${summary.pending_amount.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp ${summary.total_amount.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function displayPagination(pagination) {
    const container = document.getElementById('pagination');
    let html = '';

    // Previous button
    if (pagination.current_page > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="loadInvoices(${pagination.current_page - 1})">Previous</a></li>`;
    }

    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.last_page, pagination.current_page + 2); i++) {
        html += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadInvoices(${i})">${i}</a>
                 </li>`;
    }

    // Next button
    if (pagination.current_page < pagination.last_page) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="loadInvoices(${pagination.current_page + 1})">Next</a></li>`;
    }

    container.innerHTML = html;
}

async function viewInvoiceDetail(paymentId) {
    try {
        const response = await fetch(`/api/invoices/${paymentId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Failed to load invoice detail');
        }

        const data = await response.json();
        
        if (data.success) {
            currentInvoiceData = data.data;
            displayInvoiceDetail(data.data);
            
            const modal = new bootstrap.Modal(document.getElementById('invoiceDetailModal'));
            modal.show();
        }
    } catch (error) {
        console.error('Error loading invoice detail:', error);
        showAlert('error', 'Gagal memuat detail invoice');
    }
}

function displayInvoiceDetail(invoice) {
    const container = document.getElementById('invoice-detail-content');
    container.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Invoice Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Invoice Number:</strong></td><td>${invoice.invoice_number}</td></tr>
                    <tr><td><strong>Order ID:</strong></td><td>${invoice.order_id}</td></tr>
                    <tr><td><strong>Status:</strong></td><td>${getStatusBadge(invoice.status)}</td></tr>
                    <tr><td><strong>Amount:</strong></td><td><strong>${invoice.amount.formatted_total}</strong></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Customer Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Name:</strong></td><td>${invoice.user.name}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>${invoice.user.email}</td></tr>
                    <tr><td><strong>Phone:</strong></td><td>${invoice.user.phone || '-'}</td></tr>
                    <tr><td><strong>Institution:</strong></td><td>${invoice.user.institution || '-'}</td></tr>
                </table>
            </div>
        </div>
        <hr>
        <h6>Items</h6>
        <table class="table table-sm">
            <thead>
                <tr><th>Description</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr>
            </thead>
            <tbody>
                ${invoice.items.map(item => `
                    <tr>
                        <td>${item.description}</td>
                        <td>${item.quantity}</td>
                        <td>${item.formatted_price}</td>
                        <td><strong>${item.formatted_price}</strong></td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
        <hr>
        <h6>Finance Notes</h6>
        <p><strong>Accounting Code:</strong> ${invoice.finance_notes.accounting_code}</p>
        <p><strong>Verification Status:</strong> ${invoice.finance_notes.verification_status}</p>
        <p><strong>Processed By:</strong> ${invoice.finance_notes.processed_by}</p>
    `;
}

function applyFilters() {
    currentFilters = {
        status: document.getElementById('status-filter').value,
        date_from: document.getElementById('date-from').value,
        date_to: document.getElementById('date-to').value,
    };
    
    const searchTerm = document.getElementById('search-input').value;
    if (searchTerm) {
        currentFilters.search = searchTerm;
    }
    
    loadInvoices(1);
}

function clearFilters() {
    document.getElementById('status-filter').value = '';
    document.getElementById('date-from').value = '';
    document.getElementById('date-to').value = '';
    document.getElementById('search-input').value = '';
    currentFilters = {};
    loadInvoices(1);
}

function refreshInvoices() {
    loadInvoices(currentPage);
}

function downloadInvoice(paymentId) {
    window.open(`/api/invoices/${paymentId}`, '_blank');
}

function downloadInvoiceJSON() {
    if (currentInvoiceData) {
        const dataStr = JSON.stringify(currentInvoiceData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        const url = URL.createObjectURL(dataBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = `invoice-${currentInvoiceData.invoice_number}.json`;
        link.click();
        URL.revokeObjectURL(url);
    }
}

function exportInvoices() {
    // This would typically call a server endpoint to generate Excel
    showAlert('info', 'Export Excel feature akan segera tersedia');
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'paid': '<span class="badge bg-success">Paid</span>',
        'failed': '<span class="badge bg-danger">Failed</span>',
        'expired': '<span class="badge bg-secondary">Expired</span>'
    };
    return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleString('id-ID');
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-info';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}
</script>
@endpush
