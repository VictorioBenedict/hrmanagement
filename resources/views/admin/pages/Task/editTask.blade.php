@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Edit Task List</h6>
</div>

<div class="container my-5 py-5">
    <!-- Section: Form Design Block -->
    <section>
        <div class="d-flex justify-content-center">

            {{-- Task Update Form --}}
            <div class="w-50">
                <div class="card mb-3">
                    <div class="card-header py-3">
                        <h5 class="text-uppercase">Update Task</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('updateTask', $task->id) }}" method="post">
                            @csrf
                            @method('put')

                            <!-- Task Name Input -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-outline">
                                        <label class="form-label mt-2" for="task_name">Task Name</label>
                                        <input 
                                            value="{{ $task->task_name }}" 
                                            placeholder="Enter Task Name"
                                            class="form-control" 
                                            name="task_name" 
                                            id="task_name" 
                                            required>
                                    </div>
                                </div>
                            </div>

                            <!-- Task Description Input -->
                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-outline">
                                        <label class="form-label mt-2" for="task_description">Task Description</label>
                                        <textarea 
                                            id="task_description" 
                                            name="task_description" 
                                            class="form-control" 
                                            rows="4" 
                                            required>{{ $task->task_description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center w-25 mx-auto">
                                <button type="submit" class="btn btn-info p-2 rounded">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
