<?php

namespace App\Http\Controllers;

use App\Models\{Appointment, Patient, Doctor};
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('appointment_number', 'like', "%$search%")
                  ->orWhereHas('patient', fn($p) => $p->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"))
                  ->orWhereHas('doctor', fn($d) => $d->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"));
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('date')) $query->whereDate('appointment_date', $request->date);
        if ($request->filled('doctor_id')) $query->where('doctor_id', $request->doctor_id);

        $appointments = $query->orderBy('appointment_date', 'desc')->orderBy('appointment_time')->paginate(15)->withQueryString();
        $doctors = Doctor::where('status', 'active')->get();

        return view('appointments.index', compact('appointments', 'doctors'));
    }

    public function create()
    {
        $patients = Patient::where('status', 'active')->get();
        $doctors  = Doctor::where('status', 'active')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'type'             => 'required|in:consultation,follow_up,emergency,routine_checkup',
            'reason'           => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($validated['doctor_id']);
        $validated['fee'] = $doctor->consultation_fee;

        $appointment = Appointment::create($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', "Appointment {$appointment->appointment_number} scheduled successfully.");
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'prescriptions.medicine']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::where('status', 'active')->get();
        $doctors  = Doctor::where('status', 'active')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration_minutes' => 'required|integer|min:15|max:240',
            'type'             => 'required|in:consultation,follow_up,emergency,routine_checkup',
            'status'           => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'reason'           => 'nullable|string',
            'notes'            => 'nullable|string',
            'diagnosis'        => 'nullable|string',
            'prescription'     => 'nullable|string',
            'fee'              => 'required|numeric|min:0',
            'payment_status'   => 'required|in:pending,paid,waived',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Appointment status updated.');
    }
}
