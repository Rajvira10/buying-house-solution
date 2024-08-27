@extends('admin.layout')
@section('title', 'Edit Query')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Queries</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries</a></li>
                                    <li class="breadcrumb-item active">Edit Query</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ __('Edit Query') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('queries.update', $query->id) }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="buyer_id">
                                                    {{ __('Select Buyer') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control select-category" name="buyer_id" id="buyer_id"
                                                    required>
                                                    @if ($logged_in_user_is_buyer != null)
                                                        <option value="{{ $logged_in_user_is_buyer->id }}" selected>
                                                            {{ $logged_in_user_is_buyer->user->first_name }}
                                                            {{ $logged_in_user_is_buyer->user->last_name }}</option>
                                                    @else
                                                        <option value="" disabled selected>Select a Buyer</option>
                                                        @foreach ($buyers as $buyer)
                                                            <option value="{{ $buyer->id }}">
                                                                {{ $buyer->user->first_name }}
                                                                {{ $buyer->user->last_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="products-container">
                                        @foreach ($query->items as $index => $item)
                                            <div class="product-item mb-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Product {{ $index + 1 }}
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm float-end remove-product">Remove</button>
                                                        </h5>
                                                        <div class="row mb-3 mt-2">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="product_name">
                                                                        {{ __('Product Name') }}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="text" class="form-control"
                                                                        name="products[{{ $index }}][product_name]"
                                                                        value="{{ $item->product_name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="trim_ids">
                                                                        {{ __('Trim') }}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <select class="form-control multiple-select-category"
                                                                        name="products[{{ $index }}][trim_ids][]"
                                                                        multiple required>
                                                                        <option value="" disabled>Select a category
                                                                        </option>
                                                                        @foreach ($trims as $trim)
                                                                            <option value="{{ $trim->id }}"
                                                                                {{ in_array($trim->id, $item->trims->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                                                {{ $trim->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="details">
                                                                        {{ __('Details') }}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <textarea class="form-control" name="products[{{ $index }}][details]" rows="4" required>{{ $item->details }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="approximate_quantity">
                                                                        {{ __('Approximate Quantity') }}
                                                                        <span class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="number" class="form-control"
                                                                        name="products[{{ $index }}][approximate_quantity]"
                                                                        value="{{ $item->approximate_quantity }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="query_images">
                                                                        {{ __('Query Images') }}
                                                                    </label>
                                                                    <input type="file" class="form-control"
                                                                        name="products[{{ $index }}][query_images][]"
                                                                        multiple>
                                                                    <div class="mt-2">
                                                                        @foreach ($item->images as $image)
                                                                            <a href="{{ $image->absolute_path }}"> <img
                                                                                    src="{{ $image->absolute_path }}"
                                                                                    alt="Query Image" width="100">
                                                                            </a>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label for="query_measurements">
                                                                        {{ __('Query Measurements') }}
                                                                    </label>
                                                                    <input type="file" class="form-control"
                                                                        name="products[{{ $index }}][query_measurements][]"
                                                                        multiple>
                                                                    <div class="mt-2">
                                                                        @foreach ($item->measurements as $measurement)
                                                                            <a href="{{ $measurement->absolute_path }}"
                                                                                target="_blank">View File</a><br>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-secondary" id="add-product">Add
                                                Product</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button id="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light">Update</button>
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

    <!-- Product template (hidden) -->
    <template id="product-template">
        <!-- ... (template content remains the same) ... -->
    </template>
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

        document.addEventListener("DOMContentLoaded", function() {
            const selectCategory = document.querySelectorAll(".select-category");

            selectCategory.forEach((select) => {
                const selectr = new Selectr(select);
            });

            const productsContainer = document.getElementById('products-container');
            const addProductButton = document.getElementById('add-product');
            const productTemplate = document.getElementById('product-template');
            let productCount = {{ $query->items->count() }};

            function initializeSelectr(element) {
                new Selectr(element, {
                    multiple: true,
                    placeholder: 'Select a category'
                });
            }

            function addProduct(productData = null) {
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
                initializeSelectr(newSelect);

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

            addProductButton.addEventListener('click', () => addProduct());

            // Initialize Selectr for existing products
            document.querySelectorAll('.multiple-select-category').forEach(select => {
                initializeSelectr(select);
            });

            // Add event listeners to existing remove buttons
            document.querySelectorAll('.remove-product').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.product-item').remove();
                    updateProductNumbers();
                });
            });

            // If no products exist, add one empty product form
            if (productCount === 0) {
                addProduct();
            }
        });
    </script>
@endsection
