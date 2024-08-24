@extends('admin.layout')
@section('title', 'Add Investment Return')
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
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Investments</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('investments.index') }}">Investments</a>
                                    </li>
                                    <li class="breadcrumb-item active">Add Return</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3>Return for {{ $investment->investment_no }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('investments.storeReturn', $investment->id) }}" method="post"
                                    class="form-group" onsubmit="return disableOnSubmit()">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="amount">
                                                    Amount
                                                    <span class="text-danger">*</span>
                                                    <span class="text-muted">( Due: {{ $due }})</span>
                                                </label>
                                                <input id="amount" type="text"
                                                    class="form-control @error('amount') is-invalid @enderror"
                                                    name="amount" value="{{ old('amount') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('amount')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="date">
                                                    {{ __('Date') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                    data-default-date="{{ date('d/m/Y') }}" id="date" type="date"
                                                    class="form-control @error('date') is-invalid @enderror" name="date"
                                                    value="{{ old('date') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('date')
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
                                                <label for="account_id">
                                                    {{ __('Account') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="account_id"
                                                    class="form-control select-category @error('account_id') is-invalid @enderror"
                                                    name="account_id">
                                                    <option value="">Select an Account</option>
                                                    @foreach ($accounts as $account)
                                                        <option class="account-field" value="{{ $account->id }}">
                                                            {{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('account_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="note">
                                                    {{ __('Note') }}
                                                </label>
                                                <input type="text" name="note"
                                                    class="form-control @error('note') is-invalid @enderror"
                                                    value="{{ old('note') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('note')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button id="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light">Submit</button>
                                            <a href="{{ route('investments.index') }}" style="border: none"
                                                class="btn btn-outline-danger waves-effect waves-light float-right">Cancel</a>

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
