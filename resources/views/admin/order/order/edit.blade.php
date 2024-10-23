@extends('admin.layout')
@section('title', 'Edit Order')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="card shadow-lg mb-5">
                    <div class="card-header bg-primary">
                        <h2 class="text-center text-white">Edit Order #{{ $order->order_no }}</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Order Date: <span class="text-muted">{{ $order->order_date->format('d-m-Y') }}</span></h5>
                                </div>
                                <div class="col-md-4">
                                    <h5>Query No: <span class="text-muted">{{ $order->queryModel->query_no }}</span></h5>
                                </div>
                                <div class="col-md-4">
                                    <h5>Total Quantity: <span class="text-muted">{{ $order->total_quantity }}</span></h5>
                                </div>
                            </div>

                            <!-- Divider -->
                            <hr class="border border-primary">

                            <!-- Edit Items -->
                            <div class="my-4">
                                <h4 class="text-primary">Order Items</h4>
                                @foreach ($order->items as $index => $item)
                                    @php
                                        $colors = json_decode($item->colors);
                                        $sizes = json_decode($item->sizes);
                                    @endphp
                                    <div class="mb-4">
                                        <label for="item[{{ $index }}]">Item</label>
                                        <input type="text" name="item[{{ $index }}]" value="{{ $item->item }}" class="form-control">

                                        <!-- Repeat similar structure for other fields (style_no, factory_cost, final_cost, etc.) -->

                                        <label for="colors[{{ $index }}]">Colors</label>
                                        <input type="text" name="colors[{{ $index }}]" value="{{ json_encode($colors) }}" class="form-control">

                                        <label for="product_image[{{ $index }}]">Product Image</label>
                                        <input type="file" name="product_image[{{ $index }}]" class="form-control">
                                        @if ($item->image)
                                            <img src="{{ asset($item->image) }}" alt="Image" class="img-fluid mt-2" style="max-width: 100px;">
                                        @endif

                                        <label for="shipment_date[{{ $index }}]">Shipment Date</label>
                                        <input type="date" name="shipment_date[{{ $index }}]" value="{{ $item->shipment_date->format('Y-m-d') }}" class="form-control">
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary">Update Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
