@extends('admin.master')

@section('content')

<style>
    body {/
        color: #F0F1F7;/* White text for high contrast */
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
        color: white !important; /* White text */
    }

    .pagination .page-item.disabled .page-link {
        color: black !important ;/* Light gray color for disabled buttons */
        pointer-events: none; /* Prevent clicks */
    }

    .pagination .page-item.active .page-link {  
        background-color: #5A6D7B !important;;
        color: white !important; /* Dark color for page numbers */
    }

    .pagination .page-link {
        color: black !important; /* Dark color for page numbers */
        border: 1px solid #D1D6E8; /* Light border for links */
        padding: 0.5rem 0.75rem;
    }

    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #5A6D7B !important;;
        color:black !important;
    }

    .pagination .page-link i {
        font-size: 1.2rem;
        color:black !important /* Match the text color */
    }
   .taka{
        background-color: #5A6D7B !important;;
        color:white !important;

    }
</style>
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercases">Archived Documents</h6>
    <a href="{{route('admin.pages.hrdocuments.index')}}" class="btn btn-success p-2 ms-2 rounded-pill" >
        </i>Documents
    </a>
</div><br>
    <table class="table align-middle text-center w-100  "><div class="input-group">

        </div>
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>Document Name</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(count($documents) > 0)
                @foreach($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>{{ $document->title }}</td>
                    <td>{{ strtoupper($document->file_type) }}</td>
                    <td>
                        <button class="btn btn-success text-light" data-toggle="modal" data-target="#confirmDeleteModal{{ $document->id }}">
                            Restore
                        </button>

                        <button class="btn btn-danger text-light" data-toggle="modal" data-target="#confirmPermanentDeleteModal{{ $document->id }}">
                            Delete
                        </button>

                        <div class="modal fade" id="confirmDeleteModal{{ $document->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $document->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel{{ $document->id }}">Confirm Restore</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to restore this document?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        <form action="{{ route('hrdocuments.restore', $document->id) }}" method="POST" id="deleteForm{{ $document->id }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success text-light">Restore</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="confirmPermanentDeleteModal{{ $document->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmPermanentDeleteModal{{ $document->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmPermanentDeleteModalLabel{{ $document->id }}">Confirm Permanent Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to permanently delete this document? This cannot be undone!
                                    </div>
                                    <div class="modal-footer">
                                  
                                        <form action="{{ route('hrdocuments.permanentDelete', $document->id) }}" method="POST" id="deletePermanentForm{{ $document->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger text-light">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-info">View</a>
                    </td>
                </tr>
                @endforeach
            @else
                <tr class="text-danger">
                    <td colspan="4">No Documents Found</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="w-25 mx-auto mt-4">
        {{ $documents->withQueryString()->links() }}
    </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</div>
@endsection
