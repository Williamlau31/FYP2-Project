<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'staff'])
            ->latest()
            ->get();
        
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);
        $appointment->load(['patient', 'staff']);

        return response()->json($appointment, 201);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'staff']);
        return response()->json($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'status' => 'sometimes|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);
        $appointment->load(['patient', 'staff']);

        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }
}