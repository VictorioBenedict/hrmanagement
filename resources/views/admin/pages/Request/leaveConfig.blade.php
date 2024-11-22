@extends('admin.master')

@section('content')

<!-- Section Styling -->
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
        background-color: #AE7DAC; /* Background for table header */
        color: #F0F1F7; /* White text on header */
    }

    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for checkboxes */
    }

    .card-header {
        background-color: #AE7DAC; /* Dark background for cards */
        color: #F0F1F7; /* White text */
    }

    .input-group-text {
        background-color: white; /* Light background for search box */
        color: #AE7DAC; /* Dark text color */
    }

    .input-group .form-control {
        background-color: #D1D6E8; /* Light background for input */
        color: #AE7DAC; /* Dark text color */
    }

    .modal-content {
        background-color: #F1916D; /* Darker background for modal */
        color: #F0F1F7; /* White text */
    }

    .modal-header {
        background-color: #AE7DAC; /* Dark background for modal header */
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
        color: #AE7DAC; /* Dark color for page numbers */
        border: 1px solid #D1D6E8; /* Light border for links */
        padding: 0.5rem 0.75rem;
    }

    /* Hover effect for page links */
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #3D5A5C !important;
        color: white !important;
    }

    /* Custom icon styles */
    .pagination .page-link i {
        font-size: 1.2rem;
        color: #AE7DAC; /* Match the text color */
    }
</style>


<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center  ">
        <h6 class="text-uppercase" style="color:#AE7DAC;">Request Form Config</h6>
        <a href="{{ route('leave.documentForm', true) }}" class="btn btn-success p-2 text-lg rounded-pill">
            <i class="fa-regular fa-eye me-1"></i> Preview Form
        </a>
    </div>
</section>    

<br>

<!-- Search Section -->

<!-- Leave Fields Table -->
<table class="table align-middle mb-3 text-center">
    <thead class="bg-light">
        <tr>
            <th>Fields</th>
            <th>Is Visible</th>
        </tr>
    </thead>
    <tbody>
        @php
            $fieldLabels = [
                'name' => 'Employee Name',
                'empno' => 'Employee No',
                'department' => 'Department',
                'date_filed' => 'Date of Filing',
                'date_leave' => 'Date of Leave',
                'illness' => 'If Sick Leave (Specify Illness)',
                'place' => 'If Vacation Leave (Specify Place)',
                'signature' => 'Signature',
            ];
        @endphp
        @forelse ($leaveFields as $fields)
            @if(!in_array($fields->leave_fieldname, ['employee_id', 'requestedLeaves', 'status', 'statuses_id', 'reject_reason', 'released_by', 'received_by', 'released_timestamp', 'received_timestamp']))
            <tr>
                <td>
                    {{ $fieldLabels[$fields->leave_fieldname] ?? optional($fields->typeConnection)->leave_type_id ?? 'Default Value' }}
                </td>
                <td>
                    <input type="checkbox" class="toggle-visibility"
                        data-url="{{ route('leave.setVisible', $fields->id) }}"
                        @if($fields->is_visible) checked @endif />
                </td>
            </tr>
            @endif
        @empty
            <tr>
                <td colspan="2">No Data to display.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Section -->
<div class="w-25 mx-auto mt-4">
    {{ $leaveFields->appends(['search' => $searchQuery])->links() }}
</div>

<!-- JavaScript to handle checkbox visibility toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-visibility').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var isVisible = this.checked ? 1 : 0;
                var url = this.getAttribute('data-url') + '?is_visible=' + isVisible;

                // Perform a GET request to the URL
                window.location.href = url;
            });
        });
    });
</script>

@endsection
