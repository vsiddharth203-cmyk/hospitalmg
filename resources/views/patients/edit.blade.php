@extends('layouts.app')
@section('title', 'Edit Patient')

@section('content')
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4>Edit Patient — {{ $patient->full_name }}</h4>
            <p>{{ $patient->patient_id }}</p>
        </div>
    </div>
</div>

<form action="{{ route('patients.update', $patient) }}" method="POST">
@csrf @method('PUT')

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $patient->first_name) }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $patient->last_name) }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="male"   {{ old('gender',$patient->gender)==='male'   ? 'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender',$patient->gender)==='female' ? 'selected':'' }}>Female</option>
                            <option value="other"  {{ old('gender',$patient->gender)==='other'  ? 'selected':'' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Blood Group</label>
                        <select name="blood_group" class="form-select">
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group',$patient->blood_group)===$bg ? 'selected':'' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone',$patient->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email',$patient->email) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address',$patient->address) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" value="{{ old('city',$patient->city) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <input type="text" name="state" class="form-control" value="{{ old('state',$patient->state) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code',$patient->postal_code) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Medical Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Medical History</label>
                        <textarea name="medical_history" class="form-control" rows="3">{{ old('medical_history',$patient->medical_history) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2">{{ old('allergies',$patient->allergies) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-telephone-plus me-2"></i>Emergency Contact</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name',$patient->emergency_contact_name) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone',$patient->emergency_contact_phone) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Relation</label>
                        <input type="text" name="emergency_contact_relation" class="form-control" value="{{ old('emergency_contact_relation',$patient->emergency_contact_relation) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0"><i class="bi bi-shield-check me-2"></i>Insurance</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Insurance Provider</label>
                        <input type="text" name="insurance_provider" class="form-control" value="{{ old('insurance_provider',$patient->insurance_provider) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Policy Number</label>
                        <input type="text" name="insurance_number" class="form-control" value="{{ old('insurance_number',$patient->insurance_number) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white py-3"><h6 class="mb-0">Status</h6></div>
            <div class="card-body">
                <select name="status" class="form-select">
                    <option value="active"     {{ old('status',$patient->status)==='active'     ? 'selected':'' }}>Active</option>
                    <option value="inactive"   {{ old('status',$patient->status)==='inactive'   ? 'selected':'' }}>Inactive</option>
                    <option value="discharged" {{ old('status',$patient->status)==='discharged' ? 'selected':'' }}>Discharged</option>
                </select>
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-2"></i>Update Patient</button>
            <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</div>
</form>
@endsection
