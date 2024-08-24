@extends('admin.layout')
@section('title', 'Show Project')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Project Details</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                                    <li class="breadcrumb-item active">Project Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ __('Project Details') }}</h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview"
                                            role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="phases-tab" data-bs-toggle="tab" href="#phases"
                                            role="tab" aria-controls="phases" aria-selected="false">Phases</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="payments-tab" data-bs-toggle="tab" href="#payments"
                                            role="tab" aria-controls="payments" aria-selected="false">Payments</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                        aria-labelledby="overview-tab">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-md-6">
                                                <h5>Client</h5>
                                                <p>{{ $project->client->company_name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Name</h5>
                                                <p>{{ $project->name ? $project->name : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h5>Project Type</h5>
                                                <p>{{ $project->project_type ? $project->project_type->name : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Project Status</h5>
                                                <p>{{ $project->project_status ? $project->project_status->name : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h5>Project Start Date</h5>
                                                <p>{{ $project->start_date->format('d M, Y') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Project End Date</h5>
                                                @if ($project->end_date)
                                                    <div>{{ $project->end_date->format('d M, Y') }}</div>
                                                @else
                                                    <div>N/A</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h5>Project Total Amount</h5>
                                                <p>{{ number_format($project->project_phases()->sum('amount'), 2, '.', ',') }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Project Note</h5>
                                                <p>{{ $project->note ? $project->note : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="phases" role="tabpanel" aria-labelledby="phases-tab">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-md-12">
                                                <h5>Project Phases</h5>
                                            </div>
                                        </div>
                                        @foreach ($project->project_phases as $phase)
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <h6>Phase Name</h6>
                                                    <p>{{ $phase->name }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Phase Amount</h6>
                                                    <p>{{ number_format($phase->amount, 2, '.', ',') }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Phase Status</h6>
                                                    <p>{{ $phase->projectPhaseStatus->name }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Phase Dates</h6>
                                                    <p>{{ $phase->start_date->format('d M, Y') }} -
                                                        {{ $phase->end_date ? $phase->end_date->format('d M, Y') : 'N/A' }}
                                                </div>
                                                <div class="col-md-12">
                                                    <h6>Phase Note</h6>
                                                    <p>{{ $phase->description ? $phase->description : 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>
                                    <div class="tab-pane fade" id="payments" role="tabpanel"
                                        aria-labelledby="payments-tab">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-md-12">
                                                <h5>Project Payments</h5>
                                            </div>
                                        </div>
                                        @foreach ($project->payments as $payment)
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <h6>Payment Date</h6>
                                                    <p>{{ date('d M, Y', strtotime($payment->date)) }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Payment Amount</h6>
                                                    <p>{{ number_format($payment->amount, 2, '.', ',') }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Account</h6>
                                                    <p>{{ $payment->account->name }}</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Payment Note</h6>
                                                    <p>{{ $payment->note ? $payment->note : 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back to
                                            Projects</a>
                                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary">Edit
                                            Project</a>
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
