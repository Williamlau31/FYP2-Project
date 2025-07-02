@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Safely access $user, which should be passed from the controller,
         or use Auth::user() directly after checking authentication. --}}
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Welcome, {{ Auth::user()->name }}!</h1>

    @if (Auth::user()->isAdmin())
        {{-- Admin Dashboard --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Patients</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_patients'] }}</p>
                </div>
                <i class="fas fa-users text-5xl text-blue-200"></i>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Staff</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_staff'] }}</p>
                </div>
                <i class="fas fa-user-md text-5xl text-green-200"></i>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Today's Appointments</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['today_appointments'] }}</p>
                </div>
                <i class="fas fa-calendar-day text-5xl text-yellow-200"></i>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Patients in Queue</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['queue_count'] }}</p>
                </div>
                <i class="fas fa-list-ol text-5xl text-purple-200"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Today's Appointments</h2>
                @if ($today_appointments->isEmpty())
                    <p class="text-gray-600">No appointments scheduled for today.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left rounded-tl-lg">Time</th>
                                    <th class="py-3 px-6 text-left">Patient</th>
                                    <th class="py-3 px-6 text-left">Staff</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-center rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm font-light">
                                @foreach ($today_appointments as $appointment)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6 text-left">{{ $appointment->formatted_time }}</td>
                                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $appointment->patient->name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $appointment->staff->name }}</td>
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
                                                <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit Appointment">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- Delete button for admin --}}
                                                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this appointment?');" class="inline-block ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete Appointment">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">View All Appointments &rarr;</a>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Recent Activities</h2>
                @if ($recent_activities->isEmpty())
                    <p class="text-gray-600">No recent activities.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($recent_activities as $activity)
                            <li class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        @if($activity['color'] == 'primary') bg-blue-100 text-blue-600
                                        @elseif($activity['color'] == 'success') bg-green-100 text-green-600
                                        @else bg-gray-100 text-gray-600
                                        @endif">
                                        <i class="fas fa-{{ $activity['icon'] }}"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $activity['title'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @elseif (Auth::user()->isPatient())
        {{-- Patient Dashboard --}}
        <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-calendar-alt mr-3 text-blue-600"></i> Your Appointments
            </h2>

            {{-- Ensure patient relationship exists before accessing its properties --}}
            @if (Auth::user()->patient && Auth::user()->patient->appointments->isEmpty())
                <p class="text-gray-600 text-lg">You have no appointments scheduled.</p>
                <div class="mt-6">
                    <a href="{{ route('appointments.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        <i class="fas fa-plus-circle mr-2"></i> Book New Appointment
                    </a>
                </div>
            @elseif (Auth::user()->patient)
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full bg-white rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left rounded-tl-lg">Date</th>
                                <th class="py-3 px-6 text-left">Time</th>
                                <th class="py-3 px-6 text-left">Doctor/Staff</th>
                                <th class="py-3 px-6 text-left">Type</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-center rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @foreach (Auth::user()->patient->appointments->sortBy('date')->sortBy('time') as $appointment)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $appointment->formatted_date }}</td>
                                    <td class="py-3 px-6 text-left">{{ $appointment->formatted_time }}</td>
                                    <td class="py-3 px-6 text-left">{{ $appointment->staff->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $appointment->appointment_type ?? 'General Checkup' }}</td>
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
                                        <div class="flex item-center justify-center space-x-2">
                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-800" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- Only allow editing if scheduled --}}
                                            @if($appointment->status == 'scheduled')
                                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit Appointment">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            {{-- "Make Payment" button for completed and unpaid appointments --}}
                                            @if($appointment->status == 'completed' && !$appointment->isPaid())
                                                <a href="{{ route('appointments.pay', $appointment->id) }}" class="text-green-600 hover:text-green-800" title="Make Payment">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </a>
                                            @elseif($appointment->status == 'completed' && $appointment->isPaid())
                                                <span class="text-gray-500" title="Already Paid">
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-lg">Your patient profile is not yet linked. Please contact support.</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-list-ol mr-3 text-purple-600"></i> Your Queue Status
            </h2>

            @if (Auth::user()->patient && Auth::user()->patient->queueItems->whereIn('status', ['waiting', 'called'])->isEmpty())
                <p class="text-gray-600 text-lg">You are not currently in the queue.</p>
                <div class="mt-6">
                    <a href="{{ route('queue.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        <i class="fas fa-plus-circle mr-2"></i> Join Queue
                    </a>
                </div>
            @elseif (Auth::user()->patient)
                @php
                    $currentQueueItem = Auth::user()->patient->queueItems->whereIn('status', ['waiting', 'called'])->first();
                    $waitingCount = \App\Models\QueueItem::where('queue_number', '<', $currentQueueItem->queue_number)
                        ->where('status', 'waiting')
                        ->count();
                @endphp
                <div class="text-center">
                    <p class="text-lg text-gray-700 mb-2">Your current queue number is:</p>
                    <p class="text-6xl font-extrabold text-purple-700 mb-4">{{ $currentQueueItem->queue_number }}</p>
                    <p class="text-xl text-gray-700 mb-4">Status:
                        <span class="font-semibold
                            @if($currentQueueItem->status == 'waiting') text-yellow-600
                            @elseif($currentQueueItem->status == 'called') text-blue-600
                            @else text-gray-600
                            @endif">
                            {{ ucfirst($currentQueueItem->status) }}
                        </span>
                    </p>
                    @if ($currentQueueItem->status == 'waiting')
                        <p class="text-md text-gray-600">There are <span class="font-bold text-purple-700">{{ $waitingCount }}</span> patients ahead of you.</p>
                    @elseif ($currentQueueItem->status == 'called')
                        <p class="text-md text-green-600 font-semibold">It's your turn! Please proceed to the counter.</p>
                    @endif
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('queue.index') }}" class="inline-block bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        View Full Queue
                    </a>
                </div>
            @else
                <p class="text-gray-600 text-lg">Your patient profile is not yet linked. Please contact support.</p>
            @endif
        </div>
    @elseif (Auth::user()->isStaff())
        {{-- Staff Dashboard (can be similar to admin, or a simplified version) --}}
        {{-- For simplicity, staff might see a subset of admin stats or their own specific stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Today's Appointments (Your's)</h3>
                    {{-- Assuming staff can see their own appointments --}}
                    <p class="text-3xl font-bold text-yellow-600">{{ $staff_today_appointments_count ?? 'N/A' }}</p>
                </div>
                <i class="fas fa-calendar-day text-5xl text-yellow-200"></i>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Patients in Queue</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['queue_count'] ?? 'N/A' }}</p>
                </div>
                <i class="fas fa-list-ol text-5xl text-purple-200"></i>
            </div>
            {{-- Add more staff-specific stats as needed --}}
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Your Upcoming Appointments</h2>
            {{-- Display appointments assigned to the logged-in staff member --}}
            @if (Auth::user()->staffMember && Auth::user()->staffMember->staffAppointments->where('status', 'scheduled')->isEmpty())
                <p class="text-gray-600">You have no upcoming appointments assigned to you.</p>
            @elseif (Auth::user()->staffMember)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left rounded-tl-lg">Time</th>
                                <th class="py-3 px-6 text-left">Patient</th>
                                <th class="py-3 px-6 text-left">Type</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-center rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @foreach (Auth::user()->staffMember->staffAppointments->where('status', 'scheduled')->sortBy('date')->sortBy('time') as $appointment)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left">{{ $appointment->formatted_time }}</td>
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $appointment->patient->name }}</td>
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
                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-800 mr-3" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit Appointment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-lg">Your staff profile is not yet linked. Please contact support.</p>
            @endif
        </div>
    @else
        {{-- Default view for unauthenticated or unassigned roles --}}
        <div class="bg-white rounded-lg shadow-xl p-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Welcome to BookItNow!</h2>
            <p class="text-gray-600 text-lg mb-6">Please log in or register to access the full features of the application.</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Login</a>
                <a href="{{ route('register') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Register</a>
            </div>
        </div>
    @endif
</div>
@endsection