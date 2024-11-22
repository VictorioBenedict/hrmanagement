@extends('admin.master')

@section('content')
<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercase">Request Document</h6>
        @if($IsPreview)
        <a href="{{ route('request.config') }}" class="btn p-2 text-lg rounded-pill">Config Request</a>
        @endif
    </div><br>

    @php
        $visibility = $configField->pluck('is_visible', 'document_fieldname')->toArray();
    @endphp

    <div>
        <div class="mx-auto">
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 text-uppercase">Request Form</h5>
                </div>
                <div class="card-body">
                    <form id="requestForm" action="{{ route('request.documentForm.submit') }}" method="post">
                        @csrf

                        <!-- Employee Info Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label mt-2 fw-bold">Name:</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mt-2 fw-bold">Employee No:</label>
                                <input type="text" class="form-control" name="employee_id" value="{{ auth()->user()->employee->employee_id ?? 'N/A' }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label mt-2 fw-bold">Department:</label>
                                <input type="text" class="form-control" name="department" value="{{ auth()->user()->employee->department->department_name ?? 'N/A' }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label mt-2 fw-bold">Date:</label>
                                <input type="date" class="form-control" name="date" value="{{ now()->toDateString() }}" readonly>
                            </div>
                        </div>

                        <!-- Document Request Section -->
                        <div class="row mb-2">
                            @foreach($configField as $fieldType)
                            @if(isset($fieldType->typeConnection) && $fieldType->is_visible)
                            <div class="col-md-6">
                                <input type="checkbox" id="doc{{ $fieldType->id }}" name="doc{{ $fieldType->id }}">
                                <label for="doc{{ $fieldType->id }}">{{ $fieldType->typeConnection->document_type }}</label>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <!-- Purpose Section -->
                        <div class="mb-4">
                            <label class="form-label mt-2 fw-bold">Purpose/s:</label>
                            <textarea name="purposes" class="form-control" required></textarea>
                        </div>

                        <!-- Hidden Fields -->
                        <div class="mb-4" style="display: none;">
                            <label class="form-label mt-2 fw-bold">Status:</label>
                            <input name="status_id" class="form-control" readonly value="{{ $statusPending->status_type }}">
                        </div>

                        <div class="mb-4" style="display: none;">
                            <label class="form-label mt-2 fw-bold">Status:</label>
                            <input name="status" class="form-control" readonly value="pending">
                        </div>

                        <div class="mb-4" style="display: none;">
                            <label class="form-label mt-2 fw-bold">Submit To:</label>
                            <select name="SystemAdmin" class="form-control" {{ $documentAdmin->isEmpty() ? 'disabled' : '' }}>
                                @forelse($documentAdmin as $admin)
                                    <option value="{{ $admin->email }}">{{ $admin->name }}</option>
                                @empty
                                    <option value="">No Admin Found</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="text-center mb-2">
                            <button type="button" class="btn btn-success" id="submitBtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal for confirmation -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                <!-- Close button with data-bs-dismiss="modal" to close the modal -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit this request?
            </div>
            <div class="modal-footer">
                <!-- Cancel button with data-bs-dismiss="modal" to close the modal -->
                <button type="button" class="btn btn-dangrt" data-bs-dismiss="modal">Cancel</button>
                <!-- Confirm button to submit the form -->
                <button type="button" class="btn btn-success" id="confirmSubmit">Confirm</button>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap JS (ensure correct version) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const submitBtn = document.getElementById('submitBtn'); // The Submit button
        const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal')); // Modal object
        const confirmSubmitBtn = document.getElementById('confirmSubmit'); // Confirm button in modal
        const form = document.getElementById('requestForm'); // The form to be submitted

        // Show the confirmation modal when the user clicks "Submit"
        submitBtn.addEventListener('click', function () {
            confirmationModal.show();
        });

        // Submit the form when the user clicks "Confirm"
        confirmSubmitBtn.addEventListener('click', function () {
            form.submit();
        });
    });
</script>
@endsection