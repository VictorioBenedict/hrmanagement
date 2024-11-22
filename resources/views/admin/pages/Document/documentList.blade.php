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
    .takas{
    background-color: #3D5A5C !important;
    color:white !important;
    margin-right:60%;
    padding:10px;
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

    
<div class="mt-1">
    <h6 class="shadow p-3 d-flex justify-content-between align-items-center">Document Requests</h6>
    <table class="table align-middle text-center w-100  ">
        <thead class="bg-light">
                <th>RQ NO</th>
                <th>Employee Name</th>
                <th>Employee No</th>
                <th>Department</th>
                <th>Date</th>
                <th>Requested Docs</th>
                <th>Purposes</th>
                <th>Status</th>
                <th>Released By</th>
                <th>Received By</th>
                <th>Reject Reason</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $key => $docs)
                    <tr>
                        <td><p class="fw-bold mb-1">{{ $docs->id }}</p></td>
                        <td>{{ $docs->employee->name ?? null }}</td>
                        <td>{{ $docs->employee->employee_id ?? null }}</td>
                        <td>{{ $docs->employee->department->department_name ?? 'N/A' }}</td>
                        <td>{{ date('m-d-Y', strtotime($docs->date)) }}</td>
                        <td class="text-wrap col-1">
                            <div style="text-center">
                                @foreach(explode(',', $docs->requestedDocs) as $doc)
                                    <span class="badge badge-pill bg-primary text-white mb-1" style="width:fit-content;">{{ trim($doc) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-wrap col-1">{{ $docs->purposes }}</td>
                        <td class="text-wrap col-1">
                            <div style="text-center">
                                <span class="badge badge-pill bg-warning text-white">Pending</span>
                            </div>
                        </td>
                        <td class="text-wrap col-1">
                            @if($docs->released_by)
                                {{ $docs->released_by }} at {{ date('m-d-Y h:i a', strtotime($docs->released_timestamp)) }}
                            @else
                                <span class="badge badge-pill bg-warning"> Not Released</span>
                            @endif
                        </td>
                        <td class="text-wrap col-1">
                            @if($docs->received_by)
                                {{ $docs->received_by }} at {{ date('m-d-Y h:i a', strtotime($docs->received_timestamp)) }}
                            @else
                            <span class="badge badge-pill bg-warning"> Not Received</span>
                            @endif
                        </td>
                        <td class="text-wrap col-1">{{ $docs->reject_reason ?? "None" }}</td>
                        <td>
                            @if(is_null($docs->reject_reason))
                                @if (is_null($docs->released_by) || is_null($docs->received_by))
                                <div style="display: flex; flex-direction:row; gap:2px;">
                                    <a class="btn btn-success rounded-pill" data-toggle="modal" data-target="#processModal{{ $docs->id }}" href="#">{{$docs->received_by == null ? 'Receive': 'Process'}}</a>
                                    <a class="btn btn-danger rounded-pill" data-toggle="modal" data-target="#rejectModal{{ $docs->id }}" href="#">Reject</a>
                                </div>
                                @endif
                            @endif
                        </td>
                    </tr>
    
                    {{-- Process Modal for each Request --}}
                    <div class="modal fade" id="processModal{{ $docs->id }}" tabindex="-1" role="dialog" aria-labelledby="processModal{{ $docs->id }}Label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold" id="processModal{{ $docs->id }}Label">{{$docs->received_by == null ? 'Receive' : 'Process'}} Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('process.Document', $docs->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                  
                                    @if($docs->received_by != null)
    
                                    <p class="fw-bold">Please select a status for this request:</p>
                                    <select class="form-control" name="status_type" id="status_type">
                                        <option value="" disabled selected>Select status type</option>
                                        @foreach($statusTypes as $statusType)
                                            <option value="{{ $statusType->id }}">{{ $statusType->status_type }}</option>
                                        @endforeach
                                    </select>
    
                                    <p class="mt-3 fw-bold" id="requirements-label" style="display: none;">Requirements to bring: (if applicable):</p>
                                    <textarea class="form-control" name="bringRequirements" id="bringRequirements" rows="3" placeholder="Enter requirements here..." style="display: none;"></textarea>
    
    
    
                                    <p class="mt-3 fw-bold d-none">Please select system admin who released this request:</p>
                                    <select class="form-control d-none" name="released_by">
                                        <option value="" disabled selected>Released By: System Admin</option>
                                        @foreach($systemAdminUsers as $user)
                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
    
                                    @else
                                    <p class="mt-3 fw-bold">This request is received by:</p>
                                    <input type="text" disabled class="form-control" name="received_by" value="{{Auth::user()->name}}" placeholder="Enter receiver's name" />
                                  
                                    @endif
                                </div>
                                <div class="modal-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
    
                                    @if($docs->received_by == null)
                                    <button type="submit" class="btn btn-primary" name="action_type" value="receive">Receive</button>
                                    @else
                                    <button type="submit" class="btn btn-primary" name="action_type" value="release">Release</button>
    
                                    <button type="submit" class="btn btn-success" name="action_type" value="confirm">Confirm</button>
                                    @endif
                                  
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    
                    {{-- Reject Modal for each Request --}}
                    <div class="modal fade" id="rejectModal{{ $docs->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModal{{ $docs->id }}Label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="rejectModal{{ $docs->id }}Label">Reject Request</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('reject.Document', $docs->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="fw-bold">Please provide a reason for rejection:</p>
                                        <textarea class="form-control" name="reject_reason" rows="3" placeholder="Enter rejection reason here..." required></textarea>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Confirm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
               
            @empty
                <tr>
                    <td colspan="12"><span class="badge badge-pill bg-warning text-center">No Data to display</span></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<!-- New Table for Rejected Document Requests -->
<div class="mt-1">
    <h6 class="shadow p-3 d-flex justify-content-between align-items-center  ">Rejected Document Requests</h6>
    <table class="table align-middle text-center w-100  ">
        <thead class="bg-light">
            <tr>
                <th>RQ NO</th>
                <th>Employee Name</th>
                <th>Employee No</th>
                <th>Department</th>
                <th>Date</th>
                <th>Requested Docs</th>
                <th>Status</th>
                <th>Reject Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rejectedDocument as $rejectedDoc) <!-- Change to use $rejectedDocument -->
            <tr>
                <td>{{ $rejectedDoc->id }}</td>
                <td>{{ $rejectedDoc->employee->name ?? null }}</td>
                <td>{{ $rejectedDoc->employee->employee_id  ?? null}}</td>
                <td>{{ $rejectedDoc->employee->department->department_name ?? 'N/A' }}</td>
                <td>{{ date('m-d-Y', strtotime($rejectedDoc->date)) }}</td>
                <td class="text-wrap col-1">
                    <div style="text-center">
                        @foreach(explode(',', $rejectedDoc->requestedDocs) as $doc)
                            <span class="badge badge-pill bg-primary text-white mb-1" style="width:fit-content;">{{ trim($doc) }}</span>
                        @endforeach
                    </div>
                </td>
                <td> <span class="badge badge-pill bg-danger text-white">Rejected</span></td>
                <td><span class="badge badge-pill bg-danger">{{ $rejectedDoc->reject_reason ?? 'None' }}</span> </td>
            </tr>
            @endforeach
            @if($rejectedDocument->isEmpty()) <!-- Use $rejectedDocument here -->
            <tr>
                <td colspan="8"><span class="badge badge-pill bg-danger">No Rejected Document Requests to display.</span></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- New Table for Released Document Requests -->
<div class="mt-1">
    <h6 class="shadow p-3 d-flex justify-content-between align-items-center  ">Released Document Requests</h6>
    <table class="table align-middle text-center w-100  ">
        <thead class="bg-light">
            <tr>
                <th>RQ NO</th>
                <th>Employee Name</th>
                <th>Employee No</th>
                <th>Department</th>
                <th>Date</th>
                <th>Requested Docs</th>
                <th>Status</th>
                <th>Released By</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receivedDocument as $releasedDoc) <!-- Change to use $receivedDocument -->
            <tr>
                <td>{{ $releasedDoc->id }}</td>
                <td>{{ $releasedDoc->employee->name ?? null }}</td>
                <td>{{ $releasedDoc->employee->employee_id ?? null }}</td>
                <td>{{ $releasedDoc->employee->department->department_name ?? 'N/A' }}</td>
                <td>{{ date('m-d-Y', strtotime($releasedDoc->date)) }}</td>
                <td class="text-wrap col-1">
                    <div style="text-center">
                        @foreach(explode(',', $releasedDoc->requestedDocs) as $doc)
                            <span class="badge badge-pill bg-primary text-white mb-1" style="width:fit-content;">{{ trim($doc) }}</span>
                        @endforeach
                    </div>
                </td>
                <td> <span class="badge badge-pill bg-success text-white">Approved & Released</span></td>
                <td><span class="badge badge-pill bg-success">{{ $releasedDoc->released_by }} at {{ date('m-d-Y h:i a', strtotime($releasedDoc->released_timestamp)) }}</span></td>
            </tr>
            @endforeach
            @if($receivedDocument->isEmpty()) <!-- Use $receivedDocument here -->
            <tr>
                <td colspan="8"><span class="badge badge-pill bg-success">No Released Document Requests to display.</span></td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

  


    <!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Function to handle showing/hiding the textarea based on selected status
    document.getElementById('status_type').addEventListener('change', function() {
        var status = this.options[this.selectedIndex].text;
        var requirementsLabel = document.getElementById('requirements-label');
        var bringRequirements = document.getElementById('bringRequirements');
        
        // Check if status is not 'Pending' or 'On Process'
        if (status !== 'Pending' && status !== 'On-Process') {
            requirementsLabel.style.display = 'none';  // Show the label
            bringRequirements.style.display = 'none';  // Show the textarea
        } else {
            requirementsLabel.style.display = 'block';  // Hide the label
            bringRequirements.style.display = 'block';  // Hide the textarea
        }
    });

    // Initial check on page load (if a status is pre-selected)
    window.onload = function() {
        var status = document.getElementById('status_type').value;
        if (status !== 'Pending' && status !== 'On-Process') {
            document.getElementById('requirements-label').style.display = 'block';
            document.getElementById('bringRequirements').style.display = 'block';
        }
    };
</script>

</div>
@endsection
