@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Appointment</h1>

    <div class="bg-white rounded-lg shadow-xl p-8">
        <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if(Auth::user()->isAdmin())
                <div class="mb-6">
                    <label for="patient_id" class="block text-gray-700 text-sm font-bold mb-2">Patient</label>
                    <select name="patient_id" id="patient_id" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('patient_id') border-red-500 @enderror" required>
                        <option value="">Select a Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ (old('patient_id') ?? $appointment->patient_id) == $patient->id ? 'selected' : '' }}>{{ $patient->name }} ({{ $patient->email }})</option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
            @endif

            <div class="mb-6">
                <label for="staff_id" class="block text-gray-700 text-sm font-bold mb-2">Doctor/Staff</label>
                <select name="staff_id" id="staff_id" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('staff_id') border-red-500 @enderror" required>
                    <option value="">Select a Doctor/Staff</option>
                    @foreach($staff as $s)
                        <option value="{{ $s->id }}" {{ (old('staff_id') ?? $appointment->staff_id) == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->specialization ?? $s->role }})</option>
                    @endforeach
                </select>
                @error('staff_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                    <input type="date" name="date" id="date" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('date') border-red-500 @enderror" value="{{ old('date') ?? $appointment->date->format('Y-m-d') }}" required>
                    @error('date')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="time" class="block text-gray-700 text-sm font-bold mb-2">Time</label>
                    <input type="time" name="time" id="time" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('time') border-red-500 @enderror" value="{{ old('time') ?? \Carbon\Carbon::parse($appointment->time)->format('H:i') }}" required>
                    @error('time')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="appointment_type" class="block text-gray-700 text-sm font-bold mb-2">Appointment Type (Optional)</label>
                <input type="text" name="appointment_type" id="appointment_type" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('appointment_type') border-red-500 @enderror" value="{{ old('appointment_type') ?? $appointment->appointment_type }}" placeholder="e.g., General Checkup, Follow-up">
                @error('appointment_type')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" id="status" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('status') border-red-500 @enderror" required
                    @if(Auth::user()->isUser()) disabled @endif> {{-- Patients can't change status --}}
                    <option value="scheduled" {{ (old('status') ?? $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ (old('status') ?? $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ (old('status') ?? $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @if(Auth::user()->isUser())
                    <p class="text-gray-500 text-xs italic mt-2">Only staff can change the appointment status.</p>
                @endif
                @error('status')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="4" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('notes') border-red-500 @enderror" placeholder="Any specific notes for the appointment...">{{ old('notes') ?? $appointment->notes }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                    Update Appointment
                </button>
                <a href="{{ route('appointments.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
