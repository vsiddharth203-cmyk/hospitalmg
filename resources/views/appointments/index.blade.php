@extends('layouts.app')
@section('title', 'Appointments')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-calendar-check me-2"></i>Appointments</h4>
        <p>Manage patient appointments and schedules</p>
    </div>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-calendar-plus me-1"></i>Book Appointment
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Patient, Doctor, Apt #..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    @foreach(['scheduled','confirmed','in_progress','completed','cancelled','no_show'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Doctor</label>
                <select name="doctor_id" class="form-select">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>{{ $d->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Appointment #</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $apt)
                    <tr>
                        <td><span class="badge bg-light text-dark">{{ $apt->appointment_number }}</span></td>
                        <td>
                            <a href="{{ route('patients.show', $apt->patient) }}" class="text-decoration-none">
                                <div class="fw-500">{{ $apt->patient->full_name }}</div>
                                <small class="text-muted">{{ $apt->patient->patient_id }}</small>
                            </a>
                        </td>
                        <td>{{ $apt->doctor->full_name }}</td>
                        <td>
                            <div class="fw-500">{{ $apt->appointment_date->format('d M Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$apt->type)) }}</span></td>
                        <td><span class="badge {{ $apt->status_badge }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span></td>
                        <td>₹{{ number_format($apt->fee, 2) }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('appointments.show', $apt) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('appointments.edit', $apt) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                            No appointments found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($appointments->hasPages())
    <div class="card-footer bg-white">{{ $appointments->links() }}</div>
    @endif
</div>
@endsection
