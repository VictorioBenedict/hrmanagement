    <!-- New Table for Rejected Document Requests -->
<!-- <div class="mt-5">
    <h4 class="text-uppercase">Rejected Leave Requests</h4>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rejectedLeaves as $key => $leave)
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
                <td>
                    @if(is_null($leave->reject_reason))
                        @if (is_null($leave->released_by) || is_null($leave->received_by))
                        <div style="display: flex; flex-direction:row; gap:2px;">
                            <a class="btn btn-success rounded-pill"
                            data-toggle="modal" data-target="#processModal{{ $leave->id }}" href="#">Process</a>

                            <a class="btn btn-danger rounded-pill" data-toggle="modal" data-target="#rejectModal{{ $leave->id }}" href="#">Reject</a>
                        </div>
                        @endif
                    @endif
                </td>



            </tr>

            {{-- Process Modal on each Request 
            <div class="modal fade" id="processModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="processModal{{ $leave->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="processModal{{ $leave->id }}Label">Process Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('process.Leave', $leave->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p>Please select a status for this request:</p>
                                <select class="form-control" name="status_type" {{ $leave->released_timestamp ? 'disabled' : '' }}>
                                    <option value="" disabled selected>Select status type</option>
                                    @foreach($statusTypes as $statusType)
                                        <option value="{{ $statusType->id }}">{{ $statusType->status_type }}</option>
                                    @endforeach
                                </select>

                                <p class="mt-3">Requirements to bring: (if applicable):</p>
                                <textarea class="form-control" name="bringRequirements" rows="3" placeholder="Enter requirements here..."></textarea>

                                @if($leave->received_by != null)
                                <p class="mt-3 d-none">Please enter receiver of this request:</p>
                                <input type="text" value="{{Auth::user()->name}}" class="form-control d-none" name="received_by" placeholder="Enter receiver's name" />
                                @endif
                              
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
                                @if($leave->received_by == null)
                                <button type="submit" class="btn btn-primary" name="action_type" value="receive">Receive</button>
                                @else
                                <button type="submit" class="btn btn-primary" name="action_type" value="release">Release</button>
                                @endif
                                <button type="submit" class="btn btn-success" name="action_type" value="confirm">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            --}}



            {{-- Reject Modal on each Request 
            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModal{{ $leave->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModal{{ $leave->id }}Label">Reject Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('reject.Leave', $leave->id) }}" method="POST">
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
            --}}

            @empty
            <td colspan="12">No Rejected Leave Data to display.</td>

            @endforelse
        </tbody>
    </table>
</div> -->

<!-- New Table for Released Document Requests -->
<!-- <div class="mt-5">
    <h4 class="text-uppercase">Released Leave Requests</h4>
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
                <td>
                    @if(is_null($leave->reject_reason))
                        @if (is_null($leave->released_by) || is_null($leave->received_by))
                        <div style="display: flex; flex-direction:row; gap:2px;">
                            <a class="btn btn-success rounded-pill"
                            data-toggle="modal" data-target="#processModal{{ $leave->id }}" href="#">Process</a>

                            <a class="btn btn-danger rounded-pill" data-toggle="modal" data-target="#rejectModal{{ $leave->id }}" href="#">Reject</a>
                        </div>
                        @endif
                    @endif
                </td>



            </tr>

            {{-- Process Modal on each Request
            <div class="modal fade" id="processModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="processModal{{ $leave->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="processModal{{ $leave->id }}Label">Process Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('process.Leave', $leave->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p>Please select a status for this request:</p>
                                <select class="form-control" name="status_type" {{ $leave->released_timestamp ? 'disabled' : '' }}>
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
            --}}



            {{-- Reject Modal on each Request
            <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModal{{ $leave->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModal{{ $leave->id }}Label">Reject Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('reject.Leave', $leave->id) }}" method="POST">
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
            --}}

            @empty
            <td colspan="12">No Received Data to display.</td>

            @endforelse
        </tbody>
    </table>
</div> -->