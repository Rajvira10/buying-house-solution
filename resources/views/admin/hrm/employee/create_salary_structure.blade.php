@extends('admin.layout')
@section('title', 'Add Salary Structure')
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
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('employees.show', $employee->id) }}">Employee Details</a></li>
                                    <li class="breadcrumb-item active">Add Salary Structure</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3>{{ __('Add Salary Structure for ' . $employee->unique_id) }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('employees.store_salary_structure', $employee->id) }}" method="post"
                                    class="form-group" enctype="multipart/form-data" onsubmit="return disableOnSubmit()">
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
                                                    value="{{ $employee->name }}" placeholder="" readonly>
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
                                                <label for="gross_salary">
                                                    {{ __('Gross Salary') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="gross_salary" type="number" step="any"
                                                    class="form-control @error('gross_salary') is-invalid @enderror"
                                                    name="gross_salary" value="{{ old('gross_salary') }}" placeholder="">
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
        const disableOnSubmit = () => {
            const button = document.querySelector('#submit');
            button.disabled = true;
            button.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
            return true;
        }
    </script>
@endsection
