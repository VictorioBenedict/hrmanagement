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

<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Searched Employee</h6>
    <div>
        <a href="{{ route('manageEmployee.addEmployee') }}" class="btn btn-success p-2 text-lg rounded-pill">
            Add New Employee
        </a>
    </div>
</div>

<br>

<div class="fw-normal mb-3">
    <h2 class="fw-normal fs-5 mx-auto text-center rounded-pill p-2 w-50 mb-5
        @if ($employees->count() > 0) bg-success
        @else
            bg-danger text-white @endif">
        @if ($employees->count() === 1)
        Found 1 matching data for "{{ request()->search }}"
        @elseif ($employees->count() > 1)
        Found {{ $employees->count() }} matching data for "{{ request()->search }}"
        @else
        No Data found for "{{ request()->search }}"
        @endif
    </h2>
</div>

@if ($employees->count() > 0)
<div class="float-end mb-5">
    <button onclick="printContent('printDiv')" class="btn btn-danger text-capitalize border-0" data-mdb-ripple-color="dark">
        <i class="fa-solid fa-print me-1"></i> Print
    </button>
</div>

<div id="printDiv">
    <div class="col-md-12 mt-5 mb-3">
        <div class="text-center">
            <h4 class="pt-0">Employee Records</h4>
            <p class="pt-0"></p>
        </div>
    </div>
    <table class="table align-middle text-center">
        <thead class="bg-light">
            <tr>
                <th>SL NO</th>
                <th>Employee Name</th>
                <th>Image</th>
                <th>Employee ID</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Salary Grade</th>
                <th>Mode of Join</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $key => $employee)
            <tr>
                <td>
                    <div>
                        <p class="fw-bold mb-1">{{ $key + 1 }}</p>
                    </div>
                </td>
                <td>{{ $employee->name }}</td>
                <td><img class="avatar p-1" src="{{ url('/uploads//' . $employee->employee_image) }}" alt=""></td>
                <td>{{ $employee->employee_id }}</td>
                <td>{{ $employee->department->department_name }}</td>
                <td>{{ $employee->designation->designation_name }}</td>
                <td>{{ $employee->salaryStructure->salary_class }}</td>
                <td>{{ $employee->joining_mode }}</td>
                <td>
                    <a class="btn btn-warning rounded-pill fw-bold text-white" href="{{ route('Employee.profile', $employee->id) }}">View</a>
                    <a class="btn btn-success rounded-pill fw-bold text-white" href="{{ route('Employee.edit', $employee->id) }}">Edit</a>
                    <a class="btn btn-danger rounded-pill fw-bold text-white" href="{{ route('Employee.delete', $employee->id) }}">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
</div>

@endsection

@push('yourJsCode')
<script type="text/javascript">
    function printContent(el){
        var restorepage = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage);
    }
</script>
@endpush
