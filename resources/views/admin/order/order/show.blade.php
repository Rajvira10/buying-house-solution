@extends('admin.layout')
@section('title', 'Order Details')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- Card for Order Details -->
                <div class="card shadow-lg mb-5">
                    <div class="card-header bg-primary">
                        <h2 class="text-center text-white">Order #{{ $order->order_no }}</h2>
                    </div>
                    <div class="card-body">
                        <!-- Order Info -->
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
                                <h5>Buyer: <span class="text-muted">{{ $order->queryModel->buyer->user->username }}</span>
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
                                            <th>Disculpe</th>
                                            <th>Brand</th>
                                            <th>Code</th>
                                            <th>Function</th>
                                            <th>Model</th>
                                            <th>Fit</th>
                                            <th>Fabric</th>
                                            <th>Weight</th>
                                            <th>Pieces</th>
                                            <th>Shipment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td>{{ $item->disculpe }}</td>
                                                <td>{{ $item->brand }}</td>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ $item->function }}</td>
                                                <td>{{ $item->model }}</td>
                                                <td>{{ $item->fit }}</td>
                                                <td>{{ $item->fabric }}</td>
                                                <td>{{ $item->weight }}</td>
                                                <td>{{ $item->pieces }}</td>
                                                <td>{{ $item->shipment_date->format('d-m-Y') }}</td>
                                            </tr>

                                            <!-- Nested table for Order Item Colors -->
                                            <tr>
                                                <td colspan="10">
                                                    <h6 class="text-primary">Colors</h6>
                                                    <table class="table table-sm table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Color</th>
                                                                <th>Color Details</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($item->colors as $color)
                                                                <tr>
                                                                    <td>{{ $color->color }}</td>
                                                                    <td>{{ $color->color_details }}</td>
                                                                    <td>{{ $color->quantity }}</td>
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
