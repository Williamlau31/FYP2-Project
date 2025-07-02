@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <h1 class="text-4xl font-bold text-gray-800 mb-8">Payments</h1>

    @auth
        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white
                    font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Record New Payment
                </a>

                {{-- Optional: Add filters for payments if needed (e.g., by date, patient, method) --}}
                <form action="{{ route('payments.index') }}" method="GET" class="flex items-center space-x-4">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-
                        input rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-
                        200 focus:ring-opacity-50">
                    <span class="text-gray-600">to</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input
                        rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200
                        focus:ring-opacity-50">
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2
                        px-4 rounded-lg shadow-md transition duration-300">
                        Filter
                    </button>
                    <a href="{{ route('payments.index') }}" class="bg-gray-400 hover:bg-gray-500 text-
                        white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                        Reset
                    </a>
                </form>
            </div>
        @endif
    @endauth

    @if ($payments->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-600 text-lg">No payments recorded yet.</p>
            @auth
                @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                    <div class="mt-4">
                        <a href="{{ route('payments.create') }}" class="inline-block bg-blue-600 hover:bg-
                            blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                            <i class="fas fa-plus-circle mr-2"></i> Record New Payment
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left rounded-tl-lg">Patient</th>
                            <th class="py-3 px-6 text-left">Appointment ID</th>
                            <th class="py-3 px-6 text-left">Amount</th>
                            <th class="py-3 px-6 text-left">Method</th>
                            <th class="py-3 px-6 text-left">Date</th>
                            <th class="py-3 px-6 text-center rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @foreach ($payments as $payment)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $payment->patient->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $payment->appointment_id ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">RM {{ number_format($payment->amount, 2) }}</td>
                                <td class="py-3 px-6 text-left">{{ $payment->payment_method }}</td>
                                <td class="py-3 px-6 text-left">{{ $payment->formatted_payment_date }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="{{ route('payments.show', $payment->id) }}" class="w-4 mr-
                                            2 transform hover:text-blue-500 hover:scale-110" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Only Admin and Staff can edit payments --}}
                                        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                                            <a href="{{ route('payments.edit', $payment->id) }}" class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110" title="Edit Payment">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        {{-- Only Admin can delete payments --}}
                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('payments.destroy', $payment->id) }}"
                                                method="POST" onsubmit="return confirm('Are you sure you want to delete this payment record?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500
                                                    hover:scale-110" title="Delete Payment">
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
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @endif
</div>

@endsection