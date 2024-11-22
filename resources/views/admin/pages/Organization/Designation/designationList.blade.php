@extends('admin.master')

@section('content')

<!-- Section: Form Design Block -->
<style>
    /* Styling for the page and components */
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
        background-color: #3D5A5C; /* Bootstrap primary color */
        color: white; /* White text on primary background */
    }

    .table tbody tr:hover {
        background-color: #D1D6E8; /* Light gray on hover */
    }

    .table thead {
        background-color: #DF7A30; /* Background for table header */
        color: #F0F1F7; /* White text on header */
    }

    .toggle-visibility {
        cursor: pointer; /* Pointer cursor for checkboxes */
    }

    .card-header {
        background-color: #DF7A30; /* Dark background for cards */
        color: #F0F1F7; /* White text */
    }

    .input-group-text {
        background-color: white; /* Light background for search box */
        color: black; /* Dark text color */
    }

    .input-group .form-control {
        background-color: #D1D6E8; /* Light background for input */
        color: #DF7A30; /* Dark text color */
    }

    .modal-content {
        background-color: #DF7A30; /* Darker background for modal */
        color: #F0F1F7; /* White text */
    }

    .modal-header {
        background-color: #3D5A5C !important; /* Dark background for modal header */
        color: white !important; /* White text */
    }

    .modal-footer button {
        color: white; /* White text */
    }

    .pagination .page-item.disabled .page-link {
        color: black !important; /* Light gray color for disabled buttons */
        pointer-events: none; /* Prevent clicks */
    }

    .pagination .page-item.active .page-link {
        background-color: #5A6D7B !important;
        color: white !important; /* Dark color for page numbers */
    }

    .pagination .page-link {
        color: black !important; /* Dark color for page numbers */
        border: 1px solid #D1D6E8; /* Light border for links */
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #5A6D7B !important;
        color: black !important;
    }

    .pagination .page-link i {
        font-size: 1.2rem;
        color: black !important; /* Match the text color */
    }

    .taka {
        background-color: #5A6D7B !important;
        color: white !important;
    }
</style>

<!-- Header Section -->
<div class="shadow p-3 d-flex justify-content-between align-items-center" style="background-color: #DF7A30;">
    <h6 class="text-uppercase text-black">
        Position List
    </h6>

    <div class="d-flex align-items-center"> 
        <a href="#" class="btn btn-success p-2 ms-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#addPositionModal">
            <i class="fa-solid fa-plus me-1"></i>Create Position
        </a>
    </div>
</div><br>



<!-- Table Section -->
<div class="row justify-content-center">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table align-middle text-center table-bordered" style="table-layout: auto;">
                <thead class="bg-light">
                    <tr>
                        <th>POS NO</th>
                        <th>Position Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($designations as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->designation_name }}</td>
                            <td>
                                <div class="btn-group gap-2">
                                    <!-- Edit Button - Opens Modal -->
                                    <a class="btn btn-success rounded-pill fw-bold text-white"
                                       href="#" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#updateModal{{ $item->id }}"
                                       data-id="{{ $item->id }}" 
                                       data-designation="{{ $item->designation_name }}">
                                       Edit
                                    </a>
                                    <!-- Delete Button -->
                                    <a class="btn btn-danger rounded-pill fw-bold text-white"
                                       href="#" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#deleteModal" 
                                       data-action="{{ route('designation.delete', $item->id) }}">Delete</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted fst-italic">No Data to display.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination Section -->
<div class="w-25 mx-auto mt-4">
    {{ $designations->appends(['search' => $searchQuery])->links() }}
</div>

<!-- Update Position Modal -->
@foreach($designations as $item)
<div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" aria-labelledby="updateModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel{{ $item->id }}">Update Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('designations.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Position Name Input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="designation_name">Position Name</label>
                        <input type="text" 
                               class="form-control" 
                               name="designation_name" 
                               id="designation_name{{ $item->id }}" 
                               value="{{ old('designation_name', $item->designation_name) }}" 
                               required>
                    </div>
                    
                    <!-- Submit Button -->
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
@endforeach

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this position?</p>
            </div>
            <div class="modal-footer">
                <a id="deleteButton" class="btn btn-danger" href="#">Delete</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPositionModalLabel">Create New Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Create Position Form -->
                <form action="{{ route('organization.designation.store') }}" method="POST">
                    @csrf
                    
                    <!-- Position Name Input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="designation_name">Position Name</label>
                        <input type="text" class="form-control" name="designation_name" id="designation_name" required>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-danger p-2 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success p-2 px-3">Create Position</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script Section -->
<script>
    // Get all edit buttons and handle the modal population dynamically
    const editButtons = document.querySelectorAll('[data-bs-target^="#updateModal"]');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = button.getAttribute('data-id');
            const designation = button.getAttribute('data-designation');
            
            // Populate the modal form fields with the existing data
            document.getElementById('designation_name' + id).value = designation;
        });
    });

    // Get all delete buttons
    const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const deleteUrl = button.getAttribute('data-action');
            const deleteButton = document.getElementById('deleteButton');
            deleteButton.setAttribute('href', deleteUrl); // Set the link to delete
        });
    });
</script>

<!-- External Libraries -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
