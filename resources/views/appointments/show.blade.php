@extends('layouts.app')
@section('title', 'Appointment Details')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>{{ $appointment->appointment_number }}</h4>
            <p>{{ $appointment->appointment_date->format('l, d F Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @if($appointment->status !== 'cancelled')
        <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                @foreach(['scheduled','confirmed','in_progress','completed','cancelled','no_show'] as $s)
                <option value="{{ $s }}" {{ $appointment->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>
</div>

<div class="row g-3">
    <!-- Left: Main Details -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Appointment Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-600">Patient</label>
                        <div class="mt-1">
                            <a href="{{ route('patients.show', $appointment->patient) }}" class="text-decoration-none fw-600">{{ $appointment->patient->full_name }}</a>
                            <div class="text-muted small">{{ $appointment->patient->patient_id }} · {{ $appointment->patient->age }} yrs · {{ ucfirst($appointment->patient->gender) }}</div>
                            <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $appointment->patient->phone }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-600">Doctor</label>
                        <div class="mt-1">
                            <div class="fw-600">{{ $appointment->doctor->full_name }}</div>
                            <div class="text-muted small">{{ $appointment->doctor->specialization }}</div>
                            <div class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $appointment->doctor->phone }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small text-uppercase fw-600">Date</label>
                        <div class="mt-1 fw-500">{{ $appointment->appointment_date->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small text-uppercase fw-600">Time</label>
                        <div class="mt-1 fw-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small text-uppercase fw-600">Duration</label>
                        <div class="mt-1 fw-500">{{ $appointment->duration_minutes }} min</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small text-uppercase fw-600">Type</label>
                        <div class="mt-1"><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$appointment->type)) }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-600">Status</label>
                        <div class="mt-1"><span class="badge {{ $appointment->status_badge }}">{{ ucfirst(str_replace('_',' ',$appointment->status)) }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase fw-600">Payment Status</label>
                        <div class="mt-1">
                            @php $pc=['paid'=>'bg-success','pending'=>'bg-warning text-dark','waived'=>'bg-info']; @endphp
                            <span class="badge {{ $pc[$appointment->payment_status] ?? 'bg-secondary' }}">{{ ucfirst($appointment->payment_status) }}</span>
                        </div>
                    </div>
                    @if($appointment->reason)
                    <div class="col-12">
                        <label class="text-muted small text-uppercase fw-600">Reason for Visit</label>
                        <p class="mt-1 mb-0">{{ $appointment->reason }}</p>
                    </div>
                    @endif
                    @if($appointment->notes)
                    <div class="col-12">
                        <label class="text-muted small text-uppercase fw-600">Notes</label>
                        <p class="mt-1 mb-0">{{ $appointment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($appointment->diagnosis || $appointment->prescription)
        <div class="card mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-clipboard-pulse me-2"></i>Clinical Notes</h6></div>
            <div class="card-body">
                @if($appointment->diagnosis)
                <h6 class="text-muted small text-uppercase mb-2">Diagnosis</h6>
                <p class="mb-3">{{ $appointment->diagnosis }}</p>
                @endif
                @if($appointment->prescription)
                <h6 class="text-muted small text-uppercase mb-2">Prescription / Treatment</h6>
                <p class="mb-0">{{ $appointment->prescription }}</p>
                @endif
            </div>
        </div>
        @endif

        @if($appointment->prescriptions->count())
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-capsule me-2"></i>Medicines Prescribed</h6></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Medicine</th><th>Dosage</th><th>Frequency</th><th>Duration</th><th>Instructions</th></tr>
                    </thead>
                    <tbody>
                        @foreach($appointment->prescriptions as $rx)
                        <tr>
                            <td class="fw-500">{{ $rx->medicine->name ?? '—' }}</td>
                            <td>{{ $rx->dosage }}</td>
                            <td>{{ $rx->frequency }}</td>
                            <td>{{ $rx->duration_days }} days</td>
                            <td>{{ $rx->instructions ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Right: Billing Summary -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-cash me-2"></i>Fee Summary</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Consultation Fee</span>
                    <span class="fw-600">₹{{ number_format($appointment->fee, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-600">Total</span>
                    <span class="fw-700 text-primary">₹{{ number_format($appointment->fee, 2) }}</span>
                </div>
                <div class="mt-3">
                    @php $pc=['paid'=>'success','pending'=>'warning','waived'=>'info']; @endphp
                    <span class="badge bg-{{ $pc[$appointment->payment_status] ?? 'secondary' }} w-100 py-2">
                        Payment: {{ ucfirst($appointment->payment_status) }}
                    </span>
                </div>
                @if($appointment->payment_status === 'pending')
                <a href="{{ route('billing.create') }}?appointment_id={{ $appointment->id }}&patient_id={{ $appointment->patient_id }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                    <i class="bi bi-receipt me-1"></i>Generate Bill
                </a>
                @elseif($appointment->bill)
                <a href="{{ route('billing.show', $appointment->bill) }}" class="btn btn-outline-success btn-sm w-100 mt-2">
                    <i class="bi bi-eye me-1"></i>View Bill
                </a>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Quick Actions</h6></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('patients.show', $appointment->patient) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-person me-1"></i>View Patient Profile
                </a>
                <a href="{{ route('appointments.create') }}?patient_id={{ $appointment->patient_id }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calendar-plus me-1"></i>Book Follow-up
                </a>
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i>Edit Appointment
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
