@extends('admin.layout')
@section('title', 'Show Call')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Call Details</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('calls.index') }}">Calls</a></li>
                                    <li class="breadcrumb-item active">Call Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ __('Call Details') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h5>Client</h5>
                                        <p>{{ $call->client->company_name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Contact Person</h5>
                                        <p>{{ $call->contact_person ? $call->contact_person->name : 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h5>Call Type</h5>
                                        <p>{{ $call->call_type ? $call->call_type->name : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Call Status</h5>
                                        <p>{{ $call->call_status ? $call->call_status->name : 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h5>Call Date</h5>
                                        <p>{{ $call->call_date->format('d M, Y h:i A') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Handled By</h5>
                                        <p>{{ $call->user->first_name }} {{ $call->user->last_name }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h5>Call Summary</h5>
                                        <p>{{ $call->call_summary }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('calls.index') }}" class="btn btn-secondary">Back to Calls</a>
                                        <a href="{{ route('calls.edit', $call->id) }}" class="btn btn-primary">Edit
                                            Call</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
