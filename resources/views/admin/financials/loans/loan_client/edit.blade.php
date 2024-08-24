@extends('admin.layout')
@section('title', 'Edit Loan Client')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Loan Clients</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href={{ route('loans.index') }}>Loan</a></li>
                                    <li class="breadcrumb-item"><a href={{ route('loan_clients.index') }}>Loan Clients</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit Loan Client</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- @include('include.message') --}}
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3>{{ __('Edit Loan Client') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('loan_clients.update', $loan_client->id) }}" method="post"
                                    class="form-group">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="name">
                                                    {{ __('Name') }}
                                                    
                                                </label>
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ $loan_client->name }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('name')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">
                                                    {{ __('Email') }}
                                                    
                                                </label>
                                                <input id="email" type="text"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ $loan_client->email }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('email')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="contact_no">
                                                    {{ __('Contact No') }}
                                                    
                                                </label>
                                                <input id="contact_no" type="text"
                                                    class="form-control @error('contact_no') is-invalid @enderror"
                                                    name="contact_no" value={{ $loan_client->contact_no }} placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('contact_no')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="address">
                                                    {{ __('Address') }}
                                                    
                                                </label>
                                                <input id="address" type="text"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    name="address" value="{{ $loan_client->address }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('address')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn-primary waves-effect waves-light">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
