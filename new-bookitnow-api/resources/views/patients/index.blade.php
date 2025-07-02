@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Patients</h1>

    @if(Auth::user()->isAdmin())
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('patients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 flex items-center">
                <i class="fas fa-user-plus mr-2"></i> Add New Patient
            </a>
            <form action="{{ route('patients.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patients..." class="form-input rounded-lg border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-64">
                <button type="submit" class="ml-2 bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    @endif

    @if ($patients->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-600 text-lg">No patients found.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left rounded-tl-lg">Name</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Phone</th>
                            <th class="py-3 px-6 text-left">Gender</th>
                            <th class="py-3 px-6 text-center rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @foreach ($patients as $patient)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $patient->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $patient->email }}</td>
                                <td class="py-3 px-6 text-left">{{ $patient->phone }}</td>
                                <td class="py-3 px-6 text-left">{{ ucfirst($patient->gender) }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="{{ route('patients.show', $patient->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('patients.edit', $patient->id) }}" class="w-4 mr-2 transform hover:text-yellow-500 hover:scale-110" title="Edit Patient">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" title="Delete Patient">
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
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection
