@extends('layouts.app')
@section('title', 'Edit Appointment')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>Edit Appointment — {{ $appointment->appointment_number }}</h4>
            <p>Update appointment details, status, and clinical notes</p>
        </div>
    </div>
</div>

<form action="{{ route('appointments.update', $appointment) }}" method="POST">
@csrf @method('PUT')

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-calendar me-2"></i>Appointment Details</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select" required>
                            @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id',$appointment->patient_id)==$p->id ? 'selected':'' }}>
                                {{ $p->full_name }} ({{ $p->patient_id }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Doctor <span class="text-danger">*</span></label>
                        <select name="doctor_id" class="form-select" required>
                            @foreach($doctors as $d)
                            <option value="{{ $d->id }}" {{ old('doctor_id',$appointment->doctor_id)==$d->id ? 'selected':'' }}>
                                {{ $d->full_name }} — {{ $d->specialization }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date',$appointment->appointment_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Time <span class="text-danger">*</span></label>
                        <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time',$appointment->appointment_time) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (min)</label>
                        <select name="duration_minutes" class="form-select">
                            @foreach([15,30,45,60,90,120] as $d)
                            <option value="{{ $d }}" {{ old('duration_minutes',$appointment->duration_minutes)==$d ? 'selected':'' }}>{{ $d }} min</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            @foreach(['consultation','follow_up','routine_checkup','emergency'] as $t)
                            <option value="{{ $t }}" {{ old('type',$appointment->type)===$t ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['scheduled','confirmed','in_progress','completed','cancelled','no_show'] as $s)
                            <option value="{{ $s }}" {{ old('status',$appointment->status)===$s ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="pending" {{ old('payment_status',$appointment->payment_status)==='pending' ? 'selected':'' }}>Pending</option>
                            <option value="paid"    {{ old('payment_status',$appointment->payment_status)==='paid'    ? 'selected':'' }}>Paid</option>
                            <option value="waived"  {{ old('payment_status',$appointment->payment_status)==='waived'  ? 'selected':'' }}>Waived</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fee (₹)</label>
                        <input type="number" name="fee" class="form-control" value="{{ old('fee',$appointment->fee) }}" step="0.01" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="2">{{ old('reason',$appointment->reason) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes',$appointment->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-clipboard-pulse me-2"></i>Clinical Notes</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Diagnosis</label>
                        <textarea name="diagnosis" class="form-control" rows="3" placeholder="Clinical diagnosis after examination...">{{ old('diagnosis',$appointment->diagnosis) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Prescription / Treatment Plan</label>
                        <textarea name="prescription" class="form-control" rows="3" placeholder="Treatment plan, medicines, recommendations...">{{ old('prescription',$appointment->prescription) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Patient Info</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="width:40px;height:40px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-person text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-600">{{ $appointment->patient->full_name }}</div>
                        <small class="text-muted">{{ $appointment->patient->patient_id }}</small>
                    </div>
                </div>
                @if($appointment->patient->allergies)
                <div class="alert alert-danger py-2 small mb-0">
                    <strong>⚠️ Allergies:</strong> {{ $appointment->patient->allergies }}
                </div>
                @endif
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-2"></i>Update Appointment</button>
            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
</form>
@endsection
