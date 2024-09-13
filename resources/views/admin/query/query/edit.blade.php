@extends('admin.layout')
@section('title', 'Edit Query')
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
                        <li class="breadcrumb-item active" aria-current="page">Edit Query</li>
                    </ol>
                </nav>

                <!-- Main Content -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0 text-white">{{ __('Edit Query') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('queries.update', $query->id) }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="buyer_id" class="form-label fw-bold">{{ __('Select Buyer') }}
                                                <span class="text-danger">*</span></label>
                                            <select class="form-select select-category" name="buyer_id" id="buyer_id"
                                                required>
                                                <option value="" disabled selected>Select a Buyer</option>
                                                @foreach ($buyers as $buyer)
                                                    <option value="{{ $buyer->id }}"
                                                        {{ $query->buyer_id == $buyer->id ? 'selected' : '' }}>
                                                        {{ $buyer->user->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="product_type_id" class="form-label fw-bold">{{ __('Product Type') }}
                                                <span class="text-danger">*</span></label>
                                            <select class="form-select select-category" name="product_type_id" required>
                                                <option value="" selected disabled>Select a product type</option>
                                                @foreach ($product_types as $product_type)
                                                    <option value="{{ $product_type->id }}"
                                                        {{ $query->product_type_id == $product_type->id ? 'selected' : '' }}>
                                                        {{ $product_type->name }}
                                                        >{{ $product_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="employee_id"
                                                class="form-label fw-bold">{{ __('Select Merchandiser') }}
                                            </label>
                                            <select class="form-select select-category" name="employee_id" required>
                                                <option value="" selected disabled>Select a Merchandiser</option>
                                                @foreach ($merchandisers as $merchandiser)
                                                    <option value="{{ $merchandiser->id }}"
                                                        {{ $query->employee_id == $merchandiser->id ? 'selected' : '' }}>
                                                        {{ $merchandiser->user->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Products Container -->
                                    <div id="products-container">
                                        @foreach ($query->items as $index => $item)
                                            <div class="product-item mb-4">
                                                <div class="card shadow-sm">
                                                    <div class="card-header bg-light">
                                                        <h5 class="card-title mb-0">
                                                            Product <span
                                                                class="product-number badge bg-primary">{{ $loop->iteration }}</span>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm float-end remove-product">
                                                                <i class="ri-delete-bin-line"></i> Remove
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">


                                                            <div class="col-md-6">
                                                                <label for="product_id"
                                                                    class="form-label">{{ __('Product') }} <span
                                                                        class="text-danger">*</span></label>
                                                                <select class="form-select select-category"
                                                                    name="products[{{ $index }}][product_id]"
                                                                    required>
                                                                    <option value="" selected disabled>Select a
                                                                        product</option>
                                                                    @foreach ($products as $product)
                                                                        <option value="{{ $product->id }}"
                                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                                            {{ $product->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Quantity and Price -->
                                                            <div class="col-md-6">
                                                                <label for="approximate_quantity"
                                                                    class="form-label">{{ __('Approximate Quantity') }}
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control"
                                                                    name="products[{{ $index }}][approximate_quantity]"
                                                                    value="{{ $item->approximate_quantity }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="target_price"
                                                                    class="form-label">{{ __('Target Price') }} <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control"
                                                                    name="products[{{ $index }}][target_price]"
                                                                    value="{{ $item->target_price }}" required>
                                                            </div>

                                                            <!-- Dates -->
                                                            <div class="col-md-6">
                                                                <label for="price_submission_date"
                                                                    class="form-label">{{ __('Price Submission Date') }}
                                                                    <span class="text-danger">*</span></label>
                                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                    data-default-date={{ date('d/m/Y', strtotime($item->price_submission_date)) }}"
                                                                    type="date"
                                                                    class="form-control price_submission_date"
                                                                    name="products[{{ $index }}][price_submission_date]"
                                                                    value="{{ $item->price_submission_date ? date('d/m/Y', strtotime($item->price_submission_date)) : date('d/m/Y') }}"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="sample_submission_date"
                                                                    class="form-label">{{ __('Sample Submission Date') }}</label>
                                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                    data-default-date={{ $item->sample_submission_date ? date('d/m/Y', strtotime($item->sample_submission_date)) : '' }}"
                                                                    type="date"
                                                                    class="form-control sample_submission_date"
                                                                    name="products[{{ $index }}][sample_submission_date]"
                                                                    value="{{ $item->sample_submission_date ? date('d/m/Y', strtotime($item->sample_submission_date)) : '' }}">
                                                            </div>

                                                            <!-- Model and Trim -->
                                                            <div class="col-md-6">
                                                                <label for="product_model"
                                                                    class="form-label">{{ __('Product Model') }} <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    name="products[{{ $index }}][product_model]"
                                                                    value="{{ $item->product_model }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="trim_ids"
                                                                    class="form-label">{{ __('Trim') }} <span
                                                                        class="text-danger">*</span></label>
                                                                <select class="form-select multiple-select-category"
                                                                    name="products[{{ $index }}][trim_ids][]"
                                                                    multiple required>
                                                                    @foreach ($trims as $trim)
                                                                        <option value="{{ $trim->id }}"
                                                                            {{ in_array($trim->id, $item->trims->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                                            {{ $trim->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Images and Measurements -->
                                                            <div class="col-md-6">
                                                                <label for="query_images"
                                                                    class="form-label">{{ __('Query Images') }} <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="file" class="form-control"
                                                                    name="products[{{ $index }}][query_images][]"
                                                                    multiple>
                                                                <!-- Existing images logic (optional) -->
                                                                @if ($item->images->isNotEmpty())
                                                                    <div class="mt-2">
                                                                        <strong>Current Images:</strong>
                                                                        <ul>
                                                                            @foreach ($item->images as $image)
                                                                                <li><a href="{{ asset($image->absolute_path) }}"
                                                                                        target="_blank">{{ $image->absolute_path }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="query_measurements"
                                                                    class="form-label">{{ __('Query Measurements') }}
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="file" class="form-control"
                                                                    name="products[{{ $index }}][query_measurements][]"
                                                                    multiple>
                                                                <!-- Existing measurements logic (optional) -->
                                                                @if ($item->measurements->isNotEmpty())
                                                                    <div class="mt-2">
                                                                        <strong>Current Measurements:</strong>
                                                                        <ul>
                                                                            @foreach ($item->measurements as $measurement)
                                                                                <li><a href="{{ asset($measurement->absolute_path) }}"
                                                                                        target="_blank">{{ $measurement->absolute_path }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-12">
                                                                <label for="details"
                                                                    class="form-label">{{ __('Details') }} <span
                                                                        class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="products[{{ $index }}][details]" rows="4" required>{{ $item->details }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary"
                                                id="submitBtn">{{ __('Update Query') }}</button>
                                            <button type="button" id="add-more" class="btn btn-success"><i
                                                    class="ri-add-line align-bottom me-1"></i> Add More</button>
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div>
    </div>

@endsection

@section('custom-script')
    <script>
        const disableOnSubmit = () => {
            const button = document.querySelector('#submitBtn');
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
            selectCategory.forEach(select => {
                new Selectr(select);
            });
            const selectMultipleCategory = document.querySelectorAll(".multiple-select-category");
            selectMultipleCategory.forEach(select => {
                new Selectr(select, {
                    multiple: true,
                    placeholder: 'Select a category'
                });
            });

            const productsContainer = document.getElementById('products-container');
            const addProductButton = document.getElementById('add-more');
            let productCount = {{ count($query->items) }};

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
                const productTemplate = `
                    <div class="product-item mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    Product <span class="product-number badge bg-primary">${productCount}</span>
                                    <button type="button" class="btn btn-danger btn-sm float-end remove-product">
                                        <i class="ri-delete-bin-line"></i> Remove
                                    </button>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="product_id" class="form-label">{{ __('Product') }} <span class="text-danger">*</span></label>
                                        <select class="form-select select-category" name="products[${productCount - 1}][product_id]" required>
                                            <option value="" selected disabled>Select a product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="approximate_quantity" class="form-label">{{ __('Approximate Quantity') }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="products[${productCount - 1}][approximate_quantity]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="target_price" class="form-label">{{ __('Target Price') }} <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="products[${productCount - 1}][target_price]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="price_submission_date" class="form-label">{{ __('Price Submission Date') }} <span class="text-danger">*</span></label>
                                        <input data-provider="flatpickr" data-date-format="d/m/Y" type="date" class="form-control price_submission_date" name="products[${productCount - 1}][price_submission_date]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sample_submission_date" class="form-label
                                        ">{{ __('Sample Submission Date') }}</label>
                                        <input data-provider="flatpickr" data-date-format="d/m/Y" type="date" class="form-control sample_submission_date" name="products[${productCount - 1}][sample_submission_date]">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="product_model" class="form-label
                                        ">{{ __('Product Model') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="products[${productCount - 1}][product_model]" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="trim_ids" class="form-label">{{ __('Trim') }} <span class="text-danger">*</span></label>
                                        <select class="form-select multiple-select-category" name="products[${productCount - 1}][trim_ids][]" multiple required>
                                            @foreach ($trims as $trim)
                                                <option value="{{ $trim->id }}">{{ $trim->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="query_images" class="form-label">{{ __('Query Images') }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="products[${productCount - 1}][query_images][]" multiple>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="query_measurements" class="form-label">{{ __('Query Measurements') }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="products[${productCount - 1}][query_measurements][]" multiple>
                                    </div>
                                    <div class="col-12">
                                        <label for="details" class="form-label">{{ __('Details') }} <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="products[${productCount - 1}][details]" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                productsContainer.insertAdjacentHTML('beforeend', productTemplate);

                const newProduct = productsContainer.lastElementChild;

                newProduct.querySelectorAll('.select-category').forEach(select => {
                    initializeSelectr(select);
                });
                initializeSelectr(newProduct.querySelector('.multiple-select-category'), true);

                flatpickr(newProduct.querySelector('.price_submission_date'), {
                    dateFormat: 'd/m/Y',
                    defaultDate: new Date()
                });

                flatpickr(newProduct.querySelector('.sample_submission_date'), {
                    dateFormat: 'd/m/Y',
                });

                newProduct.querySelector('.remove-product').addEventListener('click', function() {
                    newProduct.remove();
                    updateProductNumbers();
                });
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

            // If there are no existing products, add one by default
            if (productCount === 0) addProduct();
        });
    </script>
@endsection
