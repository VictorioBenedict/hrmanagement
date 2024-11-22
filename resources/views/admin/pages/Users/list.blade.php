@extends('admin.master')

@section('content')



<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="text-uppercase" style="color:black;">User's List</h6>
        </div>

        <!-- Role Filter Buttons (Include search in query parameters) -->
        <a href="{{ route('users.list', ['role' => 'All', 'search' => request()->search]) }}" class="btn {{ $role == 'All' ? 'btn-info' : 'btn-light' }} mx-1">All Users</a>
        <a href="{{ route('users.list', ['role' => 'Employee', 'search' => request()->search]) }}" class="btn {{ $role == 'Employee' ? 'btn-success' : 'btn-light' }} mx-1">Employees</a>
        <a href="{{ route('users.list', ['role' => 'Admin', 'search' => request()->search]) }}" class="btn {{ $role == 'Admin' ? 'btn-primary' : 'btn-light' }} mx-1">Admin</a>
        <a href="{{ route('users.list', ['role' => 'System Admin', 'search' => request()->search]) }}" class="btn {{ $role == 'System Admin' ? 'btn-secondary' : 'btn-light' }} mx-1">System Admin</a>

        <!-- Search Form (Include role and search term) -->
        <div class="input-group rounded w-25">
            <form action="{{ route('users.list') }}" method="get" class="w-100">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search..." name="search" value="{{ request()->search }}" aria-label="Search Employee">
                    <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- User Table -->
    <div class="shadow p-3">
        <table class="table align-middle text-center">
            <thead class="bg-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a class="btn btn-warning" href="{{ route('edit', $user->id) }}">Edit</a>
                            <a class="btn btn-primary" href="{{ route('users.profile.view', $user->id) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No users to display.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>

</section>



@endsection
