@extends('layouts.app')
@section('title', 'Add Doctor')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>Add New Doctor</h4>
            <p>Register a new doctor to the system</p>
        </div>
    </div>
</div>

<form action="{{ route('doctors.store') }}" method="POST">
@csrf

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Doctor Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Specialization <span class="text-danger">*</span></label>
                        <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" value="{{ old('specialization') }}" list="specializations-list" required>
                        <datalist id="specializations-list">
                            @foreach(['Cardiology','Dermatology','Endocrinology','Gastroenterology','General Medicine','Gynecology','Hematology','Nephrology','Neurology','Oncology','Ophthalmology','Orthopedics','Pediatrics','Psychiatry','Pulmonology','Radiology','Rheumatology','Surgery','Urology'] as $s)
                            <option value="{{ $s }}">
                            @endforeach
                        </datalist>
                        @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Qualification <span class="text-danger">*</span></label>
                        <input type="text" name="qualification" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification') }}" placeholder="MBBS, MD, MS..." required>
                        @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Experience (Years) <span class="text-danger">*</span></label>
                        <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years') }}" min="0" max="60" required>
                        @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Consultation Fee (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="consultation_fee" class="form-control @error('consultation_fee') is-invalid @enderror" value="{{ old('consultation_fee') }}" step="0.01" min="0" required>
                        @error('consultation_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">License Number <span class="text-danger">*</span></label>
                        <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" value="{{ old('license_number') }}" required>
                        @error('license_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="Brief professional bio...">{{ old('bio') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-clock me-2"></i>Availability</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Available Days</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available_days[]" value="{{ $day }}" id="day_{{ $day }}"
                                    {{ in_array($day, old('available_days', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Available From</label>
                        <input type="time" name="available_from" class="form-control" value="{{ old('available_from', '09:00') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Available To</label>
                        <input type="time" name="available_to" class="form-control" value="{{ old('available_to', '17:00') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Status</h6></div>
            <div class="card-body">
                <select name="status" class="form-select">
                    <option value="active"   {{ old('status')==='active'   ? 'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status')==='inactive' ? 'selected':'' }}>Inactive</option>
                    <option value="on_leave" {{ old('status')==='on_leave' ? 'selected':'' }}>On Leave</option>
                </select>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-primary"><i class="bi bi-person-check me-2"></i>Add Doctor</button>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
</form>
@endsection
