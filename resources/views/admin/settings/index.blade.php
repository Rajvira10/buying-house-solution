@extends('admin.layout')
@section('title', 'Settings')
@section('content')
    <style>
        .selectr-input {
            outline: none;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3>{{ __('Settings') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('settings.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="full_name">
                                                    {{ __('Full Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="full_name"
                                                    class="form-control @error('full_name') is-invalid @enderror"
                                                    value="{{ $settings->full_name ?? '' }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('full_name')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="short_name">
                                                    {{ __('Short Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="short_name"
                                                    class="form-control @error('short_name') is-invalid @enderror"
                                                    value="{{ $settings->short_name ?? '' }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('short_name')
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
                                                <label for="logo">
                                                    {{ __('Logo') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="logo" type="file"
                                                    class="form-control @error('logo') is-invalid @enderror" name="logo"
                                                    value="{{ '#saggqgqgq' }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('logo')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @if (isset($settings) && $settings->logo_id)
                                                    <img src="{{ $settings->logo->absolute_path }}" alt="Logo"
                                                        width="100">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="favicon">
                                                    {{ __('Favicon') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="favicon" type="file"
                                                    class="form-control @error('favicon') is-invalid @enderror"
                                                    name="favicon" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('favicon')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @if (isset($settings) && $settings->favicon_id)
                                                    <img src="{{ $settings->favicon->absolute_path }}" alt="Favicon"
                                                        width="100">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="address">
                                                    {{ __('Address') }}
                                                </label>
                                                <input type="text" name="address"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    value="{{ $settings->address ?? '' }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('address')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="contact1">
                                                    {{ __('Contact 1') }}
                                                </label>
                                                <input id="contact1" type="text"
                                                    class="form-control @error('contact1') is-invalid @enderror"
                                                    name="contact1" value="{{ $settings->contact1 ?? '' }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('contact1')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="contact2">
                                                    {{ __('Contact 2') }}
                                                </label>
                                                <input id="contact2" type="text"
                                                    class="form-control @error('contact2') is-invalid @enderror"
                                                    name="contact2" value="{{ $settings->contact2 ?? '' }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('contact2')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email1">
                                                    {{ __('Email 1') }}
                                                </label>
                                                <input id="email1" type="text"
                                                    class="form-control @error('email1') is-invalid @enderror"
                                                    name="email1" value="{{ $settings->email1 ?? '' }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('email1')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email2">
                                                    {{ __('Email 2') }}
                                                </label>
                                                <input id="email2" type="text"
                                                    class="form-control @error('email2') is-invalid @enderror"
                                                    name="email2" value="{{ $settings->email2 ?? '' }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('email2')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="website">
                                                    {{ __('Website') }}
                                                </label>
                                                <input id="website" type="text"
                                                    class="form-control @error('website') is-invalid @enderror"
                                                    name="website" value="{{ $settings->website ?? '' }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('website')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" id="submit"
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

@section('custom-script')
    @include('admin.message')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectCategory = document.querySelectorAll(".select-category");
            for (let i = 0; i < selectCategory.length; i++) {
                new Selectr(selectCategory[i]);
            }
        });

        const disableOnSubmit = () => {
            const button = document.querySelector('#submit');
            button.disabled = true;
            button.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
            return true;
        }
    </script>
@endsection
