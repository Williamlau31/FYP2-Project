@extends('layouts.app')

@section('title', 'Dashboard - BookItNow')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-person-circle" style="font-size: 4rem; opacity: 0.9;"></i>
                        </div>
                        <div>
                            <h1 class="page-title">Welcome back, {{ $user->name }}!</h1>
                            <p class="page-subtitle">
                                <span class="badge badge-{{ $user->isAdmin() ? 'danger' : 'primary' }} me-2">
                                    <i class="bi bi-{{ $user->isAdmin() ? 'shield-check' : 'person' }} me-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                                Manage your healthcare operations efficiently
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="text-white-50">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ now()->format('l, F j, Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h4 mb-3">
                    <i class="bi bi-graph-up text-primary me-2"></i>
                    Quick Overview
                </h2>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card primary fade-in">
                    <div class="stat-icon primary">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h3 class="h2 mb-1 text-primary">{{ $stats['today_appointments'] }}</h3>
                    <p class="text-muted mb-0">Today's Appointments</p>
                    <small class="text-success">
                        <i class="bi bi-arrow-up me-1"></i>
                        +12% from yesterday
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card secondary fade-in" style="animation-delay: 0.1s;">
                    <div class="stat-icon secondary">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="h2 mb-1 text-info">{{ $stats['total_patients'] }}</h3>
                    <p class="text-muted mb-0">Total Patients</p>
                    <small class="text-success">
                        <i class="bi bi-arrow-up me-1"></i>
                        +5 new this week
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card warning fade-in" style="animation-delay: 0.2s;">
                    <div class="stat-icon warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3 class="h2 mb-1 text-warning">{{ $stats['queue_count'] }}</h3>
                    <p class="text-muted mb-0">In Queue</p>
                    <small class="text-info">
                        <i class="bi bi-clock me-1"></i>
                        Avg wait: 15 min
                    </small>
                </div>
            </div>
            
            @if($user->isAdmin())
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card success fade-in" style="animation-delay: 0.3s;">
                    <div class="stat-icon success">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h3 class="h2 mb-1 text-success">{{ $stats['total_staff'] }}</h3>
                    <p class="text-muted mb-0">Staff Members</p>
                    <small class="text-success">
                        <i class="bi bi-check-circle me-1"></i>
                        All active
                    </small>
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <!-- Main Navigation -->
            <div class="col-lg-8 mb-4">
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-grid-3x3-gap me-2"></i>
                            Main Modules
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('appointments.index') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm module-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon primary me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-calendar-event"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">Appointments</h6>
                                                    <p class="card-text text-muted small mb-2">Schedule and manage patient appointments</p>
                                                    <div class="d-flex gap-1">
                                                        <span class="badge bg-light text-dark">Schedule</span>
                                                        <span class="badge bg-light text-dark">Manage</span>
                                                        <span class="badge bg-light text-dark">Track</span>
                                                    </div>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-md-6">
                                <a href="{{ route('patients.index') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm module-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon secondary me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">Patients</h6>
                                                    <p class="card-text text-muted small mb-2">Manage patient records and information</p>
                                                    <div class="d-flex gap-1">
                                                        <span class="badge bg-light text-dark">Records</span>
                                                        <span class="badge bg-light text-dark">History</span>
                                                        <span class="badge bg-light text-dark">Contact</span>
                                                    </div>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                            @if($user->isAdmin())
                            <div class="col-md-6">
                                <a href="{{ route('staff.index') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm module-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon success me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">Staff Management</h6>
                                                    <p class="card-text text-muted small mb-2">Manage healthcare staff and roles</p>
                                                    <div class="d-flex gap-1">
                                                        <span class="badge bg-light text-dark">Doctors</span>
                                                        <span class="badge bg-light text-dark">Nurses</span>
                                                        <span class="badge bg-light text-dark">Admin</span>
                                                    </div>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            
                            <div class="col-md-6">
                                <a href="{{ route('queue.index') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 shadow-sm module-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="stat-icon warning me-3" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-clock-history"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">Patient Queue</h6>
                                                    <p class="card-text text-muted small mb-2">Monitor and manage patient waiting queue</p>
                                                    <div class="d-flex gap-1">
                                                        <span class="badge bg-light text-dark">Waiting</span>
                                                        <span class="badge bg-light text-dark">Called</span>
                                                        <span class="badge bg-light text-dark">Status</span>
                                                    </div>
                                                </div>
                                                <i class="bi bi-chevron-right text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Today's Appointments -->
                <div class="card card-custom mb-4">
                    <div class="card-header gradient-bg text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-calendar-day me-2"></i>
                            Today's Appointments
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if($today_appointments->count() > 0)
                            @foreach($today_appointments as $appointment)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="me-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $appointment->patient->name }}</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $appointment->formatted_time }}
                                    </small>
                                </div>
                                <span class="badge badge-{{ $appointment->status_color }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            @endforeach
                            <div class="p-3">
                                <a href="{{ route('appointments.index') }}" class="btn btn-gradient btn-sm w-100">
                                    View All Appointments
                                </a>
                            </div>
                        @else
                            <div class="empty-state py-4">
                                <i class="bi bi-calendar-x"></i>
                                <h6>No appointments today</h6>
                                <p class="small">Schedule your first appointment</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card card-custom">
                    <div class="card-header gradient-bg text-white">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-activity me-2"></i>
                            Recent Activity
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if($recent_activities->count() > 0)
                            @foreach($recent_activities as $activity)
                            <div class="d-flex align-items-start p-3 border-bottom">
                                <div class="me-3">
                                    <div class="bg-{{ $activity['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-{{ $activity['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 small">{{ $activity['title'] }}</h6>
                                    <p class="mb-1 small text-muted">{{ $activity['description'] }}</p>
                                    <small class="text-muted">{{ $activity['time'] }}</small>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state py-4">
                                <i class="bi bi-clock-history"></i>
                                <h6>No recent activity</h6>
                                <p class="small">Activity will appear here</p>
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
    .module-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .module-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .module-card .stat-icon {
        font-size: 1.2rem;
    }
</style>
@endpush
@endsection
