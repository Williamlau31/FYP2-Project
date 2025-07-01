@extends('layouts.app')

@section('title', 'Patient Queue - BookItNow')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Patient Queue
                    </h1>
                    <p class="page-subtitle">Monitor and manage patient waiting queue</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#queueModal">
                            <i class="bi bi-person-plus me-2"></i>
                            Add to Queue
                        </button>
                        <button type="button" class="btn btn-gradient-success" onclick="callNext()">
                            <i class="bi bi-megaphone me-2"></i>
                            Call Next
                        </button>
                        @if(auth()->user()->isAdmin())
                        <button type="button" class="btn btn-gradient-danger" onclick="resetQueue()">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset Queue
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Queue Stats -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card warning">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon warning me-3">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['waiting'] }}</h4>
                            <small class="text-muted">Waiting</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card primary">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon primary me-3">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['called'] }}</h4>
                            <small class="text-muted">Called</small>
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
                <div class="stat-card secondary">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon secondary me-3">
                            <i class="bi bi-list-ol"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Queue Display -->
        <div class="row">
            <!-- Now Serving -->
            <div class="col-lg-4 mb-4">
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-megaphone me-2"></i>
                            Now Serving
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @php
                            $currentPatient = $queue->where('status', 'called')->first();
                        @endphp
                        
                        @if($currentPatient)
                            <div class="current-patient">
                                <div class="queue-number-display mb-3">
                                    <span class="display-1 fw-bold text-primary">{{ $currentPatient->queue_number }}</span>
                                </div>
                                <h4 class="mb-2">{{ $currentPatient->patient->name }}</h4>
                                <p class="text-muted mb-3">Please proceed to the consultation room</p>
                                <button class="btn btn-gradient-success" onclick="updateQueueStatus({{ $currentPatient->id }}, 'completed')">
                                    <i class="bi bi-check-lg me-2"></i>
                                    Mark as Completed
                                </button>
                            </div>
                        @else
                            <div class="empty-state py-4">
                                <i class="bi bi-person-x"></i>
                                <h5>No Patient Called</h5>
                                <p class="text-muted">Click "Call Next" to serve the next patient</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Queue List -->
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ol me-2"></i>
                            Queue List
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($queue->count() > 0)
                            <div class="queue-list">
                                @foreach($queue->sortBy('queue_number') as $item)
                                <div class="queue-item d-flex align-items-center p-3 border-bottom {{ $item->status === 'called' ? 'bg-light' : '' }}">
                                    <div class="queue-number me-3">
                                        <div class="bg-{{ $item->status_color }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <span class="fw-bold">{{ $item->queue_number }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="patient-info flex-grow-1">
                                        <h6 class="mb-1">{{ $item->patient->name }}</h6>
                                        <small class="text-muted">{{ $item->patient->phone }}</small>
                                    </div>
                                    
                                    <div class="status-info me-3">
                                        <span class="badge badge-{{ $item->status_color }}">
                                            <i class="bi bi-{{ $item->status_icon }} me-1"></i>
                                            {{ ucfirst($item->status) }}
                                        </span>
                                        <div class="small text-muted mt-1">
                                            Added {{ $item->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    
                                    <div class="queue-actions">
                                        <div class="btn-group" role="group">
                                            @if($item->status === 'waiting')
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="updateQueueStatus({{ $item->id }}, 'called')">
                                                    <i class="bi bi-megaphone"></i>
                                                </button>
                                            @elseif($item->status === 'called')
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="updateQueueStatus({{ $item->id }}, 'completed')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromQueue({{ $item->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-clock-history"></i>
                                <h3>No Patients in Queue</h3>
                                <p>Add patients to the queue to get started</p>
                                <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#queueModal">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Add First Patient
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add to Queue Modal -->
<div class="modal fade modal-custom" id="queueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>
                    Add Patient to Queue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="queueForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Patient <span class="text-danger">*</span></label>
                        <select class="form-select form-control-custom" id="patient_id" name="patient_id" required>
                            <option value="">Choose a patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->name }} - {{ $patient->phone }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Select the patient to add to the waiting queue</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient btn-loading">
                        <i class="bi bi-plus-lg me-2"></i>
                        Add to Queue
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .queue-number-display {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
    }
    
    .queue-number-display span {
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .queue-item {
        transition: all 0.3s ease;
    }
    
    .queue-item:hover {
        background-color: #f8f9fa !important;
    }
    
    .current-patient {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh queue every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
    
    // Form submission
    $('#queueForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("queue.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#queueModal').modal('hide');
                    showNotification(response.message, 'success');
                    location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    if (xhr.responseJSON.message) {
                        showNotification(xhr.responseJSON.message, 'warning');
                    } else {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Please fix the following errors:\n';
                        Object.keys(errors).forEach(key => {
                            errorMessage += `- ${errors[key][0]}\n`;
                        });
                        showNotification(errorMessage, 'error');
                    }
                } else {
                    showNotification('An error occurred while adding patient to queue', 'error');
                }
            }
        });
    });
    
    // Reset modal on close
    $('#queueModal').on('hidden.bs.modal', function() {
        $('#queueForm')[0].reset();
    });
});

function callNext() {
    $.ajax({
        url: '{{ route("queue.call-next") }}',
        method: 'POST',
        success: function(response) {
            if (response.success) {
                showNotification(response.message, 'success');
                location.reload();
            } else {
                showNotification(response.message, 'info');
            }
        },
        error: function(xhr) {
            showNotification('Failed to call next patient', 'error');
        }
    });
}

function updateQueueStatus(id, status) {
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    
    $.ajax({
        url: `/queue/${id}/status`,
        method: 'POST',
        data: { status: status },
        success: function(response) {
            if (response.success) {
                showNotification(`Patient marked as ${statusText.toLowerCase()}`, 'success');
                location.reload();
            }
        },
        error: function(xhr) {
            showNotification(`Failed to update patient status`, 'error');
        }
    });
}

function removeFromQueue(id) {
    confirmAction(
        'Remove from Queue',
        'Are you sure you want to remove this patient from the queue?',
        'Yes, remove!'
    ).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/queue/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to remove patient from queue', 'error');
                }
            });
        }
    });
}

function resetQueue() {
    confirmAction(
        'Reset Queue',
        'Are you sure you want to reset the entire queue? This will remove all patients from the queue.',
        'Yes, reset queue!'
    ).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("queue.reset") }}',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to reset queue', 'error');
                }
            });
        }
    });
}
</script>
@endpush
@endsection
