@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Patient Details: {{ $patient->name }}</h1>

    <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Name:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->name }}</p>

                <p class="text-gray-600 text-sm font-semibold">Email:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->email }}</p>

                <p class="text-gray-600 text-sm font-semibold">Phone:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->phone }}</p>

                <p class="text-gray-600 text-sm font-semibold">Address:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->address }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Date of Birth:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->date_of_birth ? $patient->date_of_birth->format('M j, Y') : 'N/A' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Age:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->age ?? 'N/A' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Gender:</p>
                <p class="text-gray-800 text-lg mb-4">{{ ucfirst($patient->gender) ?? 'N/A' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Emergency Contact:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->emergency_contact ?? 'N/A' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Medical History:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $patient->medical_history ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-3xl font-bold text-gray-800 mb-6">Patient's Appointments</h2>
    @if ($appointments->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-600 text-lg">This patient has no appointments.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left rounded-tl-lg">Date</th>
                            <th class="py-3 px-6 text-left">Time</th>
                            <th class="py-3 px-6 text-left">Staff</th>
                            <th class="py-3 px-6 text-left">Type</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-center rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @foreach ($appointments as $appointment)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $appointment->formatted_date }}</td>
                                <td class="py-3 px-6 text-left">{{ $appointment->formatted_time }}</td>
                                <td class="py-3 px-6 text-left">{{ $appointment->staff->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $appointment->appointment_type ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($appointment->status == 'scheduled') bg-blue-200 text-blue-800
                                        @elseif($appointment->status == 'completed') bg-green-200 text-green-800
                                        @elseif($appointment->status == 'cancelled') bg-red-200 text-red-800
                                        @else bg-gray-200 text-gray-800
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110" title="Edit Appointment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" title="Delete Appointment">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('patients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Patients List
        </a>
    </div>
</div>
@endsection