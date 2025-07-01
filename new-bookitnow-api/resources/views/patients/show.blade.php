@extends('layouts.app')

@section('title', $patient->name . ' - Patient Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-person fs-3"></i>
                        </div>
                        <div>
                            <h1 class="page-title">{{ $patient->name }}</h1>
                            <p class="page-subtitle">Patient Details & Medical History</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gradient" onclick="editPatient({{ $patient->id }})">
                            <i class="bi bi-pencil me-2"></i>
                            Edit Patient
                        </button>
                        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Patient Information -->
            <div class="col-lg-4 mb-4">
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-lines-fill me-2"></i>
                            Personal Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="patient-info-item mb-3">
                            <label class="text-muted small">Full Name</label>
                            <div class="fw-bold">{{ $patient->name }}</div>
                        </div>
                        
                        <div class="patient-info-item mb-3">
                            <label class="text-muted small">Email</label>
                            <div>{{ $patient->email }}</div>
                        </div>
                        
                        <div class="patient-info-item mb-3">
                            <label class="text-muted small">Phone</label>
                            <div>{{ $patient->phone }}</div>
                        </div>
                        
                        @if($patient->date_of_birth)
                        <div class="patient-info-item mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <div>{{ $patient->date_of_birth->format('F j, Y') }} ({{ $patient->age }} years old)</div>
                        </div>
                        @endif
                        
                        @if($patient->gender)
                        <div class="patient-info-item mb-3">
                            <label class="text-muted small">Gender</label>
                            <div>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-person me-1"></i>
                                    {{ ucfirst($patient->gender) }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="patient-info-item mb-3">
                            <label class="text-muted small">Address</label>
                            <div>{{ $patient->address }}</div>
                        </div>
                        
                        @if($patient->emergency_contact)
                        <div class="patient-info-item mb-3">
                            <label class="text-muted small">Emergency Contact</label>
                            <div class="text-danger">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                {{ $patient->emergency_contact }}
                            </div>
                        </div>
                        @endif
                        
                        <div class="patient-info-item">
                            <label class="text-muted small">Registered</label>
                            <div>{{ $patient->created_at->format('F j, Y') }}</div>
                        </div>
                    </div>
                </div>

                @if($patient->medical_history)
                <div class="card card-custom mt-4">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-heart-pulse me-2"></i>
                            Medical History
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="medical-history">
                            {{ $patient->medical_history }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Appointments History -->
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-event me-2"></i>
                            Appointment History
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($appointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-custom mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date & Time</th>
                                            <th>Staff</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Notes</th>
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
                                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
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
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $appointment->notes }}">
                                                        {{ $appointment->notes }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <h5>No Appointments Yet</h5>
                                <p>This patient hasn't had any appointments</p>
                                <a href="{{ route('appointments.create') }}" class="btn btn-gradient">
                                    <i class="bi bi-calendar-plus me-2"></i>
                                    Schedule Appointment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .patient-info-item {
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .patient-info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .medical-history {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--bs-primary);
        line-height: 1.6;
    }
</style>
@endpush
@endsection
