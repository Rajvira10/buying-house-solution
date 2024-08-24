@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp

@extends('admin.layout')
@section('title', 'Clients')
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
                                        <h4 class="card-title mb-0">Clients</h4>
                                    </div>
                                    <div class="col-sm-auto">
                                        @if (in_array('client.create', session('user_permissions')))
                                            <a href="{{ route('clients.create') }}">
                                                <button type="button" class="btn btn-success add-btn">
                                                    <i class="ri-add-line align-bottom me-1"></i> Add
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="businessCategoryList">
                                    <div class="card-body">
                                        <table id="businessCategoryTable" class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('#') }}</th>
                                                    <th>{{ __('Company Name') }}</th>
                                                    <th>{{ __('Contact Number') }}</th>
                                                    <th>{{ __('Client Source') }}</th>
                                                    <th>{{ __('Business Category') }}</th>
                                                    <th>{{ __('Interested In') }}</th>
                                                    <th>{{ __('Client Status') }}</th>
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
    <!-- Modal HTML -->
    <!-- Modal HTML -->
    <div class="modal fade" id="addContactPersonModal" tabindex="-1" role="dialog" aria-labelledby="addContactPersonLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addContactPersonForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addContactPersonLabel">Add Contact Person</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="contact_name">Contact Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_designation">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_designation" name="contact_designation"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_phone">Contact Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_dob">Date of Birth</label>
                            <input type="date" class="form-control" id="contact_dob" name="contact_dob" required>
                        </div>
                        <input type="hidden" id="client_id" name="client_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Contact Person</button>
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

            var dTable = $('#businessCategoryTable').DataTable({
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: true,
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
                    url: "{{ route('clients.index') }}",
                    type: "get"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'company_name',
                        name: 'company_name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'contact_no',
                        name: 'contact_no',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'client_source',
                        name: 'client_source',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'business_category',
                        name: 'business_category',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'interested_in',
                        name: 'interested_in',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'client_status',
                        name: 'client_status',
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
        const deleteClient = (id) => {
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
                        url: "{{ route('clients.destroy') }}",
                        method: 'POST',
                        data: {
                            client_id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#businessCategoryTable').DataTable().ajax.reload();
                                toaster('Client Deleted Successfully', 'success');
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

        function showAddContactPersonModal(clientId) {
            $('#client_id').val(clientId);
            $('#addContactPersonModal').modal('show');
        }

        $('#addContactPersonForm').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: "{{ route('clients.add_contact_person') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#addContactPersonModal').modal('hide');
                        $('#addContactPersonForm')[0].reset();
                        $('#businessCategoryTable').DataTable().ajax.reload();
                        toaster('Contact Person Added Successfully', 'success');
                    } else {
                        toaster(response.error, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    toaster('Something went wrong', 'danger');
                }
            });
        });
    </script>
@endsection
