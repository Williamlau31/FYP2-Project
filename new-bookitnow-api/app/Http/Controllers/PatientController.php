<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->paginate(10);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string',
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

        $patient = Patient::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient added successfully!',
                'patient' => $patient
            ]);
        }

        return redirect()->route('patients.index')->with('success', 'Patient added successfully!');
    }

    public function show(Patient $patient)
    {
        $appointments = $patient->appointments()->with('staff')->orderBy('date', 'desc')->get();
        return view('patients.show', compact('patient', 'appointments'));
    }

    public function edit(Patient $patient)
    {
        if (request()->ajax()) {
            return response()->json($patient);
        }
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'emergency_contact' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string',
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

        $patient->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient updated successfully!',
                'patient' => $patient
            ]);
        }

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully!'
            ]);
        }

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $patients = Patient::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($patients);
    }
}
