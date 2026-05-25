@extends('layouts.app')
@section('title', 'Billing')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-receipt me-2"></i>Billing</h4>
        <p>Manage patient invoices and payments</p>
    </div>
    <a href="{{ route('billing.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Create Bill
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#0066cc,#0044aa);color:#fff;border-radius:12px;">
            <div class="card-body">
                <div class="small opacity-75">Total Billed</div>
                <div style="font-size:1.8rem;font-weight:700;">₹{{ number_format($stats['total_billed'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#198754,#157347);color:#fff;border-radius:12px;">
            <div class="card-body">
                <div class="small opacity-75">Total Collected</div>
                <div style="font-size:1.8rem;font-weight:700;">₹{{ number_format($stats['total_paid'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0" style="background:linear-gradient(135deg,#dc3545,#b02a37);color:#fff;border-radius:12px;">
            <div class="card-body">
                <div class="small opacity-75">Outstanding Balance</div>
                <div style="font-size:1.8rem;font-weight:700;">₹{{ number_format($stats['total_pending'], 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search bill number or patient..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['draft','sent','paid','partial','overdue','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bill #</th>
                        <th>Patient</th>
                        <th>Bill Date</th>
                        <th>Due Date</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $bill)
                    <tr>
                        <td><a href="{{ route('billing.show', $bill) }}" class="fw-600 text-decoration-none">{{ $bill->bill_number }}</a></td>
                        <td>
                            <a href="{{ route('patients.show', $bill->patient) }}" class="text-decoration-none">
                                <div class="fw-500">{{ $bill->patient->full_name }}</div>
                                <small class="text-muted">{{ $bill->patient->patient_id }}</small>
                            </a>
                        </td>
                        <td>{{ $bill->bill_date->format('d M Y') }}</td>
                        <td class="{{ $bill->due_date < today() && $bill->status !== 'paid' ? 'text-danger fw-600' : '' }}">{{ $bill->due_date->format('d M Y') }}</td>
                        <td class="fw-600">₹{{ number_format($bill->total, 2) }}</td>
                        <td class="text-success">₹{{ number_format($bill->paid_amount, 2) }}</td>
                        <td class="{{ $bill->balance > 0 ? 'text-danger' : 'text-success' }}">₹{{ number_format($bill->balance, 2) }}</td>
                        <td>
                            @php $bc=['paid'=>'bg-success','partial'=>'bg-warning text-dark','overdue'=>'bg-danger','sent'=>'bg-info','draft'=>'bg-secondary','cancelled'=>'bg-secondary']; @endphp
                            <span class="badge {{ $bc[$bill->status] ?? 'bg-secondary' }}">{{ ucfirst($bill->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('billing.show', $bill) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-receipt fs-1 d-block mb-3"></i>No bills found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bills->hasPages())
    <div class="card-footer bg-white">{{ $bills->links() }}</div>
    @endif
</div>
@endsection
