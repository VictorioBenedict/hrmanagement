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
        background-color: #F1916D; /* Custom active color */
        border-color: #F1916D;
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
    <h6 class="text-uppercases">Edit Employee</h6>
    <div>
        <a href="{{ route('manageEmployee.ViewEmployee') }}" class="btn btn-success px-3 p-2 text-lg rounded-pill"><i
                class="fa-sharp fa-regular fa-eye me-1"></i>Employee
            List</a>
    </div>
</div><br>

    <!--Section: Form Design Block-->
    <section>

        <div>
            <div class=" mx-auto">
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-font text-uppercases">Update Employee Details</h5>
                    </div>
                    <div class="card-body">
                    <form action="{{ route('Employee.update', $employee->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')

    <!-- Personal Information -->
    <fieldset style="border: 2px solid #ccc; padding: 20px; margin-bottom: 20px;">
        <legend class="fw-bold">Personal Information</legend>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-outline">
                    <label class="form-label mt-2 fw-bold" for="firstname">Employee First Name</label>
                    <input required placeholder="Enter First Name" type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname', $employee->firstname) }}" />
                </div>
                @error('firstname')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <div class="form-outline">
                    <label class="form-label mt-2 fw-bold" for="middlename">Employee Middle Name</label>
                    <input required placeholder="Enter Middle Name" type="text" id="middlename" name="middlename" class="form-control" value="{{ old('middlename', $employee->middlename) }}" />
                </div>
                @error('middlename')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <div class="form-outline">
                    <label class="form-label mt-2 fw-bold" for="lastname">Employee Last Name</label>
                    <input required placeholder="Enter Last Name" type="text" id="lastname" name="lastname" class="form-control" value="{{ old('lastname', $employee->lastname) }}" />
                </div>
                @error('lastname')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-outline mb-4">
                    <label class="form-label mt-2 fw-bold" for="form11Example3" >Date of Birth</label>
                    <input value="{{ $employee->date_of_birth }}" required type="date" id="form11Example3" name="date_of_birth" class="form-control" />
                </div>
                <div class="mt-2">
                    @error('date_of_birth')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </fieldset>

    <!-- Job Information -->
    <fieldset style="border: 2px solid #ccc; padding: 20px; margin-bottom: 20px;">
        <legend class="fw-bold">Job Information</legend>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="d-flex flex-column">
                    <label class="form-label mt-2 fw-bold" for="employee_id">Employee ID</label>
                    <input 
                        required 
                        placeholder="Enter ID" 
                        type="text" 
                        id="employee_id" 
                        name="employee_id" 
                        class="form-control mb-2" 
                        value="{{ old('employee_id', $employee->employee_id ?? $nextEmployeeId) }}" 
                        oninput="this.value = this.value.replace(/[^0-9-_]/g, '');" 
                        pattern="^[0-9-_]+$" 
                        title="Employee ID can only contain numbers, hyphens (-), and underscores (_)"  readonly/>
                    @if(!isset($employee)) 
                        <small class="text-muted ms-2">Next available ID: {{ $nextEmployeeId }}</small>
                    @endif
                </div>                
                <div class="mt-2">
                    @error('employee_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-outline">
                    <label class="form-label mt-2" for="form11Example1">Department Name</label>
                    <select type="text" class="form-control" name="department_id">
                        <option value="{{ $employee->department->id ?? null }}" selected>Current: {{ $employee->department->department_name ?? 'No Department applied' }}</option>
                        @foreach ($departments as $department)
                        <option value="{{$department->id}}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2">
                    @error('department_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-outline mb-4">
                    <label class="form-label mt-2" for="form11Example1">Position</label>
                    <select required class="form-control" name="designation_id">
                        <option value="{{ $employee->designation->id ?? null }}" selected>Current: {{ $employee->designation->designation_name ?? 'No Designation applied' }}</option>
                        @foreach ($designations as $designation)
                        <option value="{{$designation->id}}">{{ $designation->designation_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2">
                    @error('designation_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </fieldset>

    <!-- Contact Information -->
    <fieldset style="border: 2px solid #ccc; padding: 20px; margin-bottom: 20px;">
        <legend class="fw-bold">Contact Information</legend>
        <div class="row mb-4">
            

            <div class="col-md-4">
                <div class="form-outline mb-4">
                    <label class="form-label mt-2 fw-bold" for="form11Example6">Phone</label>
                    <input value="{{ $employee->phone }}" required placeholder="Phone Number" type="text" id="form11Example6" name="phone" class="form-control" pattern="^(?:\+63|0)9\d{9}$" title="Enter a valid Philippine phone number" maxlength="11" inputmode="numeric" />
                </div>
                <div class="mt-2">
                    @error('phone')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-outline mb-4">
                    <label class="form-label mt-2 fw-bold" for="form11Example7">Address</label>
                    <input value="{{ $employee->location }}" required placeholder="Enter Address" type="text" id="form11Example7" name="location" class="form-control" />
                </div>
                <div class="mt-2">
                    @error('location')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </fieldset>

    <div class="form-outline" style="display: none;">
        <label class="form-label mt-2 fw-bold" for="role" style="display: none;">Select Role</label>
        <input type="text" id="role" name="role" class="form-control" style="display: none;" value="{{ $employee->role }}" readonly>                            
    </div>

    <label class="form-label mt-2 fw-bold" style="display: none;" for="form11Example6">Image</label>
    <small style="display: none;">CURRENT:</small>
    <img class="avatar p-1 w-1" style="display: none;" src="{{ Storage::url($employee->employee_image) }}" alt="">
    <input value="{{ $employee->employee_image }}" type="file" id="form11Example6" name="employee_image" class="form-control" accept="image/jpeg, image/png" style="display: none;"/>

    <!-- Save Button -->
    <div class="text-center gap-2">
        <a href="{{ route('manageEmployee.ViewEmployee') }}" class="btn btn-primary">Back</a>
        <button type="submit" class="btn btn-warning">Save Change</button>
    </div>
</form>

                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection
