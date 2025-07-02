@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <h1 class="text-4xl font-bold text-gray-800 mb-8">Payment Details</h1>

    <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Payment ID:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $payment->id }}</p>

                <p class="text-gray-600 text-sm font-semibold">Patient Name:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $payment->patient->name }}</p>

                <p class="text-gray-600 text-sm font-semibold">Related Appointment ID:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $payment->appointment_id ?? 'N/A' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Amount Paid:</p>
                <p class="text-gray-800 text-lg mb-4">RM {{ number_format($payment->amount, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Payment Method:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $payment->payment_method }}</p>

                <p class="text-gray-600 text-sm font-semibold">Payment Date:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $payment->formatted_payment_date }}</p>

                <p class="text-gray-600 text-sm font-semibold">Notes:</p>
                <p class="text-gray-800 text-lg mb-4">{{ $payment->notes ?? 'No notes.' }}</p>

                <p class="text-gray-600 text-sm font-semibold">Recorded At:</p>
                <p class="text-gray-800 text-lg">{{ $payment->created_at->format('M j, Y H:i A') }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-start space-x-4">
        <a href="{{ route('payments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white
            font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Payments List
        </a>

        {{-- Only Admin and Staff can edit payments --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
            <a href="{{ route('payments.edit', $payment->id) }}" class="bg-yellow-500
                hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration
                300">
                <i class="fas fa-edit mr-2"></i> Edit Payment
            </a>
        @endif
        {{-- Only Admin can delete payments --}}
        @if(Auth::user()->isAdmin())
            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this payment record?');" class="inline-
                block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-
                    4 rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-trash-alt mr-2"></i> Delete Payment
                </button>
            </form>
        @endif
    </div>
</div>
@endsection