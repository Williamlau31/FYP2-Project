@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-160px)]">
    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Login to BookItNow</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror" required autocomplete="current-password">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 flex items-center justify-between">
                <label class="flex items-center text-gray-700 text-sm">
                    <input type="checkbox" name="remember" id="remember" class="mr-2 rounded text-blue-600 focus:ring-blue-500" {{ old('remember') ? 'checked' : '' }}>
                    Remember Me
                </label>
                {{-- <a href="#" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                    Forgot Password? (Coming Soon)
                </a> --}}
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 w-full">
                    Login
                </button>
            </div>
        </form>

        <p class="text-center text-gray-600 text-sm mt-8">
            Don't have an account? <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-800">Register here</a>.
        </p>
    </div>
</div>
@endsection
