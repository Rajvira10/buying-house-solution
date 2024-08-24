@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp


@extends('admin.layout')
@section('title', 'Account Statements')
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
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-3">
                                        <h4 class="card-title mb-0">Account Statements</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" data-provider="flatpickr" data-date-format="d M, Y"
                                            data-range-date="true" class="form-control" id="date-range">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="accounts" id="accounts">
                                            <option value="" selected disabled>Select Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-sm-auto">
                                        <a href="{{ route('account_statements.create') }}">
                                            <button type="button" class="btn btn-success add-btn">
                                                <i class="ri-add-line align-bottom me-1"></i> Add
                                            </button>
                                        </a>
                                    </div> --}}
                                </div>
                                <div class="row">

                                </div>
                            </div>

                            <div class="card-body">
                                <div id="accountStatementsList">
                                    <div class="card-body">
                                        <table id="accountStatementsTable" class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Statement Date') }}</th>
                                                    <th>{{ __('Transaction Date') }}</th>
                                                    <th>{{ __('Type') }}</th>
                                                    <th>{{ __('Description') }}</th>
                                                    <th>{{ __('Debit') }}</th>
                                                    <th>{{ __('Credit') }}</th>
                                                    <th>{{ __('Balance') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div><!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end col -->
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom-script')

    @include('admin.message')
    <script type="text/javascript">
        var dTable = null;

        $(document).ready(function() {
            var searchable = [];
            var selectable = [];
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            dTable = $('#accountStatementsTable').DataTable({
                order: [],
                lengthMenu: [
                    [25, 50, 100, -1],
                    [25, 50, 100, "All"]
                ],
                destroy: true,
                processing: true,
                responsive: true,
                serverSide: false,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'collection',
                        text: 'Export',
                        buttons: [{
                                extend: 'copy',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'csv',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'pdf',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':visible'
                                },
                                customize: function(win) {
                                    $(win.document.body)
                                        .css('font-size', '10pt')
                                        .prepend(
                                            `
                                            <div class="d-flex justify-content-between">
                                                <img src="{{ $settings->logo->absolute_path ?? asset('public/admin-assets/images/logo-sm.png') }}" alt = ""
                                                height = "40" >
                                                <div>
                                                    {{ $settings->full_name ?? '' }} <br>
                                                    {{ $settings->address ?? '' }} <br>
                                                    @if ($settings)
                                                        {{ $settings->contact1 ?? '' }}
                                                        @if ($settings->contact1 && $settings->contact2)
                                                            , {{ $settings->contact2 }}
                                                        @elseif ($settings->contact2)
                                                            {{ $settings->contact2 }}
                                                        @endif
                                                    @endif <br />
                                                    Warehouse : {{ session('user_warehouse')->name ?? '' }}
                                                </div>
                                            </div>
                                             `
                                        );

                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                                }
                            }
                        ]
                    },
                    'colvis'
                ],
                dom: "<'row'<'col-sm-4'l><'col-sm-5 text-center mb-2'B><'col-sm-3'f>>tipr",
                language: {
                    processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                ajax: {
                    url: "{{ route('account_statements.index') }}",
                    type: "get"
                },
                columns: [{
                        data: 'statement_date',
                        name: 'Statement Date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'transaction_date',
                        name: 'Statement Date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'type',
                        name: 'Type',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'reference',
                        name: 'Description',
                        orderable: true,
                        searchable: true
                    },

                    {
                        data: 'debit',
                        name: 'Debit',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'credit',
                        name: 'Credit',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'balance',
                        name: 'Balance',
                        orderable: false,
                        searchable: false
                    },

                ],

            });

            $(document).on('change', '#accounts', function() {

                var account_id = $(this).val();

                var date_range = $('#date-range').val();

                var date_range_array = date_range.split(' to ');

                var start_date = date_range_array[0];

                var end_date = date_range_array[1] ?? date_range_array[0];

                var url = "{{ route('account_statements.index') }}" + "?account_id=" + account_id +
                    "&start_date=" + start_date + "&end_date=" + end_date;

                dTable.ajax.url(url).load();
            });

            $(document).on('input', '#date-range', function() {

                var account_id = $('#accounts').val();

                if (account_id == '') {
                    alert('Please select an account');
                    return false;
                }

                var date_range = $(this).val();

                var date_range_array = date_range.split(' to ');

                var start_date = date_range_array[0];

                var end_date = date_range_array[1] ?? date_range_array[0];

                if (start_date !== '' && end_date !== '') {
                    var url = "{{ route('account_statements.index') }}" + "?account_id=" + account_id +
                        "&start_date=" + start_date + "&end_date=" + end_date;

                    dTable.ajax.url(url).load();
                }


            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const accounts = document.getElementById("accounts");

            new Selectr(accounts, {
                searchable: false
            })

            const dateRange = document.querySelector("#date-range");

            const today = new Date();

            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

            const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            flatpickr(dateRange, {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: [firstDayOfMonth, lastDayOfMonth],
            });
        });
    </script>

@endsection
