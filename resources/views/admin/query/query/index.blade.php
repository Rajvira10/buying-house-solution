@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp

@extends('admin.layout')
@section('title', 'Queries')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col">
                                        <h4 class="card-title mb-0">Queries</h4>
                                    </div>
                                    <div class="col-sm-auto">
                                        @if (in_array('order.create', session('user_permissions')))
                                            <a href="{{ route('queries.approve') }}">
                                                <button type="button" class="btn btn-success add-btn">
                                                    Approve Orders
                                                </button>
                                            </a>
                                        @endif
                                        @if (in_array('query.create', session('user_permissions')))
                                            <a href="{{ route('queries.create') }}">
                                                <button type="button" class="btn btn-success add-btn">
                                                    <i class="ri-add-line align-bottom me-1"></i> Add
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="expenseCategoryList">
                                    <div class="card-body">
                                        <table id="expenseCategoryTable" class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Query No') }}</th>
                                                    <th>{{ __('Brand') }}</th>
                                                    <th>{{ __('Merchandiser') }}</th>
                                                    <th>{{ __('Product Type') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div><!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end col -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white pb-3">
                    <h5 class="modal-title text-white" id="orderModalLabel">
                        <i class="bi bi-cart-plus"></i> Create Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="query_id" id="query_id">
                    <input type="hidden" name="query_product_type" id="query_product_type">
                    <div class="modal-body">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    Available Sizes</h6>
                            </div>
                            <div class="card-body">
                                <div id="sizeSection">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="ri-ruler-2-line"></i></span>
                                        <input type="text" name="sizes[]" class="form-control sizeInput"
                                            placeholder="Enter Size (e.g., M, L, XL)">
                                        <button type="button" class="btn btn-outline-danger removeSize">
                                            <i class="ri-close-fill"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary mt-2" id="addSizeBtn">
                                    <i class="ri-add-line"></i> Add Another Size
                                </button>
                            </div>
                        </div>

                        <!-- Products Section -->
                        <div id="productSection">
                            <div class="product-entry card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="ri-shopping-bag-2-line"></i> Product Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 knit">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" name="product_image[]" class="form-control">
                                    </div>
                                    <div class="row mb-3 knit">
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Code
                                            </label>
                                            <input type="text" name="product_code[]" class="form-control" id="knitCode"
                                                placeholder="Enter Product Code">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Function
                                            </label>
                                            <input type="text" name="product_function[]" class="form-control"
                                                id="function" placeholder="Enter Product Function">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Model
                                            </label>
                                            <input type="text" name="product_model[]" class="form-control" id="model"
                                                placeholder="Enter Product Model">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Shipment Date
                                            </label>
                                            <input type="date" name="product_shipment_date[]" class="form-control"
                                                id="shipment_date" placeholder="Enter Product Shipment Date">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3 woven">
                                            <label class="form-label">
                                                Code
                                            </label>
                                            <input type="text" name="product_code[]" class="form-control"
                                                id="wovenCode" placeholder="Enter Product Code">
                                        </div>
                                        <div class="col-md-3 knit">
                                            <label class="form-label">
                                                Details
                                            </label>
                                            <input type="text" name="product_details[]" class="form-control"
                                                id="details" placeholder="Enter Product Details">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Fabric
                                            </label>
                                            <input type="text" name="product_fabric[]" class="form-control"
                                                placeholder="Enter Product Fabric" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Weight
                                            </label>
                                            <input type="text" name="product_weight[]" class="form-control"
                                                placeholder="Enter Product Weight" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">
                                                Master Box
                                            </label>
                                            <input type="number" name="product_master_box[]" class="form-control"
                                                placeholder="Enter Product Master Box Quantity" required>
                                        </div>
                                    </div>

                                    <!-- Colors Section -->
                                    <div class="card mt-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="ri-palette-fill"></i> Color Details</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="colorSection" data-product-index="0">
                                                <div class="color-entry mb-3">
                                                    <div class="input-group mb-2">
                                                        <span class="input-group-text"><i
                                                                class="ri-palette-fill"></i></span>
                                                        <input type="text" name="colors[0][0][name]"
                                                            class="form-control" placeholder="Color Name" required>
                                                        <input type="text" name="colors[0][0][details]"
                                                            class="form-control" placeholder="Color Details" required>

                                                        <button type="button" class="btn btn-outline-danger removeColor">
                                                            <i class="ri-close-fill"></i>
                                                        </button>
                                                    </div>
                                                    <div class="row mb-2 sizeQuantitiesSection">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary addColorBtn">
                                                <i class="ri-add-line"></i> Add Another Color
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success addProductBtn">
                            <i class="ri-add-line"></i> Add Another Product
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-fill"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-check-line"></i> Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('custom-script')

    @include('admin.message')


    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            const addSizeBtn = document.getElementById('addSizeBtn');
            const sizeSection = document.getElementById('sizeSection');
            const productSection = document.getElementById('productSection');
            let productCounter = 0;

            // Add new size field
            addSizeBtn.addEventListener('click', function() {
                const newSizeField = `
            <div class="input-group mb-2">
                <span class="input-group-text"><i class="ri-ruler-2-line"></i></span>
                <input type="text" name="sizes[]" class="form-control sizeInput" 
                       placeholder="Enter Size (e.g., M, L, XL)">
                <button type="button" class="btn btn-outline-danger removeSize">
                    <i class="ri-close-fill"></i>
                </button>
            </div>
        `;
                sizeSection.insertAdjacentHTML('beforeend', newSizeField);
                updateSizeQuantities();
            });

            function updateSizeQuantities() {
                const sizeInputs = document.querySelectorAll('input[name="sizes[]"]');
                const colorSections = document.querySelectorAll('.colorSection');

                colorSections.forEach(section => {
                    const productIndex = section.dataset.productIndex;
                    const colorEntries = section.querySelectorAll('.color-entry');

                    colorEntries.forEach((colorEntry, colorIndex) => {
                        const quantitySection = colorEntry.querySelector('.sizeQuantitiesSection');

                        // Check if quantitySection exists
                        if (!quantitySection) return;

                        const currentQuantities = {};

                        // Get existing quantities for this color
                        quantitySection.querySelectorAll('input[type="number"]').forEach(input => {
                            const sizeLabel = input.closest('.col-md-4')?.querySelector(
                                '.sizeLabel')?.textContent.trim();
                            if (sizeLabel) {
                                currentQuantities[sizeLabel] = input.value;
                            }
                        });

                        // Clear existing quantity section
                        quantitySection.innerHTML = '';

                        // Loop through size inputs to recreate quantity fields
                        sizeInputs.forEach((sizeInput, sizeIndex) => {
                            const sizeValue = sizeInput.value || `Size ${sizeIndex + 1}`;
                            const existingQuantity = currentQuantities[sizeValue] || '';

                            const sizeInputField = `
                    <div class="col-md-4 mb-2">
                        <label class="form-label sizeLabel">${sizeValue}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-stack-line"></i></span>
                            <input type="number" 
                                   name="colors[${productIndex}][${colorIndex}][quantities][${sizeValue}]" 
                                   class="form-control" 
                                   placeholder="Quantity" 
                                   value="${existingQuantity}" 
                                   required min="0">
                        </div>
                    </div>
                `;
                            quantitySection.insertAdjacentHTML('beforeend', sizeInputField);
                        });
                    });
                });
            }

            // Event delegation for dynamic elements
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('removeSize')) {
                    event.target.closest('.input-group').remove();
                    updateSizeQuantities();
                }

                if (event.target.classList.contains('removeColor')) {
                    event.target.closest('.color-entry').remove();
                }

                if (event.target.classList.contains('addColorBtn')) {
                    const productEntry = event.target.closest('.product-entry');
                    const productIndex = productEntry.querySelector('.colorSection').dataset.productIndex;
                    addNewColor(productIndex, productEntry);
                }
            });

            // Add new product
            document.querySelector('.addProductBtn').addEventListener('click', function() {
                productCounter++;
                const newProductEntry = `
            <div class="product-entry card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="ri-shopping-bag-2-line"></i> Product Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="product_image[]" class="form-control">
                    </div>
                    <div class="row mb-3 knit">
                        <div class="col-md-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="product_code[]" class="form-control" 
                                   placeholder="Enter Product Code" >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Function</label>
                            <input type="text" name="product_function[]" class="form-control" 
                                   placeholder="Enter Product Function" >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="product_model[]" class="form-control" 
                                   placeholder="Enter Product Model" >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Shipment Date</label>
                            <input type="date" name="product_shipment_date[]" class="form-control" 
                                   placeholder="Enter Product Shipment Date" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 woven">
                            <label class="form-label">Code</label>
                            <input type="text" name="product_code[]" class="form-control" 
                                   placeholder="Enter Product Code" >
                        </div>
                        <div class="col-md-3 knit">
                            <label class="form-label">Details</label>
                            <input type="text" name="product_details[]" class="form-control" 
                                   placeholder="Enter Product Details" >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fabric</label>
                            <input type="text" name="product_fabric[]" class="form-control" 
                                   placeholder="Enter Product Fabric" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Weight</label>
                            <input type="text" name="product_weight[]" class="form-control" 
                                   placeholder="Enter Product Weight" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Master Box</label>
                            <input type="number" name="product_master_box[]" class="form-control" 
                                   placeholder="Enter Product Master Box Quantity" required>
                        </div>
                    </div>

                    <!-- Colors Section -->
                    
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="ri-palette-fill"></i> Color Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="colorSection" data-product-index="${productCounter}" data-color-counter="0">
                                ${getColorEntryHTML(productCounter, 0)}
                            </div>
                            <button type="button" class="btn btn-outline-primary addColorBtn">
                                <i class="ri-add-line"></i> Add Another Color
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;


                productSection.insertAdjacentHTML('beforeend', newProductEntry);
                const queryProductType = document.getElementById('query_product_type').value;
                console.log(queryProductType);
                if (queryProductType === 'Woven') {
                    const knit = document.querySelectorAll('.knit');
                    knit.forEach((element) => {
                        element.style.display = 'none';
                    });
                    const woven = document.querySelectorAll('.woven');
                    woven.forEach((element) => {
                        element.style.display = 'block';
                    });
                } else if (queryProductType === 'Knit') {
                    const woven = document.querySelectorAll('.woven');
                    woven.forEach((element) => {
                        element.style.display = 'none';
                    });
                    const knit = document.querySelectorAll('.knit');
                    knit.forEach((element) => {
                        element.style.display = 'block';
                    });
                }

                updateSizeQuantities();
            });

            // Helper function to get color entry HTML
            function getColorEntryHTML(productIndex, colorCounter) {
                return `
    <div class="color-entry mb-3">
        <div class="input-group mb-2">
            <span class="input-group-text"><i class="ri-palette-fill"></i></span>
            <input type="text" name="colors[${productIndex}][${colorCounter}][name]" class="form-control" 
                   placeholder="Color Name" required>
            <input type="text" name="colors[${productIndex}][${colorCounter}][details]" class="form-control" 
                   placeholder="Color Details" required>
            <button type="button" class="btn btn-outline-danger removeColor">
                <i class="ri-close-fill"></i>
            </button>
        </div>
        <div class="row mb-2 sizeQuantitiesSection"></div>
    </div>
    `;
            }

            function addNewColor(productIndex, productEntry) {
                const colorSection = productEntry.querySelector('.colorSection');
                const colorIndex = colorSection.querySelectorAll('.color-entry').length;

                const newColorEntry = `
        <div class="color-entry mb-3">
            <div class="input-group mb-2">
                <span class="input-group-text"><i class="ri-palette-fill"></i></span>
                <input type="text" name="colors[${productIndex}][${colorIndex}][name]" class="form-control" 
                    placeholder="Color Name" required>
                <input type="text" name="colors[${productIndex}][${colorIndex}][details]" class="form-control" 
                    placeholder="Color Details" required>
                <button type="button" class="btn btn-outline-danger removeColor">
                    <i class="ri-close-fill"></i>
                </button>
            </div>
            <div class="row mb-2 sizeQuantitiesSection">
                <!-- Size quantity fields -->
                <div class="col">
                    <input type="number" name="colors[${productIndex}][${colorIndex}][quantities][]" 
                        class="form-control" placeholder="M Quantity" required>
                </div>
                <div class="col">
                    <input type="number" name="colors[${productIndex}][${colorIndex}][quantities][]" 
                        class="form-control" placeholder="L Quantity" required>
                </div>
                <!-- Add more sizes as needed -->
            </div>
        </div>
    `;
                colorSection.insertAdjacentHTML('beforeend', newColorEntry);
                updateSizeQuantities();
            }


            // Update size quantities when size input changes
            document.addEventListener('input', function(event) {
                if (event.target.classList.contains('sizeInput')) {
                    updateSizeQuantities();
                }
            });

            // Initial update of size quantities
            updateSizeQuantities();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var searchable = [];
            var selectable = [];
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            var dTable = $('#expenseCategoryTable').DataTable({
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: false,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'collection',
                        text: 'Export',
                        buttons: [{
                                extend: 'copy',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(win) {
                                    $(win.document.body)
                                        .css('font-size', '10pt')
                                        .prepend(
                                            `
                                            <div class="d-flex justify-content-between">
                                                <img src="{{ $settings->logo->absolute_path ?? asset('public/admin-assets/images/logo-sm.png') }}" alt = ""
                                                height = "40" >
                                                <div>
                                                    {{ $settings->full_name ?? '' }} <br>
                                                    {{ $settings->address ?? '' }} <br>
                                                    @if ($settings)
                                                        {{ $settings->contact1 ?? '' }}
                                                        @if ($settings->contact1 && $settings->contact2)
                                                            , {{ $settings->contact2 }}
                                                        @elseif ($settings->contact2)
                                                            {{ $settings->contact2 }}
                                                        @endif
                                                    @endif <br />
                                                    Warehouse : {{ session('user_warehouse')->name ?? '' }}
                                                </div>
                                            </div>
                                             `
                                        );

                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                                }
                            }
                        ]
                    },
                    'colvis'
                ],
                dom: "<'row'<'col-sm-4'l><'col-sm-5 text-center mb-2'B><'col-sm-3'f>>tipr",
                language: {
                    processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('queries.index') }}",
                    type: "get"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'Date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'query_no',
                        name: 'Query No',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'brand',
                        name: 'brand',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'merchandiser',
                        name: 'Merchandiser',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'product_type',
                        name: 'Product Type',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'quantity',
                        name: 'Quantity',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'Status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

        });
        const deleteQuery = (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('queries.destroy') }}",
                        method: 'POST',
                        data: {
                            query_id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#expenseCategoryTable').DataTable().ajax.reload();
                                toaster('Query Deleted Successfully', 'success');
                            } else {
                                toaster(response.error, 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            toaster('Something went wrong', 'danger');
                        }
                    });
                }
            })
        }

        const showRejectionNote = (note) => {
            Swal.fire({
                title: 'Rejection Note',
                text: `${note}`,
                icon: 'info',
                confirmButtonColor: '#556ee6',
            });
        }

        const changeQueryStatus = (id, type) => {
            Swal.fire({
                title: 'Change Query Status',
                html: `
            <select id="queryStatus" class="form-control mb-3">
                <option value="Pending">Pending</option>
                <option value="Rejected">Rejected</option>
                <option value="Sent For Approval">Send For Approval</option>
            </select>
            <textarea id="rejection_note" class="form-control d-none" placeholder="Rejection Note"></textarea>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, change it!',
                didOpen: () => {
                    $('#queryStatus').change(function() {
                        const selectedStatus = $(this).val();
                        if (selectedStatus === 'Rejected') {
                            $('#rejection_note').removeClass('d-none');
                        } else {
                            $('#rejection_note').addClass('d-none');
                        }
                    });
                },
                preConfirm: () => {
                    const status = document.getElementById('queryStatus').value;
                    const rejectionNote = document.getElementById('rejection_note').value;
                    return {
                        status,
                        rejectionNote
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const {
                        status,
                        rejectionNote
                    } = result.value;

                    if (status === 'Sent For Approval') {
                        $('#query_id').val(id);
                        $('#query_product_type').val(type);
                        if (type === 'Woven') {
                            $('.knit').hide();
                            $('.woven').show();
                        }
                        if (type === 'Knit') {
                            $('.woven').hide();
                            $('.knit').show();
                        }
                        $('#orderModal').modal('show');

                    } else {
                        $.ajax({
                            url: "{{ route('queries.change_status') }}",
                            method: 'POST',
                            data: {
                                query_id: id,
                                status: status,
                                rejection_note: rejectionNote,
                                _token: '{{ csrf_token() }}'
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#expenseCategoryTable').DataTable().ajax.reload();
                                    toaster('Query Status Changed Successfully', 'success');
                                } else {
                                    toaster(response.error, 'danger');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                toaster('Something went wrong', 'danger');
                            }
                        });
                    }
                }
            });
        };


        const assignMerchandiser = (id) => {
            Swal.fire({
                title: 'Assign Merchandiser',
                html: `
            <select id="merchandiser" class="form-control" required>
                <option value="">Select Merchandiser</option>
                @foreach ($merchandisers as $merchandiser)
                    <option value="{{ $merchandiser->id }}">{{ $merchandiser->user->username }}</option>
                @endforeach
            </select>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, assign it!',
                preConfirm: () => {
                    const merchandiser = document.getElementById('merchandiser').value;
                    return merchandiser;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedMerchandiser = result.value;
                    $.ajax({
                        url: "{{ route('queries.assign_merchandiser') }}",
                        method: 'POST',
                        data: {
                            query_id: id,
                            merchandiser_id: selectedMerchandiser,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#expenseCategoryTable').DataTable().ajax.reload();
                                toaster('Merchandiser Assigned Successfully', 'success');
                            } else {
                                toaster(response.error, 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            toaster('Something went wrong', 'danger');
                        }
                    });
                }
            });
        }
    </script>
@endsection
