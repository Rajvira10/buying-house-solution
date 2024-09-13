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
                                                <td><strong>Buyer:</strong></td>
                                                <td>{{ $query->buyer->user->username }}
                                                </td>
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
                                                    <td><strong>Product Type:</strong></td>
                                                    <td>{{ $item->productType->name }}</td>
                                                </tr>
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
                                            <h6 class="mt-4 text-secondary">Trims:</h6>
                                            <ul class="list-unstyled">
                                                @foreach ($item->trims as $trim)
                                                    <li><i class="ri-checkbox-circle-line"></i> {{ $trim->name }}</li>
                                                @endforeach
                                            </ul>
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
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
