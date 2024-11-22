@extends('admin.master')

@section('content')
<style>
    /* Custom styles for modal */
    .modal-backdrop {
        opacity: 0.5 !important;
    }
</style>

<div class="row">
    <div class="col-12 bg-warning d-flex flex-column flex-sm-row align-items-center justify-content-between p-3 rounded shadow">
        <h6 class="text-uppercase text-white mb-2 mb-sm-0">Add Employee</h6>
        <a href="{{ route('manageEmployee.ViewEmployee') }}" class="btn px-3 py-2 text-lg rounded-pill mt-2 mt-sm-0" style="background-color: #2c3e50; color: white;">
            <i class="fa-sharp fa-regular fa-eye me-1"></i> Employee List
        </a>
    </div>
</div>

<br>

<section>
    <div>
        <div class="mx-auto">
            <div class="card mb-3">
                <div class="card-header py-3">
                    <h5 class="mb-0 text-font text-uppercases">Submit Employee Details</h5>
                </div>
                <div class="card-body" style="color:black !important;">
                    <form id="employeeForm" action="{{ route('manageEmployee.addEmployee.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information Section -->
                        <fieldset class="mb-4" style="border: 2px solid #ccc; padding: 20px;">
                            <legend class="fw-bold text-uppercase">Personal Information</legend>
                            <h5 class="text-secondary mt-2">Provide the personal details of the employee</h5>
                            <div class="row">
                                <!-- First Name -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="firstname">Employee First Name</label>
                                        <input required placeholder="Enter First Name" type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname') }}" />
                                    </div>
                                    @error('firstname')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Middle Name -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="middlename">Employee Middle Name</label>
                                        <input required placeholder="Enter Middle Name" type="text" id="middlename" name="middlename" class="form-control" value="{{ old('middlename') }}" />
                                    </div>
                                    @error('middlename')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="lastname">Employee Last Name</label>
                                        <input required placeholder="Enter Last Name" type="text" id="lastname" name="lastname" class="form-control" value="{{ old('lastname') }}" />
                                    </div>
                                    @error('lastname')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="date_of_birth">Date of Birth</label>
                                        <input required type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" max="{{ \Carbon\Carbon::today()->toDateString() }}" />
                                    </div>
                                    @error('date_of_birth')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <!-- Job Information Section -->
                        <fieldset class="mb-4" style="border: 2px solid #ccc; padding: 20px;">
                            <legend class="fw-bold text-uppercase">Job Information</legend>
                            <h5 class="text-secondary mt-2">Provide the job details of the employee</h5>
                            <div class="row">
                                <!-- Employee ID -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="employee_id">Employee ID</label>
                                        <div class="d-flex flex-column">
                                            <input 
                                                required 
                                                placeholder="Enter ID" 
                                                type="text" 
                                                id="employee_id" 
                                                name="employee_id" 
                                                class="form-control mb-2" 
                                                value="{{ old('employee_id', $nextEmployeeId) }}" 
                                                oninput="this.value = this.value.replace(/[^0-9-_]/g, '');" 
                                                pattern="^[0-9-_]+$" 
                                                title="Employee ID can only contain numbers, hyphens (-), and underscores (_)" readonly/>
                                            <small class="text-muted ms-2">Next available ID: {{ $nextEmployeeId }}</small>
                                        </div>
                                    </div>
                                    @error('employee_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Department -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="department_id">Department Name</label>
                                        <select class="form-control" name="department_id">
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('department_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Position -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="designation_id">Position</label>
                                        <select required class="form-control" name="designation_id">
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->id }}" 
                                                    @if(old('designation_id', $selectedDesignationId ?? '') == $designation->id) selected @endif>
                                                    {{ $designation->designation_name }}
                                                </option>
                                            @endforeach                    
                                        </select>
                                    </div>
                                    @error('designation_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <!-- Contact Information Section -->
                        <fieldset class="mb-4" style="border: 2px solid #ccc; padding: 20px;">
                            <legend class="fw-bold text-uppercase">Contact Information</legend>
                            <h5 class="text-secondary mt-2">Provide the contact details of the employee</h5>
                            <div class="row">
                                <!-- Address -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="location">Employee Address</label>
                                        <input required placeholder="Enter Address" type="text" id="location" name="location" class="form-control" value="{{ old('location') }}" />
                                    </div>
                                    @error('location')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contact Number -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="phone">Phone</label>
                                        <input required placeholder="Phone Number" type="tel" id="phone" name="phone" class="form-control" pattern="[0-9]{11}" maxlength="11" inputmode="numeric" value="{{ old('phone') }}" />
                                    </div>
                                    @error('phone')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Address -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="email">Email Address</label>
                                        <input required placeholder="Enter Email" type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" />
                                    </div>
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <!-- Role and Account Settings Section -->
                        <fieldset class="mb-4" style="border: 2px solid #ccc; padding: 20px;">
                            <legend class="fw-bold text-uppercase">Role & Account Settings</legend>
                            <h5 class="text-secondary mt-2">Specify the role and account settings for the employee</h5>
                            <div class="row">
                                <!-- Role Selection -->
                         
                             <div>
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="role" style="display: none;">Select Role</label>
                                    @if (Auth::check() && Auth::user()->role == 'Admin')
                                    <select required id="role" name="role" class="form-control">
                                        <option value="" disabled selected>Select User Type: </option>
                                        <option value="Employee">Employee User</option>
                                        <option value="System Admin">System Admin</option>
                                    </select>
                                    @elseif (Auth::check() && Auth::user()->role == 'System Admin')
                                        <select required id="role" name="role" class="form-control" style="display: none;">
                                            <option value="Employee">Employee User</option>
                                        </select>
                                    @endif
                                </div>
                                @error('role')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                             </div>
                            

                                <!-- Employee Image -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="employee_image">Image</label>
                                        <input type="file" 
                                               id="employee_image" 
                                               name="employee_image" 
                                               class="form-control" 
                                               accept="image/jpeg, image/png" 
                                               onchange="previewImage(event)" />
                                    </div>
                                    @error('employee_image')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Image Preview -->
                                    <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                                        <img id="imagePreview" src="" alt="Selected Image" class="img-fluid" style="max-width: 100%; border-radius: 8px;" />
                                    </div>
                                </div>

                                <!-- Set Password -->
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="hasPassword" style="font-size: 16px; color: black;">Set Default Password?</label>
                                        <div style="display: flex; justify-content: center; align-items: center;">
                                            <input type="checkbox" id="hasPassword" name="hasPassword" class="ms-2"/>
                                        </div>
                                    </div>
                                    @error('hasPassword')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit the employee details?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmitBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Confirm Submission
    document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
        document.getElementById('employeeForm').submit();
    });

    // Image Preview
    function previewImage(event) {
        const file = event.target.files[0];
        
        if (file && (file.type === 'image/jpeg' || file.type === 'image/png')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                
                document.getElementById('imagePreviewContainer').style.display = 'block';
            };
            
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreviewContainer').style.display = 'none';
            alert("Please select a valid image (JPEG or PNG).");
        }
    }

    // Ensure date of birth is not in the future
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_of_birth').setAttribute('max', today);
    });
</script>

@endsection
