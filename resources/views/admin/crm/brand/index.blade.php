@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp

@extends('admin.layout')
@section('title', 'Brands')
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
                                        <h4 class="card-title mb-0">Brands</h4>
                                    </div>
                                    <div class="col-sm-auto">
                                        @if (in_array('brand.create', session('user_permissions')))
                                            <a href="{{ route('brands.create') }}">
                                                <button type="button" class="btn btn-success add-btn">
                                                    <i class="ri-add-line align-bottom me-1"></i> Add
                                                </button>
                                            </a>
                                        @endif
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
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Phone') }}</th>
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

    <div class="modal" id="addBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('brands.store_bank') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="brand_id" id="brand_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="branch" class="form-label">Branch</label>
                            <input type="text" class="form-control" id="branch" name="branch" required>
                        </div>
                        <div class="mb-3">
                            <label for="swift_code" class="form-label">Swift Code</label>
                            <input type="text" class="form-control" id="swift_code" name="swift_code" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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
                    url: "{{ route('brands.index') }}",
                    type: "get"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'Name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'phone',
                        name: 'Phone',
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

        $('#addBankModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var brand_id = button.data('brand_id');
            console.log(brand_id);
            $('#brand_id').val(brand_id);
        });



        const deleteBrand = (id) => {
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
                        url: "{{ route('brands.destroy') }}",
                        method: 'POST',
                        data: {
                            brand_id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#expenseCategoryTable').DataTable().ajax.reload();
                                toaster('Brand Deleted Successfully', 'success');
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
    </script>
@endsection
