@extends('admin.master')

@section('content')

<!-- Section: Form Design Block -->
<style>
    body {
        color: #cecECE; /* Dark text for readability */
    }

    .table {
        border-radius: 0.5rem; /* Rounded corners for the table */
        overflow: hidden; /* Prevents overflow from corners */
    }

    .table th, .table td {
        vertical-align: middle; /* Center-align table content */
    }

    .table th {
        background-color: #F1916D; /* Custom background color for table header */
        color: white; /* White text on primary background */
    }

    .table tbody tr:hover {
        background-color: #D1D6E8; /* Light gray hover effect */
    }

    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for visibility toggles */
    }

    /* Header styles */
    .page-header h4 {
        color: black;
    }

    /* Card and Form Styling */
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #F1916D; /* Consistent card header color */
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

    .w-50 {
        width: 50%;
    }

</style>

<section>
    <!-- Header Section -->
    <div class="shadow p-3 d-flex justify-content-between align-items-center" style="background-color: #F1916D;">
        <h6 class="text-uppercase" style="color: white; font-size: 1.2rem;">
            Position Form
        </h6>
        <div>
            <a href="{{ route('organization.designationList') }}" class="btn btn-success p-2 rounded-pill" style="font-size: 0.9rem;">
                <i class="fa-regular fa-eye me-1"></i> Position List
            </a>
        </div>
    </div><br>

    <!-- Form Section -->
    <div class="d-flex gap-5 justify-content-center align-content-center">
        {{-- Position Form start --}}
        <div class="text-left w-50 mx-auto">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="text-uppercase">New Position</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('organization.designation.store') }}" method="POST">
                        @csrf

                        <div class="row mb-4">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label mt-2" for="form11Example1" style="color: black;">Position Name</label>
                                    <input type="text" class="form-control" name="designation_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center w-25 mx-auto">
                            <button type="button" class="btn btn-success p-2 rounded" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                                Add Position
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Position Confirmation Modal -->
<div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPositionModalLabel">Confirm Addition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to <strong>add</strong> this position?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAddPosition">Yes, Add</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('confirmAddPosition').addEventListener('click', function () {
        // Submit the form once the user confirms
        document.querySelector('form[action="{{ route('organization.designation.store') }}"]').submit();
    });
</script>

@endsection
