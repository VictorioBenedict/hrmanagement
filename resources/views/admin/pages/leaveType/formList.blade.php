@extends('admin.master')

@section('content')

<!-- Section: Form Design Block -->
<style>
  body {
        color: #F0F1F7;
        margin-bottom: 3%;
    }

    .table {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .table th {
        background-color: #3D5A5C;
        color: white;
    }

    .table tbody tr:hover {
        background-color: #D1D6E8;
    }

    .table thead {
        background-color: #DF7A30;
        color: #F0F1F7;
    }

    .modal-content {
        background-color: #DF7A30;
        color: #F0F1F7;
    }

    .modal-header {
        background-color: #3D5A5C;
        color: white;
    }

    .modal-footer button {
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        color: black !important;
        pointer-events: none;
    }

    .pagination .page-item.active .page-link {
        background-color: #5A6D7B !important;
        color: white !important;
    }

    .pagination .page-link {
        color: black !important;
        border: 1px solid #D1D6E8;
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #5A6D7B !important;
        color: black !important;
    }

    .pagination .page-link i {
        font-size: 1.2rem;
        color: black !important;
    }

    .taka {
        background-color: #5A6D7B !important;
        color: white !important;
    }
</style>

<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center ">
        <h4 class="justify-start" style="color:#AE7DAC;">Leave Type</h4>
        <div class="d-flex justify-center">
            <button type="button" class="btn btn-success p-2 text-lg rounded-pill" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa fa-plus"></i> Add New Leave Type
            </button>
        </div>
    </div><br>

    <div class="justify-content-center align-content-center">
        <table class="table align-middle mb-3 text-center">
            <thead class="bg-light">
                <tr>
                    <th>LV NO</th>
                    <th>Leave Type</th>
                    <th>Leave Days</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaveTypes as $key => $item)
                    <tr>
                        <td>{{ str_pad($key + 1, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $item->leave_type_id }}</td>
                        <td>{{ $item->leave_days }}</td>
                        <td>
                            <button class="btn btn-success rounded-pill fw-bold text-white"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $item->id }}">
                                Edit
                            </button>
                            <!-- Delete Button triggers the modal -->
                            <button class="btn btn-danger rounded-pill fw-bold text-white"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal-{{ $item->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Leave Type</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('leave.leaveType.update', $item->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-outline">
                                                    <label class="form-label mt-2" for="form11Example1">Leave Type</label>
                                                    <input value="{{ $item->leave_type_id }}" placeholder="Enter Leave Type"
                                                        class="form-control" name="leave_type_id" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-outline">
                                                    <label class="form-label mt-2" for="form11Example1">Leave Days</label>
                                                    <input value="{{ $item->leave_days }}" placeholder="Number of Days"
                                                        class="form-control" name="leave_days">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-center gap-2">
                                            <div class="text-center">
                                                <button type="button" class="btn btn-danger p-2 px-3" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-warning p-2 px-3">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete Leave Type</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-3">Are you sure you want to delete this leave type? This action cannot be undone.</p>
                                    <form action="{{ route('leave.leaveType.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-danger p-2 px-3 rounded-pill">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="4">No Data to display.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create New Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('leave.leaveType.store') }}" method="post">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label mt-2" for="leave_type_id">Leave Type</label>
                                    <input placeholder="Enter Leave Type" class="form-control" name="leave_type_id" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label mt-2" for="leave_days">Leave Days (Optional)</label>
                                    <input placeholder="Number of Days" type="number" class="form-control" name="leave_days">
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success p-2 px-3 rounded-pill">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
