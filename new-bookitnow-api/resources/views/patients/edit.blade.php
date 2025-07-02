@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Patient: {{ $patient->name }}</h1>

    <div class="bg-white rounded-lg shadow-xl p-8">
        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" value="{{ old('name') ?? $patient->name }}" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror" value="{{ old('email') ?? $patient->email }}" required>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('phone') border-red-500 @enderror" value="{{ old('phone') ?? $patient->phone }}" required>
                    @error('phone')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                <textarea name="address" id="address" rows="3" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('address') border-red-500 @enderror" required>{{ old('address') ?? $patient->address }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="date_of_birth" class="block text-gray-700 text-sm font-bold mb-2">Date of Birth (Optional)</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('date_of_birth') border-red-500 @enderror" value="{{ old('date_of_birth') ?? ($patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '') }}">
                    @error('date_of_birth')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="gender" class="block text-gray-700 text-sm font-bold mb-2">Gender (Optional)</label>
                    <select name="gender" id="gender" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('gender') border-red-500 @enderror">
                        <option value="">Select Gender</option>
                        <option value="male" {{ (old('gender') ?? $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ (old('gender') ?? $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ (old('gender') ?? $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="emergency_contact" class="block text-gray-700 text-sm font-bold mb-2">Emergency Contact (Optional)</label>
                <input type="text" name="emergency_contact" id="emergency_contact" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('emergency_contact') border-red-500 @enderror" value="{{ old('emergency_contact') ?? $patient->emergency_contact }}" placeholder="Name and Phone Number">
                @error('emergency_contact')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="medical_history" class="block text-gray-700 text-sm font-bold mb-2">Medical History (Optional)</label>
                <textarea name="medical_history" id="medical_history" rows="5" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('medical_history') border-red-500 @enderror" placeholder="Any relevant medical history, allergies, etc.">{{ old('medical_history') ?? $patient->medical_history }}</textarea>
                @error('medical_history')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                    Update Patient
                </button>
                <a href="{{ route('patients.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection