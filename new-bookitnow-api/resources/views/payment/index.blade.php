@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Make a Payment</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ url('/process-payment') }}" method="POST" id="paymentForm">
            @csrf

            <div class="mb-6">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount to Pay (RM):</label>
                <input type="number" step="0.01" name="amount" id="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror" value="{{ old('amount') }}" placeholder="e.g., 50.00" required>
                @error('amount')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description (Optional):</label>
                <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" placeholder="e.g., Payment for appointment #123 and medicine">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Payment Method:</label>
                <div class="mt-2 space-y-3">
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 rounded-full" name="payment_method" value="credit_card" checked>
                        <span class="ml-2 text-gray-700">Credit/Debit Card</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 rounded-full" name="payment_method" value="tng_ewallet">
                        <span class="ml-2 text-gray-700">Touch 'n Go eWallet</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 rounded-full" name="payment_method" value="cash">
                        <span class="ml-2 text-gray-700">Cash (Pay at Counter)</span>
                    </label>
                </div>
                @error('payment_method')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Conditional fields for Credit/Debit Card --}}
            <div id="creditCardFields" class="space-y-4 mb-6">
                <h3 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Credit/Debit Card Details</h3>
                <div>
                    <label for="card_number" class="block text-gray-700 text-sm font-bold mb-2">Card Number:</label>
                    <input type="text" name="card_number" id="card_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="XXXX XXXX XXXX XXXX">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="expiry_date" class="block text-gray-700 text-sm font-bold mb-2">Expiry Date (MM/YY):</label>
                        <input type="text" name="expiry_date" id="expiry_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="MM/YY">
                    </div>
                    <div>
                        <label for="cvc" class="block text-gray-700 text-sm font-bold mb-2">CVC:</label>
                        <input type="text" name="cvc" id="cvc" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="XXX">
                    </div>
                </div>
                <div>
                    <label for="cardholder_name" class="block text-gray-700 text-sm font-bold mb-2">Cardholder Name:</label>
                    <input type="text" name="cardholder_name" id="cardholder_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Full Name">
                </div>
            </div>

            <div class="flex items-center justify-center">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out text-lg">
                    Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
        const creditCardFields = document.getElementById('creditCardFields');

        function toggleCreditCardFields() {
            if (document.querySelector('input[name="payment_method"]:checked').value === 'credit_card') {
                creditCardFields.style.display = 'block';
                creditCardFields.querySelectorAll('input').forEach(input => input.setAttribute('required', 'required'));
            } else {
                creditCardFields.style.display = 'none';
                creditCardFields.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
            }
        }

        paymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', toggleCreditCardFields);
        });

        // Initial call to set correct visibility on page load
        toggleCreditCardFields();

        // Basic form submission handling for demonstration
        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const amount = document.getElementById('amount').value;
            const description = document.getElementById('description').value;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            let message = `Processing payment of RM ${amount} via ${paymentMethod}.`;

            if (paymentMethod === 'credit_card') {
                const cardNumber = document.getElementById('card_number').value;
                const expiryDate = document.getElementById('expiry_date').value;
                const cvc = document.getElementById('cvc').value;
                const cardholderName = document.getElementById('cardholder_name').value;
                message += `\nCard: ${cardNumber}, Exp: ${expiryDate}, CVC: ${cvc}, Name: ${cardholderName}`;
            } else if (paymentMethod === 'tng_ewallet') {
                message += `\nYou will be redirected to Touch 'n Go eWallet for payment.`;
            } else if (paymentMethod === 'cash') {
                message += `\nPlease proceed to the counter to complete your payment.`;
            }

            // In a real application, you would send this data to your Laravel backend
            // using fetch or Axios for actual payment processing.
            // For now, we'll just show an alert.
            alert(message);

            // You might want to redirect or show a success message after a real payment
            // window.location.href = '/payment-success';
        });
    });
</script>
@endsection