@extends('admin.master')

@section('content')

<style>
    body {
        color: #F0F1F7; /* White text for high contrast */
        margin-bottom: 3%;
    }

    .table {
        border-radius: 0.5rem; /* Rounded corners */
        overflow: hidden; /* Prevents overflow from corners */
    }

    .table th, .table td {
        vertical-align: middle; /* Center align */
    }

    .table th {
        background-color: #19305C; /* Bootstrap primary color */
        color: white; /* White text on primary background */
    }

    .table tbody tr:hover {
        background-color: #D1D6E8; /* Light gray on hover */
    }

    .table thead {
        background-color: black; /* Background for table header */
        color: #F0F1F7; /* White text on header */
    }

    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for checkboxes */
    }

    .card-header {
        background-color: black; /* Dark background for cards */
        color: #F0F1F7; /* White text */
    }

    .input-group-text {
        background-color: white; /* Light background for search box */
        color: black; /* Dark text color */
    }

    .input-group .form-control {
        background-color: #D1D6E8; /* Light background for input */
        color: black; /* Dark text color */
    }

    .modal-content {
        background-color: #F1916D; /* Darker background for modal */
        color: #F0F1F7; /* White text */
    }

    .modal-header {
        background-color: black; /* Dark background for modal header */
        color: #F0F1F7; /* White text */
    }

    .modal-footer button {
        background-color: #F1916D; /* Button color in modal */
        color: #F0F1F7; /* White text */
    }

    /* Pagination Container */
    .pagination {
        margin-top: 20px;
    }

    /* Disable state for previous and next buttons */
    .pagination .page-item.disabled .page-link {
        color: #A0A6B1; /* Light gray color for disabled buttons */
        pointer-events: none; /* Prevent clicks */
    }

    /* Active page number styling */
    .pagination .page-item.active .page-link {
        background-color: #3D5A5C !important; /* Custom active color */
        border-color: #3D5A5C !important;
        color: #F0F1F7; /* Light text color */
    }

    /* Page link styling */
    .pagination .page-link {
        color: black; /* Dark color for page numbers */
        border: 1px solid #D1D6E8; /* Light border for links */
        padding: 0.5rem 0.75rem;
    }

    /* Hover effect for page links */
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #D1D6E8;
        color: black;
    }

    /* Custom icon styles */
    .pagination .page-link i {
        font-size: 1.2rem;
        color: black; /* Match the text color */
    }
</style>

<!-- Section: Form Design Block -->
<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercase" style="color:black;">My Profile</h6>
    </div>

    <section>
        <br>
        <div class="row" style="width: 100%; background-color: #f8f9fa; padding-top: 30px;">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        {{-- <img src="{{ url('/uploads//' . $employee->employee_image) }}" alt="avatar"
                            class="rounded-circle mx-auto img-fluid"
                            style="width: 150px; height: 150px; object-fit: cover;"> --}}
                        <h5 class="my-3">{{ $employee->name }}</h5>
                        <p class="text-muted mb-1">{{ $employee->designation->designation_name }}</p>
                        <p class="text-muted mb-3">{{ $employee->location }}</p>
                        <div class="d-flex justify-content-center mb-2">
                            {{-- <button type="button" class="btn btn-primary">Follow</button> --}}
                            {{-- <a class="btn btn-success" href="{{ route('Employee.edit', $employee->id) }}">Update Profile</a> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->name }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Employee ID</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->employee_id }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Department</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->department->department_name }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Designation</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->designation->designation_name }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->email }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->phone }}</p>
                            </div>
                        </div>
                        <hr>
                        {{-- <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Salary</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->salaryStructure->salary_class }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Hire Date</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->hire_date }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Mode of Join</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->joining_mode }}</p>
                            </div>
                        </div> --}}
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->location }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

@endsection
