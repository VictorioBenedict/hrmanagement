@extends('admin.master')

@section('content')

<section>
    <!-- Header Section -->
    <div class="shadow p-3 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercase" style="color:#AE7DAC;">Incoming Document Copy</h6>
        <a href="{{ route('incoming.config') }}" class="btn btn-success p-2 text-lg rounded-pill">
            <i class="fa-solid fa-gear me-1"></i>Config Incoming Document Form
        </a>
    </div><br>

    <!-- Form Section -->
    <div class="mx-auto">
        <div class="card mb-4">
            <div class="card-header py-3">
                <h5 class="mb-0 text-font text-uppercase">Incoming Document Copy Form</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('incoming.documentForm.submit') }}" method="post" id="incomingForm">
                    @csrf
                    <!-- Employee Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label mt-2 fw-bold">Name:</label>
                            <input type="text" value="{{ auth()->user()->name }}" class="form-control" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mt-2 fw-bold">Employee No:</label>
                            <input type="text" value="{{ $empno }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label mt-2 fw-bold">Department:</label>
                            <input type="text" value="{{ $departmentName }}" class="form-control" disabled>
                        </div>
                    </div>

                    <!-- Dynamic Actions Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="form-label mt-2 fw-bold text-uppercase">Actions Needed:</h5>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                @forelse ($incomingFields as $fields)
                                    @if (!in_array($fields->incoming_fieldname, [
                                        'employee_id', 'requestedLeaves', 'status', 'actions_id', 'remarks',
                                        'statuses_id', 'reject_reason', 'released_by', 'received_by',
                                        'released_timestamp', 'received_timestamp'
                                    ]))
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="incoming{{ $fields->id }}" value="on"
                                                @if($fields->is_visible) checked @endif>
                                            <label class="form-check-label">
                                                {{ $fieldLabels[$fields->incoming_fieldname] ?? optional($fields->typeConnection)->action_type_id ?? 'Default Value' }}
                                            </label>
                                        </div>
                                    @endif
                                @empty
                                    <p>No actions available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Remarks Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label mt-2 fw-bold">Remarks (Title):</label>
                            <textarea placeholder="Enter remarks here" id="remarksInput" name="remarks" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-6" style="display: none;">
                            <label class="form-label mt-2 fw-bold">Submit To:</label>
                            <select class="form-control" name="SystemAdmin" id="systemAdmin" required>
                                @foreach($systemAdmins as $admin)
                                    <option value="{{ $admin->email }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-3">
                        <button type="submit" id="submitButton" class="btn btn-success p-2 text-lg rounded-pill">Submit</button>
                    </div>
                </form>
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
                <p>Are you sure you want to submit the incoming document form?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmit">Yes, Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Section -->
<script>
    // Handle form validation and confirmation
    document.getElementById('incomingForm').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent form submission initially

        const remarks = document.getElementById('remarksInput').value;
        const systemAdmin = document.getElementById('systemAdmin').value;

        // Check if required fields are filled
        if (!remarks.trim() || !systemAdmin) {
            alert('Please fill in all required fields.');
            return;  // Stop execution if any required field is missing
        }

        // Ensure at least one checkbox is selected
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="incoming"]');
        const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        if (!isChecked) {
            alert('Please select at least one action.');
            return;  // Stop execution if no checkboxes are selected
        }

        // Show confirmation modal
        $('#confirmationModal').modal('show');
    });

    // Handle the confirm button click in the modal
    document.getElementById('confirmSubmit').addEventListener('click', function() {
        // Submit the form after confirmation
        document.getElementById('incomingForm').submit();
    });
</script>

<!-- Bootstrap 5 Integration -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@endsection
