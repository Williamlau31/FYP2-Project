@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">

    <h1 class="text-4xl font-bold text-gray-800 mb-8">Appointments</h1>

    {{-- Admin and Staff can see the "Schedule New Appointment" button and filters --}}
    @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('appointments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-
                white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Schedule New Appointment
            </a>

            <form action="{{ route('appointments.index') }}" method="GET" class="flex items-center
                space-x-4">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-
                    input rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-
                    200 focus:ring-opacity-50">
                <span class="text-gray-600">to</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input
                    rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200
                    focus:ring-opacity-50">
                <select name="status" class="form-select rounded-lg border-gray-300 shadow-sm
                    focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">All Statuses</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : ''
                        }}>Scheduled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : ''
                        }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : ''
                        }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2
                    px-4 rounded-lg shadow-md transition duration-300">
                    Filter
                </button>
                <a href="{{ route('appointments.index') }}" class="bg-gray-400 hover:bg-gray-500 text-
                    white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Reset
                </a>
            </form>
        </div>
    @elseif(Auth::user()->isPatient())
        {{-- Patient specific "Book New Appointment" --}}
        <div class="flex justify-end items-center mb-6">
            <a href="{{ route('appointments.create') }}" class="inline-block bg-blue-600 hover:bg-
                blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                <i class="fas fa-plus-circle mr-2"></i> Book New Appointment
            </a>
        </div>
    @endif

    @if ($appointments->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-600 text-lg">No appointments found.</p>
            {{-- This button is redundant if already shown above for patients, but kept for clarity --}}
            @if(Auth::user()->isPatient())
                <div class="mt-4">
                    <a href="{{ route('appointments.create') }}" class="inline-block bg-blue-600 hover:bg-
                        blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        <i class="fas fa-plus-circle mr-2"></i> Book New Appointment
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left rounded-tl-lg">Patient</th>
                            <th class="py-3 px-6 text-left">Staff</th>
                            <th class="py-3 px-6 text-left">Date</th>
                            <th class="py-3 px-6 text-left">Time</th>
                            <th class="py-3 px-6 text-left">Type</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-center rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @foreach ($appointments as $appointment)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $appointment->patient->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $appointment->staff->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $appointment->formatted_date }}</td>
                                <td class="py-3 px-6 text-left">{{ $appointment->formatted_time }}</td>
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
                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="w-4 mr-
                                            2 transform hover:text-blue-500 hover:scale-110" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Only Admin and Staff can edit/delete appointments --}}
                                        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110" title="Edit Appointment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('appointments.destroy', $appointment->id) }}"
                                                method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500
                                                    hover:scale-110" title="Delete Appointment">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                            {{-- Status update dropdown for staff/admin --}}
                                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                                <button @click="open = !open" class="w-4 transform hover:text-purple
                                                    500 hover:scale-110" title="Update Status">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false" class="origin-top-right
                                                    absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5
                                                    focus:outline-none z-10">
                                                    <div class="py-1" role="menu" aria-orientation="vertical" aria
                                                        labelledby="options-menu">
                                                        {{-- Update status to Completed --}}
                                                        <form action="{{ route('appointments.update-status', $appointment->id) }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="block w-full text-left">Completed</button>
                                                        </form>
                                                        {{-- Update status to Cancelled --}}
                                                        <form action="{{ route('appointments.update-status', $appointment->id) }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button type="submit" class="block w-full text-left">Cancelled</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    @endif
</div>

@endsection
