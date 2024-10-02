@extends('admin.layout')
@section('title', 'Create Query')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="ri-home-5-line"></i>
                                Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Query</li>
                    </ol>
                </nav>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0 text-white">{{ __('Add Query') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('queries.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()" enctype="multipart/form-data">
                                    @csrf


                                    <div class="mb-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="brand_id" class="form-label fw-bold">{{ __('Select Brand') }}
                                                    <span class="text-danger">*</span></label>
                                                <div class="d-flex">
                                                    <select class="form-select select-category" name="brand_id"
                                                        id="brand_id" required>
                                                        <option value="" disabled selected>Select a Brand</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}">
                                                                {{ $brand->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if (in_array('brand.create', session('user_permissions')))
                                                        <button type="button" class="btn btn-outline-secondary ms-2"
                                                            data-bs-toggle="modal" data-bs-target="#createBuyerModal">
                                                            <i class="ri-add-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="product_type_id"
                                                    class="form-label fw-bold">{{ __('Product Type') }}
                                                    <span class="text-danger">*</span></label>
                                                <div class="d-flex">
                                                    <select class="form-select select-category" name="product_type_id"
                                                        id="product_type_id" required>
                                                        <option value="" selected disabled>Select a product type
                                                        </option>
                                                        @foreach ($product_types as $product_type)
                                                            <option value="{{ $product_type->id }}">
                                                                {{ $product_type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn btn-outline-secondary ms-2"
                                                        data-bs-toggle="modal" data-bs-target="#createProductTypeModal">
                                                        <i class="ri-add-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="employee_id"
                                                    class="form-label fw-bold">{{ __('Select Merchandiser') }}</label>
                                                <div class="d-flex">
                                                    <select class="form-select select-category" name="employee_id"
                                                        id="employee_id">
                                                        <option value="" selected disabled>Select a Merchandiser
                                                        </option>
                                                        @foreach ($merchandisers as $merchandiser)
                                                            <option value="{{ $merchandiser->id }}">
                                                                {{ $merchandiser->user->username }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" class="btn btn-outline-secondary ms-2"
                                                        data-bs-toggle="modal" data-bs-target="#createMerchandiserModal">
                                                        <i class="ri-add-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Products Container -->
                                    <div id="products-container">
                                        <!-- Product forms will be added here -->
                                    </div>

                                    <div class="mb-4 flex justify-content-between align-items-center w-100">

                                        <button id="submit" type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Submit Query
                                        </button>
                                        <button type="button" class="btn btn-success" id="add-product">
                                            <i class="ri-add-line me-1"></i> Add More
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Template -->
    <template id="product-template">
        <div class="product-item mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        Product <span class="product-number badge bg-primary"></span>
                        <button type="button" class="btn btn-danger btn-sm float-end remove-product">
                            <i class="ri-delete-bin-line"></i> Remove
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Product Type and Product -->

                        <div class="col-md-6">
                            <label for="product_id" class="form-label">{{ __('Product') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select select-category" name="products[0][product_id]" required>
                                <option value="" selected disabled>Select a product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity and Price -->
                        <div class="col-md-6">
                            <label for="approximate_quantity" class="form-label">{{ __('Approximate Quantity') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="products[0][approximate_quantity]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="target_price" class="form-label">
                                {{ __('Target Price') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="products[0][target_price]" required>
                            </div>
                        </div>


                        <!-- Dates -->
                        <div class="col-md-6">
                            <label for="price_submission_date" class="form-label">{{ __('Price Submission Date') }} <span
                                    class="text-danger">*</span></label>
                            <input data-provider="flatpickr" data-date-format="d/m/Y"
                                data-default-date="{{ date('d/m/Y') }}" type="date"
                                class="form-control price_submission_date" name="products[0][price_submission_date]"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="sample_submission_date"
                                class="form-label">{{ __('Sample Submission Date') }}</label>
                            <input data-provider="flatpickr" data-date-format="d/m/Y" type="date"
                                class="form-control sample_submission_date" name="products[0][sample_submission_date]">
                        </div>

                        <!-- Model and Trim -->
                        <div class="col-md-6">
                            <label for="product_model" class="form-label">{{ __('Product Model') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="products[0][product_model]" required>
                        </div>

                        <!-- Images and Measurements -->
                        <div class="col-md-6">
                            <label for="query_images" class="form-label">{{ __('Query Images') }} <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="products[0][query_images][]" multiple
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="query_measurements" class="form-label">{{ __('Query Measurements') }} <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="products[0][query_measurements][]" multiple
                                required>
                        </div>

                        <div class="col-12">
                            <label for="details" class="form-label">{{ __('Details') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" name="products[0][details]" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>


    <div class="modal modal-xl fade" id="createBuyerModal" tabindex="-1" aria-labelledby="createBuyerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary  pb-4">
                    <h5 class="modal-title text-white" id="createBuyerModalLabel">Create Brand</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('brands.store') }}" method="post" class="form-group"
                        onsubmit="return disableOnSubmit()">
                        @csrf
                        <input type="hidden" name="modal" value="true">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="buyer_id">
                                        {{ __('Buyer') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="buyer_id"
                                        class="form-control select-category @error('buyer_id') is-invalid @enderror"
                                        name="buyer_id">
                                        <option value="">Select</option>
                                        @foreach ($buyers as $buyer)
                                            <option value="{{ $buyer->id }}">{{ $buyer->user->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    @error('buyer_id')
                                        <span class="text-danger-error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">
                                        {{ __('Name') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="email">
                                        {{ __('Email') }}
                                    </label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="phone">
                                        {{ __('Phone') }}
                                    </label>
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="address">
                                        {{ __('Address') }}
                                    </label>
                                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="text-danger" role="alert">
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

    <div class="modal modal-xl fade" id="createProductTypeModal" tabindex="-1"
        aria-labelledby="createProductTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary pb-4">
                    <h5 class="modal-title text-white" id="createProductTypeModalLabel">Create Product Type</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('product_types.store') }}" method="post" class="form-group"
                        onsubmit="return disableOnSubmit()">
                        @csrf
                        <input type="hidden" name="modal" value="true">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">
                                        Name
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

    <div class="modal fade modal-xl" id="createMerchandiserModal" tabindex="-1"
        aria-labelledby="createMerchandiserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-primary pb-4">
                    <h5 class="modal-title text-white" id="createMerchandiserModalLabel">Create Merchandiser</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employees.store') }}" method="post" class="form-group"
                        onsubmit="return disableOnSubmit()" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="modal" value="true">

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="username">
                                        {{ __('Name') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" placeholder="">
                                    <div class="help-block with-errors"></div>
                                    @error('username')
                                        <span class="text-danger-error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="password">
                                        Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="">
                                    <div class="help-block with-errors"></div>
                                    @error('password')
                                        <span class="text-danger-error" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="password_confirmation">
                                        Confirm Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" placeholder="">
                                </div>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="department_id">
                                        {{ __('Department') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select id="department_id"
                                        class="form-control @error('department_id') is-invalid @enderror"
                                        name="department_id" readonly>
                                        <option value="">Select a department</option>
                                        @foreach ($departments as $department)
                                            <option class="department-field" value="{{ $department->id }}"
                                                {{ $department->name == 'Merchandiser' ? 'selected' : '' }}>
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
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="designation">
                                        {{ __('Designation') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="designation" type="text"
                                        class="form-control @error('designation') is-invalid @enderror" name="designation"
                                        value="{{ old('designation') }}" placeholder="">
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
                                        class="form-control @error('contact_no') is-invalid @enderror" name="contact_no"
                                        value="{{ old('contact_no') }}" placeholder="">
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
                                        class="form-control @error('image') is-invalid @enderror" name="image"
                                        value="{{ old('image') }}" placeholder="">
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
                                        name="present_address" value="{{ old('present_address') }}" placeholder="">
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
                                        name="permanent_address" value="{{ old('permanent_address') }}" placeholder="">
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
                                        data-default-date="{{ date('d/m/Y') }}" id="joining_date" type="date"
                                        class="form-control @error('joining_date') is-invalid @enderror"
                                        name="joining_date" value="{{ old('joining_date') }}" placeholder="">
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
                                        data-default-date="{{ date('d/m/Y') }}" id="current_salary_starting_date"
                                        type="date"
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
                                        name="h_rent_percent" value="{{ old('h_rent_percent') }}" placeholder="">
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
                                        name="conv_percent" value="{{ old('conv_percent') }}" placeholder="">
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

@endsection

@section('custom-script')
    @include('admin.message')
    <script>
        const disableOnSubmit = () => {
            const button = document.querySelector('#submit');
            document.querySelectorAll('select').forEach(select => {
                select.removeAttribute('aria-hidden');
            });
            button.disabled = true;
            button.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
            return true;
        }

        document.addEventListener("DOMContentLoaded", function() {

            const selectCategory = document.querySelectorAll(".select-category");

            for (let i = 0; i < selectCategory.length; i++) {
                new Selectr(selectCategory[i]);
            }

            $('.select-with-button .selectr-container').css('z-index', '1056');

            const productsContainer = document.getElementById('products-container');
            const addProductButton = document.getElementById('add-product');
            const productTemplate = document.getElementById('product-template');
            let productCount = 0;

            function initializeSelectr(element, multiple = false) {
                if (multiple) {
                    new Selectr(element, {
                        multiple: true,
                        placeholder: 'Select a category'
                    });
                } else {
                    new Selectr(element, {
                        placeholder: 'Select a category'
                    });
                }
            }

            function addProduct() {
                productCount++;
                const productClone = document.importNode(productTemplate.content, true);
                productClone.querySelector('.product-number').textContent = productCount;

                // Update name attributes
                productClone.querySelectorAll('input, select, textarea').forEach(element => {
                    if (element.name) {
                        element.name = element.name.replace('products[0]', `products[${productCount - 1}]`);
                    }
                });

                const removeButton = productClone.querySelector('.remove-product');
                removeButton.addEventListener('click', function() {
                    this.closest('.product-item').remove();
                    updateProductNumbers();
                });

                productsContainer.appendChild(productClone);

                const newSelect = productsContainer.lastElementChild.querySelector('.multiple-select-category');
                if (newSelect)
                    initializeSelectr(newSelect, true);

                const newSelectCategories = productsContainer.lastElementChild.querySelectorAll('.select-category');
                newSelectCategories.forEach(newSelectCategory => {
                    initializeSelectr(newSelectCategory);
                });

                flatpickr(productsContainer.lastElementChild.querySelector('.price_submission_date'), {
                    dateFormat: 'd/m/Y',
                    defaultDate: new Date()
                });

                flatpickr(productsContainer.lastElementChild.querySelector('.sample_submission_date'), {
                    dateFormat: 'd/m/Y',
                });

                updateProductNumbers();
            }

            function updateProductNumbers() {
                const products = productsContainer.querySelectorAll('.product-item');
                products.forEach((product, index) => {
                    product.querySelector('.product-number').textContent = index + 1;

                    // Update name attributes
                    product.querySelectorAll('input, select, textarea').forEach(element => {
                        if (element.name) {
                            element.name = element.name.replace(/products\[\d+\]/,
                                `products[${index}]`);
                        }
                    });
                });
            }

            addProductButton.addEventListener('click', addProduct);

            // Add the first product by default
            addProduct();
        });
    </script>
@endsection
