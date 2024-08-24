@extends('admin.layout')
@section('title', 'Show Meeting')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Meeting Details</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">Meetings</a></li>
                                    <li class="breadcrumb-item active">Meeting Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ __('Meeting Details') }}</h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview"
                                            role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="minutes-tab" data-bs-toggle="tab" href="#minutes"
                                            role="tab" aria-controls="minutes" aria-selected="false">Meeting Minutes</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                        aria-labelledby="overview-tab">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-md-6">
                                                <h5>Client</h5>
                                                <p>{{ $meeting->client->company_name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Meeting Title</h5>
                                                <p>{{ $meeting->meeting_title ? $meeting->meeting_title->name : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h5>Meeting Type</h5>
                                                <p>{{ $meeting->meeting_type ? $meeting->meeting_type->name : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Meeting Status</h5>
                                                <p>{{ $meeting->meeting_status ? $meeting->meeting_status->name : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h5>Meeting Date</h5>
                                                <p>{{ $meeting->date->format('d M, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade mt-3" id="minutes" role="tabpanel"
                                        aria-labelledby="minutes-tab">
                                        @foreach ($meeting->meeting_minutes as $minute)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $minute->note }}</h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">
                                                        {{ $minute->created_at->format('d M, Y h:i A') }}</h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <a href="{{ route('meetings.index') }}" class="btn btn-secondary">Back to
                                            Meetings</a>
                                        <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-primary">Edit
                                            Meeting</a>
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
