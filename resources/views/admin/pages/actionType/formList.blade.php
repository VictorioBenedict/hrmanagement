@extends('admin.master')

@section('content')

    <!--Section: Form Design Block-->
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


        <div class="shadow p-3 d-flex justify-content-between align-items-center ">
            <h6 class="text-uppercase" style="color:#AE7DAC;">Action Type</h6>
            <button type="button" class="btn btn-success p-2 px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#createModal">
                Create New Action Type
            </button>
            
        </div>
        <br>

        <!-- Form and Table Container -->
        <div class="d-flex gap-5 justify-content-center align-items-center">

        

            <!-- Action Type Table -->
    
                <div class="card-body">
        
                    <!-- Table -->
                    <table class="table align-middle mb-3 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>ACN NO</th>
                                <th>Action Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($actionTypes as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->action_type_id }}</td>
                                    <td>
                                        <button class="btn btn-success rounded-pill fw-bold text-white" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal" 
                                            data-action-type-id="{{ $item->id }}"
                                            data-action-type="{{ $item->action_type_id }}">
                                        Edit
                                        </button>
                                        <button class="btn btn-danger rounded-pill fw-bold text-white"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-delete-url="{{ route('action.actionType.delete', $item->id) }}">
                                        Delete
                                       </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">No Data to display.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="w-25 mx-auto mt-4">
                        {{ $actionTypes->appends(['search' => $searchQuery])->links() }}
                    </div>
                </div>
         

        </div>
        
  
    <!-- Modal for Editing Action Type -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Action Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to edit Action Type -->
                <form id="editActionForm" action="{{ route('action.actionType.update', ':id') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label mt-2" for="action_type_id">Action Type</label>
                                <input type="text" class="form-control" name="action_type_id" id="action_type_id" placeholder="Enter Action Type" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-danger p-2 px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success p-2 px-3 rounded-pill">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Creating Action Type -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create New Action Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Create Action Type -->
                <form action="{{ route('action.actionType.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label mt-2" for="action_type_id">Action Type</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="action_type_id" 
                                    id="action_type_id" 
                                    placeholder="Enter Action Type" 
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-danger p-2 px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success p-2 px-3 rounded-pill">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Deleting Action Type -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Action Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Action Type?</p>
            </div>
            <div class="modal-footer">
                <!-- Delete Form (to submit the deletion request) -->
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger p-2 px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success p-2 px-3 rounded-pill">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.querySelectorAll('.btn-success[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function () {
            // Get data attributes from the button
            const actionTypeId = this.getAttribute('data-action-type-id');
            const actionType = this.getAttribute('data-action-type');

            // Set the form action URL with the correct action ID
            const formAction = document.getElementById('editActionForm');
            formAction.action = formAction.action.replace(':id', actionTypeId);

            // Set the value of the input field
            document.getElementById('action_type_id').value = actionType;
        });
    });
    document.querySelectorAll('.btn-danger[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function () {
            // Get the URL to delete the action type from the data attribute
            const deleteUrl = this.getAttribute('data-delete-url');

            // Set the form action URL for the deletion
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = deleteUrl;
        });
    });
</script>

@endsection
