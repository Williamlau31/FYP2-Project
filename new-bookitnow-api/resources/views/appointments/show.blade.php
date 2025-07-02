@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Appointment Details</h1>

    <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Patient Name:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $appointment->patient->name }}</p>

                <p class="text-gray-600 text-sm font-semibold">Staff/Doctor Name:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $appointment->staff->name }}</p>

                <p class="text-gray-600 text-sm font-semibold">Date:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $appointment->formatted_date }}</p>

                <p class="text-gray-600 text-sm font-semibold">Time:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $appointment->formatted_time }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Appointment Type:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $appointment->appointment_type ?? 'N/A' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Status:</p>
                <p class="text-gray-800 text-lg mb-4">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($appointment->status == 'scheduled') bg-blue-200 text-blue-800
                        @elseif($appointment->status == 'completed') bg-green-200 text-green-800
                        @elseif($appointment->status == 'cancelled') bg-red-200 text-red-800
                        @else bg-gray-200 text-gray-800
                        @endif">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </p>

                <p class="text-gray-600 text-sm font-semibold">Notes:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $appointment->notes ?? 'No notes.' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Created At:</p>
                <p class="text-gray-800 text-lg">{{ $appointment->created_at->format('M j, Y H:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-start space-x-4">
        <a href="{{ route('appointments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
        </a>
        @if(Auth::user()->isAdmin() || (Auth::user()->isUser() && $appointment->status == 'scheduled'))
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                <i class="fas fa-edit mr-2"></i> Edit Appointment
            </a>
        @endif
        @if(Auth::user()->isAdmin())
            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-trash-alt mr-2"></i> Delete Appointment
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
