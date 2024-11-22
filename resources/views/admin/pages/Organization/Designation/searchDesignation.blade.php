@extends('admin.master')

@section('content')

<style>
    body {
        background-color: #f8f9fa; /* Light background for contrast */
        color: #AE7DAC; /* Dark text for readability */
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

    /* Header Styling */
    .page-header h4 {
        color: black !important;
    }

    /* Button Styling */
    .btn {
        border-radius: 0.5rem;
    }

    .btn-success {
        background-color: #F1916D;
        border: none;
        color: white;
    }

    .btn-danger {
        background-color: #E74C3C;
        border: none;
        color: white;
    }

    .btn:hover {
        opacity: 0.8;
    }

    .btn-success:hover {
        background-color: #5E6782; /* Darker shade for hover effect */
    }

    .btn-danger:hover {
        background-color: #C0392B; /* Darker shade for delete hover */
    }

    /* Table Styles */
    .table {
        margin-top: 20px;
    }

    .table th, .table td {
        padding: 1rem;
    }

    .input-group {
        width: 25%;
    }

    .text-center {
        text-align: center;
    }

    /* Pagination Style */
    .w-25 {
        width: 25%;
    }

    .mt-4 {
        margin-top: 2rem;
    }

    .p-4 {
        padding: 2rem;
    }

    .d-flex {
        display: flex;
    }

    .gap-5 {
        gap: 2rem;
    }

    .justify-content-between {
        justify-content: space-between;
    }
</style>

<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Designation List</h6>
    <div>
        <a href="{{ route('organization.designation') }}" class="btn btn-success p-2 text-lg rounded-pill">
            <i class="fa-solid fa-plus me-1"></i>Create Designation
        </a>
    </div>
</div><br>

<div class="fw-normal mb-3">
    <h2 class="fw-normal fs-5 mx-auto text-center rounded-pill p-2 w-50 mb-5
        @if ($designations->count() > 0) bg-success
        @else
            bg-danger text-white @endif">
        @if ($designations->count() === 1)
            Found 1 matching data for "{{ request()->search }}"
        @elseif ($designations->count() > 1)
            Found {{ $designations->count() }} matching data for "{{ request()->search }}"
        @else
            No Data found for "{{ request()->search }}"
        @endif
    </h2>
</div>

@if ($designations->count() > 0)
    {{-- Department Table start --}}
    <div>
        <table class="table align-middle mb-3 text-center">
            <thead class="bg-light">
                <tr>
                    <th>SL NO</th>
                    <th>Designation ID</th>
                    <th>Designation Name</th>
                    <th>Department Name</th>
                    <th>Salary Class</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($designations as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->designation_id }}</td>
                        <td>{{ $item->designation_name }}</td>
                        <td>{{ optional($item->department)->department_name }}</td>
                        {{-- <td>{{ $item->salary->salary_class }}</td> --}}
                        <td>{{ optional($item->salary)->salary_class }}</td>
                        <td>
                            <a class="btn btn-success rounded-pill fw-bold text-white"
                               href="{{ route('designation.edit', $item->id) }}">Edit</a>
                            <a class="btn btn-danger rounded-pill fw-bold text-white"
                               href="{{ route('designation.delete', $item->id) }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
