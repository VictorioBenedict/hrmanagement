@extends('admin.master')

@section('content')

<!-- Style Block -->
<style>
    body {
        color: #F0F1F7; /* Light background for contrast */
    }

    .table {
        border-radius: 0.5rem; /* Rounded corners */
        overflow: hidden; /* Prevents overflow from corners */
    }

    .table th, .table td {
        vertical-align: middle; /* Center align */
    }

    .table th {
        background-color: #F0F1F7; /* Bootstrap primary color */
        color: white; /* White text on primary background */
    }

    .table tbody tr:hover {
        background-color: #f1f1f1; /* Light gray on hover */
    }

    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for checkboxes */
    }

    /* Hover Effects */
    .far.fa-edit:hover {
        transform: scale(1.2);
        color: #33AEFF;
    }

    /* Styling Card Body Content */
    .card-body p {
        font-size: 1.1em;
        line-height: 1.5;
    }

    .card-body h6 {
        font-size: 1.1em;
        color: #333;
    }

</style>

<!-- Header Section -->
<div class="shadow p-3 d-flex justify-content-between align-items-center" style="background-color: #DF7A30;">
    <h6 class="text-uppercase font-weight-bold text-primary">User Information</h6>
</div>
<br>

<!-- User Profile Section -->
<div class="col col-lg-6 mb-4 mb-lg-0 p-3 mx-auto" style="width: 100%; background-color: #f8f9fa; padding-top: 30px;">
    <div class="card shadow-sm p-4 rounded" style="background-color: #ffffff;">
        <div class="row g-0">
            
            <!-- Avatar and User Info -->
            <div class="col-md-4 gradient-custom text-center text-white" style="background-color: #DF7A30; border-radius: 10px;">
                <img src="{{ $employee && $employee->employee_image 
                ? Storage::url($employee->employee_image) 
                : asset('storage/default-avatar.jpg') }}" 
                alt="Avatar"
                class="img-fluid my-5 mx-auto rounded-circle"
                style="width: 120px; height: 120px; object-fit: cover; border: 4px solid white;" />
            
                <h5 class="text-white font-weight-bold">{{ $user->name }}</h5>
                <p class="text-white">{{ $user->role }}</p>
                
                <!-- Admin Edit Link -->
                @admin
                <a href="{{ route('edit', $user->id) }}" class="text-white">
                    <i class="far fa-edit mb-3" style="font-size: 1.2em; transition: transform 0.3s ease;"></i>
                </a>
                @endadmin
            </div>

            <!-- User Information Details -->
            <div class="col-md-8">
                <div class="card-body p-4">
                    <h6 class="text-dark font-weight-bold">Information</h6>
                    <hr class="mt-0 mb-4" style="border-top: 2px solid #0062E6;">

                    <!-- User Details -->
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Name</h6>
                        <p class="text-muted">{{ $employee->name ?? $user->name }}</p>
                    </div>

                    <div class="row pt-1">
                        <div class="col-6 mb-3">
                            <h6 class="font-weight-bold">Email</h6>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>
                        <div class="col-6 mb-3">
                            <h6 class="font-weight-bold">Role</h6>
                            <p class="text-muted">{{ $user->role }}</p>
                        </div>

                        @isset($employee)
                            <div class="col-6 mb-3">
                                <h6 class="font-weight-bold">Department</h6>
                                <p class="text-muted">{{ $employee->department->department_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <h6 class="font-weight-bold">Designation</h6>
                                <p class="text-muted">{{ $employee->designation->designation_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <h6 class="font-weight-bold">Location</h6>
                                <p class="text-muted">{{ $employee->location ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <h6 class="font-weight-bold">Phone</h6>
                                <p class="text-muted">{{ $employee->phone ?? 'N/A' }}</p>
                            </div>
                        @endisset
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
