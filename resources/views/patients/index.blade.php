@extends('layouts.app')
@section('title', 'Patients')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="bi bi-people me-2"></i>Patients</h4>
        <p>Manage all patient records</p>
    </div>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Register Patient
    </a>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Name, ID, Phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="discharged" {{ request('status') === 'discharged' ? 'selected' : '' }}>Discharged</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <option value="">All Genders</option>
                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="">All</option>
                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                    <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Age / Gender</th>
                        <th>Blood Group</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td><span class="badge bg-light text-dark fw-600">{{ $patient->patient_id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:50%;background:{{ $patient->gender === 'male' ? '#dbeafe' : '#fce7f3' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-person" style="color:{{ $patient->gender === 'male' ? '#1d4ed8' : '#be185d' }};font-size:0.9rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-500">{{ $patient->full_name }}</div>
                                    <small class="text-muted">{{ $patient->email ?? '—' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $patient->age }} yrs / {{ ucfirst($patient->gender) }}</td>
                        <td>
                            @if($patient->blood_group)
                                <span class="badge bg-danger">{{ $patient->blood_group }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $patient->phone }}</td>
                        <td>{{ $patient->city }}</td>
                        <td>
                            @php $statusColors = ['active'=>'bg-success','inactive'=>'bg-secondary','discharged'=>'bg-info']; @endphp
                            <span class="badge {{ $statusColors[$patient->status] ?? 'bg-secondary' }}">{{ ucfirst($patient->status) }}</span>
                        </td>
                        <td>{{ $patient->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Delete this patient?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            No patients found.
                            <a href="{{ route('patients.create') }}">Register the first patient</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($patients->hasPages())
    <div class="card-footer bg-white">
        {{ $patients->links() }}
    </div>
    @endif
</div>
@endsection
