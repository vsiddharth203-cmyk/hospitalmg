@extends('layouts.app')
@section('title', $doctor->full_name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>{{ $doctor->full_name }}</h4>
            <p>{{ $doctor->specialization }} · {{ $doctor->doctor_id }}</p>
        </div>
    </div>
    <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div style="width:80px;height:80px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-person-badge" style="font-size:2.5rem;color:#1d4ed8;"></i>
                </div>
                <h5 class="fw-700">{{ $doctor->full_name }}</h5>
                <p class="text-primary mb-1">{{ $doctor->specialization }}</p>
                <p class="text-muted small mb-3">{{ $doctor->qualification }}</p>
                @php $sc=['active'=>'bg-success','inactive'=>'bg-secondary','on_leave'=>'bg-warning text-dark']; @endphp
                <span class="badge {{ $sc[$doctor->status] ?? 'bg-secondary' }}">{{ ucfirst(str_replace('_',' ',$doctor->status)) }}</span>

                <div class="row g-2 border-top pt-3 mt-3">
                    <div class="col-4">
                        <div class="fw-700 text-primary">{{ $stats['total_appointments'] }}</div>
                        <small class="text-muted">Total</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-700">{{ $stats['this_month'] }}</div>
                        <small class="text-muted">This Month</small>
                    </div>
                    <div class="col-4">
                        <div class="fw-700 text-success">{{ $stats['completed'] }}</div>
                        <small class="text-muted">Done</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Contact & Info</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-telephone text-muted"></i><span>{{ $doctor->phone }}</span></div>
                <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-envelope text-muted"></i><span>{{ $doctor->email }}</span></div>
                <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-card-text text-muted"></i><span>License: {{ $doctor->license_number }}</span></div>
                <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-clock text-muted"></i><span>{{ $doctor->experience_years }} years experience</span></div>
                <div class="d-flex align-items-center gap-2"><i class="bi bi-currency-rupee text-muted"></i><span>₹{{ number_format($doctor->consultation_fee, 2) }} per visit</span></div>
            </div>
        </div>

        @if($doctor->available_days)
        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Availability</h6></div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach($doctor->available_days as $day)
                    <span class="badge bg-primary">{{ substr($day,0,3) }}</span>
                    @endforeach
                </div>
                @if($doctor->available_from && $doctor->available_to)
                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($doctor->available_from)->format('h:i A') }} – {{ \Carbon\Carbon::parse($doctor->available_to)->format('h:i A') }}</small>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        @if($doctor->bio)
        <div class="card mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0">About</h6></div>
            <div class="card-body"><p class="mb-0">{{ $doctor->bio }}</p></div>
        </div>
        @endif

        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between">
                <h6 class="mb-0">Recent Appointments</h6>
                <a href="{{ route('appointments.create') }}?doctor_id={{ $doctor->id }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus"></i> New</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Date</th><th>Patient</th><th>Type</th><th>Status</th><th>Fee</th></tr>
                    </thead>
                    <tbody>
                        @forelse($doctor->appointments->sortByDesc('appointment_date')->take(10) as $apt)
                        <tr>
                            <td>{{ $apt->appointment_date->format('d M Y') }}</td>
                            <td><a href="{{ route('patients.show', $apt->patient) }}" class="text-decoration-none">{{ $apt->patient->full_name }}</a></td>
                            <td>{{ ucfirst(str_replace('_',' ',$apt->type)) }}</td>
                            <td><span class="badge {{ $apt->status_badge }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                            <td>₹{{ number_format($apt->fee,2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No appointments yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
