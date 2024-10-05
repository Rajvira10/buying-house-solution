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
                                        class="text-muted">{{ $order->queryModel->product_type->name }}</span>
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <h5>Brand: <span class="text-muted">
                                        {{ $order->queryModel->brand->name }}</span></h5>
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <h5>Merchandiser: <span
                                        class="text-muted">{{ $order->queryModel->merchandiser->user->username }}</span>
                                </h5>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="border border-primary">

                        <!-- Order Items Section -->
                        <div class="my-4">
                            <h4 class="text-primary">Order Items</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light sticky-header">
                                        <tr>
                                            @if ($order->product_type == 'Knit')
                                                <th>Image</th>
                                            @endif
                                            <th>Code</th>
                                            @if ($order->product_type == 'Knit')
                                                <th>Function</th>
                                                <th>Model</th>
                                                <th>Fit</th>
                                            @endif
                                            <th>Fabric</th>
                                            <th>Weight</th>
                                            @if ($order->product_type == 'Knit')
                                                <th>Shipment Date</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                @if ($order->product_type == 'Knit')
                                                    <td>
                                                        <img src="{{ asset($item->image) }}" alt="Image"
                                                            class="img-fluid" style="max-width: 100px;">
                                                    </td>
                                                @endif

                                                <td>{{ $item->code }}</td>
                                                @if ($order->product_type == 'Knit')
                                                    <td>{{ $item->function }}</td>
                                                    <td>{{ $item->model }}</td>
                                                    <td>{{ $item->fit }}</td>
                                                @endif
                                                <td>{{ $item->fabric }}</td>
                                                <td>{{ $item->weight }}</td>
                                                @if ($order->product_type == 'Knit')
                                                    <td>{{ $item->shipment_date->format('d-m-Y') }}</td>
                                                @endif
                                            </tr>

                                            <!-- Nested table for Order Item Colors -->
                                            @php
                                                $colors = json_decode($item->colors);
                                                $sizes = json_decode($item->sizes);
                                                $totalQuantities = 0;
                                            @endphp

                                            <tr>
                                                <td colspan="10">
                                                    <h6 class="text-primary">Colors</h6>
                                                    <table class="table table-sm table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Color</th>
                                                                <th>Color Details</th>
                                                                @foreach ($sizes as $size)
                                                                    <th>{{ $size }}</th>
                                                                @endforeach
                                                                <th>PCS X MASTER BOX</th>
                                                                <th>MASTER BOX</th>
                                                                <th>QNT/COL</th>
                                                                <th>Pieces</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($colors as $index => $color)
                                                                @php
                                                                    $rowQuantities = is_object($color->quantities)
                                                                        ? (array) $color->quantities
                                                                        : $color->quantities;
                                                                    $sumRowQuantities = is_array($rowQuantities)
                                                                        ? array_sum($rowQuantities)
                                                                        : 0;
                                                                    $totalQuantities += $sumRowQuantities;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $color->name }}</td>
                                                                    <td>{{ $color->details }}</td>

                                                                    @foreach ($sizes as $size)
                                                                        <td>
                                                                            @if (is_object($color->quantities))
                                                                                {{ $color->quantities->$size ?? 0 }}
                                                                            @elseif (is_array($color->quantities))
                                                                                {{ $rowQuantities[$loop->index] ?? 0 }}
                                                                            @else
                                                                                0
                                                                            @endif
                                                                        </td>
                                                                    @endforeach

                                                                    @if ($loop->first)
                                                                        <td rowspan="{{ count($colors) }}">
                                                                            {{ $item->pieces }}</td>
                                                                        <td rowspan="{{ count($colors) }}">
                                                                            {{ $item->master_box }}</td>
                                                                    @endif

                                                                    <td>{{ $sumRowQuantities * $item->master_box }}</td>
                                                                    @if ($loop->first)
                                                                        <td rowspan="{{ count($colors) }}">
                                                                            {{ $item->pieces }}</td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
