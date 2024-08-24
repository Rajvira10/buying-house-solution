@extends('admin.layout')
@section('title', 'Loan Client')
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
                                    <li class="breadcrumb-item"><a href={{ route('loans.index') }}>Loan</a></li>
                                    <li class="breadcrumb-item"><a href={{ route('loan_clients.index') }}>Loan Clients</a>
                                    </li>
                                    <li class="breadcrumb-item active">Loan Client Details</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="profile-foreground position-relative mx-n4 mt-n4">
                    <div class="profile-wid-bg">
                        <img src={{ asset('public/admin-assets/images/profile-bg.jpg') }} alt=""
                            class="profile-wid-img" />
                    </div>
                </div>
                <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
                    <div class="row g-4">
                        <div class="col-auto">
                            <div class="avatar-lg">
                                <img src={{ asset('public/admin-assets/images/user-dummy-img.jpg') }} alt="user-img"
                                    class="img-thumbnail rounded-circle" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-2">
                                <h3 class="text-white mb-1">{{ $loan_client->name }}</h3>
                                <p class="text-white-75">Loan Client</p>
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
                                                        <a href={{ route('loan_clients.edit', $loan_client->id) }}
                                                            class="btn btn-success"><i
                                                                class="ri-edit-box-line align-bottom"></i> Edit </a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td><strong>Name :</strong></td>
                                                                <td>{{ $loan_client->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Unique ID :</strong></td>
                                                                <td>{{ $loan_client->unique_id }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Email :</strong></td>
                                                                <td>{{ $loan_client->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Contact No :</strong></td>
                                                                <td>{{ $loan_client->contact_no }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Address :</strong></td>
                                                                <td>{{ $loan_client->address }}</td>
                                                            </tr>
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

            @endsection
