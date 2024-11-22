@extends('admin.master')

@section('content')
    <style>
        body {
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

        .input-group-text {
            background-color: white; /* Light background for search box */
            color: black !important; /* Dark text color */
        }

        .input-group .form-control {
            background-color: #D1D6E8; /* Light background for input */
            color: black !important; /* Dark text color */
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
            background-color: #3D5A5C !important;
            color: white !important;
        }

        /* Custom icon styles */
        .pagination .page-link i {
            font-size: 1.2rem;
            color: black; /* Match the text color */
        }
    </style>

    <div class="shadow p-3 d-flex justify-content-between align-items-center rounded">
        <div class="p-2 d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase" style="color:#AE7DAC;">Request Form Config</h6>
        </div>

        <div>
            <a href="{{ route('request.documentForm', true) }}" class="btn btn-success p-2 text-lg rounded-pill">
                <i class="fa-regular fa-eye me-1"></i> Preview Form
            </a>
        </div>
    </div>
    <br>
    <table class="table align-middle mb-3 text-center">
        <br>
        <thead>
            <tr>
                <th>Fields</th>
                <th>Is Visible</th>
            </tr>
        </thead>
        <tbody>
            @php
                $fieldLabels = [
                    'name' => 'Name',
                    'empno' => 'Employee No',
                    'department' => 'Department',
                    'date' => 'Date',
                    'purposes' => 'Purposes',
                ];
            @endphp

            @forelse ($docFields as $fields)
                {{-- HIDDEN FIELDS --}}
                @if(!in_array($fields->document_fieldname, ['employee_id', 'requestedDocs', 'status', 'statuses_id', 'released_by', 'received_by', 'released_timestamp', 'received_timestamp', 'reject_reason']))
                    <tr>
                        <td>
                            {{ $fieldLabels[$fields->document_fieldname] ?? optional($fields->typeConnection)->document_type ?? 'Default Value' }}
                        </td>
                        <td>
                            <input type="checkbox" class="toggle-visibility" data-url="{{ route('request.setVisible', $fields->id) }}" @if($fields->is_visible) checked @endif />
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="12">No Data to display.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


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
