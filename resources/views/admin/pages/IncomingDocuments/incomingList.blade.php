@extends('admin.master')

@section('content')
    <section>
        <div class="shadow p-3 d-flex justify-content-between align-items-center">
            <h6 class="text-uppercase" style="color:#AE7DAC;">Incoming Document Requests</h6>
        </div><br>

        <div class="w-100">
            <div class="card mb-4">
                <div class="card-body">
                    @if($incomingDocuments->isEmpty())
                        <p>No incoming documents to display.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Employee Name</th>
                                    <th class="text-center align-middle">Emp No</th>
                                    <th class="text-center align-middle">Department</th>
                                    <th class="text-center align-middle">Actions</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle">Update Status</th>
                                    <th class="text-center align-middle">Remarks</th>
                                    <th class="text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incomingDocuments as $document)
                                    <tr>
                                        <td class="text-center align-middle">{{ $document->name }}</td>
                                        <td class="text-center align-middle">{{ $document->empno }}</td>
                                        <td class="text-center align-middle">{{ $document->department }}</td>
                                        <td class="text-center align-middle">{{ $document->actions_id }}</td>

                                        <!-- Status with dynamic badge -->
                                        <td class="text-center align-middle">
                                            @php
                                                if($document->status == 'pending') {
                                                    echo '<span class="badge badge-pill bg-warning">Pending</span>';
                                                } elseif ($document->status == 'approve') {
                                                    echo '<span class="badge badge-pill bg-success">Approved</span>';
                                                } else {
                                                    echo '<span class="badge badge-pill bg-danger">Rejected</span>';
                                                }
                                            @endphp
                                        </td>

                                        <!-- Update Status Dropdown -->
                                        <td class="text-center align-middle">
                                            <form action="{{ route('incoming.document.updateStatus', $document->id) }}" method="POST" id="statusForm_{{ $document->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" id="updatestatus_{{ $document->id }}" class="form-control text-center" required onchange="this.form.submit()">
                                                    <option disabled selected >Pending</option>
                                                    <option value="approve" @if($document->status == 'approve') selected @endif>Approve</option>
                                                    <option value="reject" @if($document->status == 'reject') selected @endif>Reject</option>
                                                </select>
                                            </form>
                                        </td>                                        

                                        <td class="text-center align-middle">{{ $document->remarks }}</td>

                                        <td class="text-center align-middle">
                                            <div>
                                                <form action="{{ route('incoming.document.delete',$document->id) }}" method="POST" style="display:inline;">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{ $incomingDocuments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
