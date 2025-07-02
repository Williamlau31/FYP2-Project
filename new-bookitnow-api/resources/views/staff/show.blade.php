@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Staff Details: {{ $staff->name }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-600"><strong>Email:</strong> {{ $staff->email }}</p>
                <p class="text-gray-600"><strong>Phone:</strong> {{ $staff->phone }}</p>
                <p class="text-gray-600"><strong>Role:</strong> {{ ucfirst($staff->role) }}</p>
                <p class="text-gray-600"><strong>Department:</strong> {{ $staff->department }}</p>
            </div>
            <div>
                <p class="text-gray-600"><strong>Specialization:</strong> {{ $staff->specialization ?? 'N/A' }}</p>
                <p class="text-gray-600"><strong>License Number:</strong> {{ $staff->license_number ?? 'N/A' }}</p>
                <p class="text-gray-600"><strong>Hire Date:</strong> {{ $staff->hire_date ? $staff->hire_date->format('M j, Y') : 'N/A' }}</p>
                <p class="text-gray-600"><strong>Salary:</strong> {{ $staff->salary ? 'RM' . number_format($staff->salary, 2) : 'N/A' }}</p>
            </div>
        </div>
        <p class="text-gray-600 mb-6"><strong>Address:</strong> {{ $staff->address ?? 'N/A' }}</p>

        <div class="flex items-center space-x-4">
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('staff.edit', $staff->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                        Edit Staff
                    </a>
                    <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                            Delete Staff
                        </button>
                    </form>
                @endif
            @endauth
            <a href="{{ route('staff.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                Back to Staff List
            </a>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Appointments with {{ $staff->name }}</h2>
        @if ($appointments->isEmpty())
            <p class="text-gray-600">No appointments found for this staff member.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tl-lg">
                                Patient
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Time
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tr-lg">
                                Type
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $appointment->patient->name }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $appointment->formatted_date }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $appointment->formatted_time }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                        <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full bg-{{ $appointment->status_color }}-200"></span>
                                        <span class="relative text-{{ $appointment->status_color }}-900">{{ ucfirst($appointment->status) }}</span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $appointment->appointment_type ?? 'General' }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection