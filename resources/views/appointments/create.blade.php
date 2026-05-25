@extends('layouts.app')
@section('title', 'Book Appointment')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>Book Appointment</h4>
            <p>Schedule a new patient appointment</p>
        </div>
    </div>
</div>

<form action="{{ route('appointments.store') }}" method="POST">
@csrf

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Appointment Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ (old('patient_id', request('patient_id')) == $p->id) ? 'selected' : '' }}>
                                {{ $p->full_name }} ({{ $p->patient_id }})
                            </option>
                            @endforeach
                        </select>
                        @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doctor <span class="text-danger">*</span></label>
                        <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $d)
                            <option value="{{ $d->id }}" {{ old('doctor_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->full_name }} — {{ $d->specialization }} (₹{{ $d->consultation_fee }})
                            </option>
                            @endforeach
                        </select>
                        @error('doctor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Appointment Date <span class="text-danger">*</span></label>
                        <input type="date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('appointment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Appointment Time <span class="text-danger">*</span></label>
                        <input type="time" name="appointment_time" class="form-control @error('appointment_time') is-invalid @enderror" value="{{ old('appointment_time', '09:00') }}" required>
                        @error('appointment_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (minutes)</label>
                        <select name="duration_minutes" class="form-select">
                            <option value="15">15 minutes</option>
                            <option value="30" selected>30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60">1 hour</option>
                            <option value="90">1.5 hours</option>
                            <option value="120">2 hours</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Appointment Type</label>
                        <select name="type" class="form-select">
                            <option value="consultation">Consultation</option>
                            <option value="follow_up">Follow Up</option>
                            <option value="routine_checkup">Routine Checkup</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Reason for Visit</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Chief complaint or reason for the appointment...">{{ old('reason') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any additional information...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Summary</h6></div>
            <div class="card-body">
                <p class="text-muted small">Review the appointment details before confirming.</p>
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle me-1"></i>
                    The consultation fee will be auto-filled based on the selected doctor.
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-primary"><i class="bi bi-calendar-check me-2"></i>Book Appointment</button>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
</form>
@endsection
