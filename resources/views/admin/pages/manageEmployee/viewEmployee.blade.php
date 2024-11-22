@extends('admin.master')

@section('content')
<style>
    /* Additional custom styles if needed */
</style>
<div class="row">
    <div class="col-12 bg-warning d-flex flex-column flex-sm-row align-items-center justify-content-between p-3 rounded shadow">
        <h6 class="text-uppercase text-white mb-2 mb-sm-0">View Employee Details</h6>
        <div class="btn-group mt-2 mt-sm-0 d-flex justify-content-between w-100 gap-2">
            <a href="{{ route('manageEmployee.addEmployee') }}" class="btn btn-success p-2 text-lg rounded-pill">
                <i class="fa-solid fa-plus me-1"></i> Add New Employee
            </a>
            <a href="{{ route('users.list') }}" class="btn btn-success p-2 text-lg rounded-pill">
                <i class="fa-solid fa-plus me-1"></i> Edit Role & Password
            </a>
            <a href="{{ route('employees.archived') }}" class="btn btn-secondary p-2 ms-2 rounded-pill">
                View Archived Employees
            </a>
        </div>
    </div>
</div>


<br>

@if(Auth::user()->role !== 'Employee')
<div class="d-flex justify-content-end mb-3">
    <div class="input-group rounded w-25 w-sm-25">
        <form action="{{ route('employee.search') }}" method="get" class="w-100">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search..." name="search" aria-label="Search Employee">
                <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table align-middle text-center table-bordered" style="table-layout: auto;">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Employee Name</th>
                        <th>Image</th>
                        <th>Employee ID</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($employees->where('isArchived', '!=', 1)->where('employee_id', '!=', '999999999') as $key => $employee)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
                        <td>
                            <img class="avatar rounded-circle" 
                                 src="{{ Storage::url($employee->employee_image) }}" 
                                 alt="Image of {{ $employee->firstname }}" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>{{ $employee->employee_id }}</td>
                        <td>{{ $employee->role }}</td>
                        <td>{{ optional($employee->department)->department_name ?? 'N/A' }}</td>
                        <td>{{ optional($employee->designation)->designation_name ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group gap-2 d-flex flex-column flex-sm-row align-items-center">
                                <a class="btn btn-secondary btn-sm rounded-pill fw-bold text-white" href="{{ route('Employee.profile', $employee->id) }}">View</a>
                                @if($employee->role == 'System Admin' or $employee->role =='Employee')
                                    <a class="btn btn-success btn-sm rounded-pill fw-bold text-white" href="{{ route('Employee.edit', $employee->id) }}">Edit Basic Information</a>
                                @endif
                                <a class="btn btn-danger btn-sm rounded-pill fw-bold text-white" href="#" data-bs-toggle="modal"
                                   data-bs-target="#deleteEmployeeModal"
                                   onclick="prepareDeleteForm('{{ route('Employee.delete', $employee->id) }}')">Delete</a>
                                <a class="btn btn-dark btn-sm rounded-pill fw-bold text-white" 
                                   href="#" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#archiveEmployeeModal"
                                   onclick="prepareArchiveForm('{{ route('employee.archive', $employee->id) }}')">Archive</a>
                            </div>                            
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted fst-italic">No data available to display.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Archive Employee Modal -->
<div class="modal" id="archiveEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="archiveEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveEmployeeModalLabel">Confirm Archiving</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive this employee?</p>
            </div>
            <div class="modal-footer"> 
                <form id="archiveForm" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                    Archive</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function prepareDeleteForm(deleteUrl) {
        document.getElementById('deleteForm').setAttribute('action', deleteUrl);
    }

    function prepareArchiveForm(archiveUrl) {
        document.getElementById('archiveForm').setAttribute('action', archiveUrl);
    }
</script>

<!-- Delete Employee Modal -->
<div class="modal" id="deleteEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEmployeeModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this employee?</p>
            </div>
            <div class="modal-footer"> 
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
