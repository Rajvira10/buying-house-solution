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

                                    <!-- Buyer Selection -->
                                    <div class="mb-4">
                                        <label for="buyer_id" class="form-label fw-bold">{{ __('Select Buyer') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="buyer_id" id="buyer_id" required>
                                            @if ($logged_in_user_is_buyer != null)
                                                <option value="{{ $logged_in_user_is_buyer->id }}" selected>
                                                    {{ $logged_in_user_is_buyer->user->first_name }}
                                                    {{ $logged_in_user_is_buyer->user->last_name }}
                                                </option>
                                            @else
                                                <option value="" disabled selected>Select a Buyer</option>
                                                @foreach ($buyers as $buyer)
                                                    <option value="{{ $buyer->id }}">
                                                        {{ $buyer->user->first_name }} {{ $buyer->user->last_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Products Container -->
                                    <div id="products-container">
                                        <!-- Product forms will be added here -->
                                    </div>

                                    <!-- Add Product Button -->
                                    <div class="mb-4">
                                        <button type="button" class="btn btn-secondary" id="add-product">
                                            <i class="ri-add-line me-1"></i> Add Product
                                        </button>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-end">
                                        <button id="submit" type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Submit Query
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
                            <label for="product_type_id" class="form-label">{{ __('Product Type') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select select-category" name="products[0][product_type_id]" required>
                                <option value="" selected disabled>Select a product type</option>
                                @foreach ($product_types as $product_type)
                                    <option value="{{ $product_type->id }}">{{ $product_type->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
                            <label for="target_price" class="form-label">{{ __('Target Price') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="products[0][target_price]" required>
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
                        <div class="col-md-6">
                            <label for="trim_ids" class="form-label">{{ __('Trim') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select multiple-select-category" name="products[0][trim_ids][]" multiple
                                required>
                                <option value="" disabled>Select a category</option>
                                @foreach ($trims as $trim)
                                    <option value="{{ $trim->id }}">{{ $trim->name }}</option>
                                @endforeach
                            </select>
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
@endsection

@section('custom-script')
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

            const selectCategory = document.querySelector("#buyer_id");

            new Selectr(selectCategory);

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
