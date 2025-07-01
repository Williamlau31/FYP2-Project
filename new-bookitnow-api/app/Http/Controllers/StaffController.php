<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index()
    {
        $staff = Staff::orderBy('created_at', 'desc')->paginate(10);
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:20',
            'role' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
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

        $staff = Staff::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff member added successfully!',
                'staff' => $staff
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully!');
    }

    public function show(Staff $staff)
    {
        $appointments = $staff->appointments()->with('patient')->orderBy('date', 'desc')->get();
        return view('staff.show', compact('staff', 'appointments'));
    }

    public function edit(Staff $staff)
    {
        if (request()->ajax()) {
            return response()->json($staff);
        }
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'phone' => 'required|string|max:20',
            'role' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
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

        $staff->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff member updated successfully!',
                'staff' => $staff
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully!');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff member deleted successfully!'
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully!');
    }
}
