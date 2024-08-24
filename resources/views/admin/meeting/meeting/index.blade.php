@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp

@extends('admin.layout')
@section('title', 'Meetings')
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
                                        <h4 class="card-title mb-0">Meetings</h4>
                                    </div>
                                    <div class="col-sm-auto">
                                        @if (in_array('meeting.create', session('user_permissions')))
                                            <a href="{{ route('meetings.create') }}">
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
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Meeting ID') }}</th>
                                                    <th>{{ __('Client') }}</th>
                                                    <th>{{ __('Meeting Title') }}</th>
                                                    <th>{{ __('Meeting Status') }}</th>
                                                    <th>{{ __('Meeting Type') }}</th>
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
    <!-- Add Minute Modal -->
    <div class="modal fade" id="addMinuteModal" tabindex="-1" aria-labelledby="addMinuteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addMinuteForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMinuteModalLabel">Add Meeting Minute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="addMinuteMeetingId" name="meeting_id">
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="addMinuteNote" name="note" required></textarea>
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

    <!-- View Minute Modal -->
    <div class="modal fade" id="viewMinuteModal" tabindex="-1" aria-labelledby="viewMinuteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMinuteModalLabel">View Meeting Minute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="viewMinuteNote"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Minute Modal -->
    <div class="modal fade" id="editMinuteModal" tabindex="-1" aria-labelledby="editMinuteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editMinuteForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMinuteModalLabel">Edit Meeting Minute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editMinuteMeetingId" name="meeting_id">
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="editMinuteNote" name="note" required></textarea>
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
                    url: "{{ route('meetings.index') }}",
                    type: "get"
                },
                columns: [{
                        data: 'date',
                        name: 'date',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'meeting_id',
                        name: 'meeting_id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'client',
                        name: 'client',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'meeting_title',
                        name: 'meeting_title',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'meeting_status',
                        name: 'meeting_status',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'meeting_type',
                        name: 'meeting_type',
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
        const deleteMeeting = (id) => {
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
                        url: "{{ route('meetings.destroy') }}",
                        method: 'POST',
                        data: {
                            meeting_id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#businessCategoryTable').DataTable().ajax.reload();
                                toaster('Meeting Deleted Successfully', 'success');
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

        function openAddMinuteModal(meetingId) {
            $('#addMinuteMeetingId').val(meetingId);
            $('#addMinuteNote').val('');
            $('#addMinuteModal').modal('show');
        }

        function openViewMinuteModal(meetingId) {
            $.ajax({
                url: "{{ route('meetings.meeting_minutes', '') }}/" + meetingId,
                method: 'GET',
                success: function(response) {
                    $('#viewMinuteNote').text(response.note);
                    $('#viewMinuteModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function openEditMinuteModal(meetingId) {
            $.ajax({
                url: "{{ route('meetings.meeting_minutes', '') }}/" + meetingId,
                method: 'GET',
                success: function(response) {
                    $('#editMinuteMeetingId').val(meetingId);
                    $('#editMinuteNote').val(response.note);
                    $('#editMinuteModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        $('#addMinuteForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('meetings.add_meeting_minutes') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    meeting_id: $('#addMinuteMeetingId').val(),
                    note: $('#addMinuteNote').val()
                },
                success: function(response) {
                    $('#addMinuteModal').modal('hide');
                    toaster(response.success, 'success');
                    $('#businessCategoryTable').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    toaster('Something went wrong', 'danger');
                }
            });
        });

        $('#editMinuteForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('meetings.add_meeting_minutes') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    meeting_id: $('#editMinuteMeetingId').val(),
                    note: $('#editMinuteNote').val()
                },
                success: function(response) {
                    $('#editMinuteModal').modal('hide');
                    toaster(response.success, 'success');
                    $('#businessCategoryTable').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    toaster('Something went wrong', 'danger');
                }
            });
        });
    </script>
@endsection
