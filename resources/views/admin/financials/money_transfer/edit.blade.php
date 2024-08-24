@extends('admin.layout')
@section('title', 'Edit Money Transfer')
@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/gh/mobius1/selectr@latest/dist/selectr.min.css" rel="stylesheet" type="text/css">
        <script src="https://cdn.jsdelivr.net/gh/mobius1/selectr@latest/dist/selectr.min.js" defer type="text/javascript">
        </script>
    </head>

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
                            <h4 class="mb-sm-0">Edit Money Transfer</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Financials</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('money_transfers.index') }}">Money
                                            Transfers</a></li>
                                    <li class="breadcrumb-item active">Edit</li>
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
                                <h3>{{ __('Edit Money Transfer') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('money_transfers.update', $money_transfer->id) }}" method="post"
                                    class="form-group">
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
                                                    value="{{ date('d/m/Y', strtotime($money_transfer->date)) }}"
                                                    placeholder="">
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
                                                <label for="sender_account_id">
                                                    {{ __('Sender Account') }}
                                                    <span class="text-red">*</span>
                                                </label>
                                                <select id="sender_account_id"
                                                    class="form-control select-category @error('sender_account_id') is-invalid @enderror"
                                                    name="sender_account_id">
                                                    <option value="">Select Sender Account</option>
                                                    @foreach ($accounts as $account)
                                                        <option class="sender_account-field" value="{{ $account->id }}"
                                                            {{ $account->id == $money_transfer->sender_account_id ? 'selected' : '' }}>
                                                            {{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('sender_account_id')
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
                                                <label for="receiver_account_id">
                                                    {{ __('Receiver Account') }}
                                                    <span class="text-red">*</span>
                                                </label>
                                                <select id="receiver_account_id"
                                                    class="form-control select-category @error('receiver_account_id') is-invalid @enderror"
                                                    name="receiver_account_id">
                                                    <option value="">Select Receiver Account</option>
                                                    @foreach ($accounts as $account)
                                                        <option class="receiver_account-field" value="{{ $account->id }}"
                                                            {{ $account->id == $money_transfer->receiver_account_id ? 'selected' : '' }}>
                                                            {{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('receiver_account_id')
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
                                                    name="amount" value="{{ $money_transfer->amount }}" placeholder="">
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
                                                <label for="note">
                                                    {{ __('Note') }}
                                                </label>
                                                <textarea name="note" id="note" class="form-control" cols="30" rows="10">{{ $money_transfer->note }}</textarea>
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectCategory = document.querySelectorAll(".select-category");

            for (var i = 0; i < selectCategory.length; i++) {
                new Selectr(selectCategory[i]);
            }

        });
    </script>
@endsection
