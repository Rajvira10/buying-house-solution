@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp
@extends('admin.layout')
@section('title', 'Pending Revenues')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Revenues</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('revenues.index') }}">Revenues</a></li>
                                    <li class="breadcrumb-item active">Pending Revenues</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col">
                                        <h4 class="card-title mb-0">Pending Revenues</h4>
                                    </div>
                                    {{-- <div class="col-sm-auto">
                                        <a href="{{ route('revenues.index') }}">
                                            <button type="button" class="btn btn-success">
                                                Approved Revenues
                                            </button>
                                        </a>
                                    </div> --}}
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="revenueList">
                                    <div class="card-body">
                                        <table id="revenueTable" class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Warehouse') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Payment Status') }}</th>
                                                    <th>{{ __('Note') }}</th>
                                                    <th>{{ __('Finalized By') }}</th>
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

            var dTable = $('#revenueTable').DataTable({
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
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
                    url: "{{ route('revenues.pending') }}",
                    type: "get"
                },
                columns: [{
                        data: 'date',
                        name: 'Date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'warehouse_name',
                        name: 'Warehouse',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'category_name',
                        name: 'Category',
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
                        data: 'payment_status',
                        name: 'Payment Status',
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

                ],
            });
        });
    </script>
@endsection
