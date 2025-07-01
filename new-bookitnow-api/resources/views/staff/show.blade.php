@extends('layouts.app')

@section('title', $staff->name . ' - Staff Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <div>
                            <h1 class="page-title">{{ $staff->name }}</h1>
                            <p class="page-subtitle">{{ $staff->role }} - {{ $staff->department }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        @if(auth()->user()->isAdmin())
                        <button type="button" class="btn btn-gradient" onclick="editStaff({{ $staff->id }})">
                            <i class="bi bi-pencil me-2"></i>
                            Edit Staff
                        </button>
                        @endif
                        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">
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
            <!-- Staff Information -->
            <div class="col-lg-4 mb-4">
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-lines-fill me-2"></i>
                            Staff Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Full Name</label>
                            <div class="fw-bold">{{ $staff->name }}</div>
                        </div>
                        
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Role</label>
                            <div>
                                <span class="badge badge-primary">{{ $staff->role }}</span>
                            </div>
                        </div>
                        
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Department</label>
                            <div>{{ $staff->department }}</div>
                        </div>
                        
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Email</label>
                            <div>{{ $staff->email }}</div>
                        </div>
                        
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Phone</label>
                            <div>{{ $staff->phone }}</div>
                        </div>
                        
                        @if($staff->specialization)
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Specialization</label>
                            <div>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-star me-1"></i>
                                    {{ $staff->specialization }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        @if($staff->license_number)
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">License Number</label>
                            <div class="font-monospace">{{ $staff->license_number }}</div>
                        </div>
                        @endif
                        
                        @if($staff->hire_date)
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Hire Date</label>
                            <div>{{ $staff->hire_date->format('F j, Y') }}</div>
                        </div>
                        @endif
                        
                        @if($staff->salary && auth()->user()->isAdmin())
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Salary</label>
                            <div class="fw-bold text-success">${{ number_format($staff->salary, 2) }}</div>
                        </div>
                        @endif
                        
                        @if($staff->address)
                        <div class="staff-info-item mb-3">
                            <label class="text-muted small">Address</label>
                            <div>{{ $staff->address }}</div>
                        </div>
                        @endif
                        
                        <div class="staff-info-item">
                            <label class="text-muted small">Added to System</label>
                            <div>{{ $staff->created_at->format('F j, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointments History -->
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-event me-2"></i>
                            Recent Appointments
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
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $appointment->patient->name }}</div>
                                                        <small class="text-muted">{{ $appointment->patient->phone }}</small>
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
                                <p>This staff member hasn't been assigned to any appointments</p>
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
    .staff-info-item {
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .staff-info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
</style>
@endpush
@endsection
