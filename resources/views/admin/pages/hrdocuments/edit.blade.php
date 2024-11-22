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
<div class="shadow p-3 d-flex justify-content-between align-items-center  ">
    <h6 class="text-uppercases"style="color:;">Documents</h6>
</div>
<br>
    <div class="container" Style="background-color: white !important; /* Dark background for modal header */
    color: black !important; padding:10px;">
        <h1 class="mb-5">Edit Document</h1>
        <form action="{{ route('hrdocuments.update', $hrdocument->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title">Document Name</label>
                <input type="text" name="title" value="{{ old('title', $hrdocument->title) }}" class="form-control" required>
            </div>
            
            <div class="form-group mt-3">
                <label for="file">Current File: <a href="{{ Storage::url($hrdocument->file_path) }}" target="_blank">{{str_replace('public/hr/documents/', '', $hrdocument->file_path)}}</a></label>
                <br>
                <label for="file" class="mt-2">Upload New Document (optional)</label>
                <input type="file" name="file" class="form-control" 
                accept=".pdf,.csv,.doc,.docx,.xls,.xlsx">
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update Document</button>
        </form>
    </div>
</div>
@endsection
