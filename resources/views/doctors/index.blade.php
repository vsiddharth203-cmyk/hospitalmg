@extends('layouts.app')
@section('title', 'Doctors')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-person-badge me-2"></i>Doctors</h4>
        <p>Manage medical staff and specialists</p>
    </div>
    <a href="{{ route('doctors.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Add Doctor
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, ID, specialization..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="specialization" class="form-select">
                    <option value="">All Specializations</option>
                    @foreach($specializations as $spec)
                    <option value="{{ $spec }}" {{ request('specialization') === $spec ? 'selected' : '' }}>{{ $spec }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status')==='active' ? 'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')==='inactive' ? 'selected':'' }}>Inactive</option>
                    <option value="on_leave" {{ request('status')==='on_leave' ? 'selected':'' }}>On Leave</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Doctor Cards -->
<div class="row g-3">
    @forelse($doctors as $doctor)
    <div class="col-md-4 col-lg-3">
        <div class="card h-100">
            <div class="card-body text-center py-4">
                <div style="width:64px;height:64px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-person-badge" style="font-size:1.8rem;color:#1d4ed8;"></i>
                </div>
                <h6 class="fw-700 mb-1">{{ $doctor->full_name }}</h6>
                <p class="text-primary small mb-1">{{ $doctor->specialization }}</p>
                <p class="text-muted small mb-3">{{ $doctor->qualification }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @php $sc=['active'=>'bg-success','inactive'=>'bg-secondary','on_leave'=>'bg-warning text-dark']; @endphp
                    <span class="badge {{ $sc[$doctor->status] ?? 'bg-secondary' }}">{{ ucfirst(str_replace('_',' ',$doctor->status)) }}</span>
                    <span class="badge bg-light text-dark">{{ $doctor->doctor_id }}</span>
                </div>
                <div class="row g-2 border-top pt-3 text-center">
                    <div class="col-6">
                        <div class="fw-700 text-primary">{{ $doctor->appointments_count }}</div>
                        <small class="text-muted">Appointments</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-700">{{ $doctor->experience_years }}y</div>
                        <small class="text-muted">Experience</small>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 d-flex gap-2">
                <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-primary btn-sm flex-grow-1">View</a>
                <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil"></i></a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-person-badge fs-1 d-block mb-3"></i>
                No doctors found.
                <a href="{{ route('doctors.create') }}">Add a doctor</a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($doctors->hasPages())
<div class="mt-3">{{ $doctors->links() }}</div>
@endif
@endsection
