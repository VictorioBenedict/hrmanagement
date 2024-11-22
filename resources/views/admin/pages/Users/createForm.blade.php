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

    .pagination {
        margin-top: 20px;
    }

    .pagination .page-item.disabled .page-link {
        color: #A0A6B1; /* Light gray color for disabled buttons */
        pointer-events: none; /* Prevent clicks */
    }

    .pagination .page-item.active .page-link {
        background-color: #3D5A5C !important; /* Custom active color */
        border-color: #3D5A5C !important;
        color: #F0F1F7; /* Light text color */
    }

    .pagination .page-link {
        color: black;
        border: 1px solid #D1D6E8;
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #D1D6E8;
        color: black;
    }

    .pagination .page-link i {
        font-size: 1.2rem;
        color: black;
    }
</style>

<!-- Section: Create User Account Form -->
<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercase" style="color:black;">Create User Account</h6>
    </div><br>

    <!-- User Account Form -->
    <section>
        <div class="w-75 mx-auto">
            <div class="card mb-3">
                <div class="card-header py-3">
                    <h5 class="mb-0 text-uppercase">User Account Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                        @if (session()->has('myError'))
                            <p class="alert alert-danger">{{ session()->get('myError') }}</p>
                        @endif

                        @if (session()->has('message'))
                            <p class="alert alert-success">{{ session()->get('message') }}</p>
                        @endif

                        @csrf

                        <!-- User Name and Role Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Enter User Name:</label>
                                    <input placeholder="Employee Name" type="text" id="form11Example1" name="name" class="form-control" value="{{ old('name') }}" />
                                </div>
                                <div class="mt-2">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Select Role:</label>
                                    <select {{ Auth::user()->role == 'System Admin' ? 'disabled' : '' }} required type="text" id="form11Example1" name="role" class="form-control">
                                        <option value="Employee">Employee(User)</option>
                                        <option value="System Admin">System Admin(HR)</option>
                                    </select>
                                </div>
                                <div class="mt-2">
                                    @error('role')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Email and Password Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Enter User Email:</label>
                                    <input required placeholder="Enter Email" type="email" id="form11Example1" name="email" class="form-control" value="{{ old('email') }}" />
                                </div>
                                <div class="mt-2">
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Has Password ?:</label>
                                    <input type="checkbox" id="form11Example1" name="hasPassword" class="form-control" />
                                </div>
                                <div class="mt-2">
                                    @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- User Image Upload -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Image:</label>
                                    <input type="file" id="form11Example1" name="user_image" class="form-control" accept="image/jpeg, image/png" />
                                </div>
                                <div class="mt-2">
                                    @error('image')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center w-25 mx-auto mt-3">
                            <button id="submitButton" type="submit" class="btn btn-success p-2 text-lg rounded-pill col-md-10" onclick="disableSubmitButton()">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
    function disableSubmitButton() {
        // Disable the button after submission
        const button = document.getElementById('submitButton');

        setTimeout(function() {
            button.disabled = true;
        }, 500); // 0.5 seconds delay
    }
</script>

@endsection
