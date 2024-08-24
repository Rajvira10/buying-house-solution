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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3>{{ $query->query_no }} {{ __('Details') }}</h3>
                                <p>{{ date('d/m/Y', strtotime($query->query_date)) }}</p>
                            </div>
                            <div class="card-bodyg">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="details">
                                                {{ __('Details') }}
                                            </label>
                                            <p id="details">{{ $query->details }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="approximate_quantity">
                                                {{ __('Approximate Quantity') }}
                                            </label>
                                            <p id="approximate_quantity">{{ $query->approximate_quantity }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="trim_ids">
                                                {{ __('Trims') }}
                                            </label>
                                            <ul id="trim_ids">
                                                @foreach ($query->trims as $trim)
                                                    <li>{{ $trim->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="query_images">
                                                {{ __('Query Images') }}
                                            </label>
                                            <div id="query_images" class="d-flex flex-wrap">
                                                @foreach ($query->images as $image)
                                                    <div class="p-2">
                                                        <img src="{{ asset('public/' . $image->absolute_path) }}"
                                                            alt="Query Image" class="img-thumbnail" width="150">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="query_measurements">
                                                {{ __('Query Measurements') }}
                                            </label>
                                            <ul id="query_measurements">
                                                @foreach ($query->measurements as $document)
                                                    @php
                                                        $extension = pathinfo(
                                                            $document->absolute_path,
                                                            PATHINFO_EXTENSION,
                                                        );
                                                    @endphp
                                                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                        <li>
                                                            <img src="{{ $document->absolute_path }}"
                                                                alt="Query Measurement" class="img-thumbnail"
                                                                width="150">
                                                        </li>
                                                    @else
                                                        <li><a href="{{ $document->absolute_path }}"
                                                                target="_blank">{{ $document->file_type }}</a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('queries.edit', $query->id) }}"
                                        class="btn btn-primary waves-effect waves-light">Edit</a>
                                    <a href="{{ route('queries.index') }}"
                                        class="btn btn-secondary waves-effect waves-light">Back to List</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
