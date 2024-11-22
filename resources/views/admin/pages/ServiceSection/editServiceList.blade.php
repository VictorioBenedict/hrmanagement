@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Update Service</h6>
    <div>
        <a href="{{ route('list.service') }}" class="btn btn-success p-2 text-lg rounded-pill">Services List</a>
    </div>
</div>

<div class="container my-5 py-5">

    <!-- Section: Form Design Block -->
    <section>
        <div class="w-75 mx-auto">
            <div class="card mb-3">
                <div class="card-header py-3">
                    <h5 class="mb-0 text-font text-uppercase">Edit Service Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('serviceUpdate', $service->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="row mb-3">
                            <!-- Service Name -->
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="service_name">Service Name</label>
                                    <input required value="{{ $service->service_name }}" placeholder="Enter service Name" 
                                           type="text" id="service_name" name="service_name" class="form-control" />
                                </div>
                                <div class="mt-2">
                                    @error('service_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Service Title -->
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="description">Service Title</label>
                                    <input required value="{{ $service->description }}" placeholder="Service Title" 
                                           type="text" id="description" name="description" class="form-control" />
                                </div>
                                <div class="mt-2">
                                    @error('description')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="service_image">Image</label>
                                    <input type="file" id="service_image" name="service_image" class="form-control" />
                                </div>
                                <div class="mt-2">
                                    @error('service_image')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Display Existing Image if Available -->
                                @if($service->service_image)
                                    <div class="mt-3">
                                        <label class="fw-bold">Current Image:</label>
                                        <img src="{{ url('/uploads/' . $service->service_image) }}" class="img-fluid" 
                                             style="max-width: 150px; height: auto; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Service Details -->
                        <div>
                            <label class="form-label mt-2 fw-bold" for="details">Service Details</label>
                            <textarea id="details" name="details" class="form-control" cols="30" rows="10">{{ $service->details }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center w-25 mx-auto mt-3">
                            <button type="submit" class="btn btn-success p-2 text-lg rounded-pill col-md-10">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
