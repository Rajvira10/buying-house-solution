@extends('admin.layout')
@section('title', 'Client')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0"></h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                                    <li class="breadcrumb-item active">Client Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-foreground position-relative mx-n4 mt-n4">
                    <div class="profile-wid-bg">
                        <img src="{{ asset('public/admin-assets/images/profile-bg.jpg') }}" alt=""
                            class="profile-wid-img" />
                    </div>
                </div>
                <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
                    <div class="row g-4">
                        <div class="col-auto">
                            <div class="avatar-lg">
                                <img src="{{ asset('public/admin-assets/images/user-dummy-img.jpg') }}" alt="user-img"
                                    class="img-thumbnail rounded-circle" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2">
                                <h3 class="text-white mb-1">{{ $client->company_name }}</h3>
                                <p class="text-white-75">Client </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="d-flex">
                                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab"
                                            role="tab">
                                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Overview</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#contact-persons-tab"
                                            role="tab">
                                            <i class="ri-contacts-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Contact Persons</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#calls-tab" role="tab">
                                            <i class="ri-phone-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Calls</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#meetings-tab" role="tab">
                                            <i class="ri-phone-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Meetings</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects-tab" role="tab">
                                            <i class="ri-phone-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Projects</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content pt-4 text-muted">
                                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-3">About</h5>
                                                    <div class="flex-shrink-0">
                                                        <a href="{{ route('clients.edit', $client->id) }}"
                                                            class="btn btn-success"><i
                                                                class="ri-edit-box-line align-bottom"></i> Edit </a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td><strong>Company Name :</strong></td>
                                                                <td>{{ $client->company_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Email :</strong></td>
                                                                <td>{{ $client->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Contact Number :</strong></td>
                                                                <td>{{ $client->contact_no }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Client Source :</strong></td>
                                                                <td>{{ $client->client_source->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Business Category :</strong></td>
                                                                <td>{{ $client->business_category->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Interested In :</strong></td>
                                                                <td>{{ $client->interested_in->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Client Status :</strong></td>
                                                                <td>{{ $client->client_status->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Country :</strong></td>
                                                                <td>{{ $client->country->name ?? '' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>State :</strong></td>
                                                                <td>{{ $client->state->name ?? '' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>City :</strong></td>
                                                                <td>{{ $client->city->name ?? '' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Address :</strong></td>
                                                                <td>{{ $client->address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Note :</strong></td>
                                                                <td>{{ $client->note }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="contact-persons-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">Contact Persons</h5>
                                                <hr>
                                                <div class="row text-black">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th><strong>#</strong></th>
                                                            <th><strong>Name</strong></th>
                                                            <th><strong>Designation</strong></th>
                                                            <th><strong>Email</strong></th>
                                                            <th><strong>Phone</strong></th>
                                                        </tr>
                                                        @foreach ($client->contact_persons as $contact_person)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $contact_person->name }}</td>
                                                                <td>{{ $contact_person->designation }}</td>
                                                                <td>{{ $contact_person->email }}</td>
                                                                <td>{{ $contact_person->phone }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="calls-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">Calls</h5>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <th><strong>#</strong></th>
                                                                <th><strong>Call Date</strong></th>
                                                                <th><strong>Called By</strong></th>
                                                                <th><strong>Call Type</strong></th>
                                                                <th><strong>Call Status</strong></th>
                                                                <th><strong>Action</strong></th>
                                                            </tr>
                                                            @foreach ($client->calls as $call)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ date('d M, Y h:i A', strtotime($call->call_date)) }}
                                                                    </td>
                                                                    <td>{{ $call->user->first_name }}
                                                                        {{ $call->user->last_name }}
                                                                    </td>
                                                                    <td>{{ $call->call_type->name }}</td>
                                                                    <td>{{ $call->call_status->name }}</td>
                                                                    <td>
                                                                        <a href="{{ route('calls.show', $call->id) }}"
                                                                            class="btn btn-primary btn-sm">View</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add more tabs as needed -->
                                </div>

                                <div class="tab-pane" id="meetings-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">Meetings</h5>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <th><strong>#</strong></th>
                                                                <th><strong>Meeting Date</strong></th>
                                                                <th><strong>Meeting Time</strong></th>
                                                                <th><strong>Meeting With</strong></th>
                                                                <th><strong>Meeting Type</strong></th>
                                                                <th><strong>Meeting Status</strong></th>
                                                                <th><strong>Action</strong></th>
                                                            </tr>
                                                            @foreach ($client->meetings as $meeting)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ date('d M, Y', strtotime($meeting->meeting_date)) }}
                                                                    </td>
                                                                    <td>{{ date('h:i A', strtotime($meeting->meeting_time)) }}
                                                                    </td>
                                                                    <td>{{ $meeting->meeting_with }}</td>
                                                                    <td>{{ $meeting->meeting_type->name }}</td>
                                                                    <td>{{ $meeting->meeting_status->name }}</td>
                                                                    <td>
                                                                        <a href="{{ route('meetings.show', $meeting->id) }}"
                                                                            class="btn btn-primary btn-sm">View</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="tab-pane" id="projects-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">Projects</h5>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <th><strong>#</strong></th>
                                                                <th><strong>Project Name</strong></th>
                                                                <th><strong>Project Type</strong></th>
                                                                <th><strong>Project Status</strong></th>
                                                                <th><strong>Start Date</strong></th>
                                                                <th><strong>End Date</strong></th>
                                                                <th><strong>Amount</strong></th>
                                                                <th><strong>Action</strong></th>
                                                            </tr>
                                                            @foreach ($client->projects as $project)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $project->name }}</td>
                                                                    <td>{{ $project->project_type->name }}</td>
                                                                    <td>{{ $project->project_status->name }}</td>
                                                                    <td>{{ date('d M, Y', strtotime($project->start_date)) }}
                                                                    </td>
                                                                    <td>{{ $project->end_date ? date('d M, Y', strtotime($project->end_date)) : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $project->amount }}</td>
                                                                    <td>
                                                                        <a href="{{ route('projects.show', $project->id) }}"
                                                                            class="btn btn-primary btn-sm">View</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
