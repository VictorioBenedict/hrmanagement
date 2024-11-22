@extends('admin.master')

@section('content')<style>
    body {
        background-color: #f8f9fa; /* Light background for contrast */
    }
    .table {
        border-radius: 0.5rem; /* Rounded corners */
        overflow: hidden; /* Prevents overflow from corners */
    }
    .table th, .table td {
        vertical-align: middle; /* Center align */
    }
    .table th {
        background-color: #007bff; /* Bootstrap primary color */
        color: white; /* White text on primary background */
    }
    .table tbody tr:hover {
        background-color: #f1f1f1; /* Light gray on hover */
    }
    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for checkboxes */
    }
</style>
<div class="page-header ">
    {{-- <h1 class="page-heading">dashboard</h1> --}}
    {{-- <span class="fw-bold page-heading" style="font-size: 30px">Today</span><br> --}}
    <span id="dayOfWeek" class="page-heading" style="font-size: 30px"></span><br>
    <span id='ct7' class="page-heading" style="font-size: 25px"></span>
    <p class="fw-medium fs-5 animated-text"> <span>Hello,</span>
        <span class="fw-bold ">{{ auth()->user()->name }}</span>
        <span>Welcome</span>
        <span>to</span>
        <span>Employee Human Resource</span>
        <span>System👋</span>
        <hr>
    </p>
