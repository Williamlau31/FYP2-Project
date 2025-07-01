@extends('layouts.app')

@section('title', 'Appointments - BookItNow')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">
                        <i class="bi bi-calendar-event me-2"></i>
                        Appointments
                    </h1>
                    <p class="page-subtitle">Manage and schedule patient appointments</p>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                        <i class="bi bi-plus-lg me-2"></i>
                        New Appointment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card primary">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon primary me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['scheduled'] }}</h4>
                            <small class="text-muted">Scheduled</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card success">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon success me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['completed'] }}</h4>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card danger">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon danger me-3">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['cancelled'] }}</h4>
                            <small class="text-muted">Cancelled</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card warning">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon warning me-3">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card card-custom mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('appointments.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" class="form-control form-control-custom" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" class="form-control form-control-custom" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select form-control-custom" name="status">
                            <option value="">All Status</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-search me-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="mb-4">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('appointments.index', ['filter' => 'today']) }}" 
                   class="btn btn-outline-primary btn-sm {{ request('filter') == 'today' ? 'active' : '' }}">
                    <i class="bi bi-calendar-day me-1"></i>
                    Today
                </a>
                <a href="{{ route('appointments.index', ['filter' => 'week']) }}" 
                   class="btn btn-outline-primary btn-sm {{ request('filter') == 'week' ? 'active' : '' }}">
                    <i class="bi bi-calendar-week me-1"></i>
                    This Week
                </a>
                <a href="{{ route('appointments.index', ['status' => 'scheduled']) }}" 
                   class="btn btn-outline-success btn-sm {{ request('status') == 'scheduled' ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-1"></i>
                    Scheduled
                </a>
                <a href="{{ route('appointments.index', ['status' => 'completed']) }}" 
                   class="btn btn-outline-info btn-sm {{ request('status') == 'completed' ? 'active' : '' }}">
                    <i class="bi bi-check-circle me-1"></i>
                    Completed
                </a>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="card card-custom">
            <div class="card-header gradient-bg text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Appointments List
                </h5>
            </div>
            <div class="card-body p-0">
                @if($appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Patient</th>
                                    <th>Staff</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $appointment->formatted_date }}</div>
                                        <small class="text-muted">{{ $appointment->formatted_time }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $appointment->patient->name }}</div>
                                                <small class="text-muted">{{ $appointment->patient->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                <i class="bi bi-person-badge"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $appointment->staff->name }}</div>
                                                <small class="text-muted">{{ $appointment->staff->role }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $appointment->appointment_type ?? 'General' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $appointment->status_color }}">
                                            <i class="bi bi-{{ $appointment->status_icon }} me-1"></i>
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($appointment->notes)
                                            <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $appointment->notes }}">
                                                {{ $appointment->notes }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editAppointment({{ $appointment->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="viewAppointment({{ $appointment->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAppointment({{ $appointment->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="card-footer bg-light">
                        {{ $appointments->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <h3>No Appointments Found</h3>
                        <p>Start by scheduling your first appointment</p>
                        <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                            <i class="bi bi-plus-lg me-2"></i>
                            Schedule Appointment
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade modal-custom" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-plus me-2"></i>
                    <span id="modalTitle">New Appointment</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="appointmentForm">
                <div class="modal-body">
                    <input type="hidden" id="appointmentId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient <span class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="patient_id" name="patient_id" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }} - {{ $patient->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Staff <span class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="staff_id" name="staff_id" required>
                                <option value="">Select Staff</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }} - {{ $member->role }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-custom" id="date" name="date" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control form-control-custom" id="time" name="time" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="status" name="status" required>
                                <option value="scheduled">Scheduled</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Appointment Type</label>
                            <input type="text" class="form-control form-control-custom" id="appointment_type" name="appointment_type" placeholder="e.g., Consultation, Follow-up">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control form-control-custom" id="notes" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient btn-loading">
                        <i class="bi bi-check-lg me-2"></i>
                        <span id="submitText">Save Appointment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Set minimum date to today
    $('#date').attr('min', new Date().toISOString().split('T')[0]);
    
    // Form submission
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const appointmentId = $('#appointmentId').val();
        const url = appointmentId ? `/appointments/${appointmentId}` : '{{ route("appointments.store") }}';
        const method = appointmentId ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#appointmentModal').modal('hide');
                    showNotification(response.message, 'success');
                    location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Please fix the following errors:\n';
                    Object.keys(errors).forEach(key => {
                        errorMessage += `- ${errors[key][0]}\n`;
                    });
                    showNotification(errorMessage, 'error');
                } else {
                    showNotification(xhr.responseJSON.message || 'An error occurred', 'error');
                }
            }
        });
    });
    
    // Reset modal on close
    $('#appointmentModal').on('hidden.bs.modal', function() {
        $('#appointmentForm')[0].reset();
        $('#appointmentId').val('');
        $('#modalTitle').text('New Appointment');
        $('#submitText').text('Save Appointment');
    });
});

function editAppointment(id) {
    $.get(`/appointments/${id}/edit`, function(data) {
        $('#appointmentId').val(data.id);
        $('#patient_id').val(data.patient_id);
        $('#staff_id').val(data.staff_id);
        $('#date').val(data.date);
        $('#time').val(data.time);
        $('#status').val(data.status);
        $('#appointment_type').val(data.appointment_type);
        $('#notes').val(data.notes);
        
        $('#modalTitle').text('Edit Appointment');
        $('#submitText').text('Update Appointment');
        $('#appointmentModal').modal('show');
    });
}

function viewAppointment(id) {
    window.location.href = `/appointments/${id}`;
}

function deleteAppointment(id) {
    confirmAction(
        'Delete Appointment',
        'Are you sure you want to delete this appointment? This action cannot be undone.',
        'Yes, delete it!'
    ).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/appointments/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to delete appointment', 'error');
                }
            });
        }
    });
}
</script>
@endpush
@endsection
