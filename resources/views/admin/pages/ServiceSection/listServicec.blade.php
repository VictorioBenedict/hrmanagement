@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Service List</h6>
    <div>
        <a href="{{ route('service.form') }}" class="btn btn-success p-2 text-lg rounded-pill">
            <i class="fa-solid fa-plus me-2"></i>Create New Service
        </a>
    </div>
</div>

<div class="container my-5 py-5">
    <table class="table align-middle mb-3 text-center table-bordered table-hover">
        <thead class="bg-light">
            <tr>
                <th>#</th>
                <th>Service Name</th>
                <th>Service Title</th>
                <th>Service Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $key => $item)
            <tr>
                <td>
                    <div>
                        <p class="fw-bold mb-1">{{ $key + 1 }}</p>
                    </div>
                </td>
                <td>{{ $item->service_name }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    <!-- Service image with fixed size for uniformity -->
                    <img class="avatar p-1" src="{{ url('/uploads//' . $item->service_image) }}" alt=""
                        style="max-width: 50px; max-height: 50px; object-fit: cover;">
                </td>
                <td>
                    <!-- Action Buttons -->
                    <a class="btn btn-warning rounded-pill" target="_blank" href="{{ route('services') }}">
                        <i class="fa-regular fa-eye"></i>
                    </a>
                    <a class="btn btn-success rounded-pill" href="{{ route('serviceEdit', $item->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a class="btn btn-danger rounded-pill" href="{{ route('serviceDelete', $item->id) }}">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination (Uncomment if pagination is enabled) -->
    <div class="w-25 mx-auto">
        {{-- {{ $services->links() }} --}}
    </div>
</div>
@endsection
