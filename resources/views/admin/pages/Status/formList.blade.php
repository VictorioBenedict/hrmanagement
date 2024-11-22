@extends('admin.master')

@section('content')

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

<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase" style="color:#AE7DAC;">
        {{ request()->is('Status/StatusType') ? 'Status Type List' : 'Status Type Archived List' }}
    </h6>
    <a href="#" class="btn btn-success p-2 ms-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fa-solid fa-plus me-1"></i>Create New Status Type
    </a>
</div>
<br>

<div>
    <table class="table align-middle mb-4 text-center">
        <thead class="bg-light">
            <tr>
                <th>ST NO</th>
                <th>Status Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($statusTypes as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->status_type }}</td>
                    <td>
                        <!-- Edit Button to Trigger the Edit Modal -->
                        <button class="btn btn-success rounded-pill fw-bold text-white" data-bs-toggle="modal" data-bs-target="#editModal-{{ $item->id }}">
                            Edit
                        </button>
                        
                        <!-- Delete Button for Deleting Status Type -->
                        <a class="btn btn-danger rounded-pill fw-bold text-white" href="#" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal" 
                            data-action="{{ route('status.delete', $item->id) }}">Delete</a>
                    </td>
                </tr>

                <!-- Edit Modal for each Status Type -->
                <div class="modal fade" id="editModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Status Type</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('status.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-4">
                                        <div class="col">
                                            <div class="form-outline">
                                                <label class="form-label mt-2" for="status_type">Status Type</label>
                                                <input type="text" id="status_type" class="form-control" name="status_type" value="{{ $item->status_type }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-warning p-2 px-3 rounded-pill">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="3">No Data to display.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create Status Type Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create New Status Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('status.store') }}" method="post">
                    @csrf
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label mt-2" for="status_type">Status Type</label>
                                <input placeholder="Enter Status Type" class="form-control" name="status_type" required>
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this status type?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="" style="display:inline;">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Delete Button Logic
    const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = document.getElementById('deleteForm');
            form.action = button.getAttribute('data-action');
        });
    });
</script>

@endsection