</div>
<section class="mb-3 mb-lg-5">
            @php
                $role = auth()->user()->role;
            @endphp
    {{-- DOCUMENT REQUEST --}}

    <div class="shadow p-4 d-flex justify-content-between align-items-center row">
        <h4 class="text-uppercase">Document Request</h4>
        <div class="row">
            @if($searchQuery)
            <p class="text-uppercase">You searched: {{ $searchQuery ?? null }}</p>
            <a href="/dashboard" class="btn btn-danger p-2 px-3 rounded-pill col-1"><i class="fa-solid fa-xmark"></i> Search</a>
            @endif
        </div>
    </div>
    <div class="my-2 py-5">

        <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="input-group rounded w-50">
                <form action="{{ route('searchDocumentList') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." name="search">
                        <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon">  
                        </button>
                    </div>
                </form>
            </div>
            {{-- <a href="allLeaveReport" class="btn btn-danger text-capitalize border-0" data-mdb-ripple-color="dark"><i
                    class="fa-regular fa-paste me-1"></i>Report</a> --}}
        </div>
        <table class="table align-middle text-center w-100  ">
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
                </tr>
            </thead>
            <tbody>
                @forelse ($documentRequest as $key => $docs)
                <tr>
                    <td>
                        <div>
                            <p class="fw-bold mb-1">{{ $key + 1 }}</p>
                            {{-- <p class="fw-bold mb-1">{{ $docs->id}}</p> --}}
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
                        $statusType = $docs->status->status_type ?? $deletedStatusesDocument[$docs->id] ?? $statusType;
                    }
                @endphp
                    <td class="text-wrap col-1">{{ $statusType }}</td>
                    @if($docs->released_by)
                        <td class="text-wrap col-1">{{ $docs->released_by}} at {{date('m-d-Y h:i a', strtotime($docs->released_timestamp)) }}</td>
                    @else
                    <td>Not Released</td>
                    @endif
                    @if($docs->received_by)
                        <td class="text-wrap col-1">{{ $docs->received_by}} at {{ date('m-d-Y h:i a', strtotime($docs->received__timestamp)) }}</td>
                    @else
                    <td>Not Received</td>
                    @endif
                    <td class="text-wrap col-1">{{ $docs->reject_reason ?? 'None' }}</td>
                </tr>
                @empty
                <td colspan="12">No Data to display.</td>
                @endforelse
            </tbody>
        </table>
        <div class="w-25 mx-auto">
            {{ $documentRequest->appends(['search' => $searchQuery])->links() }}
        </div>
    </div>
    {{-- LEAVE REQUEST --}}

    <div class="shadow p-4 d-flex justify-content-between align-items-center row">
        <h4 class="text-uppercase">Leave Request</h4>
        <div class="row">
            @if($searchQueryLeave)
            <p class="text-uppercase">You searched: {{ $searchQueryLeave ?? null }}</p>
            <a href="/dashboard" class="btn btn-danger p-2 px-3 rounded-pill col-1"><i class="fa-solid fa-xmark"></i> Search</a>
            @endif
        </div>
    </div>
    <div class="my-2 py-5">

        <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="input-group rounded w-50">
                <form action="{{ route('searchLeaveList.dash') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." name="search">
                        <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon">  
                        </button>
                    </div>
                </form>
            </div>
            {{-- <a href="allLeaveReport" class="btn btn-danger text-capitalize border-0" data-mdb-ripple-color="dark"><i
                    class="fa-regular fa-paste me-1"></i>Report</a> --}}
        </div>
        <table class="table align-middle text-center w-100  ">
            <thead class="bg-light">
                <tr>
                    <th>LV NO</th>
                    <th>Employee Name</th>
                    <th>Employee No</th>
                    <th>Department</th>
                    <th>Date of Filling</th>
                    <th>Date of Leave</th>
                    <th>Leave Request</th>
                    <th>Status</th>
                    <th>Released By</th>
                    <th>Received By</th>
                    <th>Reject Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaves as $key => $leave)
                <tr>
                    <td>
                        <div>
                            {{-- <p class="fw-bold mb-1">{{ $key + 1 }}</p> --}}
                            <p class="fw-bold mb-1">{{ $leave->id}}</p>
                        </div>
                    </td>
                    <td>{{ $leave->employee->name }}</td>
                    <td>{{ $leave->employee->employee_id }}</td>
                    <td>{{ $leave->employee->department->department_name }}</td>
                    <td>{{ date('m-d-Y', strtotime($leave->date_filed)) }}</td>
                    <td>{{ date('m-d-Y', strtotime($leave->date_leave)) }}</td>
                    <td class="text-wrap col-1">
                        <div style="display:flex; flex-direction:column; align-items:start; height:60px; overflow:auto; padding:5px;">
                                @foreach(explode(',', $leave->requestedLeaves) as $doc)
                                    <span class="badge badge-pill bg-primary text-white mb-1" style="width:fit-content;">{{ trim($doc) }}</span>
                                @endforeach
                        </div>

                    </td>
                        @php
                            $statusType = 'Unknown Status';

                            if ($leave->statuses_id == 0) {
                                $statusType = 'Rejected';
                            } elseif ($leave->statuses_id == 999) {
                                $statusType = 'Released';
                            } elseif ($leave->statuses_id == 909) {
                                $statusType = 'Received';
                            } else {
                                $statusType = $leave->status->status_type ?? $deletedStatusesLeave[$leave->id] ?? $statusType;
                            }
                        @endphp
                    <td class="text-wrap col-1">{{ $statusType }}
                    </td>
                    @if($leave->released_by)
                        <td class="text-wrap col-1">{{ $leave->released_by}} at {{date('m-d-Y h:i a', strtotime($leave->released_timestamp)) }}</td>
                    @else
                    <td>Not Released</td>
                    @endif
                    @if($leave->received_by)
                        <td class="text-wrap col-1">{{ $leave->received_by}} at {{ date('m-d-Y h:i a', strtotime($leave->received__timestamp)) }}</td>
                    @else
                    <td>Not Received</td>
                    @endif
                    <td class="text-wrap col-1">{{ $leave->reject_reason ?? "None" }}</td>

                </tr>

                @empty
                <td colspan="12">No Data to display.</td>

                @endforelse
            </tbody>
        </table>
        <div class="w-25 mx-auto mt-4">
            {{ $leaves->appends(['search' => $searchQueryLeave])->links() }}
        </div>
    </div>
</div>

@endsection
