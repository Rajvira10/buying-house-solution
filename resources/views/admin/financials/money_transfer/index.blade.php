@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp
@extends('admin.layout')
@section('title', 'Money Transfers')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-3">
                                        <h4 class="card-title mb-0">Money Transfer</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" data-provider="flatpickr" data-date-format="d M, Y"
                                            data-range-date="true" class="form-control" id="date-range">
                                    </div>
                                    <div class="col-sm-auto">
                                        @if (in_array('money_transfer.create', session('user_permissions')))
                                            <a href="{{ route('money_transfers.create') }}">
                                                <button type="button" class="btn btn-success add-btn">
                                                    <i class="ri-add-line align-bottom me-1"></i> Add
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="money_transferList">
                                    <div class="card-body">
                                        <table id="money_transferTable" class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Transfer No') }}</th>
                                                    <th>{{ __('Sender Account') }}</th>
                                                    <th>{{ __('Receiver Account') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Note') }}</th>
                                                    <th>{{ __('Finalized By') }}</th>
                                                    <th>{{ __('Action') }}</th>
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
        $(document).ready(function() {
            var searchable = [];
            var selectable = [];
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            var dTable = $('#money_transferTable').DataTable({
                ordering: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
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
                processing: true,
                responsive: true,
                serverSide: false,
                language: {
                    processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-4'l><'col-sm-5 text-center mb-2'B><'col-sm-3'f>>tipr",
                ajax: {
                    url: "{{ route('money_transfers.index') }}" + "?id={{ $money_transfer_id }}",
                    type: "get"
                },
                columns: [{
                        data: 'date',
                        name: 'Date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'transfer_no',
                        name: 'Transfer No',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'sender_account_name',
                        name: 'Sender Account',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'receiver_account_name',
                        name: 'Receiver Account',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'amount',
                        name: 'Amount',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'note',
                        name: 'Note',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'finalized_by',
                        name: 'Finalized By',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],
            });

            const dateRange = document.querySelector("#date-range");

            const today = new Date();

            let firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

            const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            flatpickr(dateRange, {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: [firstDayOfMonth, lastDayOfMonth],
            });

            const moneyTransferId = '{{ $money_transfer_id }}';

            if (moneyTransferId != '') {
                dateRange.value = '';
            }

            $(document).on('input', '#date-range', function() {

                var date_range = $(this).val();

                var date_range_array = date_range.split(' to ');

                var start_date = date_range_array[0];

                var end_date = date_range_array[1] ?? date_range_array[0];

                if (start_date !== '' && end_date !== '') {
                    var url = "{{ route('money_transfers.index') }}" +
                        "?start_date=" + start_date + "&end_date=" + end_date;

                    dTable.ajax.url(url).load();
                }

            });
        });

        const deleteMoneyTransfer = (transferId) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('money_transfers.destroy') }}",
                        method: 'POST',
                        data: {
                            transfer_id: transferId,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);
                            $('#money_transferTable').DataTable().ajax.reload();
                            toaster('Money Transfer Deleted Successfully', 'success');
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            toaster('Something went wrong', 'danger');
                        }
                    });
                }
            })
        }
    </script>
@endsection
