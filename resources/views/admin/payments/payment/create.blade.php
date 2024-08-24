@extends('admin.layout')
@section('title', 'Make Payment')
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
                            <h4 class="mb-sm-0">Payments</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Payments</a></li>
                                    <li class="breadcrumb-item active">Add Payment</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-body">
                                <form action="{{ route('payments.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()">
                                    @csrf
                                    <div class="row mb-3">
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
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="supplier_customer">
                                                    {{ __('Payment From/To') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="supplier_customer"
                                                    class="form-control select-category @error('supplier_customer') is-invalid @enderror"
                                                    name="supplier_customer">
                                                    <option value="">Select</option>
                                                    <option value="Customer">Customer</option>
                                                    <option value="Supplier">Supplier</option>
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('supplier_customer')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="transaction_type">
                                                    {{ __('Transaction Type') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="transaction_type"
                                                    class="form-control select-category @error('transaction_type') is-invalid @enderror"
                                                    name="transaction_type">
                                                    <option value="">Select</option>
                                                    <option value="Payable">Paid</option>
                                                    <option value="Receivable">Received</option>
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('transaction_type')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="amount">
                                                    Amount
                                                    <span class="text-danger">*</span>
                                                    <span id="balance"></span>
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
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="account_id">
                                                    {{ __('Account') }}
                                                    <span class="text-danger">*</span>
                                                    <span id="current_balance"></span>
                                                </label>
                                                <select id="account_id"
                                                    class="form-control select-category @error('account_id') is-invalid @enderror"
                                                    name="account_id">
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('account_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4 supplier_block" style="display: none">
                                            <div class="form-group">
                                                <label for="supplier_id">
                                                    {{ __('Supplier') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="supplier_id"
                                                    class="form-control select-category @error('supplier_id') is-invalid @enderror"
                                                    name="supplier_id">
                                                    <option value="" selected disabled>Select Supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('supplier_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4 customer_block" style="display: none">
                                            <div class="form-group">
                                                <label for="customer_id">
                                                    {{ __('Customer') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="customer_id"
                                                    class="form-control select-category @error('customer_id') is-invalid @enderror"
                                                    name="customer_id">
                                                    <option value="" selected disabled>Select Customer</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('customer_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button id="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light">Submit</button>
                                            <a href="{{ route('expenses.index') }}" style="border: none"
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

            const account_id = document.querySelector("#account_id");

            $('#supplier_customer').on('change', function() {
                if (supplier_customer.value == 'Customer') {
                    document.querySelector(".customer_block").style.display = 'block';
                    document.querySelector(".supplier_block").style.display = 'none';
                } else if (supplier_customer.value == 'Supplier') {
                    document.querySelector(".customer_block").style.display = 'none';
                    document.querySelector(".supplier_block").style.display = 'block';
                }
            })

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
            checkBalance();

            $('#customer_id').on('change', function() {
                const customer_id = $('#customer_id').val();
                if (customer_id) {
                    $.ajax({
                        url: "{{ route('customers.history') }}",
                        data: {
                            customer_id: customer_id
                        },
                        type: 'GET',
                        success: function(response) {
                            console.log(response.balance);
                            $('#balance').html(`(${response.balance})`);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                }
            })

            $('#supplier_id').on('change', function() {
                const supplier_id = $('#supplier_id').val();
                if (supplier_id) {
                    $.ajax({
                        url: "{{ route('suppliers.history') }}",
                        data: {
                            supplier_id: supplier_id
                        },
                        type: 'GET',
                        success: function(response) {
                            console.log(response.balance);
                            $('#balance').html(`(${response.balance})`);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                }
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
