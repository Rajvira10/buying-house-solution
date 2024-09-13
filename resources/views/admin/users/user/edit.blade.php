@extends('admin.layout')
@section('title', 'Edit User')
@section('content')


    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Users</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                                    <li class="breadcrumb-item active">Edit User</li>
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
                                <h3>{{ __('Edit User') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('users.update', $user->id) }}" method="post" class="form-group">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="username">
                                                    First Name
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="username" type="text"
                                                    class="form-control @error('username') is-invalid @enderror"
                                                    name="username" value="{{ $user->username }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('username')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="Email Address">
                                                    Email Address
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="email" type="text"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ $user->email }}" placeholder="">
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
                                                <label for="password">
                                                    Password
                                                </label>
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('password')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="password_confirmation">
                                                    Confirm Password
                                                </label>
                                                <input id="password_confirmation" type="password" class="form-control"
                                                    name="password_confirmation" placeholder="">
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
