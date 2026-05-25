<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('patient_id', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        $patients = $query->latest()->paginate(15)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'                  => 'required|string|max:100',
            'last_name'                   => 'required|string|max:100',
            'date_of_birth'               => 'required|date|before:today',
            'gender'                      => 'required|in:male,female,other',
            'blood_group'                 => 'nullable|string|max:5',
            'phone'                       => 'required|string|max:20',
            'email'                       => 'nullable|email|max:100',
            'address'                     => 'required|string',
            'city'                        => 'required|string|max:100',
            'state'                       => 'required|string|max:100',
            'postal_code'                 => 'nullable|string|max:20',
            'emergency_contact_name'      => 'nullable|string|max:100',
            'emergency_contact_phone'     => 'nullable|string|max:20',
            'emergency_contact_relation'  => 'nullable|string|max:50',
            'medical_history'             => 'nullable|string',
            'allergies'                   => 'nullable|string',
            'insurance_provider'          => 'nullable|string|max:100',
            'insurance_number'            => 'nullable|string|max:100',
            'status'                      => 'required|in:active,inactive,discharged',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', "Patient {$patient->full_name} ({$patient->patient_id}) registered successfully.");
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'appointments.doctor',
            'admissions.doctor',
            'admissions.room',
            'bills',
            'labTests.doctor',
        ]);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'                  => 'required|string|max:100',
            'last_name'                   => 'required|string|max:100',
            'date_of_birth'               => 'required|date|before:today',
            'gender'                      => 'required|in:male,female,other',
            'blood_group'                 => 'nullable|string|max:5',
            'phone'                       => 'required|string|max:20',
            'email'                       => 'nullable|email|max:100',
            'address'                     => 'required|string',
            'city'                        => 'required|string|max:100',
            'state'                       => 'required|string|max:100',
            'postal_code'                 => 'nullable|string|max:20',
            'emergency_contact_name'      => 'nullable|string|max:100',
            'emergency_contact_phone'     => 'nullable|string|max:20',
            'emergency_contact_relation'  => 'nullable|string|max:50',
            'medical_history'             => 'nullable|string',
            'allergies'                   => 'nullable|string',
            'insurance_provider'          => 'nullable|string|max:100',
            'insurance_number'            => 'nullable|string|max:100',
            'status'                      => 'required|in:active,inactive,discharged',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient record updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')
            ->with('success', 'Patient record deleted successfully.');
    }
}
