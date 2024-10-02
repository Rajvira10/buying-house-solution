@extends('admin.layout')
@section('title', 'Query Details')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 text-primary">Query Details</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0 p-2 rounded bg-light">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries</a></li>
                                    <li class="breadcrumb-item active">Query Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-secondary">Query Information</h5>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('queries.edit', $query->id) }}" class="btn btn-success shadow-sm">
                                            <i class="ri-edit-box-line align-bottom"></i> Edit
                                        </a>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Query No:</strong></td>
                                                <td>{{ $query->query_no }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date:</strong></td>
                                                <td>{{ $query->query_date->format('Y-m-d') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Brand:</strong></td>
                                                <td>{{ $query->brand->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Merchandiser:</strong></td>
                                                <td>{{ $query->merchandiser ? $query->merchandiser->user->username : 'N/A' }}
                                            </tr>
                                            <tr>
                                                <td><strong>Product Type:</strong></td>
                                                <td>{{ $query->product_type->name }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($query->items as $index => $item)
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title text-secondary">Product {{ $index + 1 }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">

                                                <tr>
                                                    <td><strong>Product Model:</strong></td>
                                                    <td>{{ $item->product_model }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Target Price:</strong></td>
                                                    <td>{{ $item->target_price }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Price Submission Date:</strong></td>
                                                    <td>{{ date('d/M/Y', strtotime($item->price_submission_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Details:</strong></td>
                                                    <td>{{ $item->details }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Product Name:</strong></td>
                                                    <td>{{ $item->product->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Approximate Quantity:</strong></td>
                                                    <td>{{ $item->approximate_quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Sample Submission Date:</strong></td>
                                                    <td>{{ $item->sample_submission_date ? date('d/M/Y', strtotime($item->sample_submission_date)) : '' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h6 class="text-secondary">Images:</h6>
                                            <div class="d-flex flex-wrap">
                                                @foreach ($item->images as $image)
                                                    <a href="{{ $image->absolute_path }}" target="_blank" class="m-1">
                                                        <img src="{{ $image->absolute_path }}" alt="Query Image"
                                                            class="img-thumbnail shadow-sm"
                                                            style="width: 100px; height: 100px; object-fit: cover;">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-secondary">Measurements:</h6>
                                            <ul class="list-unstyled">
                                                @foreach ($item->measurements as $measurement)
                                                    <li>
                                                        <a href="{{ $measurement->absolute_path }}" target="_blank"
                                                            class="text-primary">
                                                            <i class="ri-file-list-line"></i> View File
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @if ($query->status == 'Approved')
                                        @if (in_array('query.view_specification_sheet', session('user_permissions')))
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <h6 class="text-secondary">Sample Specification Sheets:</h6>

                                                    @if (in_array('query.store_specification_sheet', session('user_permissions')))
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addSpecSheetModal{{ $item->id }}">
                                                            Add Specification Sheet
                                                        </button>
                                                    @endif

                                                    <table class="table table-bordered mt-3">
                                                        <thead>
                                                            <tr>
                                                                <th>Factory</th>
                                                                <th>Date</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($item->specificationSheets as $sheet)
                                                                <tr>
                                                                    <td>{{ $sheet->factory->name }}</td>
                                                                    <td>{{ $sheet->date->format('Y-m-d') }}</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-info btn-sm"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#viewSpecSheetModal{{ $sheet->id }}">
                                                                            View
                                                                        </button>
                                                                        @if (in_array('query.update_specification_sheet', session('user_permissions')))
                                                                            <button type="button"
                                                                                class="btn btn-warning btn-sm"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editSpecSheetModal{{ $sheet->id }}">
                                                                                Edit

                                                                            </button>
                                                                        @endif
                                                                        @if (in_array('query.print_specification_sheet', session('user_permissions')))
                                                                            <a href="{{ route('queries.print_specification_sheet', $sheet->id) }}"
                                                                                class="btn btn-secondary btn-sm"
                                                                                target="_blank">Print</a>
                                                                        @endif
                                                                        @if (in_array('query.destroy_specification_sheet', session('user_permissions')))
                                                                            <form
                                                                                action="{{ route('queries.destroy_specification_sheet', $sheet->id) }}"
                                                                                method="POST" class="d-inline">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                    class="btn btn-danger btn-sm"
                                                                                    onclick="return confirm('Are you sure you want to delete this specification sheet?')">Delete</button>
                                                                            </form>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="modal fade" id="addSpecSheetModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="addSpecSheetModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addSpecSheetModalLabel{{ $item->id }}">
                                                        Add Specification Sheet</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('queries.store_specification_sheet') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="query_item_id" value="{{ $item->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="factory_id" class="form-label">Factory</label>
                                                            <select class="form-select select-category" id="factory_id"
                                                                name="factory_id">
                                                                @foreach ($factories as $factory)
                                                                    <option value="{{ $factory->id }}">
                                                                        {{ $factory->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="date" class="form-label">Date</label>
                                                            <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                data-default-date="{{ date('d/m/Y') }}" type="date"
                                                                class="form-control" name="date">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="approximate_delivery_date"
                                                                class="form-label">Approximate
                                                                Delivery Date</label>
                                                            <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                data-default-date="{{ date('d/m/Y') }}" type="date"
                                                                class="form-control" name="approximate_delivery_date">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="express_courier" class="form-label">Express
                                                                Courier</label>
                                                            <input type="text" class="form-control"
                                                                name="express_courier">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="AWB" class="form-label">AWB</label>
                                                            <input type="text" class="form-control" name="AWB">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="AWB_date" class="form-label">AWB Date</label>
                                                            <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                data-default-date="{{ date('d/m/Y') }}" type="date"
                                                                class="form-control" name="AWB_date">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="required_size"
                                                                class="form-label
                                                                            ">Required
                                                                Size</label>
                                                            <input type="text" class="form-control"
                                                                name="required_size">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="quantity" class="form-label">Quantity</label>
                                                            <input type="number" class="form-control" name="quantity">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="fitting" class="form-label">Fitting</label>
                                                            <input type="text" class="form-control" name="fitting">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="styling" class="form-label">Styling</label>
                                                            <input type="text" class="form-control" name="styling">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="required_fabric_composition"
                                                                class="form-label">Required
                                                                Fabric Composition</label>
                                                            <textarea class="form-control" name="required_fabric_composition"></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="GSM" class="form-label">GSM</label>
                                                            <input type="text" class="form-control" name="GSM">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="fabric_color" class="form-label">Fabric
                                                                Color</label>
                                                            <input type="text" class="form-control"
                                                                name="fabric_color">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="main_label" class="form-label">Main Label</label>
                                                            <input type="text" class="form-control" name="main_label">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="care_label" class="form-label">Care Label</label>
                                                            <input type="text" class="form-control" name="care_label">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="hang_tag" class="form-label">Hang Tag</label>
                                                            <input type="text" class="form-control" name="hang_tag">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="print_instructions" class="form-label">Print
                                                                Instructions</label>
                                                            <textarea class="form-control" name="print_instructions"></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="embroidery_instructions"
                                                                class="form-label">Embroidery Instructions</label>
                                                            <textarea class="form-control" name="embroidery_instructions"></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="button_type" class="form-label">Button
                                                                Type</label>
                                                            <input type="text" class="form-control"
                                                                name="button_type">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="button_size" class="form-label">Button
                                                                Size</label>
                                                            <input type="text" class="form-control"
                                                                name="button_size">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="button_color" class="form-label">Button
                                                                Color</label>
                                                            <input type="text" class="form-control"
                                                                name="button_color">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="button_thread" class="form-label">Button
                                                                Thread</label>
                                                            <input type="text" class="form-control"
                                                                name="button_thread">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="button_hole" class="form-label ">Button
                                                                Hole</label>
                                                            <input type="text" class="form-control"
                                                                name="button_hole">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="zipper_type" class="form-label">Zipper
                                                                Type</label>
                                                            <input type="text" class="form-control"
                                                                name="zipper_type">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="zipper_size" class="form-label">Zipper
                                                                Size</label>
                                                            <input type="text" class="form-control"
                                                                name="zipper_size">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="zipper_color" class="form-label">Zipper
                                                                Color</label>
                                                            <input type="text" class="form-control"
                                                                name="zipper_color">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="zipper_tape" class="form-label ">Zipper
                                                                Tape</label>
                                                            <input type="text" class="form-control"
                                                                name="zipper_tape">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="zipper_puller"
                                                                class="form-label
                                                                            ">Zipper
                                                                Puller</label>
                                                            <input type="text" class="form-control"
                                                                name="zipper_puller">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="other_instructions" class="form-label">Other
                                                                Instructions</label>
                                                            <textarea class="form-control" name="other_instructions"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Specification
                                                            Sheet</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- View and Edit Specification Sheet Modals -->
                                    @foreach ($item->specificationSheets as $sheet)
                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewSpecSheetModal{{ $sheet->id }}"
                                            tabindex="-1" aria-labelledby="viewSpecSheetModalLabel{{ $sheet->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="viewSpecSheetModalLabel{{ $sheet->id }}">View
                                                            Specification Sheet</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Display all specification sheet details -->
                                                        <p><strong>Factory:</strong> {{ $sheet->factory->name }}</p>
                                                        <p><strong>Date:</strong> {{ $sheet->date->format('Y-m-d') }}</p>
                                                        <p><strong>Approximate Delivery Date:</strong>
                                                            {{ $sheet->approximate_delivery_date->format('Y-m-d') }}</p>
                                                        <p><strong>Express Courier:</strong> {{ $sheet->express_courier }}
                                                        </p>
                                                        <p><strong>AWB:</strong>{{ $sheet->AWB }}</p>
                                                        <p><strong>AWB Date:</strong>
                                                            {{ $sheet->AWB_date->format('Y-m-d') }}</p>
                                                        <p><strong>Required Size:</strong> {{ $sheet->required_size }}</p>
                                                        <p><strong>Quantity:</strong> {{ $sheet->quantity }}</p>
                                                        <p><strong>Fitting:</strong> {{ $sheet->fitting }}</p>
                                                        <p><strong>Styling:</strong> {{ $sheet->styling }}</p>
                                                        <p><strong>Required Fabric Composition:</strong>
                                                            {{ $sheet->required_fabric_composition }}</p>
                                                        <p><strong>GSM:</strong> {{ $sheet->GSM }}</p>
                                                        <p><strong>Fabric Color:</strong> {{ $sheet->fabric_color }}</p>
                                                        <p><strong>Main Label:</strong> {{ $sheet->main_label }}</p>
                                                        <p><strong>Care Label:</strong> {{ $sheet->care_label }}</p>
                                                        <p><strong>Hang Tag:</strong> {{ $sheet->hang_tag }}</p>
                                                        <p><strong>Print Instructions:</strong>
                                                            {{ $sheet->print_instructions }}</p>
                                                        <p><strong>Embroidery Instructions:</strong>
                                                            {{ $sheet->embroidery_instructions }}</p>
                                                        <p><strong>Button Type:</strong> {{ $sheet->button_type }}</p>
                                                        <p><strong>Button Size:</strong> {{ $sheet->button_size }}</p>
                                                        <p><strong>Button Color:</strong> {{ $sheet->button_color }}</p>
                                                        <p><strong>Button Thread:</strong> {{ $sheet->button_thread }}</p>
                                                        <p><strong>Button Hole:</strong> {{ $sheet->button_hole }}</p>
                                                        <p><strong>Zipper Type:</strong> {{ $sheet->zipper_type }}</p>
                                                        <p><strong>Zipper Size:</strong> {{ $sheet->zipper_size }}</p>
                                                        <p><strong>Zipper Color:</strong> {{ $sheet->zipper_color }}</p>
                                                        <p><strong>Zipper Tape:</strong> {{ $sheet->zipper_tape }}</p>
                                                        <p><strong>Zipper Puller:</strong> {{ $sheet->zipper_puller }}</p>
                                                        <p><strong>Other Instructions:</strong>
                                                            {{ $sheet->other_instructions }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editSpecSheetModal{{ $sheet->id }}"
                                            tabindex="-1" aria-labelledby="editSpecSheetModalLabel{{ $sheet->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editSpecSheetModalLabel{{ $sheet->id }}">Edit
                                                            Specification Sheet</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form
                                                        action="{{ route('queries.update_specification_sheet', $sheet->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="factory_id" class="form-label">Factory</label>
                                                                <select class="form-select" id="factory_id"
                                                                    name="factory_id">
                                                                    @foreach ($factories as $factory)
                                                                        <option value="{{ $factory->id }}"
                                                                            {{ $sheet->factory_id == $factory->id ? 'selected' : '' }}>
                                                                            {{ $factory->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="date" class="form-label">Date</label>
                                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                    data-default-date="{{ $sheet->date->format('d/m/Y') }}"
                                                                    type="date" class="form-control" name="date">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="approximate_delivery_date"
                                                                    class="form-label">Approximate
                                                                    Delivery Date</label>
                                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                    data-default-date="{{ $sheet->approximate_delivery_date->format('d/m/Y') }}"
                                                                    type="date" class="form-control"
                                                                    name="approximate_delivery_date">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="express_courier" class="form-label">Express
                                                                    Courier</label>
                                                                <input type="text" class="form-control"
                                                                    name="express_courier"
                                                                    value="{{ $sheet->express_courier }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="AWB" class="form-label">AWB</label>
                                                                <input type="text" class="form-control" name="AWB"
                                                                    value="{{ $sheet->AWB }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="AWB_date" class="form-label">AWB Date</label>
                                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                    data-default-date="{{ $sheet->AWB_date->format('d/m/Y') }}"
                                                                    type="date" class="form-control" name="AWB_date">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="required_size"
                                                                    class="form-label
                                                                            ">Required
                                                                    Size</label>
                                                                <input type="text" class="form-control"
                                                                    name="required_size"
                                                                    value="{{ $sheet->required_size }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="quantity" class="form-label">Quantity</label>
                                                                <input type="number" class="form-control"
                                                                    name="quantity" value="{{ $sheet->quantity }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="fitting" class="form-label">Fitting</label>
                                                                <input type="text" class="form-control" name="fitting"
                                                                    value="{{ $sheet->fitting }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="styling" class="form-label">Styling</label>
                                                                <input type="text" class="form-control" name="styling"
                                                                    value="{{ $sheet->styling }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="required_fabric_composition"
                                                                    class="form-label">Required
                                                                    Fabric Composition</label>
                                                                <textarea class="form-control" name="required_fabric_composition">{{ $sheet->required_fabric_composition }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="GSM" class="form-label">GSM</label>
                                                                <input type="text" class="form-control" name="GSM"
                                                                    value="{{ $sheet->GSM }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="fabric_color" class="form-label">Fabric
                                                                    Color</label>
                                                                <input type="text" class="form-control"
                                                                    name="fabric_color"
                                                                    value="{{ $sheet->fabric_color }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="main_label" class="form-label">Main
                                                                    Label</label>
                                                                <input type="text" class="form-control"
                                                                    name="main_label" value="{{ $sheet->main_label }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="care_label" class="form-label">Care
                                                                    Label</label>
                                                                <input type="text" class="form-control"
                                                                    name="care_label" value="{{ $sheet->care_label }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="hang_tag" class="form-label">Hang
                                                                    Tag</label>
                                                                <input type="text" class="form-control"
                                                                    name="hang_tag" value="{{ $sheet->hang_tag }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="print_instructions" class="form-label">Print
                                                                    Instructions</label>
                                                                <textarea class="form-control" name="print_instructions">{{ $sheet->print_instructions }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="embroidery_instructions"
                                                                    class="form-label">Embroidery Instructions</label>
                                                                <textarea class="form-control" name="embroidery_instructions">{{ $sheet->embroidery_instructions }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="button_type" class="form-label">Button
                                                                    Type</label>
                                                                <input type="text" class="form-control"
                                                                    name="button_type" value="{{ $sheet->button_type }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="button_size" class="form-label">Button
                                                                    Size</label>
                                                                <input type="text" class="form-control"
                                                                    name="button_size" value="{{ $sheet->button_size }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="button_color" class="form-label">Button
                                                                    Color</label>
                                                                <input type="text" class="form-control"
                                                                    name="button_color"
                                                                    value="{{ $sheet->button_color }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="button_thread" class="form-label">Button
                                                                    Thread</label>
                                                                <input type="text" class="form-control"
                                                                    name="button_thread"
                                                                    value="{{ $sheet->button_thread }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="button_hole" class="form-label ">Button
                                                                    Hole</label>
                                                                <input type="text" class="form-control"
                                                                    name="button_hole" value="{{ $sheet->button_hole }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="zipper_type" class="form-label">Zipper
                                                                    Type</label>
                                                                <input type="text" class="form-control"
                                                                    name="zipper_type" value="{{ $sheet->zipper_type }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="zipper_size" class="form-label">Zipper
                                                                    Size</label>
                                                                <input type="text" class="form-control"
                                                                    name="zipper_size" value="{{ $sheet->zipper_size }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="zipper_color" class="form-label">Zipper
                                                                    Color</label>
                                                                <input type="text" class="form-control"
                                                                    name="zipper_color"
                                                                    value="{{ $sheet->zipper_color }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="zipper_tape" class="form-label ">Zipper
                                                                    Tape</label>
                                                                <input type="text" class="form-control"
                                                                    name="zipper_tape"
                                                                    value="{{ $sheet->zipper_tape }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="zipper_puller" class="form-label">Zipper
                                                                    Puller</label>
                                                                <input type="text" class="form-control"
                                                                    name="zipper_puller"
                                                                    value="{{ $sheet->zipper_puller }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="other_instructions" class="form-label">Other
                                                                    Instructions</label>
                                                                <textarea class="form-control" name="other_instructions">{{ $sheet->other_instructions }}</textarea>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update
                                                                Specification Sheet</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
    </script>

@endsection
