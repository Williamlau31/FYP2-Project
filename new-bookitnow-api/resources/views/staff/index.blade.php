@extends('layouts.app')

@section('title', 'Staff - BookItNow')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">
                        <i class="bi bi-person-badge me-2"></i>
                        Staff Management
                    </h1>
                    <p class="page-subtitle">Manage healthcare staff and roles</p>
                </div>
                <div class="col-md-6 text-end">
                    @if(auth()->user()->isAdmin())
                    <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#staffModal">
                        <i class="bi bi-person-plus me-2"></i>
                        Add Staff Member
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Department Filter -->
        <div class="card card-custom mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control form-control-custom" id="searchInput" placeholder="Search staff by name, role, or department...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-control-custom" id="roleFilter">
                            <option value="">All Roles</option>
                            <option value="doctor">Doctor</option>
                            <option value="nurse">Nurse</option>
                            <option value="receptionist">Receptionist</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-control-custom" id="departmentFilter">
                            <option value="">All Departments</option>
                            <option value="general">General</option>
                            <option value="cardiology">Cardiology</option>
                            <option value="orthopedics">Orthopedics</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Grid -->
        <div class="row" id="staffGrid">
            @if($staff->count() > 0)
                @foreach($staff as $member)
                <div class="col-lg-4 col-md-6 mb-4 staff-card" 
                     data-name="{{ strtolower($member->name) }}" 
                     data-role="{{ strtolower($member->role) }}" 
                     data-department="{{ strtolower($member->department) }}">
                    <div class="card card-custom h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-badge fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">{{ $member->name }}</h5>
                                        <span class="badge badge-primary">{{ $member->role }}</span>
                                    </div>
                                </div>
                                @if(auth()->user()->isAdmin())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewStaff({{ $member->id }})">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="editStaff({{ $member->id }})">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteStaff({{ $member->id }})">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                            
                            <div class="staff-info">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-building text-muted me-2"></i>
                                    <span class="small">{{ $member->department }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    <span class="small">{{ $member->email }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-telephone text-muted me-2"></i>
                                    <span class="small">{{ $member->phone }}</span>
                                </div>
                                
                                @if($member->specialization)
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-star me-1"></i>
                                        {{ $member->specialization }}
                                    </span>
                                </div>
                                @endif
                                
                                @if($member->license_number)
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-card-text me-1"></i>
                                        License: {{ $member->license_number }}
                                    </small>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus me-1"></i>
                                        @if($member->hire_date)
                                            Hired {{ $member->hire_date->format('M Y') }}
                                        @else
                                            Added {{ $member->created_at->diffForHumans() }}
                                        @endif
                                    </small>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="viewStaff({{ $member->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @if(auth()->user()->isAdmin())
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editStaff({{ $member->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-person-badge"></i>
                        <h3>No Staff Members Found</h3>
                        <p>Start by adding your first staff member</p>
                        @if(auth()->user()->isAdmin())
                        <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#staffModal">
                            <i class="bi bi-person-plus me-2"></i>
                            Add Staff Member
                        </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($staff->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $staff->links() }}
        </div>
        @endif
    </div>
</div>

@if(auth()->user()->isAdmin())
<!-- Staff Modal -->
<div class="modal fade modal-custom" id="staffModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>
                    <span id="modalTitle">Add New Staff Member</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="staffForm">
                <div class="modal-body">
                    <input type="hidden" id="staffId" name="id">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom" id="name" name="name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom" id="email" name="email" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control form-control-custom" id="phone" name="phone" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Nurse">Nurse</option>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select form-control-custom" id="department" name="department" required>
                                <option value="">Select Department</option>
                                <option value="General">General</option>
                                <option value="Cardiology">Cardiology</option>
                                <option value="Orthopedics">Orthopedics</option>
                                <option value="Emergency">Emergency</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Specialization</label>
                            <input type="text" class="form-control form-control-custom" id="specialization" name="specialization" placeholder="e.g., Cardiologist, Pediatric Nurse">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">License Number</label>
                            <input type="text" class="form-control form-control-custom" id="license_number" name="license_number">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Hire Date</label>
                            <input type="date" class="form-control form-control-custom" id="hire_date" name="hire_date">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Salary</label>
                            <input type="number" class="form-control form-control-custom" id="salary" name="salary" step="0.01" min="0">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control form-control-custom" id="address" name="address" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient btn-loading">
                        <i class="bi bi-check-lg me-2"></i>
                        <span id="submitText">Save Staff Member</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
$(document).ready(function() {
    // Search and filter functionality
    $('#searchInput, #roleFilter, #departmentFilter').on('keyup change', function() {
        filterStaff();
    });
    
    function filterStaff() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const roleFilter = $('#roleFilter').val().toLowerCase();
        const departmentFilter = $('#departmentFilter').val().toLowerCase();
        
        $('.staff-card').each(function() {
            const name = $(this).data('name');
            const role = $(this).data('role');
            const department = $(this).data('department');
            
            const matchesSearch = name.includes(searchTerm) || 
                                role.includes(searchTerm) || 
                                department.includes(searchTerm);
            const matchesRole = !roleFilter || role === roleFilter;
            const matchesDepartment = !departmentFilter || department === departmentFilter;
            
            if (matchesSearch && matchesRole && matchesDepartment) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    @if(auth()->user()->isAdmin())
    // Form submission
    $('#staffForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const staffId = $('#staffId').val();
        const url = staffId ? `/staff/${staffId}` : '{{ route("staff.store") }}';
        const method = staffId ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#staffModal').modal('hide');
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
    $('#staffModal').on('hidden.bs.modal', function() {
        $('#staffForm')[0].reset();
        $('#staffId').val('');
        $('#modalTitle').text('Add New Staff Member');
        $('#submitText').text('Save Staff Member');
    });
    @endif
});

function editStaff(id) {
    @if(auth()->user()->isAdmin())
    $.get(`/staff/${id}/edit`, function(data) {
        $('#staffId').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
        $('#phone').val(data.phone);
        $('#role').val(data.role);
        $('#department').val(data.department);
        $('#specialization').val(data.specialization);
        $('#license_number').val(data.license_number);
        $('#hire_date').val(data.hire_date);
        $('#salary').val(data.salary);
        $('#address').val(data.address);
        
        $('#modalTitle').text('Edit Staff Member');
        $('#submitText').text('Update Staff Member');
        $('#staffModal').modal('show');
    });
    @else
    showNotification('You do not have permission to edit staff members', 'error');
    @endif
}

function viewStaff(id) {
    window.location.href = `/staff/${id}`;
}

function deleteStaff(id) {
    @if(auth()->user()->isAdmin())
    confirmAction(
        'Delete Staff Member',
        'Are you sure you want to delete this staff member? This action cannot be undone.',
        'Yes, delete it!'
    ).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/staff/${id}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    showNotification('Failed to delete staff member', 'error');
                }
            });
        }
    });
    @else
    showNotification('You do not have permission to delete staff members', 'error');
    @endif
}
</script>
@endpush
@endsection
