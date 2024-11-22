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
<div class="shadow p-3 d-flex justify-content-between align-items-center  ">
    <h6 class="text-uppercase">Searched Leave Request</h6>
</div>
<div class="my-5 py-5">


    <div class="fw-normal  mb-3">
        <h2 class="fw-normal fs-5 mx-auto text-center rounded-pill p-2 w-50 mb-5
            @if ($leaves->count() > 0) bg-success
            @else
                bg-danger text-white @endif">
            @if ($leaves->count() === 1)
            Found 1 matching data for "{{ request()->search }}"
            @elseif ($leaves->count() > 1)
            Found {{ $leaves->count() }} matching data for "{{ request()->search }}"
            @else
            No Data found for "{{ request()->search }}"
            @endif
        </h2>
    </div>

    @if ($leaves->count() > 0)
    <table class="table align-middle text-center w-100  ">
        <thead class="bg-light">
            <tr>
                <th>ID NO</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Days</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $key => $leave)
            <tr>
                <td>
                    <div>
                        <p class="fw-bold mb-1">{{ $key + 1 }}</p>
                    </div>
                </td>
                <td>{{ $leave->employee_name }}</td>
                <td>{{ $leave->department_name }}</td>
                <td>{{ $leave->designation_name }}</td>
                <td>{{ $leave->type->leave_type_id }}</td>
                <td>{{ $leave->from_date }}</td>
                <td>{{ $leave->to_date }}</td>
                <td>{{ $leave->total_days }}</td> <!-- Display total_days -->
                <td>{{ $leave->description }}</td>
                <td>
                    @if ($leave->status == 'approved')
                    <span class="text-white fw-bold bg-green rounded-pill p-2">Approved</span>
                    @elseif ($leave->status == 'rejected')
                    <span class="text-white fw-bold bg-red rounded-pill p-2">Rejected</span>
                    @else
                    <a class="btn btn-success rounded-pill "
                        href="{{ route('leave.approve', ['id' => $leave->id]) }}">Approve</a>
                    <a class="btn btn-danger rounded-pill "
                        href="{{ route('leave.reject', ['id' => $leave->id]) }}">Reject</a>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <div class="w-25 mx-auto mt-4">
        {{ $leaves->links() }}
    </div>
</div>
@endsection