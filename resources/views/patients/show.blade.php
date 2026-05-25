@extends('layouts.app')
@section('title', $patient->full_name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4>{{ $patient->full_name }}</h4>
            <p>{{ $patient->patient_id }} · Registered {{ $patient->created_at->format('d M Y') }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.create') }}?patient_id={{ $patient->id }}" class="btn btn-success btn-sm">
            <i class="bi bi-calendar-plus me-1"></i>Book Appointment
        </a>
        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
</div>

<div class="row g-3">
    <!-- Left Column: Patient Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div style="width:80px;height:80px;border-radius:50%;background:{{ $patient->gender === 'male' ? '#dbeafe' : '#fce7f3' }};display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-person{{ $patient->gender === 'male' ? '' : '-dress' }}" style="font-size:2.5rem;color:{{ $patient->gender === 'male' ? '#1d4ed8' : '#be185d' }};"></i>
                </div>
                <h5 class="fw-700">{{ $patient->full_name }}</h5>
                <p class="text-muted mb-2">{{ $patient->patient_id }}</p>
                @php $statusColors = ['active'=>'bg-success','inactive'=>'bg-secondary','discharged'=>'bg-info']; @endphp
                <span class="badge {{ $statusColors[$patient->status] ?? 'bg-secondary' }} mb-3">{{ ucfirst($patient->status) }}</span>

                <div class="row g-2 text-center border-top pt-3">
                    <div class="col-4">
                        <div class="fw-700 text-primary">{{ $patient->age }}</div>
                        <small class="text-muted">Age</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-700 text-danger">{{ $patient->blood_group ?? 'N/A' }}</div>
                        <small class="text-muted">Blood</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-700">{{ $patient->appointments->count() }}</div>
                        <small class="text-muted">Visits</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-telephone text-muted"></i>
                    <span>{{ $patient->phone }}</span>
                </div>
                @if($patient->email)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-envelope text-muted"></i>
                    <span>{{ $patient->email }}</span>
                </div>
                @endif
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-geo-alt text-muted mt-1"></i>
                    <span>{{ $patient->address }}, {{ $patient->city }}, {{ $patient->state }} {{ $patient->postal_code }}</span>
                </div>
            </div>
        </div>

        @if($patient->emergency_contact_name)
        <div class="card mt-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">Emergency Contact</h6>
            </div>
            <div class="card-body">
                <p class="mb-1 fw-500">{{ $patient->emergency_contact_name }}</p>
                <p class="mb-1 text-muted small">{{ $patient->emergency_contact_relation }}</p>
                <p class="mb-0"><i class="bi bi-telephone me-1 text-muted"></i>{{ $patient->emergency_contact_phone }}</p>
            </div>
        </div>
        @endif

        @if($patient->insurance_provider)
        <div class="card mt-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">Insurance</h6>
            </div>
            <div class="card-body">
                <p class="mb-1 fw-500">{{ $patient->insurance_provider }}</p>
                <p class="mb-0 text-muted small">{{ $patient->insurance_number }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Medical Records -->
    <div class="col-md-8">
        @if($patient->medical_history || $patient->allergies)
        <div class="card mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Medical Information</h6>
            </div>
            <div class="card-body">
                @if($patient->medical_history)
                <h6 class="text-muted small text-uppercase mb-2">Medical History</h6>
                <p class="mb-3">{{ $patient->medical_history }}</p>
                @endif
                @if($patient->allergies)
                <h6 class="text-muted small text-uppercase mb-2">Allergies</h6>
                <p class="mb-0 text-danger">⚠️ {{ $patient->allergies }}</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Appointments -->
        <div class="card mb-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between">
                <h6 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Appointment History</h6>
                <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus"></i> New
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->appointments->sortByDesc('appointment_date')->take(5) as $apt)
                        <tr>
                            <td>{{ $apt->appointment_date->format('d M Y') }}</td>
                            <td>{{ $apt->doctor->full_name ?? '—' }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$apt->type)) }}</td>
                            <td><span class="badge {{ $apt->status_badge }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                            <td>₹{{ number_format($apt->fee, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No appointments yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bills -->
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between">
                <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Billing History</h6>
                <a href="{{ route('billing.create') }}?patient_id={{ $patient->id }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus"></i> New Bill
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Bill #</th><th>Date</th><th>Total</th><th>Paid</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->bills->sortByDesc('bill_date')->take(5) as $bill)
                        <tr>
                            <td><a href="{{ route('billing.show', $bill) }}">{{ $bill->bill_number }}</a></td>
                            <td>{{ $bill->bill_date->format('d M Y') }}</td>
                            <td>₹{{ number_format($bill->total, 2) }}</td>
                            <td>₹{{ number_format($bill->paid_amount, 2) }}</td>
                            <td>
                                @php $bc = ['paid'=>'bg-success','partial'=>'bg-warning text-dark','overdue'=>'bg-danger','sent'=>'bg-info','draft'=>'bg-secondary','cancelled'=>'bg-secondary']; @endphp
                                <span class="badge {{ $bc[$bill->status] ?? 'bg-secondary' }}">{{ ucfirst($bill->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No bills yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
