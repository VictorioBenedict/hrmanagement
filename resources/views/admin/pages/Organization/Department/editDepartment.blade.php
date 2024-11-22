@extends('admin.master')

@section('content')

<!-- Section: Form Design Block -->
<style>
    body {
        background-color: #f8f9fa; /* Light background for contrast */
        color: #AE7DAC; /* Dark color for text */
    }

    .table {
        border-radius: 0.5rem; /* Rounded corners */
        overflow: hidden; /* Prevents overflow from corners */
    }

    .table th, .table td {
        vertical-align: middle; /* Center align */
    }

    .table th {
        background-color: #F1916D; /* Custom background color */
        color: white; /* White text on primary background */
    }

    .table tbody tr:hover {
        background-color: #D1D6E8; /* Light gray on hover */
    }

    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for checkboxes */
    }

    /* Header styles */
    .page-header h4 {
        color: black;
    }

    /* Form and card styles */
    .card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #F1916D; /* Custom color for card header */
        color: white;
    }

    .form-outline .form-label {
        font-weight: bold;
    }

    .form-control {
        background-color: #F1F4F8;
        border: 1px solid #D1D6E8;
        border-radius: 0.5rem;
        color: #AE7DAC;
    }

    .btn-info {
        background-color: #F1916D;
        border: none;
        color: white;
        border-radius: 0.5rem;
        padding: 10px 20px;
        text-transform: uppercase;
    }

    .btn-info:hover {
        background-color: #5E6782; /* Darker shade for hover effect */
    }

    .text-center {
        text-align: center;
    }

    /* Responsive layout */
    .d-flex {
        display: flex;
    }

    .gap-5 {
        gap: 2rem;
    }

    .justify-content-center {
        justify-content: center;
    }

    .align-content-center {
        align-content: center;
    }

    /* Padding and spacing */
    .shadow {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .p-4 {
        padding: 2rem;
    }
</style>

<!-- Header Section -->
<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center" style="margin-bottom: 1rem;">
        <h6 class="text-uppercase" style="color: black; font-size: 1rem;">Edit Department</h6>
    </div>    
</section>

<!-- Department Form Section -->
<section>
    <div class="d-flex gap-5 justify-content-center align-content-center">
        <div class="text-left w-50">
            <div class="card mb-3">
                <div class="card-header py-3">
                    <h5 class="text-uppercase">Update Department</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('Organization.update', $department->id) }}" method="post">
                        @csrf
                        @method('put')

                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label mt-2" for="department_id">Department ID</label>
                                    <input value="{{ $department->department_id }}" placeholder="Enter ID"
                                           class="form-control" name="department_id" id="department_id" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label mt-2" for="department_name">Department Name</label>
                                    <input value="{{ $department->department_name }}" placeholder="Enter Name"
                                           class="form-control" name="department_name" id="department_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center w-25 mx-auto">
                            <button type="submit" class="btn btn-info p-2 rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Section -->
<footer style="position: fixed; bottom: 0; width: 100%; background-color: #7B7E8A; text-align: center; padding: 2px 0; color: #ffffff;">
    <div class="text-center p-3">
        <p class="mb-0">Copyright © All Rights Reserved 2024 Human Resource OCNHS</p>
    </div>
</footer>

@endsection
