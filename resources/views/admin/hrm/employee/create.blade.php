@extends('admin.layout')
@section('title', 'Add Employee')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Employees</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href={{ route('employees.index') }}>Employees</a></li>
                                    <li class="breadcrumb-item active">Add Employee</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3>{{ __('Add Employee') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('employees.store') }}" method="post" class="form-group"
                                    enctype="multipart/form-data" onsubmit="return disableOnSubmit()">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name">
                                                    {{ __('Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('name')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="department_id">
                                                    {{ __('Department') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="department_id"
                                                    class="form-control select-category @error('department_id') is-invalid @enderror"
                                                    name="department_id">
                                                    <option value="">Select a department</option>
                                                    @foreach ($departments as $department)
                                                        <option class="department-field" value="{{ $department->id }}">
                                                            {{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('department_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="designation">
                                                    {{ __('Designation') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="designation" type="text"
                                                    class="form-control @error('designation') is-invalid @enderror"
                                                    name="designation" value="{{ old('designation') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('designation')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">
                                                    {{ __('Email') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="email" type="text"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('email')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="nid">
                                                    {{ __('NID') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="nid" type="text"
                                                    class="form-control @error('nid') is-invalid @enderror" name="nid"
                                                    value="{{ old('nid') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('nid')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="contact_no">
                                                    {{ __('Contact No') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="contact_no" type="text"
                                                    class="form-control @error('contact_no') is-invalid @enderror"
                                                    name="contact_no" value="{{ old('contact_no') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('contact_no')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="image">
                                                    {{ __('Image') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="image" type="file"
                                                    class="form-control @error('image') is-invalid @enderror"
                                                    name="image" value="{{ old('image') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('image')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="cv">
                                                    {{ __('CV') }}
                                                </label>
                                                <input id="cv" type="file"
                                                    class="form-control @error('cv') is-invalid @enderror" name="cv"
                                                    value="{{ old('cv') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('cv')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="present_address">
                                                    {{ __('Present Address') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="present_address" type="text"
                                                    class="form-control @error('present_address') is-invalid @enderror"
                                                    name="present_address" value="{{ old('present_address') }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('address')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="permanent_address">
                                                    {{ __('Permanent Address') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="permanent_address" type="text"
                                                    class="form-control @error('permanent_address') is-invalid @enderror"
                                                    name="permanent_address" value="{{ old('permanent_address') }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('address')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="joining_date">
                                                    {{ __('Joining Date') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                    data-default-date="{{ date('d/m/Y') }}" id="joining_date"
                                                    type="date"
                                                    class="form-control @error('joining_date') is-invalid @enderror"
                                                    name="joining_date" value="{{ old('joining_date') }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('joining_date')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="gross_salary">
                                                    {{ __('Gross Salary') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="gross_salary" type="number" step="any"
                                                    class="form-control @error('gross_salary') is-invalid @enderror"
                                                    name="gross_salary" value="{{ old('gross_salary') }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('gross_salary')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="current_salary_starting_date">
                                                    {{ __('Current Salary Starting Date') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                    data-default-date="{{ date('d/m/Y') }}"
                                                    id="current_salary_starting_date" type="date"
                                                    class="form-control @error('current_salary_starting_date') is-invalid @enderror"
                                                    name="current_salary_starting_date"
                                                    value="{{ old('current_salary_starting_date') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('current_salary_starting_date')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="h_rent_percent">
                                                    {{ __('House Rent Allowance Percentage (%)') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="h_rent_percent" type="number" step="any"
                                                    class="form-control @error('h_rent_percent') is-invalid @enderror"
                                                    name="h_rent_percent" value="{{ old('h_rent_percent') }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('h_rent_percent')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="med_percent">
                                                    {{ __('Medical Allowance Percentage (%)') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="med_percent" type="number" step="any"
                                                    class="form-control @error('med_percent') is-invalid @enderror"
                                                    name="med_percent" value="{{ old('med_percent') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('med_percent')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="conv_percent">
                                                    {{ __('Conveyance Allowance Percentage (%)') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="conv_percent" type="number" step="any"
                                                    class="form-control @error('conv_percent') is-invalid @enderror"
                                                    name="conv_percent" value="{{ old('conv_percent') }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('conv_percent')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button id="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('custom-script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectCategory = document.querySelectorAll(".select-category");
            for (let i = 0; i < selectCategory.length; i++) {
                new Selectr(selectCategory[i]);
            }
        });

        const disableOnSubmit = () => {
            const button = document.querySelector('#submit');
            button.disabled = true;
            button.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
            return true;
        }
    </script>
@endsection
