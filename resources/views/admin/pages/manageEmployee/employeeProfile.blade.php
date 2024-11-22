@extends('admin.master')

@section('content')
<style>
    body {
        color:black !important ;/* White text for high contrast */
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
<div class="shadow p-3 d-flex justify-content-between align-items-center  ">
    <h6 class="text-uppercases">Employee Profile</h6>
        <div>
            <a href="{{ route('manageEmployee.ViewEmployee') }}" class="btn btn-success px-3 p-2 text-lg rounded-pill"><i
                    class="fa-sharp fa-regular fa-eye me-1"></i>Employee
                List</a>
        </div>
</div>
<section><br>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center" style="height: 455px;">
                        <img src="{{ Storage::url($employee->employee_image) }}" alt="employee profile picture"
                            class="rounded-circle mx-auto img-fluid"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="my-3 form-label mt-2 fw-bold">EMP Name: {{ $employee->name }}</h5>
                        <p class="text-muted mb-1 form-label mt-2 fw-bold">Position: 
                            {{ optional($employee->designation)->designation_name }}
                        </p>
                        <p class="text-muted mb-4 form-label mt-2 fw-bold">Address: {{ $employee->location }}</p>
                        <div class="d-flex justify-content-center mb-2">
                            <a class="btn btn-primary" href="{{ route('manageEmployee.ViewEmployee') }}">
                                Back</a>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 form-label mt-2 fw-bold">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->lastname }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 form-label mt-2 fw-bold">Employee ID</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0 form-label mt-2 ">{{ $employee->employee_id }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 form-label mt-2 fw-bold">Department</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0 form-label mt-2 ">{{ $employee->department->department_name ?? 'No Department applied' }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 form-label mt-2 fw-bold">Position</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0 form-label mt-2 ">{{ $employee->designation->designation_name ?? 'No Position applied'}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 form-label mt-2 fw-bold">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0 form-label mt-2">{{ $employee->email ?? 'No email inserted' }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 form-label mt-2 fw-bold">Phone</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0 form-label mt-2 ">{{ $employee->phone ??'No phone inserted' }}</p>
                            </div>
                        </div>
                        <hr>
                        {{-- <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Salary</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ optional($employee->salaryStructure)->salary_class }}</p>
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
                                <p class="mb-0">Mode of Joining</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->joining_mode }}</p>
                            </div>
                        </div>
                        <hr>--}}
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0 form-label mt-2 fw-bold">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $employee->location ?? 'No address inserted' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">assigment</span> Project
                                    Status
                                </p>
                                <p class="mb-1" style="font-size: .77rem;">Web Design</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Website Markup</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">One Page</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 89%" aria-valuenow="89"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Mobile Template</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Backend API</p>
                                <div class="progress rounded mb-2" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 66%" aria-valuenow="66"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">assigment</span> Project
                                    Status
                                </p>
                                <p class="mb-1" style="font-size: .77rem;">Web Design</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Website Markup</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">One Page</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 89%" aria-valuenow="89"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Mobile Template</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Backend API</p>
                                <div class="progress rounded mb-2" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 66%" aria-valuenow="66"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>
@endsection
