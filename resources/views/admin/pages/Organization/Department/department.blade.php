@extends('admin.master')

@section('content')

<!-- Section: Form Design Block -->
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

<!-- Header Section -->
<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center bg-white text-black" style="background-color: #DF7A30;">
        <h6 class="text-black">
            {{ request()->has('archive') ? 'Department Archived List' : 'Department List' }}
        </h6>
        <a href="#" class="btn btn-success p-2 ms-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
            <i class="fa-solid fa-plus me-1"></i>Create New Department
        </a>
        <a href="{{ !request()->is('Organization/department/archive') ? route('organization.list.archive') : route('organization.department') }}" 
           class="btn btn-secondary p-2 ms-2 rounded-pill" style="font-size: 0.9rem;">
            <i class="fa-solid fa-archive"></i>
            {{ !request()->is('Organization/department/archive') ? 'View Archived Department' : 'View Departments' }}
        </a>
    </div>
</section>
<br>
<section>    
        <div class="{{ request()->is('Organization/department') ? 'w-100' : 'w-100' }}">
            <table class="table align-middle mb-4 text-center">
                <thead class="bg-light">
                    <tr>
                        <th>DEP NO</th>
                        <th>Department Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->department_name }}</td>
                            <td>
                                @if(request()->is('Organization/department'))
                                    <button class="btn btn-success rounded-pill fw-bold text-white" 
                                            data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger rounded-pill fw-bold text-white" 
                                        onclick="openArchiveModal('{{ $item->id }}', '{{ $item->department_name }}')">
                                    Archive
                                    </button>
                                    
                                @else
                                    <a class="btn btn-success rounded-pill fw-bold text-white"
                                       href="{{ route('Organization.restore', $item->id) }}">Restore</a>
                                    <button class="btn btn-danger rounded-pill fw-bold text-white" 
                                            onclick="openDeleteModal('{{ $item->id }}', '{{ $item->department_name }}', 'Delete')">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                        <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to archive the department <strong id="archiveDepartmentName"></strong>?
                                    </div>
                                    <div class="modal-footer"> 
                                        <a id="archiveLink" href="" class="btn btn-danger"><strong>Archive</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Modal -->
                       <!-- Update Modal -->
                    <div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" aria-labelledby="updateModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateModalLabel{{ $item->id }}">Edit Department</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="updateForm{{ $item->id }}" action="{{ route('Organization.update', $item->id) }}" method="post">
                                        @csrf
                                        @method('put')

                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-outline">
                                                    <label class="form-label mt-2" for="department_id">Department ID</label>
                                                    <input value="{{ $item->department_id }}" placeholder="Enter ID"
                                                        class="form-control" name="department_id" id="department_id" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="form-outline">
                                                    <label class="form-label mt-2" for="department_name">Department Name</label>
                                                    <input value="{{ $item->department_name }}" placeholder="Enter Name"
                                                        class="form-control" name="department_name" id="department_name" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-center gap-2">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                        <tr>
                            <td colspan="3">No Data to display.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-labelledby="createDepartmentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createDepartmentModalLabel">Create New Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Create Department Form -->
                            <form action="{{ route('organization.department.store') }}" method="post">
                                @csrf
                                
                                <!-- Department Name Input -->
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="department_name">Department Name</label>
                                    <input type="text" class="form-control" name="department_name" id="department_name" required placeholder="Enter Department Name">
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-danger p-2 px-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success p-2 px-3">Create Department</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <ul class="pagination">
                    <li class="page-item {{ $departments->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $departments->previousPageUrl() }}" aria-label="Previous">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>
                    @foreach ($departments->getUrlRange(1, $departments->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $departments->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $departments->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $departments->nextPageUrl() }}" aria-label="Next">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm <strong id="methodHeader"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to <strong id="method"></strong> the department <strong id="departmentName"></strong>?
            </div>
            <div class="modal-footer"> 
                <a id="deleteLink" href="" class="btn btn-danger"><strong id="methodBtn"></strong></a>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(id, name, method) {
    var deleteModal = document.getElementById('deleteModal');
    var deleteLink = deleteModal.querySelector('#deleteLink');
    if (method == 'Archive') {
        deleteLink.href = '{{ url("Organization/archive") }}' + '/' + id;
    } else {
        deleteLink.href = '{{ url("Organization/delete") }}' + '/' + id;
    }

    var departmentName = deleteModal.querySelector('#departmentName');
    departmentName.textContent = name;

    var methodName = deleteModal.querySelector('#method');
    methodName.textContent = method;
    var methodNameBtn = deleteModal.querySelector('#methodBtn');
    methodNameBtn.textContent = method;
    var methodNameHeader = deleteModal.querySelector('#methodHeader');
    methodNameHeader.textContent = method;

    var modal = new bootstrap.Modal(deleteModal);
    modal.show();
}
    function openArchiveModal(id, name) {
        var archiveModal = document.getElementById('archiveModal');
        var archiveLink = archiveModal.querySelector('#archiveLink');
        archiveLink.href = '{{ url("Organization/archive") }}' + '/' + id;

        var departmentName = archiveModal.querySelector('#archiveDepartmentName');
        departmentName.textContent = name;

        var modal = new bootstrap.Modal(archiveModal);
        modal.show();
    }
</script>

<!-- External Stylesheets and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

@endsection
