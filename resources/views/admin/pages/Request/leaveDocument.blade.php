@extends('admin.master')

@section('content')
<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercases" style="color:#AE7DAC;">Leave Document</h6>
        @if($IsPreview)  
            <a href="{{ route('leave.config') }}" class="btn btn-success p-2 text-lg rounded-pill">
                <i class="fa-solid fa-gear me-1"></i>Config Request
            </a> 
        @endif
    </div>
    <br>

    @php
        $visibility = $configField->pluck('is_visible', 'leave_fieldname')->toArray();
        $employee = auth()->user()->employee; // Get the authenticated user's employee
    @endphp

    <div>
        <div class="mx-auto">
            <div class="card mb-3">
                <div class="card-header py-3">
                    <h5 class="mb-0 text-font text-uppercases">Leave Form</h5>
                </div>
                <div class="card-body">
                    <form id="leaveForm" action="{{ route('leave.documentForm.submit') }}" method="post">
                        @csrf
                        <div class="row mb-3">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="employeeSelect">Name:</label>
                                        <input type="text" value="{{ auth()->user()->name }}" class="form-control" disabled>
                                    </div>
                                    <div class="mt-2">
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="employeeNoInput">Employee No:</label>
                                        <input disabled placeholder="Select Employee" type="text" 
                                            value="{{ $employee ? $employee->employee_id : 'N/A' }}" id="employeeNoInput" 
                                            name="employee_no" class="form-control" />
                                    </div>
                                    <div class="mt-2">
                                        @error('employee_no')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="form11Example1">Department</label>
                                        <input disabled value="{{ $employee->department->department_name ?? null }}" 
                                            placeholder="No department assigned" type="text" id="departmentInput" name="department" 
                                            class="form-control" />
                                    </div>
                                    <div class="mt-2">
                                        @error('department')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="form11Example1">Date of Filing</label>
                                        <input type="date" id="form11Example1" name="date_filed" class="form-control" 
                                            value="{{ now()->toDateString() }}" readonly />
                                    </div>
                                    <div class="mt-2">
                                        @error('date_filed')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="form11Example1">Date of Leave</label>
                                        <input required type="date" id="form11Example1" name="date_leave" class="form-control" 
                                            value="{{ now()->toDateString() }}" min="{{ now()->toDateString() }}" />
                                    </div>
                                    <div class="mt-2"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="form-label mt-2 fw-bold text-uppercases" for="doc1">Check the following leave that employee wants to request</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                @foreach($configField as $fieldType)
                                    @if(isset($fieldType->typeConnection) && $fieldType->is_visible)
                                        <div class="col-md-6">
                                            <div class="form-outline">
                                                <input 
                                                    type="checkbox" 
                                                    id="leave{{ $fieldType->id }}" 
                                                    name="leave{{ $fieldType->id }}"
                                                    onclick="toggleFields('{{ $fieldType->id }}', '{{ $fieldType->typeConnection->leave_type_id }}')"
                                                    class="leave-checkbox"
                                                />
                                                <label class="form-label mt-2 fw-bold" for="leave{{ $fieldType->id }}">
                                                    {{ $fieldType->typeConnection->leave_type_id }} 
                                                    @if($fieldType->typeConnection->leave_days) 
                                                        ({{ $fieldType->typeConnection->leave_days }} Days) 
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        <div id="illness_field_{{ $fieldType->id }}" class="leave-field" style="display: none;">
                                            <label class="form-label mt-2 fw-bold" for="illness_{{ $fieldType->id }}">If Sick Leave (Specify Illness)</label><br>
                                            <textarea 
                                                name="illness_{{ $fieldType->id }}" 
                                                placeholder="Write Employee Illness Here..." 
                                                id="illness_{{ $fieldType->id }}" 
                                                class="form-control" 
                                                cols="30" 
                                                rows="5"
                                            ></textarea>
                                        </div>

                                        <div id="place_field_{{ $fieldType->id }}" class="leave-field" style="display: none;">
                                            <label class="form-label mt-2 fw-bold" for="place_{{ $fieldType->id }}">If Vacation Leave (Specify Place)</label><br>
                                            <textarea 
                                                name="place_{{ $fieldType->id }}" 
                                                placeholder="Write Leave Place Here..." 
                                                id="place_{{ $fieldType->id }}" 
                                                class="form-control" 
                                                cols="30" 
                                                rows="5"
                                            ></textarea>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="col-md-6" style="display: none;">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Submit To:</label>
                                    <select class="form-control" name="SystemAdmin">
                                        @forelse($leaveAdmin as $admin)
                                            <option value="{{ $admin->email }}">{{ $admin->name }}</option>
                                        @empty
                                            <option value="null">Admin for leave not created</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="mt-2">
                                    @error('SystemAdmin')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" id="submitButton" class="btn btn-success" @if($IsPreview) disabled @endif data-bs-toggle="modal" data-bs-target="#confirmationModal">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal: Confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit the leave form?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmSubmit" class="btn btn-success">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle form submission after confirmation
    document.getElementById('confirmSubmit').addEventListener('click', function() {
        document.getElementById('leaveForm').submit();  // Submit the form after confirmation
    });
</script>
@endsection
