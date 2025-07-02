@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-160px)]">

    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8">

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Register for BookItNow</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border
                    rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2
                    focus:ring-blue-500 focus:border-transparent transition duration-200 @error('name') border
                    red-500 @enderror" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email
                    Address</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border
                    rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2
                    focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border
                    red-500 @enderror" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" class="shadow appearance-
                    none border rounded-lg w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none
                    focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200
                    @error('password') border-red-500 @enderror" required autocomplete="new-password">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-
                    2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                    duration-200" required autocomplete="new-password">
            </div>

            {{-- Removed the role selection for public registration.
                 New users will default to 'patient' in AuthController.
                 If staff/admin registration is needed, it should be an admin-only form. --}}
            <input type="hidden" name="role" value="patient"> {{-- Explicitly set role for self-registration --}}


            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3
                    px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 w-full">
                    Register
                </button>
            </div>
        </form>

        <p class="text-center text-gray-600 text-sm mt-8">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-blue-600
                hover:text-blue-800">Login here</a>.
        </p>
    </div>
</div>
@endsection