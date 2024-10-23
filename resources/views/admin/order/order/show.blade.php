@extends('admin.layout')
@section('title', 'Order Details')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="card shadow-lg mb-5">
                    <div class="card-header bg-primary">
                        <h2 class="text-center text-white">Order #{{ $order->order_no }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h5>Order Date: <span class="text-muted">{{ $order->order_date->format('d-m-Y') }}</span>
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <h5>Query No: <span class="text-muted">{{ $order->queryModel->query_no }}</span></h5>
                            </div>
                            <div class="col-md-4">
                                <h5>Total Quantity: <span class="text-muted">{{ $order->total_quantity }}</span></h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h5>Product Type: <span
                                        class="text-muted">{{ $order->queryModel->product_type->name }}</span></h5>
                            </div>
                            <div class="col-md-4">
                                <h5>Brand: <span class="text-muted">{{ $order->queryModel->brand->name }}</span></h5>
                            </div>
                            <div class="col-md-4">
                                <h5>Merchandiser: <span
                                        class="text-muted">{{ $order->queryModel->merchandiser->user->username ?? 'N/A' }}</span>
                                </h5>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="border border-primary">

                        <!-- Order Items Section -->
                        <div class="my-4">
                            <h4 class="text-primary">Order Items</h4>
                            @foreach ($order->items as $item)
                                @php
                                    $colors = json_decode($item->colors);
                                    $sizes = json_decode($item->sizes);
                                    $totalQuantityOfProduct = 0;

                                    // Calculate total quantity for all colors of this item
                                    foreach ($colors as $color) {
                                        $totalQuantityOfProduct += array_sum((array) $color->quantities);
                                    }
                                @endphp
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Image</th>
                                            <th>Brand</th>
                                            <th>Style No</th>
                                            <th>Item</th>
                                            <th>Factory Cost</th>
                                            <th>Final Cost</th>
                                            <th>Color</th>
                                            <th>Code</th>
                                            <th colspan="{{ count($sizes) }}">Size Ratios</th>
                                            <th colspan="{{ count($sizes) }}">Quantities</th>
                                            <th>Total Size Ratio</th>
                                            <th>Inner Polybag Count</th>
                                            <th>QNT X COL</th>
                                            <th>Total Quantity</th>
                                            <th>Shipment Date</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            @foreach ($sizes as $size)
                                                <th>{{ $size }}</th>
                                            @endforeach
                                            @foreach ($sizes as $size)
                                                <th>{{ $size }}</th>
                                            @endforeach
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($colors as $colorIndex => $color)
                                            @php
                                                $sizeQuantities = (array) $color->quantities;
                                                $sizeRatios = (array) $color->size_ratios;
                                                $totalQuantity = array_sum($sizeQuantities);
                                                $totalSizeRatio = array_sum($sizeRatios);
                                            @endphp
                                            <tr>
                                                @if ($loop->first)
                                                    <td rowspan="{{ count($colors) }}">
                                                        @if ($item->image)
                                                            <img src="{{ asset($item->image) }}" alt="Image"
                                                                class="img-fluid" style="max-width: 100px;">
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td rowspan="{{ count($colors) }}">
                                                        {{ $item->order->queryModel->brand->name }}
                                                    </td>
                                                    <td rowspan="{{ count($colors) }}">{{ $item->style_no }}</td>
                                                    <td rowspan="{{ count($colors) }}">{{ $item->item }}</td>
                                                    <td rowspan="{{ count($colors) }}">
                                                        {{ number_format($item->factory_cost, 2) }}
                                                    </td>
                                                    <td rowspan="{{ count($colors) }}">
                                                        {{ number_format($item->final_cost, 2) }}
                                                    </td>
                                                @endif
                                                <td>{{ $color->name }}</td>
                                                <td>{{ $color->code }}</td>
                                                @foreach ($sizes as $size)
                                                    <td>{{ $sizeRatios[$size] ?? 'N/A' }}</td>
                                                @endforeach
                                                @foreach ($sizes as $size)
                                                    <td>{{ $sizeQuantities[$size] ?? 'N/A' }}</td>
                                                @endforeach
                                                <td>{{ $totalSizeRatio }}</td>
                                                <td>{{ $color->inner_polybag ?? 'N/A' }}</td>
                                                <td>{{ $totalQuantity }}</td>
                                                @if ($loop->first)
                                                    <td rowspan="{{ count($colors) }}">{{ $totalQuantityOfProduct }}</td>
                                                    <td rowspan="{{ count($colors) }}">
                                                        {{ $item->shipment_date->format('d-m-Y') }}
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .table-responsive {
            position: relative;
            max-height: 600px;
            overflow-y: auto;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: #f8f9fa;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
