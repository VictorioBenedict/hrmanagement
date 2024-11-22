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
    <!--Section: Form Design Block-->
    <section>
        <div class="shadow p-3 d-flex justify-content-between align-items-center  ">
            <h6 class="text-uppercase" style="color:#AE7DAC;">Document Type</h6>
            <button class="btn btn-success p-2 px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#createDocumentModal">
                <i class="fa fa-plus"></i> Create New Document
            </button>
        </div>
    </section>    

    <br>


 
    <table class="table align-middle  mb-3 text-center">
        <thead class="bg-light">
            <tr>
                <th>DC NO</th>
                <th>Document Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documentTypes as $key => $item)
            <tr>
                <td>{{ str_pad($key + 1, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->document_type }}</td>
                <td>
                    <a class="btn btn-success rounded-pill fw-bold text-white"
                    href="javascript:void(0)" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editDocumentModal-{{ $item->id }}">
                     Edit
                 </a>
                    <!-- Delete Button triggers the modal -->
                    <button class="btn btn-danger rounded-pill fw-bold text-white" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal-{{ $item->id }}">
                        Delete
                    </button><div class="d-flex justify-content-end  mb-3">
                        <!-- Button to trigger modal -->
                  
                    </div>
                </td>
            </tr>
            <div class="modal fade" id="editDocumentModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDocumentModalLabel">Edit Document Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form to update document type -->
                            <form action="{{ route('document.documentType.update', $item->id) }}" method="post">
                                @csrf
                                @method('PUT') <!-- Method override for PUT request -->
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="form-outline">
                                            <label class="form-label mt-2" for="document_type">Document Type</label>
                                            <input type="text" class="form-control" name="document_type" value="{{ $item->document_type }}" required>
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

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the document type "<strong>{{ $item->document_type }}</strong>"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('document.documentType.delete', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <td colspan="3">No Data to display.</td>
            @endforelse
        </tbody>
    </table>
    <!-- Create New Document Modal -->
<div class="modal fade" id="createDocumentModal" tabindex="-1" aria-labelledby="createDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDocumentModalLabel">Create New Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to create a new document type -->
                <form action="{{ route('document.documentType.store') }}" method="post">
                    @csrf
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label mt-2" for="document_type">Document Type</label>
                                <input placeholder="Enter Document Type" class="form-control" name="document_type" required>
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

<!-- Edit Document Modal -->






</div>
@endsection
