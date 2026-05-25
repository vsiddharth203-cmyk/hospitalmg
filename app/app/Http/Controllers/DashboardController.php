<?php

namespace App\Http\Controllers;

use App\Models\{Patient, Doctor, Appointment, Bill, Admission, LabTest, Staff};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients'      => Patient::count(),
            'total_doctors'       => Doctor::where('status', 'active')->count(),
            'today_appointments'  => Appointment::whereDate('appointment_date', today())->count(),
            'admitted_patients'   => Admission::where('status', 'admitted')->count(),
            'pending_bills'       => Bill::whereIn('status', ['sent', 'partial', 'overdue'])->count(),
            'monthly_revenue'     => Bill::whereMonth('bill_date', now()->month)
                                        ->where('status', 'paid')->sum('total'),
            'total_staff'         => Staff::where('status', 'active')->count(),
            'pending_lab_tests'   => LabTest::whereIn('status', ['ordered', 'sample_collected', 'processing'])->count(),
        ];

        $recent_appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->limit(8)
            ->get();

        $recent_patients = Patient::latest()->limit(5)->get();

        $upcoming_appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', '>=', today())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        $monthly_stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthly_stats[] = [
                'month'    => $month->format('M Y'),
                'patients' => Patient::whereMonth('created_at', $month->month)
                                     ->whereYear('created_at', $month->year)->count(),
                'revenue'  => Bill::whereMonth('bill_date', $month->month)
                                  ->whereYear('bill_date', $month->year)
                                  ->where('status', 'paid')->sum('total'),
            ];
        }

        return view('dashboard.index', compact('stats', 'recent_appointments', 'recent_patients', 'upcoming_appointments', 'monthly_stats'));
    }
}
