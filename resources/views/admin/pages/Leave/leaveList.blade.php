@extends('admin.master')

@section('content')

    <div class="shadow p-3 d-flex justify-content-between align-items-center bg-white text-black mb-3">
        <h6 class="text-uppercase">Leave Request</h6>
    </div>


    <table class="table align-middle text-center w-100">
   
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
                <th>Actions</th>
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
                <td>{{ $leave->employee->name ?? null }}</td>
                <td>{{ $leave->employee->employee_id ?? null }}</td>
                <td>{{ $leave->employee->department->department_name ?? null }}</td>
                <td><span class="badge badge-pill bg-primary">{{ date('m-d-Y', strtotime($leave->date_filed)) }}</span></td>
                <td><span class="badge badge-pill bg-primary">{{ date('m-d-Y', strtotime($leave->date_leave)) }}</span></td>
                <td class="text-wrap col-1">
                    <div>
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
                            $statusType = $leave->status->status_type ?? $deletedStatuses[$leave->id] ?? $statusType;
                        }
                    @endphp
                <td class="text-wrap col-1"><span class="badge badge-pill bg-warning">{{ $statusType }}</span>
                </td>
                @if($leave->released_by)
                    <td class="text-wrap col-1">    </td>
                @else
                <td><span class="badge badgpe-pill bg-warning">Not Released</span></td>
                @endif
                @if($leave->received_by)
                    <td class="text-wrap col-1">{{ $leave->received_by}} at {{ date('m-d-Y h:i a', strtotime($leave->received__timestamp)) }}</td>
                @else
                @endif

                <td>
         
                        @if(is_null($leave->reject_reason))
                            @if (is_null($leave->released_by) || is_null($leave->received_by))
                            <div class="d-flex text-center gap-2 justify-center">
                                <a class="btn btn-success" data-toggle="modal" data-target="#processModal{{ $leave->id }}" href="#">{{$leave->received_by == null ? 'Receive' : 'Process'}}</a>
                                <a class="btn btn-warning" data-toggle="modal" data-target="#rejectModal{{ $leave->id }}" href="#">Reject</a>
                                
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $leave->id }}">Delete</button>
                            </div>
                            @endif
                        @endif
          
                    
                </td>
         
            </tr>

            {{-- Delete Modal on each Request --}}
            <div class="modal fade" id="deleteModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModal{{ $leave->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="deleteModal{{ $leave->id }}Label">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('delete.leave', $leave->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <p class="fw-bold">Are you sure you want to delete this leave request?</p>
                                <p><strong>Leave Request Number:</strong> {{ $leave->id }}</p>
                                <p><strong>Employee Name:</strong> {{ $leave->employee->name ?? 'N/A' }}</p>
                                <p><strong>Leave Date:</strong> {{ date('m-d-Y', strtotime($leave->date_leave)) }}</p>
                            </div>
                            <div class="modal-footer d-flex">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            {{-- Process Modal on each Request --}}
            <div class="modal fade" id="processModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="processModal{{ $leave->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="processModal{{ $leave->id }}Label">{{$leave->received_by == null ? 'Receive' : 'Process'}} Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('process.Leave', $leave->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                              
                                @if($leave->received_by != null)

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

                                @if($leave->received_by == null)
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



            {{-- Reject Modal on each Request --}}
            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModal{{ $leave->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="rejectModal{{ $leave->id }}Label">Reject Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('reject.Leave', $leave->id) }}" method="POST">
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
            <td colspan="12">No Data to display.</td>

            @endforelse
        </tbody>
    </table>

    <!-- New Table for Rejected Document Requests -->
<div class="mt-2">
    <h6 class="shadow p-3 d-flex justify-content-between align-items-center bg-white text-black">Rejected Leave Requests</h6>
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
            @forelse ($rejectedLeaves as $key => $leave)
            <tr>
                <td>
                    <div>
              
                        <p class="fw-bold mb-1">{{ $leave->id}}</p>
                    </div>
                </td>
                <td>{{ $leave->employee->name ?? null }}</td>
                <td>{{ $leave->employee->employee_id ?? null }}</td>
                <td>{{ $leave->employee->department->department_name ?? null }}</td>
                <td>{{ date('m-d-Y', strtotime($leave->date_filed)) }}</td>
                <td>{{ date('m-d-Y', strtotime($leave->date_leave)) }}</td>
                <td class="text-wrap col-1">
                    <div>
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
                            $statusType = $leave->status->status_type ?? $deletedStatuses[$leave->id] ?? $statusType;
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
            <td colspan="12">No Rejected Leave Data to display.</td>

            @endforelse
        </tbody>
    </table>
</div>


<div class="mt-2">
    <h6 class="shadow p-3 d-flex justify-content-between align-items-center bg-white text-black">Released Leave Requests</h6>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($receivedLeaves as $key => $leave)
            <tr>
                <td>
                    <div>
                        {{-- <p class="fw-bold mb-1">{{ $key + 1 }}</p> --}}
                        <p class="fw-bold mb-1">{{ $leave->id}}</p>
                    </div>
                </td>
                <td>{{ $leave->employee->name ?? null }}</td>
                <td>{{ $leave->employee->employee_id ?? null }}</td>
                <td>{{ $leave->employee->department->department_name ?? null }}</td>
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
                            $statusType = $leave->status->status_type ?? $deletedStatuses[$leave->id] ?? $statusType;
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
            <td colspan="12">No Received Data to display.</td>

            @endforelse
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