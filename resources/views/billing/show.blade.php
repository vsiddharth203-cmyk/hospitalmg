@extends('layouts.app')
@section('title', 'Bill Details')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>{{ $billing->bill_number }}</h4>
            <p>Created {{ $billing->bill_date->format('d F Y') }} · Due {{ $billing->due_date->format('d F Y') }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        @php $bc=['paid'=>'success','partial'=>'warning','overdue'=>'danger','sent'=>'info','draft'=>'secondary','cancelled'=>'secondary']; @endphp
        <span class="badge bg-{{ $bc[$billing->status] ?? 'secondary' }} py-2 px-3 fs-6">{{ ucfirst($billing->status) }}</span>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i>Print</button>
    </div>
</div>

<div class="row g-3">
    <!-- Bill Details -->
    <div class="col-md-8">
        <div class="card mb-3" id="printArea">
            <div class="card-body p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="text-primary fw-700">🏥 Hospital Management System</h4>
                        <p class="text-muted mb-0 small">123 Medical Center Road, Indore, MP 452001</p>
                        <p class="text-muted small">Phone: +91 731-XXXXXXX | info@hospital.com</p>
                    </div>
                    <div class="text-end">
                        <h5 class="fw-700">INVOICE</h5>
                        <p class="mb-1 text-muted small">Bill #: <strong>{{ $billing->bill_number }}</strong></p>
                        <p class="mb-1 text-muted small">Date: {{ $billing->bill_date->format('d M Y') }}</p>
                        <p class="mb-0 text-muted small">Due: {{ $billing->due_date->format('d M Y') }}</p>
                    </div>
                </div>

                <hr>

                <!-- Patient -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted small text-uppercase mb-2">Bill To</h6>
                        <p class="fw-600 mb-1">{{ $billing->patient->full_name }}</p>
                        <p class="text-muted small mb-1">{{ $billing->patient->patient_id }}</p>
                        <p class="text-muted small mb-1">{{ $billing->patient->phone }}</p>
                        <p class="text-muted small">{{ $billing->patient->address }}, {{ $billing->patient->city }}</p>
                    </div>
                    @if($billing->appointment)
                    <div class="col-md-6">
                        <h6 class="text-muted small text-uppercase mb-2">Appointment</h6>
                        <p class="fw-600 mb-1">{{ $billing->appointment->appointment_number }}</p>
                        <p class="text-muted small mb-1">{{ $billing->appointment->doctor->full_name }}</p>
                        <p class="text-muted small">{{ $billing->appointment->appointment_date->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Items Table -->
                <table class="table table-bordered mb-3">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th>Description</th>
                            <th>Category</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billing->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td><span class="badge bg-light text-dark">{{ $item->category }}</span></td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-end fw-600">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end text-muted">Subtotal</td>
                            <td class="text-end">₹{{ number_format($billing->subtotal, 2) }}</td>
                        </tr>
                        @if($billing->tax > 0)
                        <tr>
                            <td colspan="4" class="text-end text-muted">Tax</td>
                            <td class="text-end">₹{{ number_format($billing->tax, 2) }}</td>
                        </tr>
                        @endif
                        @if($billing->discount > 0)
                        <tr>
                            <td colspan="4" class="text-end text-muted">Discount</td>
                            <td class="text-end text-success">-₹{{ number_format($billing->discount, 2) }}</td>
                        </tr>
                        @endif
                        <tr style="background:#f0f7ff;">
                            <td colspan="4" class="text-end fw-700">Total</td>
                            <td class="text-end fw-700 text-primary">₹{{ number_format($billing->total, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end text-muted">Paid</td>
                            <td class="text-end text-success">₹{{ number_format($billing->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-700">Balance Due</td>
                            <td class="text-end fw-700 {{ $billing->balance > 0 ? 'text-danger' : 'text-success' }}">₹{{ number_format($billing->balance, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>

                @if($billing->notes)
                <div class="text-muted small"><strong>Notes:</strong> {{ $billing->notes }}</div>
                @endif
            </div>
        </div>

        <!-- Payment History -->
        @if($billing->payments->count())
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-cash me-2"></i>Payment History</h6></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Payment #</th><th>Date</th><th>Amount</th><th>Method</th><th>Transaction ID</th></tr>
                    </thead>
                    <tbody>
                        @foreach($billing->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_number }}</td>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="text-success fw-600">₹{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$payment->payment_method)) }}</td>
                            <td>{{ $payment->transaction_id ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        @if($billing->balance > 0 && $billing->status !== 'cancelled')
        <div class="card mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Record Payment</h6></div>
            <div class="card-body">
                <form action="{{ route('billing.record-payment', $billing) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Amount (max ₹{{ number_format($billing->balance,2) }})</label>
                            <input type="number" name="amount" class="form-control" value="{{ $billing->balance }}" step="0.01" min="0.01" max="{{ $billing->balance }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="insurance">Insurance</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Transaction ID (optional)</label>
                            <input type="text" name="transaction_id" class="form-control" placeholder="Reference number...">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check2 me-2"></i>Record Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Bill Summary</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Total Amount</span><span class="fw-600">₹{{ number_format($billing->total,2) }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Paid</span><span class="fw-600 text-success">₹{{ number_format($billing->paid_amount,2) }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-muted">Balance</span><span class="fw-700 {{ $billing->balance > 0 ? 'text-danger' : 'text-success' }}">₹{{ number_format($billing->balance,2) }}</span></div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Status</span>
                    <span class="badge bg-{{ $bc[$billing->status] ?? 'secondary' }}">{{ ucfirst($billing->status) }}</span>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Quick Actions</h6></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('patients.show', $billing->patient) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-person me-1"></i>View Patient
                </a>
                @if($billing->status !== 'cancelled')
                <form action="{{ route('billing.destroy', $billing) }}" method="POST" onsubmit="return confirm('Cancel this bill?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-x-circle me-1"></i>Cancel Bill
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
