<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'staff']);

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by today
        if ($request->has('filter') && $request->filter === 'today') {
            $query->whereDate('date', Carbon::today());
        }

        // Filter by this week
        if ($request->has('filter') && $request->filter === 'week') {
            $query->whereBetween('date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        }

        $appointments = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);

        $patients = Patient::orderBy('name')->get();
        $staff = Staff::orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => Appointment::count(),
            'scheduled' => Appointment::where('status', 'scheduled')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('appointments.index', compact('appointments', 'patients', 'staff', 'stats'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $staff = Staff::orderBy('name')->get();
        return view('appointments.create', compact('patients', 'staff'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'appointment_type' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for conflicting appointments
        $conflictingAppointment = Appointment::where('staff_id', $request->staff_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($conflictingAppointment) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot is already booked for the selected staff member.'
                ], 422);
            }
            return redirect()->back()->with('error', 'This time slot is already booked for the selected staff member.')->withInput();
        }

        $appointment = Appointment::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment scheduled successfully!',
                'appointment' => $appointment->load(['patient', 'staff'])
            ]);
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully!');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'staff']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        if (request()->ajax()) {
            return response()->json($appointment);
        }
        
        $patients = Patient::orderBy('name')->get();
        $staff = Staff::orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'staff'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'appointment_type' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check for conflicting appointments (excluding current appointment)
        $conflictingAppointment = Appointment::where('staff_id', $request->staff_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $appointment->id)
            ->first();

        if ($conflictingAppointment) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot is already booked for the selected staff member.'
                ], 422);
            }
            return redirect()->back()->with('error', 'This time slot is already booked for the selected staff member.')->withInput();
        }

        $appointment->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully!',
                'appointment' => $appointment->load(['patient', 'staff'])
            ]);
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Appointment deleted successfully!'
            ]);
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully!');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $appointment->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated successfully!',
            'appointment' => $appointment->load(['patient', 'staff'])
        ]);
    }
}
