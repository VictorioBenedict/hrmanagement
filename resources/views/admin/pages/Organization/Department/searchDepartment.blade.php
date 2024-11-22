@extends('admin.master')

@section('content')

<style>
    body {
        background-color: #f0f4f8; /* Light background for contrast */
        color: #343a40; /* Darker text color for better readability */
    }

    .table {
        border-radius: 0.5rem; /* Rounded corners */
        overflow: hidden; /* Prevents overflow from corners */
    }

    .table th, .table td {
        vertical-align: middle; /* Center align */
    }

    .table th {
        background-color: #0056b3; /* Bootstrap primary color */
        color: white; /* White text on primary background */
    }

    .table tbody tr:hover {
        background-color: #e9ecef; /* Light gray on hover */
    }

    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for checkboxes */
    }

    .form-outline {
        margin-bottom: 1.5rem; /* Improved spacing */
    }

    .btn-success {
        background-color: #28a745; /* Bootstrap success color */
        border-color: #28a745; /* Button border color */
        color: white; /* White text for better contrast */
        transition: background-color 0.3s, border-color 0.3s; /* Smooth transition */
    }

    .btn-success:hover {
        background-color: #218838; /* Darker green on hover */
        border-color: #1e7e34; /* Darker border on hover */
    }

    .btn-danger {
        background-color: #dc3545; /* Danger button color */
        border-color: #dc3545;
        color: white; /* White text */
        transition: background-color 0.3s;
    }

    .btn-danger:hover {
        background-color: #c82333; /* Darker red on hover */
        border-color: #bd2130;
    }

    .btn-view {
        background-color: #007bff; /* View button color */
        color: white; /* White text for contrast */
        border-radius: 1.5rem; /* Rounded corners */
    }

    .btn-view:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    .alert {
        margin-top: 0.5rem; /* Space above error messages */
        background-color: #f8d7da; /* Light red background for errors */
        color: #721c24; /* Dark red text for errors */
        border: 1px solid #f5c6cb; /* Border color for errors */
    }

    .card {
        border: 1px solid #ced4da; /* Card border */
        border-radius: 0.5rem; /* Rounded corners */
    }

    .card-header {
        background-color: #007bff; /* Card header background */
        color: white; /* White text for card header */
    }

    .form-label {
        font-weight: bold; /* Bold labels for clarity */
    }
</style>

<!-- Page Header -->
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Department List</h6>
</div><br>

<!-- Section: Department Search and Table -->
<section>
    <div class="fw-normal mb-3">
        <h2 class="fw-normal fs-5 mx-auto text-center rounded-pill p-2 w-50 mb-5
            @if ($departments->count() > 0) bg-success
            @else
                bg-danger text-white @endif">
            @if ($departments->count() === 1)
            Found 1 matching data for "{{ request()->search }}"
            @elseif ($departments->count() > 1)
            Found {{ $departments->count() }} matching data for "{{ request()->search }}"
            @else
            No Data found for "{{ request()->search }}"
            @endif
        </h2>
    </div>

    <!-- Department Table -->
    @if ($departments->count() > 0)
    <div class="w-100">
        <div>
            <table class="table align-middle mb-3 text-center">
                <thead class="bg-light">
                    <tr>
                        <th>SL NO</th>
                        <th>Department ID</th>
                        <th>Department Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->department_id }}</td>
                        <td>{{ $item->department_name }}</td>
                        <td>
                            <a class="btn btn-success rounded-pill fw-bold text-white"
                               href="{{ route('Organization.edit', $item->id) }}">Edit</a>
                            <a class="btn btn-danger rounded-pill fw-bold text-white"
                               href="{{ route('Organization.delete', $item->id) }}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- No Data Message -->
    <div class="alert alert-warning text-center">
        No departments found. Please add some departments.
    </div>
    @endif
</section>

@endsection
