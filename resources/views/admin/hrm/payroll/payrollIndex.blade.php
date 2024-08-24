@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp
@extends('admin.layout')
@section('title', 'Payroll')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-md-4">
                                        <h4 class="card-title mb-0">Payrolls</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="employees" id="employees">
                                            <option value="" selected disabled>Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="payrollList">
                                    <div class="card-body">
                                        <table id="payrollTable" class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('Payroll No') }}</th>
                                                    <th>{{ __('Month') }}</th>
                                                    <th>{{ __('Payment Date') }}</th>
                                                    <th>{{ __('Net Salary') }}</th>
                                                    <th>{{ __('Disbursed By') }}</th>
                                                    <th>{{ __('Printed By') }}</th>
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

            var dTable = $('#payrollTable').DataTable({
                columnDefs: [{
                    "defaultContent": "-",
                    "targets": "_all"
                }],
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
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
                    url: "{{ route('payrolls.payroll.index') }}",
                    type: "get"
                },
                columns: [{
                        data: 'payroll_no',
                        name: 'Payroll No',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'month',
                        name: 'Month',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'payment_date',
                        name: 'Payment Date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'net_salary',
                        name: 'Net Salary',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'disbursed_by',
                        name: 'Disbursed By',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'printed_by',
                        name: 'Printed By',
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
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const employees = document.querySelector("#employees");
            new Selectr(employees);
        });
    </script>

    <script>
        $("#employees").change(function() {
            var employee_id = $(this).val();
            var url = "{{ route('payrolls.payroll.index') }}";
            if (employee_id) {
                url = "{{ route('payrolls.payroll.index') }}" + "?employee_id=" + employee_id;
            }
            var dTable = $('#payrollTable').DataTable({
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
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
                    url: url,
                    type: "get"
                },
                columns: [{
                        data: 'payroll_no',
                        name: 'Payroll No',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'month',
                        name: 'Month',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'payment_date',
                        name: 'Payment Date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'net_salary',
                        name: 'Net Salary',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'disbursed_by',
                        name: 'Disbursed By',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'printed_by',
                        name: 'Printed By',
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
        });
    </script>
@endsection
