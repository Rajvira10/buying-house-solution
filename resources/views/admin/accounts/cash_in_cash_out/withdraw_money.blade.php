@extends('admin.layout')
@section('title', 'Withdraw Money')
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
                            <h4 class="mb-sm-0">Withdraw Money</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('cash_in_cash_outs.index') }}">Add/Withdraw
                                            Money</a></li>
                                    <li class="breadcrumb-item active">Withdraw Money</li>
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
                                <h3>{{ __('Withdraw Money') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('withdraw_money.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="date">
                                                    {{ __('Date') }}
                                                    <span class="text-red">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                    data-default-date="{{ date('d/m/Y') }}" id="date" type="date"
                                                    class="form-control @error('date') is-invalid @enderror" name="date"
                                                    value="{{ old('date') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('date')
                                                    <span class="text-red-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="amount">
                                                    {{ __('Amount') }}
                                                    <span class="text-red">*</span>
                                                </label>
                                                <input id="amount" type="text"
                                                    class="form-control @error('amount') is-invalid @enderror"
                                                    name="amount" value="{{ old('amount') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('amount')
                                                    <span class="text-red-error" role="alert">
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
                                                    <span class="text-red">*</span>
                                                    <span id="current_balance"></span>
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
                                                    <span class="text-red-error" role="alert">
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
                                                    <span class="text-red-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light"
                                                id="submit">Submit</button>
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
            const selectCategory = document.querySelector(".select-category");
            new Selectr(selectCategory);

            const checkBalance = () => {
                const accountId = $('#account_id').val();

                $.ajax({
                    url: "{{ route('accounts.balance') }}",
                    data: {
                        account_id: accountId
                    },
                    type: 'GET',
                    success: function(response) {
                        $('#current_balance').html(`(${response.current_balance})`);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
            $('#account_id').on('change', function() {
                checkBalance();
            })
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
