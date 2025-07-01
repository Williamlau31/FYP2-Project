<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::latest()->get();
        return response()->json($staff);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'role' => 'required|string|max:100',
            'department' => 'required|string|max:100',
        ]);

        $staff = Staff::create($validated);

        return response()->json($staff, 201);
    }

    public function show(Staff $staff)
    {
        return response()->json($staff);
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $staff->id,
            'role' => 'required|string|max:100',
            'department' => 'required|string|max:100',
        ]);

        $staff->update($validated);

        return response()->json($staff);
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return response()->json([
            'message' => 'Staff member deleted successfully'
        ]);
    }
}