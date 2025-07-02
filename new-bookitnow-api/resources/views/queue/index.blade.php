@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Hidden meta tag to pass PHP variables to JavaScript safely --}}
    <meta id="app-config"
          data-user-id="{{ Auth::check() ? Auth::id() : 'null' }}"
          data-user-role="{{ Auth::check() ? Auth::user()->role : '' }}">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Queue Management</h1>
        @auth
            @if(Auth::user()->isAdmin())
                <div class="flex space-x-3">
                    <button id="callNextBtn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                        Call Next Patient
                    </button>
                    <button id="resetQueueBtn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                        Reset Queue
                    </button>
                </div>
            @else {{-- Patient User --}}
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg shadow-md" role="alert">
                    <p class="font-bold">Your Current Queue Number:</p>
                    <p id="patientQueueNumber" class="text-2xl font-extrabold mt-2">--</p>
                    <p id="patientQueueStatus" class="text-sm mt-1">Status: --</p>
                </div>
            @endif
        @endauth
    </div>

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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Total Queue Items</h3>
            <p class="text-4xl font-extrabold text-blue-600">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Waiting</h3>
            <p class="text-4xl font-extrabold text-yellow-600">{{ $stats['waiting'] }}</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6 text-center">
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Called</h3>
            <p class="text-4xl font-extrabold text-purple-600">{{ $stats['called'] }}</p>
        </div>
    </div>

    @auth
        @if(Auth::user()->isAdmin())
            <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Add Patient to Queue</h2>
                <form id="addToQueueForm" class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <div class="flex-grow">
                        <label for="patient_id" class="sr-only">Select Patient:</label>
                        <select name="patient_id" id="patient_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select a Patient</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                            @endforeach
                        </select>
                        <p id="patient_id_error" class="text-red-500 text-xs italic mt-2 hidden"></p>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                        Add to Queue
                    </button>
                </form>
            </div>
        @endif
    @endauth

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Current Queue</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal" id="queueTable">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tl-lg">
                                Queue No.
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Patient Name
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            @auth
                                @if(Auth::user()->isAdmin())
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tr-lg">
                                        Actions
                                    </th>
                                @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($queue as $item)
                            <tr id="queue-item-{{ $item->id }}" class="hover:bg-gray-50">
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold text-lg">{{ $item->queue_number }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $item->patient->name }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                        <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full bg-{{ $item->status_color }}-200"></span>
                                        <span class="relative text-{{ $item->status_color }}-900">{{ ucfirst($item->status) }}</span>
                                    </span>
                                </td>
                                @auth
                                    @if(Auth::user()->isAdmin())
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <div class="flex items-center space-x-3">
                                                <select class="status-dropdown shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" data-id="{{ $item->id }}">
                                                    <option value="waiting" {{ $item->status == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                                    <option value="called" {{ $item->status == 'called' ? 'selected' : '' }}>Called</option>
                                                    <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                                <button type="button" class="delete-queue-item text-red-600 hover:text-red-900 font-medium" data-id="{{ $item->id }}">Delete</button>
                                            </div>
                                        </td>
                                    @endif
                                @endauth
                            </tr>
                        @empty
                            <tr id="no-queue-items">
                                <td colspan="{{ Auth::user()->isAdmin() ? '4' : '3' }}" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-600">
                                    No patients currently in the queue.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const queueTableBody = document.querySelector('#queueTable tbody');
        const patientQueueNumberDisplay = document.getElementById('patientQueueNumber');
        const patientQueueStatusDisplay = document.getElementById('patientQueueStatus');

        // Read userId and userRole from the meta tag's data attributes
        const appConfigElement = document.getElementById('app-config');
        const userId = appConfigElement.dataset.userId === 'null' ? null : parseInt(appConfigElement.dataset.userId);
        const userRole = appConfigElement.dataset.userRole;

        function fetchQueueData() {
            fetch('/queue?ajax=true') // Add a query parameter to indicate AJAX request
                .then(response => response.json())
                .then(data => {
                    updateQueueTable(data.queue);
                    updateStats(data.stats);
                    if (userRole === 'user') {
                        updatePatientQueueInfo(data.queue);
                    }
                })
                .catch(error => console.error('Error fetching queue data:', error));
        }

        function updateQueueTable(queueItems) {
            queueTableBody.innerHTML = ''; // Clear existing rows
            if (queueItems.length === 0) {
                const colspan = userRole === 'admin' ? '4' : '3';
                queueTableBody.innerHTML = `
                    <tr id="no-queue-items">
                        <td colspan="${colspan}" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-600">
                            No patients currently in the queue.
                        </td>
                    </tr>
                `;
                return;
            }

            queueItems.forEach(item => {
                const row = document.createElement('tr');
                row.id = `queue-item-${item.id}`;
                row.classList.add('hover:bg-gray-50');

                let actionsHtml = '';
                if (userRole === 'admin') {
                    actionsHtml = `
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center space-x-3">
                                <select class="status-dropdown shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" data-id="${item.id}">
                                    <option value="waiting" ${item.status === 'waiting' ? 'selected' : ''}>Waiting</option>
                                    <option value="called" ${item.status === 'called' ? 'selected' : ''}>Called</option>
                                    <option value="completed" ${item.status === 'completed' ? 'selected' : ''}>Completed</option>
                                </select>
                                <button type="button" class="delete-queue-item text-red-600 hover:text-red-900 font-medium" data-id="${item.id}">Delete</button>
                            </div>
                        </td>
                    `;
                }

                row.innerHTML = `
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-bold text-lg">${item.queue_number}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">${item.patient.name}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                            <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full bg-${item.status_color}-200"></span>
                            <span class="relative text-${item.status_color}-900">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span>
                        </span>
                    </td>
                    ${actionsHtml}
                `;
                queueTableBody.appendChild(row);
            });

            // Re-attach event listeners for status dropdowns and delete buttons after updating the table
            attachEventListeners();
        }

        function updateStats(stats) {
            document.querySelector('.text-4xl.font-extrabold.text-blue-600').textContent = stats.total;
            document.querySelector('.text-4xl.font-extrabold.text-yellow-600').textContent = stats.waiting;
            document.querySelector('.text-4xl.font-extrabold.text-purple-600').textContent = stats.called;
        }

        function updatePatientQueueInfo(queueItems) {
            // Find the queue item for the current logged-in patient
            const patientQueueItem = queueItems.find(item => item.patient_id === userId);
            if (patientQueueItem) {
                patientQueueNumberDisplay.textContent = patientQueueItem.queue_number;
                patientQueueStatusDisplay.textContent = `Status: ${patientQueueItem.status.charAt(0).toUpperCase() + patientQueueItem.status.slice(1)}`;
            } else {
                patientQueueNumberDisplay.textContent = 'N/A';
                patientQueueStatusDisplay.textContent = 'You are not in the queue.';
            }
        }

        function attachEventListeners() {
            // Status dropdowns
            document.querySelectorAll('.status-dropdown').forEach(dropdown => {
                dropdown.onchange = function() {
                    const queueItemId = this.dataset.id;
                    const newStatus = this.value;
                    updateQueueItemStatus(queueItemId, newStatus);
                };
            });

            // Delete buttons
            document.querySelectorAll('.delete-queue-item').forEach(button => {
                button.onclick = function() {
                    if (confirm('Are you sure you want to remove this patient from the queue?')) {
                        const queueItemId = this.dataset.id;
                        deleteQueueItem(queueItemId);
                    }
                };
            });
        }

        // Initial fetch when the page loads
        fetchQueueData();

        // Refresh queue data every 5 seconds (adjust as needed)
        setInterval(fetchQueueData, 5000);

        // Handle Add to Queue form submission (Admin only)
        const addToQueueForm = document.getElementById('addToQueueForm');
        if (addToQueueForm) {
            addToQueueForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const patientId = document.getElementById('patient_id').value;
                const patientIdError = document.getElementById('patient_id_error');

                if (!patientId) {
                    patientIdError.textContent = 'Please select a patient.';
                    patientIdError.classList.remove('hidden');
                    return;
                } else {
                    patientIdError.classList.add('hidden');
                }

                fetch("{{ route('queue.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ patient_id: patientId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message); // Replace with a custom modal later
                        document.getElementById('patient_id').value = ''; // Clear selection
                        fetchQueueData(); // Refresh queue
                    } else {
                        if (data.errors) {
                            patientIdError.textContent = data.errors.patient_id ? data.errors.patient_id[0] : 'An error occurred.';
                            patientIdError.classList.remove('hidden');
                        } else if (data.message) {
                            alert(data.message); // Replace with a custom modal later
                        }
                    }
                })
                .catch(error => {
                    console.error('Error adding to queue:', error);
                    alert('An error occurred while adding to queue.'); // Replace with a custom modal later
                });
            });
        }

        // Handle Call Next Patient button (Admin only)
        const callNextBtn = document.getElementById('callNextBtn');
        if (callNextBtn) {
            callNextBtn.addEventListener('click', function() {
                fetch("{{ route('queue.call-next') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Replace with a custom modal later
                    fetchQueueData(); // Refresh queue
                })
                .catch(error => {
                    console.error('Error calling next patient:', error);
                    alert('An error occurred while calling the next patient.'); // Replace with a custom modal later
                });
            });
        }

        // Handle Reset Queue button (Admin only)
        const resetQueueBtn = document.getElementById('resetQueueBtn');
        if (resetQueueBtn) {
            resetQueueBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the entire queue? This action cannot be undone.')) {
                    fetch("{{ route('queue.reset') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message); // Replace with a custom modal later
                        fetchQueueData(); // Refresh queue
                    })
                    .catch(error => {
                        console.error('Error resetting queue:', error);
                        alert('An error occurred while resetting the queue.'); // Replace with a custom modal later
                    });
                }
            });
        }

        function updateQueueItemStatus(id, status) {
            fetch(`/queue/${id}/status`, {
                method: 'POST', // Using POST as per your route definition
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status, _method: 'PATCH' }) // Laravel expects PATCH for updateStatus
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // alert(data.message); // Replace with a custom modal later
                    fetchQueueData(); // Refresh queue
                } else {
                    alert('Failed to update status: ' + data.message); // Replace with a custom modal later
                }
            })
            .catch(error => {
                console.error('Error updating queue status:', error);
                alert('An error occurred while updating queue status.'); // Replace with a custom modal later
            });
        }

        function deleteQueueItem(id) {
            fetch(`{{ url('queue') }}/${id}`, {
                method: 'POST', // Using POST as per your route definition
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ _method: 'DELETE' }) // Laravel expects DELETE for destroy
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // alert(data.message); // Replace with a custom modal later
                    fetchQueueData(); // Refresh queue
                } else {
                    alert('Failed to delete queue item: ' + data.message); // Replace with a custom modal later
                }
            })
            .catch(error => {
                console.error('Error deleting queue item:', error);
                alert('An error occurred while deleting queue item.'); // Replace with a custom modal later
            });
        }
    });
</script>
@endsection