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
    <!--Section: Form Design Block-->
    <section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center  ">
    <h6 class="text-uppercases"style="color:#AE7DAC;">Documents</h6>
    <div class="row">
        @if($searchQuery)
        <p class="text-uppercases">You searched: {{ $searchQuery ?? null }}</p>
        <a href="/Document/DocumentRequest" class="btn btn-danger p-2 px-3 rounded-pill col-1"><i class="fa-solid fa-xmark"></i> Search</a>
        @endif
    </div>
</div>

    

    <table class="table align-middle text-center w-100  "> <form action="{{ route('searchFormDocumentList') }}" method="get">
                <div class="input-group w-25 mt-3 mb-3">
                    <input type="text" class="form-control" placeholder="Search..." name="search">
                    <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon"> 
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        <thead class="bg-light">
            <tr>
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
                <td>
                    <div>
                        {{-- <p class="fw-bold mb-1">{{ $key + 1 }}</p> --}}
                        <p class="fw-bold mb-1">{{ $docs->id}}</p>
                    </div>
                </td>
                <td>{{ $docs->employee->name }}</td>
                <td>{{ $docs->employee->employee_id }}</td>
                <td>{{ $docs->employee->department->department_name }}</td>
                <td>{{ date('m-d-Y', strtotime($docs->date)) }}</td>
                <td class="text-wrap col-1">
                    <div style="display:flex; flex-direction:column; align-items:start; height:60px; overflow:auto; padding:5px;">
                            @foreach(explode(',', $docs->requestedDocs) as $doc)
                                <span class="badge badge-pill bg-primary text-white mb-1" style="width:fit-content;">{{ trim($doc) }}</span>
                            @endforeach
                    </div>

                </td>
                <td class="text-wrap col-1">{{ $docs->purposes }}</td>
                @php
                    $statusType = 'Unknown Status';

                    if ($docs->statuses_id == 0) {
                        $statusType = 'Rejected';
                    } elseif ($docs->statuses_id == 999) {
                        $statusType = 'Released';
                    } elseif ($docs->statuses_id == 909) {
                        $statusType = 'Received';
                    } else {
                        $statusType = $docs->status->status_type ?? $deletedStatuses[$docs->id] ?? $statusType;
                    }
                @endphp
                <td class="text-wrap col-1">{{ $statusType }}
                </td>
                @if($docs->released_by)
                    <td class="text-wrap col-1">{{ $docs->released_by}} at {{date('m-d-Y h:i a', strtotime($docs->released_timestamp)) }}</td>
                @else
                <td><span class="badge badge-pill bg-warning">Not Released</span></td>
                @endif
                @if($docs->received_by)
                    <td class="text-wrap col-1"><span class="badge badge-pill bg-success">{{ $docs->received_by}} at {{ date('m-d-Y h:i a', strtotime($docs->received__timestamp)) }}</span></td>
                @else
                <td>Not Received</td>
                @endif
                <td class="text-wrap col-1">{{ $docs->reject_reason ?? "None" }}</td>
                <td>
                    @if(is_null($docs->reject_reason))
                        @if (is_null($docs->released_by) || is_null($docs->received_by))
                        <div style="display: flex; flex-direction:row; gap:2px;">
                            <a class="btn btn-success rounded-pill"
                            data-toggle="modal" data-target="#processModal{{ $docs->id }}" href="#">Process</a>

                            <a class="btn btn-danger rounded-pill" data-toggle="modal" data-target="#rejectModal{{ $docs->id }}" href="#">Reject</a>
                        </div>
                        @endif
                    @endif
                </td>



            </tr>

            {{-- Process Modal on each Request --}}
            <div class="modal fade" id="processModal{{ $docs->id }}" tabindex="-1" role="dialog" aria-labelledby="processModal{{ $docs->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="processModal{{ $docs->id }}Label">Process Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('process.Document', $docs->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p>Please select a status for this request:</p>
                                <select class="form-control" name="status_type" {{ $docs->released_timestamp ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Select status type</option>
                                    @foreach($statusTypes as $statusType)
                                        <option value="{{ $statusType->id }}">{{ $statusType->status_type }}</option>
                                    @endforeach
                                </select>

                                <p class="mt-3">Requirements to bring: (if applicable):</p>
                                <textarea class="form-control" name="bringRequirements" rows="3" placeholder="Enter requirements here..."></textarea>

                                <p class="mt-3">Please enter receiver of this request:</p>
                                <input type="text" class="form-control" name="received_by" placeholder="Enter receiver's name" />
                             
                                <p class="mt-3">Please select system admin who released this request:</p>
                                <select class="form-control" name="released_by">
                                    <option value="" disabled selected>Released By: System Admin</option>
                                    @foreach($systemAdminUsers as $user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>


                                <button type="submit" class="btn btn-primary" name="action_type" value="receive">Receive</button>
                                <button type="submit" class="btn btn-primary" name="action_type" value="release">Release</button>
                                <button type="submit" class="btn btn-success" name="action_type" value="confirm">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            {{-- Reject Modal on each Request --}}
            <div class="modal fade" id="rejectModal{{ $docs->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModal{{ $docs->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModal{{ $docs->id }}Label">Reject Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('reject.Document', $docs->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p>Please provide a reason for rejection:</p>
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
            <td colspan="12">No Data to display.</td>

            @endforelse
        </tbody>
    </table>
    <div class="w-25 mx-auto mt-4">
        {{ $documents->appends(['search' => $searchQuery])->links() }}
    </div>

    <!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</div>
@endsection
