@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <h1 class="text-4xl font-bold text-gray-800 mb-8">Record New Payment</h1>

    <div class="bg-white rounded-lg shadow-xl p-8">

        <form action="{{ route('payments.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="patient_id" class="block text-gray-700 text-sm font-bold mb-2">Patient</label>
                <select name="patient_id" id="patient_id" class="shadow appearance-none border
                    rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2
                    focus:ring-blue-500 focus:border-transparent transition duration-200 @error('patient_id')
                    border-red-500 @enderror" required>
                    <option value="">Select a Patient</option>
                    {{-- You'll need to pass $patients from the controller to populate this dropdown --}}
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name }} ({{ $patient->email }})
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="appointment_id" class="block text-gray-700 text-sm font-bold mb-2">Related Appointment (Optional)</label>
                <select name="appointment_id" id="appointment_id" class="shadow appearance-none border
                    rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2
                    focus:ring-blue-500 focus:border-transparent transition duration-200 @error('appointment_id')
                    border-red-500 @enderror">
                    <option value="">No specific appointment</option>
                    {{-- You'll need to pass $appointments from the controller to populate this dropdown --}}
                    @foreach($appointments as $appointment)
                        <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                            {{ $appointment->patient->name }} - {{ $appointment->formatted_date }} {{ $appointment->formatted_time }}
                        </option>
                    @endforeach
                </select>
                @error('appointment_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount (RM)</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="shadow
                        appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-
                        none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200
                        @error('amount') border-red-500 @enderror" value="{{ old('amount') }}" required min="0.01">
                    @error('amount')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="shadow appearance-none border
                        rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2
                        focus:ring-blue-500 focus:border-transparent transition duration-200 @error('payment_method')
                        border-red-500 @enderror" required>
                        <option value="">Select Method</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="Online Transfer" {{ old('payment_method') == 'Online Transfer' ? 'selected' : '' }}>Online Transfer</option>
                        <option value="Insurance" {{ old('payment_method') == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="payment_date" class="block text-gray-700 text-sm font-bold mb-2">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" class="shadow appearance-none border
                    rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2
                    focus:ring-blue-500 focus:border-transparent transition duration-200 @error('payment_date')
                    border-red-500 @enderror" value="{{ old('payment_date') ?? \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                @error('payment_date')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="4" class="shadow appearance-none border
                    rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2
                    focus:ring-blue-500 focus:border-transparent transition duration-200 @error('notes') border-
                    red-500 @enderror" placeholder="Any additional notes about the payment...">{{ old('notes')
                    }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3
                    px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                    Record Payment
                </button>
                <a href="{{ route('payments.index') }}" class="bg-gray-400 hover:bg-gray-500 text-
                    white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition
                    duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection