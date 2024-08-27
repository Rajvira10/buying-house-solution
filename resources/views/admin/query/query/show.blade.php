@extends('admin.layout')
@section('title', 'Query Details')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Query Details</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries</a></li>
                                    <li class="breadcrumb-item active">Query Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title">Query Information</h5>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('queries.edit', $query->id) }}" class="btn btn-success">
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
                                                <td>{{ $query->buyer->user->first_name }}
                                                    {{ $query->buyer->user->last_name }}
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
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Product {{ $index + 1 }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Product Name:</strong></td>
                                                    <td>{{ $item->product_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Details:</strong></td>
                                                    <td>{{ $item->details }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Approximate Quantity:</strong></td>
                                                    <td>{{ $item->approximate_quantity }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Trims:</h6>
                                            <ul>
                                                @foreach ($item->trims as $trim)
                                                    <li>{{ $trim->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <h6>Images:</h6>
                                            <div class="d-flex flex-wrap">
                                                @foreach ($item->images as $image)
                                                    <a href="{{ $image->absolute_path }}" target="_blank">
                                                        <img src="{{ $image->absolute_path }}" alt="Query Image"
                                                            class="img-thumbnail m-1"
                                                            style="width: 100px; height: 100px; object-fit: cover;">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Measurements:</h6>
                                            <ul>
                                                @foreach ($item->measurements as $measurement)
                                                    <li>
                                                        <a href="{{ $measurement->absolute_path }}" target="_blank">
                                                            View File
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
