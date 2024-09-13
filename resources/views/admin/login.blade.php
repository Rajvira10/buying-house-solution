@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp


@extends('admin.login-layout')


@section('title', 'Admin Login')


@section('content')

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center text-white-50 mt-5">
                            <h1 class="text-center text-light pt-4 pb-3">{{ $settings->full_name ?? 'Company Name' }}</h1>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center mt-4">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4" style="padding-bottom: 30px; margin-bottom: 80px;">

                            <div class="card-body p-4 pt-5">

                                <div class="text-center mt-2 pt-4">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p class="text-muted">Please Sign in to continue</p>

                                    @if (session('login_error'))
                                        <p class="server-error-message">{{ session('login_error') }}</p>
                                    @endif

                                </div>

                                <div class="p-2 mt-3">

                                    <form action="{{ route('admin-authenticate') }}" method="post"
                                        class="g-3 needs-validation" novalidate>

                                        @csrf

                                        <div class="mb-3">
                                            <label for="credential" class="form-label">Email/Username</label>
                                            <input type="text" class="form-control" id="credential" name="credential"
                                                placeholder="Enter email/username" required>
                                            <div class="invalid-feedback">
                                                Please enter email/username
                                            </div>
                                        </div>

                                        <div class="mb-3">

                                            <label class="form-label" for="password-input">Password</label>

                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5 password-input"
                                                    placeholder="Enter password" id="password-input" name="password"
                                                    required>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                <div class="invalid-feedback">
                                                    Please enter password
                                                </div>
                                            </div>


                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Sign In</button>
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

    </div>
    <!-- end auth-page-wrapper -->

@endsection
