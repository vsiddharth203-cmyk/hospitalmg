@extends('layouts.app')
@section('title', 'Create Bill')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>Create New Bill</h4>
            <p>Generate an invoice for a patient</p>
        </div>
    </div>
</div>

<form action="{{ route('billing.store') }}" method="POST" id="billForm">
@csrf

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-person me-2"></i>Bill To</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ (old('patient_id', request('patient_id')) == $p->id) ? 'selected' : '' }}>
                                {{ $p->full_name }} ({{ $p->patient_id }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Appointment (optional)</label>
                        <select name="appointment_id" class="form-select">
                            <option value="">None</option>
                            @foreach($appointments as $apt)
                            <option value="{{ $apt->id }}" {{ (old('appointment_id', request('appointment_id')) == $apt->id) ? 'selected' : '' }}>
                                {{ $apt->appointment_number }} — {{ $apt->patient->full_name }} ({{ $apt->appointment_date->format('d M Y') }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Date <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Optional notes...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill Items -->
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Bill Items</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItem">
                    <i class="bi bi-plus me-1"></i>Add Item
                </button>
            </div>
            <div class="card-body">
                <div id="billItems">
                    <div class="row g-2 align-items-end mb-2 bill-item-row">
                        <div class="col-md-4">
                            <label class="form-label small">Description</label>
                            <input type="text" name="items[0][description]" class="form-control form-control-sm" placeholder="Service / Item" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Category</label>
                            <select name="items[0][category]" class="form-select form-select-sm">
                                <option>Consultation</option>
                                <option>Lab Test</option>
                                <option>Medicine</option>
                                <option>Room</option>
                                <option>Procedure</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Qty</label>
                            <input type="number" name="items[0][quantity]" class="form-control form-control-sm item-qty" value="1" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Unit Price</label>
                            <input type="number" name="items[0][unit_price]" class="form-control form-control-sm item-price" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Total</label>
                            <div class="form-control form-control-sm bg-light item-total fw-600">₹0.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Totals -->
    <div class="col-md-4">
        <div class="card sticky-top" style="top:80px;">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-calculator me-2"></i>Bill Summary</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-600" id="subtotalDisplay">₹0.00</span>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small">Tax (₹)</label>
                        <input type="number" name="tax" id="taxInput" class="form-control form-control-sm" value="{{ old('tax', 0) }}" step="0.01" min="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label small">Discount (₹)</label>
                        <input type="number" name="discount" id="discountInput" class="form-control form-control-sm" value="{{ old('discount', 0) }}" step="0.01" min="0">
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-700 fs-5">Total</span>
                    <span class="fw-700 fs-5 text-primary" id="totalDisplay">₹0.00</span>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-receipt me-2"></i>Generate Bill
                </button>
                <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
</form>

@push('scripts')
<script>
let itemCount = 1;

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.bill-item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = qty * price;
        row.querySelector('.item-total').textContent = '₹' + total.toFixed(2);
        subtotal += total;
    });
    const tax = parseFloat(document.getElementById('taxInput').value) || 0;
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const total = subtotal + tax - discount;
    document.getElementById('subtotalDisplay').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('totalDisplay').textContent = '₹' + total.toFixed(2);
}

document.getElementById('addItem').addEventListener('click', function () {
    const idx = itemCount++;
    const row = document.createElement('div');
    row.className = 'row g-2 align-items-end mb-2 bill-item-row';
    row.innerHTML = `
        <div class="col-md-4">
            <input type="text" name="items[${idx}][description]" class="form-control form-control-sm" placeholder="Service / Item" required>
        </div>
        <div class="col-md-2">
            <select name="items[${idx}][category]" class="form-select form-select-sm">
                <option>Consultation</option><option>Lab Test</option><option>Medicine</option>
                <option>Room</option><option>Procedure</option><option>Other</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${idx}][quantity]" class="form-control form-control-sm item-qty" value="1" min="1" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${idx}][unit_price]" class="form-control form-control-sm item-price" step="0.01" min="0" required>
        </div>
        <div class="col-md-1">
            <div class="form-control form-control-sm bg-light item-total fw-600">₹0.00</div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="bi bi-trash"></i></button>
        </div>
    `;
    document.getElementById('billItems').appendChild(row);
    row.querySelector('.remove-item').addEventListener('click', function () {
        row.remove();
        updateTotals();
    });
    row.querySelectorAll('.item-qty, .item-price').forEach(el => el.addEventListener('input', updateTotals));
});

document.querySelectorAll('.item-qty, .item-price').forEach(el => el.addEventListener('input', updateTotals));
document.getElementById('taxInput').addEventListener('input', updateTotals);
document.getElementById('discountInput').addEventListener('input', updateTotals);
</script>
@endpush
@endsection
