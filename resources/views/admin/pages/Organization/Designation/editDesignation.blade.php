@extends('admin.master')

@section('content')

<!-- Section: Edit Form -->
<style>
    body {
        color: #F0F1F7;
    }
    
    .form-outline .form-label {
        font-weight: bold;
    }
    
    .form-control {
        background-color: #D1D6E8;
        border: 1px solid #DF7A30;
        border-radius: 0.5rem;
        color: #DF7A30;
    }
    
    .btn-info {
        background-color: #DF7A30;
        color: white;
    }
    
    .btn-info:hover {
        background-color: #3D5A5C;
    }
    
    .text-center {
        text-align: center;
    }
</style>

<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase" style="color: white;">Edit Position</h6>
    <div>
        <a href="{{ route('organization.designationList') }}" class="btn btn-success p-2 text-lg rounded-pill">
            <i class="fa-regular fa-eye me-1"></i>Position List
        </a>
    </div>
</div><br>

<div class="d-flex justify-content-center">
    <div class="text-left w-50">
        <div class="card mb-4">
            <div class="card-header py-3">
                <h5 class="text-uppercase">Update Position</h5>
            </div>
            <div class="card-body">
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Position Name Input -->
                    <div class="form-outline mb-4">
                        <label class="form-label" for="designation_name">Position Name</label>
                        <input type="text" 
                               class="form-control" 
                               name="designation_name" 
                               id="designation_name" 
                               value="{{ old('designation_name', $designation->designation_name) }}" 
                               required>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-info p-2 rounded">Update Position</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
