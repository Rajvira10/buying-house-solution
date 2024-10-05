@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp

@extends('admin.layout')
@section('title', 'Queries')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col">
                                        <h4 class="card-title mb-0">Queries</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="expenseCategoryList">
                                    <div class="card-body">
                                        <table id="expenseCategoryTable" class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Query No') }}</th>
                                                    <th>{{ __('Product Type') }}</th>
                                                    <th>{{ __('Brand') }}</th>
                                                    <th>{{ __('Merchandiser') }}</th>
                                                    <th>{{ __('Total Quantity') }}</th>
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
    <!-- Modal for adding TNAs -->
    <div class="modal fade" id="addTnaModal" tabindex="-1" aria-labelledby="addTnaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTnaModalLabel">Add TNA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tnaForm">
                        <input type="hidden" id="order_id" name="order_id">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>TNA</th>
                                    <th>Plan Date</th>
                                    <th>Actual Date</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="tnaTableBody">
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveTnaBtn">Save</button>
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

            var dTable = $('#expenseCategoryTable').DataTable({
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
                    url: "{{ route('queries.approve') }}",
                    type: "get"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'query_no',
                        name: 'query_no',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'product_type',
                        name: 'product_type',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'brand',
                        name: 'brand',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'merchandiser',
                        name: 'merchandiser',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'total_quantity',
                        name: 'total_quantity',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });
        const deleteOrder = (id) => {
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
                        url: "{{ route('orders.destroy') }}",
                        method: 'POST',
                        data: {
                            order_id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#expenseCategoryTable').DataTable().ajax.reload();
                                toaster('Order Deleted Successfully', 'success');
                            } else {
                                toaster(response.error, 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            toaster('Something went wrong', 'danger');
                        }
                    });
                }
            })
        }

        const changeQueryStatus = (id) => {
            Swal.fire({
                title: 'Change Query Status',
                html: `
            <select id="queryStatus" class="form-control mb-3">
                <option value="Approved">Approve</option>
                <option value="Rejected">Reject</option>
            </select>
            <textarea id="rejection_note" class="form-control d-none" placeholder="Rejection Note"></textarea>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#556ee6',
                cancelButtonColor: '#f46a6a',
                confirmButtonText: 'Yes, change it!',
                didOpen: () => {
                    $('#queryStatus').change(function() {
                        const selectedStatus = $(this).val();
                        if (selectedStatus === 'Rejected') {
                            $('#rejection_note').removeClass('d-none');
                        } else {
                            $('#rejection_note').addClass('d-none');
                        }
                    });
                },
                preConfirm: () => {
                    const status = document.getElementById('queryStatus').value;
                    const rejectionNote = document.getElementById('rejection_note').value;
                    return {
                        status,
                        rejectionNote
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const {
                        status,
                        rejectionNote
                    } = result.value;

                    if (status === 'Sent For Approval') {
                        $('#query_id').val(id);
                        $('#orderModal').modal('show');
                    } else {
                        $.ajax({
                            url: "{{ route('queries.change_status') }}",
                            method: 'POST',
                            data: {
                                query_id: id,
                                status: status,
                                rejection_note: rejectionNote,
                                _token: '{{ csrf_token() }}'
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#expenseCategoryTable').DataTable().ajax.reload();
                                    toaster('Query Status Changed Successfully', 'success');
                                } else {
                                    toaster(response.error, 'danger');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                toaster('Something went wrong', 'danger');
                            }
                        });
                    }
                }
            });
        };
    </script>
@endsection
