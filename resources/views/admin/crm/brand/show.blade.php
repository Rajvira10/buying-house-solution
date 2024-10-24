@extends('admin.layout')
@section('title', 'Brand Details')
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
                                    <li class="breadcrumb-item"><a href={{ route('brands.index') }}>Brand</a></li>
                                    <li class="breadcrumb-item active">Brand Details</li>
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
                            {{-- <div class="avatar-lg">
                                <img src={{ asset('public/admin-assets/images/user-dummy-img.jpg') }} alt="user-img"
                                    class="img-thumbnail rounded-circle" />
                            </div> --}}
                        </div>
                        <div class="col">
                            <div class="p-2">
                                <h3 class="text-white mb-1">{{ $brand->name }}</h3>
                                <p class="text-white-75">Brand</p>
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
                                                        <div class="d-flex justify-content-end">
                                                            <a href={{ route('brands.edit', $brand->id) }}
                                                                class="btn btn-success ms-3"><i
                                                                    class="ri-edit-box-line align-bottom "></i> Edit
                                                            </a>
                                                        </div>

                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td><strong>Buyer :</strong></td>
                                                                <td>{{ $brand->buyer->user->username }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Email :</strong></td>
                                                                <td>{{ $brand->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Phone :</strong></td>
                                                                <td>{{ $brand->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Address :</strong></td>
                                                                <td>{{ $brand->address }}</td>
                                                            </tr>
                                                            @foreach ($brand->banks as $bank)
                                                                <tr>
                                                                    <td><strong>Bank Name :</strong></td>
                                                                    <td>{{ $bank->name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Account Number :</strong></td>
                                                                    <td>{{ $bank->account_no }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Branch :</strong></td>
                                                                    <td>{{ $bank->branch }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Swift Code :</strong></td>
                                                                    <td>{{ $bank->swift_code }}</td>
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
