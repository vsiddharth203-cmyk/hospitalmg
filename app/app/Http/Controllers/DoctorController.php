<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('doctor_id', 'like', "%$search%")
                  ->orWhere('specialization', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('specialization')) $query->where('specialization', 'like', "%{$request->specialization}%");

        $doctors = $query->withCount('appointments')->latest()->paginate(15)->withQueryString();

        $specializations = Doctor::distinct()->pluck('specialization');

        return view('doctors.index', compact('doctors', 'specializations'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'specialization'   => 'required|string|max:100',
            'qualification'    => 'required|string|max:200',
            'experience_years' => 'required|integer|min:0|max:60',
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|unique:doctors,email',
            'license_number'   => 'required|string|unique:doctors,license_number',
            'consultation_fee' => 'required|numeric|min:0',
            'bio'              => 'nullable|string',
            'status'           => 'required|in:active,inactive,on_leave',
            'available_days'   => 'nullable|array',
            'available_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'available_from'   => 'nullable|date_format:H:i',
            'available_to'     => 'nullable|date_format:H:i|after:available_from',
        ]);

        $doctor = Doctor::create($validated);

        return redirect()->route('doctors.show', $doctor)
            ->with('success', "Doctor {$doctor->full_name} ({$doctor->doctor_id}) added successfully.");
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['appointments.patient', 'admissions.patient']);
        $stats = [
            'total_appointments' => $doctor->appointments()->count(),
            'this_month'         => $doctor->appointments()->whereMonth('appointment_date', now()->month)->count(),
            'completed'          => $doctor->appointments()->where('status', 'completed')->count(),
        ];
        return view('doctors.show', compact('doctor', 'stats'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'specialization'   => 'required|string|max:100',
            'qualification'    => 'required|string|max:200',
            'experience_years' => 'required|integer|min:0|max:60',
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|unique:doctors,email,' . $doctor->id,
            'license_number'   => 'required|string|unique:doctors,license_number,' . $doctor->id,
            'consultation_fee' => 'required|numeric|min:0',
            'bio'              => 'nullable|string',
            'status'           => 'required|in:active,inactive,on_leave',
            'available_days'   => 'nullable|array',
            'available_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'available_from'   => 'nullable|date_format:H:i',
            'available_to'     => 'nullable|date_format:H:i',
        ]);

        $doctor->update($validated);

        return redirect()->route('doctors.show', $doctor)
            ->with('success', 'Doctor record updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor removed successfully.');
    }
}
