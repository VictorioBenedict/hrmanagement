@extends('admin.master')

@section('content')
<style>
    body {/
        color: #F0F1F7;/* White text for high contrast */
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
    <h6 class="text-uppercases">Archived Employees</h6>
    <div>
        <a href="{{ route('manageEmployee.ViewEmployee') }}" class="btn btn-info p-2 rounded-pill">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to Active Employees
        </a>
    </div>
</div>

<br>

<table class="table align-middle text-center  ">
    <thead class="bg-light">
        <tr>
            <th>EMP NO</th>
            <th>Employee Name</th>
            <th>Image</th>
            <th>Employee ID</th>
            <th>Department</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($archivedEmployees as $key => $employee)
        <tr>
            <td><p class="fw-bold mb-1">{{ $key + 1 }}</p></td>
            <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
            <td><img class="avatar p-1" src="{{ Storage::url($employee->employee_image) }}" alt="Image of {{ $employee->firstname }}"></td>
            <td>{{ $employee->employee_id }}</td>
            <td>{{ optional($employee->department)->department_name }}</td>
            <td>{{ optional($employee->designation)->designation_name }}</td>
            <td>
                <a class="btn btn-warning rounded-pill fw-bold text-white" 
                   href="#" 
                   data-bs-toggle="modal" 
                   data-bs-target="#restoreEmployeeModal"
                   onclick="prepareRestoreForm('{{ route('employee.restore', $employee->id) }}')" 
                   title="Restore Employee">
                    <i class="fa-solid fa-undo"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No Archived Employees to display.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Restore Employee Modal -->
<div class="modal" id="restoreEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="restoreEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreEmployeeModalLabel">Confirm Restoration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restore this employee?</p>
            </div>
            <div class="modal-footer"> 
                <form id="restoreForm" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function prepareRestoreForm(restoreUrl) {
        document.getElementById('restoreForm').setAttribute('action', restoreUrl);
    }
</script>

@endsection
