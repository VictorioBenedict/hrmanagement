@extends('admin.master')
@section('content')

<!-- Section: Form Design Block -->
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
    <div class="shadow p-3 d-flex justify-content-between align-items-center">
        <h6 class="text-uppercase">Action Type</h6>
        <div class="row">
            @if($searchQuery)
                <p class="text-uppercase">You searched: {{ $searchQuery ?? null }}</p>
                <a href="/Leave/LeaveType" class="btn btn-danger p-2 px-3 rounded-pill col-1">
                    <i class="fa-solid fa-xmark"></i> Clear Search
                </a>
            @endif
        </div>
    </div><br>

    <section>
        <div class="d-flex gap-5 justify-content-center align-content-center ">

            <div class="text-left w-25 ">
                <div class="card  mb-3">
                    <div class="card-header py-3">
                        <h5 class="text-uppercase">New Action</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('leave.leaveType.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-outline">
                                        <label class="form-label mt-2" for="form11Example1">Action Type</label>
                                        <input placeholder="Enter Action Type" class="form-control" name="leave_type_id" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center w-25 mx-auto">
                                <button type="submit" class="btn btn-success p-2 px-3 rounded-pill">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="w-75 card">
                <div class="card-body">
                    <form action="{{ route('leaveFormDocumentTypeList') }}" method="get">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search..." name="search">
                            <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <table class="table align-middle mb-3 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>ACN NO</th>
                                <th>Action Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaveTypes as $key => $item)
                                <tr>
                                    <td>{{ str_pad($key + 1, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $item->leave_type_id }}</td>
                                    <td>
                                        <a class="btn btn-success rounded-pill fw-bold text-white" href="{{ route('leave.leaveType.edit', $item->id) }}">Edit</a>
                                        <button class="btn btn-danger rounded-pill fw-bold text-white" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this action type?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('leave.leaveType.delete', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4">No Data to display.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="w-25 mx-auto mt-4">
                        {{ $leaveTypes->appends(['search' => $searchQuery])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

@endsection
