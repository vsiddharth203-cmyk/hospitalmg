@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0" style="font-size:0.85rem;">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4>Dashboard</h4>
        <p>Welcome back! Here's what's happening today — {{ now()->format('l, F j, Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i>New Patient
        </a>
        <a href="{{ route('appointments.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-calendar-plus me-1"></i>New Appointment
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#0066cc,#0044aa);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Total Patients</div>
                    <div class="stat-value">{{ number_format($stats['total_patients']) }}</div>
                </div>
                <i class="bi bi-people stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#198754,#157347);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active Doctors</div>
                    <div class="stat-value">{{ $stats['total_doctors'] }}</div>
                </div>
                <i class="bi bi-person-badge stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#fd7e14,#dc6502);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Today's Appointments</div>
                    <div class="stat-value">{{ $stats['today_appointments'] }}</div>
                </div>
                <i class="bi bi-calendar-check stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6f42c1,#5a2d91);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Admitted Patients</div>
                    <div class="stat-value">{{ $stats['admitted_patients'] }}</div>
                </div>
                <i class="bi bi-door-open stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#dc3545,#b02a37);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending Bills</div>
                    <div class="stat-value">{{ $stats['pending_bills'] }}</div>
                </div>
                <i class="bi bi-receipt stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#0dcaf0,#0aa5c7);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Monthly Revenue</div>
                    <div class="stat-value" style="font-size:1.5rem;">₹{{ number_format($stats['monthly_revenue']) }}</div>
                </div>
                <i class="bi bi-currency-rupee stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#20c997,#17a37a);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Active Staff</div>
                    <div class="stat-value">{{ $stats['total_staff'] }}</div>
                </div>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6610f2,#520dc2);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">Pending Lab Tests</div>
                    <div class="stat-value">{{ $stats['pending_lab_tests'] }}</div>
                </div>
                <i class="bi bi-activity stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Today's Appointments -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-600">Today's Appointments</h6>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_appointments as $apt)
                            <tr>
                                <td class="fw-600">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $apt->patient) }}" class="text-decoration-none">
                                        <div class="fw-500">{{ $apt->patient->full_name }}</div>
                                        <small class="text-muted">{{ $apt->patient->patient_id }}</small>
                                    </a>
                                </td>
                                <td>{{ $apt->doctor->full_name }}</td>
                                <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$apt->type)) }}</span></td>
                                <td>
                                    <span class="badge {{ $apt->status_badge }}">{{ ucfirst(str_replace('_',' ',$apt->status)) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('appointments.show', $apt) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                    No appointments scheduled for today
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-600">Recent Patients</h6>
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($recent_patients as $patient)
                <a href="{{ route('patients.show', $patient) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom hover-bg">
                        <div style="width:40px;height:40px;border-radius:50%;background:{{ $patient->gender === 'male' ? '#dbeafe' : '#fce7f3' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person{{ $patient->gender === 'male' ? '' : '-dress' }}" style="color:{{ $patient->gender === 'male' ? '#1d4ed8' : '#be185d' }};"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-500 text-dark text-truncate">{{ $patient->full_name }}</div>
                            <small class="text-muted">{{ $patient->patient_id }} · {{ $patient->age }} yrs · {{ ucfirst($patient->blood_group ?? 'N/A') }}</small>
                        </div>
                        <span class="badge {{ $patient->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($patient->status) }}</span>
                    </div>
                </a>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-people fs-2 d-block mb-2"></i>
                    No patients yet
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="row g-3 mt-1">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-600">6-Month Overview</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-600">Upcoming Appointments</h6>
            </div>
            <div class="card-body p-0">
                @forelse($upcoming_appointments->take(6) as $apt)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="text-center" style="width:40px;">
                        <div style="font-size:1.2rem;font-weight:700;color:#0066cc;line-height:1;">{{ $apt->appointment_date->format('d') }}</div>
                        <div style="font-size:0.65rem;color:#64748b;text-transform:uppercase;">{{ $apt->appointment_date->format('M') }}</div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-500" style="font-size:0.85rem;">{{ $apt->patient->full_name }}</div>
                        <small class="text-muted">{{ $apt->doctor->full_name }} · {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">No upcoming appointments</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const monthlyStats = @json($monthly_stats);

const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: monthlyStats.map(s => s.month),
        datasets: [
            {
                label: 'Revenue (₹)',
                data: monthlyStats.map(s => s.revenue),
                backgroundColor: 'rgba(0,102,204,0.15)',
                borderColor: '#0066cc',
                borderWidth: 2,
                borderRadius: 6,
                type: 'bar',
            },
            {
                label: 'New Patients',
                data: monthlyStats.map(s => s.patients),
                borderColor: '#198754',
                backgroundColor: 'transparent',
                borderWidth: 2,
                type: 'line',
                tension: 0.4,
                yAxisID: 'y2',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Revenue (₹)' } },
            y2: { beginAtZero: true, position: 'right', title: { display: true, text: 'Patients' }, grid: { drawOnChartArea: false } }
        }
    }
});
</script>
@endpush
