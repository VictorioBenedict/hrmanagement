@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Edit Salary</h6>
    <div>
        <a href="{{ route('salary.view') }}" class="btn btn-success p-2 text-lg rounded-pill">
            <i class="fa-sharp fa-regular fa-eye me-1"></i>View Salary List
        </a>
    </div>
</div>

<div class="container my-5 py-5">
    <!-- Section: Form Design Block -->
    <section>
        <div>
            <div class="w-75 mx-auto">
                <div class="card mb-3">
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-font text-uppercase">Salary Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('salaryUpdate', $salary->id) }}" method="post" id="salaryForm">
                            @csrf
                            @method('put')

                            <!-- Salary Class and Basic Salary -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="salaryClass">Salary Class</label>
                                        <input required value="{{ $salary->salary_class }}" id="salaryClass"
                                            placeholder="Enter Salary Class" name="salary_class" class="form-control">
                                    </div>
                                    <div class="mt-2">
                                        @error('salary_class')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="basicSalary">Basic Salary</label>
                                        <input required value="{{ $salary->basic_salary }}" placeholder="Basic Salary"
                                            type="number" id="basicSalary" name="basic_salary" class="form-control" min="0">
                                    </div>
                                    <div class="mt-2">
                                        @error('basic_salary')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Expenses, Mobile Allowance, and House Rent Allowance -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="medicalExpenses">Medical Expenses</label>
                                        <input required value="{{ $salary->medical_expenses }}" placeholder="Enter Amount"
                                            type="number" id="medicalExpenses" name="medical_expenses" class="form-control" min="0">
                                    </div>
                                    <div class="mt-2">
                                        @error('medical_expenses')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="mobileAllowance">Mobile Allowance</label>
                                        <input required value="{{ $salary->mobile_allowance }}" placeholder="Mobile Allowance"
                                            type="number" id="mobileAllowance" name="mobile_allowance" class="form-control" min="0">
                                    </div>
                                    <div class="mt-2">
                                        @error('mobile_allowance')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-outline">
                                        <label class="form-label mt-2 fw-bold" for="houseRentAllowance">House Rent Allowance</label>
                                        <input required value="{{ $salary->houseRent_allowance }}" placeholder="House Rent Allowance"
                                            type="number" name="houseRent_allowance" class="form-control" min="0">
                                    </div>
                                    <div class="mt-2">
                                        @error('houseRent_allowance')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center w-25 mx-auto mt-3">
                                <button type="submit" class="btn btn-success p-2 text-lg rounded-pill col-md-10">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
