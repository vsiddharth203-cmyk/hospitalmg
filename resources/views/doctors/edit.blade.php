@extends('layouts.app')
@section('title', 'Edit Doctor')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>Edit Doctor — {{ $doctor->full_name }}</h4>
            <p>{{ $doctor->doctor_id }}</p>
        </div>
    </div>
</div>

<form action="{{ route('doctors.update', $doctor) }}" method="POST">
@csrf @method('PUT')

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Doctor Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name',$doctor->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name',$doctor->last_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Specialization <span class="text-danger">*</span></label>
                        <input type="text" name="specialization" class="form-control" value="{{ old('specialization',$doctor->specialization) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Qualification <span class="text-danger">*</span></label>
                        <input type="text" name="qualification" class="form-control" value="{{ old('qualification',$doctor->qualification) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Experience (Years)</label>
                        <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years',$doctor->experience_years) }}" min="0" max="60">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone',$doctor->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Consultation Fee (₹)</label>
                        <input type="number" name="consultation_fee" class="form-control" value="{{ old('consultation_fee',$doctor->consultation_fee) }}" step="0.01" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email',$doctor->email) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">License Number</label>
                        <input type="text" name="license_number" class="form-control" value="{{ old('license_number',$doctor->license_number) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3">{{ old('bio',$doctor->bio) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Availability</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Available Days</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available_days[]" value="{{ $day }}" id="day_{{ $day }}"
                                    {{ in_array($day, old('available_days', $doctor->available_days ?? [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Available From</label>
                        <input type="time" name="available_from" class="form-control" value="{{ old('available_from',$doctor->available_from) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Available To</label>
                        <input type="time" name="available_to" class="form-control" value="{{ old('available_to',$doctor->available_to) }}">
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
                    <option value="active"   {{ old('status',$doctor->status)==='active'   ? 'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status',$doctor->status)==='inactive' ? 'selected':'' }}>Inactive</option>
                    <option value="on_leave" {{ old('status',$doctor->status)==='on_leave' ? 'selected':'' }}>On Leave</option>
                </select>
            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-2"></i>Update Doctor</button>
            <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
</form>
@endsection
