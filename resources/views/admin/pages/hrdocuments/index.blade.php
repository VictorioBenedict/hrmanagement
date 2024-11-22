@extends('admin.master')

@section('content')
<style>
    body {
        color: #F0F1F7; /* High contrast text */
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
        background-color: #3D5A5C; /* Header background */
        color: white; /* Header text */
    }

    .table tbody tr:hover {
        background-color: #D1D6E8; /* Row hover background */
    }

    .table thead {
        background-color: #DF7A30; /* Table header background */
        color: #F0F1F7; /* Table header text */
    }

    .card-header {
        background-color: #DF7A30;
        color: #F0F1F7;
    }

    .input-group-text, .input-group .form-control {
        background-color: #D1D6E8;
        color: #DF7A30;
    }

    .modal-content, .modal-header {
        background-color: #DF7A30;
        color: #F0F1F7;
    }

    .pagination .page-item.active .page-link {
        background-color: #5A6D7B;
        color: white;
    }

    .pagination .page-link {
        color: black;
        border: 1px solid #D1D6E8;
    }

    .pagination .page-link:hover {
        background-color: #5A6D7B;
        color: black;
    }

    .taka {
        background-color: #5A6D7B;
        color: white;
    }
</style>

<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase" style="color:#AE7DAC;">Documents</h6>
    <div class="d-flex flex-column align-items-center">
        <a href="#" class="btn btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
            <i class="fa-solid fa-plus me-1"></i> Upload New Document
        </a>
    </div>
    <a href="{{ route('archived-documents') }}" class="btn text-white" style="background-color: #3D5A5C;">
        Archived Documents
    </a>
</div>


<br>

<table class="table text-center">
    <thead>
        <tr>
            <th>ID</th>
            <th>Document Name</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if($documents->count() > 0)
            @foreach($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>{{ $document->title }}</td>
                    <td>{{ strtoupper($document->file_type) }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateDocumentModal{{ $document->id }}">Edit</button>

                        <!-- Archive Button -->
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmArchiveModal{{ $document->id }}">Archive</button>

                        <!-- View Button -->
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-success">View</a>
                    </td>
                </tr>

                <!-- Archive Confirmation Modal -->
                <div class="modal fade" id="confirmArchiveModal{{ $document->id }}" tabindex="-1" aria-labelledby="confirmArchiveLabel{{ $document->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmArchiveLabel{{ $document->id }}">Confirm Action</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">Are you sure you want to move this document to archives?</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('hrdocuments.destroy', $document->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Move to Archives</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Document Modal -->
                <div class="modal fade" id="updateDocumentModal{{ $document->id }}" tabindex="-1" aria-labelledby="updateDocumentModalLabel{{ $document->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateDocumentModalLabel{{ $document->id }}">Update Document</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('hrdocuments.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Document Name</label>
                                        <input type="text" name="title" value="{{ old('title', $document->title) }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="file">Upload New Document (optional)</label>
                                        <input type="file" name="file" class="form-control" accept=".pdf,.docx,.xlsx">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Update Document</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-danger">No Documents Found</td>
            </tr>
        @endif
    </tbody>
</table>

<div class="w-25 mx-auto mt-4">
    {{ $documents->withQueryString()->links() }}
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Upload New Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hrdocuments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Document Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload Document</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="file_type" class="form-label">Document Type</label>
                        <select name="file_type" class="form-control" required>
                            <option value="pdf">PDF</option>
                            <option value="docx">DOCX</option>
                            <option value="xlsx">XLSX</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Upload Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
