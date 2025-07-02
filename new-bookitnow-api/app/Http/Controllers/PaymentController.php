<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Patient; // Import Patient model
use App\Models\Appointment; // Import Appointment model
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Only staff and admin can view all payments
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.'); // Or redirect to dashboard
        }

        $query = Payment::query()->with('patient'); // Eager load patient for display

        // Example: filter payments by date or status if needed
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        // Add more filters as per your requirements

        $payments = $query->orderBy('payment_date', 'desc')->paginate(10);

        if ($request->expectsJson()) {
            return response()->json($payments);
        }

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment (for Staff/Admin).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Only staff and admin can create payments
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        // Pass necessary data like patients, appointments here
        $patients = Patient::all();
        $appointments = Appointment::with('patient')->get(); // Load patient for display in dropdown
        return view('payments.create', compact('patients', 'appointments'));
    }

    /**
     * Show the form for a patient to make a payment for a specific appointment.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPatientPaymentForm(Appointment $appointment)
    {
        // Ensure the user is a patient and owns this appointment
        if (!Auth::user()->isPatient() || (Auth::user()->patient && Auth::user()->patient->id !== $appointment->patient_id)) {
            // Added Auth::user()->patient check in case patient profile is not linked
            abort(403, 'Unauthorized action.');
        }

        // Ensure the appointment is completed
        if ($appointment->status !== 'completed') {
            return redirect()->back()->with('error', 'Payment can only be made for completed appointments.');
        }

        // Check if payment already exists for this appointment
        if (Payment::where('appointment_id', $appointment->id)->exists()) {
            return redirect()->back()->with('error', 'This appointment has already been paid for.');
        }

        return view('payments.patient_pay', compact('appointment'));
    }

    /**
     * Store a newly created payment in storage.
     * This method handles both staff/admin creation and patient self-payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Determine if the current user is a patient making a self-payment
        $isPatientSelfPayment = Auth::user()->isPatient();

        // Authorization check:
        // Staff/Admin can create payments for any patient/appointment.
        // Patients can only create payments for their own appointments.
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin() && !$isPatientSelfPayment) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $rules = [
                'patient_id' => 'required|exists:patients,id',
                'appointment_id' => 'nullable|exists:appointments,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|string|max:50',
                'payment_date' => 'required|date',
                'notes' => 'nullable|string|max:1000',
            ];

            // If it's a patient self-payment, enforce that patient_id and appointment_id match their own
            if ($isPatientSelfPayment) {
                // Ensure Auth::user()->patient exists before accessing its id
                if (!Auth::user()->patient) {
                    throw ValidationException::withMessages(['patient_id' => 'Your user account is not linked to a patient profile.']);
                }
                $patientId = Auth::user()->patient->id;
                $rules['patient_id'] = 'required|exists:patients,id|in:' . $patientId;
                $rules['appointment_id'] = [
                    'required', // Patient must specify an appointment
                    'exists:appointments,id',
                    function ($attribute, $value, $fail) use ($patientId) {
                        $appointment = Appointment::find($value);
                        if (!$appointment || $appointment->patient_id !== $patientId) {
                            $fail('The selected appointment does not belong to you.');
                        }
                        if ($appointment->status !== 'completed') {
                            $fail('Payment can only be made for completed appointments.');
                        }
                        if (Payment::where('appointment_id', $value)->exists()) {
                            $fail('This appointment has already been paid for.');
                        }
                    },
                ];
                // For patient self-payment, payment_date can default to today
                $request->merge(['payment_date' => now()->format('Y-m-d')]);
            }

            $request->validate($rules);

            $payment = Payment::create($request->all());

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Payment recorded successfully!', 'payment' => $payment], 201);
            }

            return redirect()->route('dashboard')->with('success', 'Payment recorded successfully!'); // Redirect patient to dashboard

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Display the specified payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show(Payment $payment)
    {
        // Only staff and admin can view payment details
        // Patients can view their own payments
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin() && (!Auth::user()->isPatient() || (Auth::user()->patient && Auth::user()->patient->id !== $payment->patient_id))) {
             // Added Auth::user()->patient check
            abort(403, 'Unauthorized action.');
        }
        if (request()->expectsJson()) {
            return response()->json($payment);
        }
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\View\View
     */
    public function edit(Payment $payment)
    {
        // Only staff and admin can edit payments
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        // Pass necessary data like patients, appointments here
        $patients = Patient::all();
        $appointments = Appointment::with('patient')->get(); // Load patient for display in dropdown
        return view('payments.edit', compact('payment', 'patients', 'appointments'));
    }

    /**
     * Update the specified payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Payment $payment)
    {
        // Only staff and admin can update payments
        if (!Auth::user()->isStaff() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'appointment_id' => 'nullable|exists:appointments,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|string|max:50',
                'payment_date' => 'required|date',
                'notes' => 'nullable|string|max:1000',
            ]);

            $payment->update($request->all());

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Payment updated successfully!', 'payment' => $payment]);
            }

            return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Remove the specified payment from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Payment $payment)
    {
        // Only admin can delete payments (or staff, depending on your policy)
        if (!Auth::user()->isAdmin()) { // Assuming only admin can delete for security
            abort(403, 'Unauthorized action.');
        }

        $payment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Payment deleted successfully!']);
        }

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}