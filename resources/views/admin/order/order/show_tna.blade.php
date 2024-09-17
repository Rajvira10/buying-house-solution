@extends('admin.layout')
@section('title', 'Order TNA Details')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center mb-5 text-primary fw-bold">Order TNA Details</h1>

                        <div class="card shadow-lg mb-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 my-3">
                                        <p><i class="fas fa-calendar-alt text-primary me-2"></i> <strong>Order
                                                Date:</strong> {{ $order->order_date->format('d-m-Y') }}</p>
                                        <p><i class="fas fa-box text-primary me-2"></i> <strong>Total Quantity:</strong>
                                            {{ $order->total_quantity }}</p>
                                        <p><i class="fas fa-user text-primary me-2"></i> <strong>Buyer:</strong>
                                            {{ $order->queryModel->buyer->user->username }}</p>
                                    </div>
                                    <div class="col-md-6 my-3">
                                        <p><i class="fas fa-hashtag text-primary me-2"></i> <strong>Query No:</strong>
                                            {{ $order->queryModel->query_no }}</p>
                                        <p><i class="fas fa-tags text-primary me-2"></i> <strong>Product Type:</strong>
                                            {{ $order->queryModel->product_type->name }}</p>
                                        <p><i class="fas fa-user-tie text-primary me-2"></i> <strong>Merchandiser:</strong>
                                            {{ $order->queryModel->merchandiser->user->username }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex">
                                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editTNAModal">
                                    Edit TNA
                                </button>
                                <button class="btn btn-danger" id="deleteTnaBtn">
                                    Delete TNA
                                </button>
                            </div>

                            <div class="card shadow-lg mx-4">
                                <div class="card-header bg-primary">
                                    <ul class="nav nav-pills card-header-pills" id="orderTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active text-white" id="timeline-tab"
                                                data-bs-toggle="pill" data-bs-target="#timeline" type="button"
                                                role="tab" aria-controls="timeline"
                                                aria-selected="true">Timeline</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link text-white" id="table-tab" data-bs-toggle="pill"
                                                data-bs-target="#table" type="button" role="tab" aria-controls="table"
                                                aria-selected="false">Table View</button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="orderTabsContent">
                                        <div class="tab-pane fade show active" id="timeline" role="tabpanel"
                                            aria-labelledby="timeline-tab">
                                            @foreach ($order->tnas as $tna)
                                                @if ($tna->plan_date)
                                                    <div class="timeline-item">
                                                        <h4 class="text-primary">{{ $tna->tna->name }}</h4>
                                                        <p><strong>Plan Date:</strong>
                                                            {{ $tna->plan_date ? $tna->plan_date->format('d-m-Y') : 'N/A' }}
                                                        </p>
                                                        <p><strong>Actual Date:</strong>
                                                            {{ $tna->actual_date ? $tna->actual_date->format('d-m-Y') : 'Pending' }}
                                                        </p>
                                                        <p><strong>Remarks:</strong> {{ $tna->remarks ?: 'No remarks' }}</p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="tab-pane fade" id="table" role="tabpanel"
                                            aria-labelledby="table-tab">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                    <thead class="table-primary">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Detail Work's</th>
                                                            <th>Plan Date</th>
                                                            <th>Actual Date</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $counter = 1; // Initialize counter
                                                        @endphp
                                                        @foreach ($order->tnas as $tna)
                                                            @if ($tna->plan_date)
                                                                <tr>
                                                                    <td>{{ $counter }}</td>
                                                                    <!-- Use manual counter -->
                                                                    <td>{{ $tna->tna->name }}</td>
                                                                    <td>{{ $tna->plan_date ? $tna->plan_date->format('d-m-Y') : '' }}
                                                                    </td>
                                                                    <td>{{ $tna->actual_date ? $tna->actual_date->format('d-m-Y') : '' }}
                                                                    </td>
                                                                    <td>{{ $tna->remarks }}</td>
                                                                </tr>
                                                                @php
                                                                    $counter++;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editTNAModal" tabindex="-1" aria-labelledby="editTNAModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTNAModalLabel">Edit TNA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('orders.update_tna', $order->id) }}" method="POST" id="tnaForm">
                        @csrf
                        <div class="modal-body">
                            <form id="tnaForm">
                                <input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
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
                                        @foreach ($order->tnas as $tna)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="tna_id[]" value="{{ $tna->id }}">
                                                    <input type="text" class="form-control"
                                                        value="{{ $tna->tna->name }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control" name="plan_date[]"
                                                        value="{{ $tna->plan_date ? $tna->plan_date->format('Y-m-d') : '' }}">
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control" name="actual_date[]"
                                                        value="{{ $tna->actual_date ? $tna->actual_date->format('Y-m-d') : '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="remarks[]"
                                                        value="{{ $tna->remarks }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveTnaBtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <style>
            .timeline-item {
                border-left: 2px solid #007bff;
                padding-left: 20px;
                margin-bottom: 30px;
                position: relative;
            }

            .timeline-item::before {
                content: '';
                width: 20px;
                height: 20px;
                background-color: #007bff;
                border-radius: 50%;
                position: absolute;
                left: -11px;
                top: 0;
            }

            .nav-pills .nav-link.active {
                background-color: #007bff;
            }
        </style>

    @endsection

    @section('custom-script')
        <script>
            $('#deleteTnaBtn').click(function() {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this TNA!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('orders.delete_tna') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                order_id: "{{ $order->id }}",
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        'TNA has been deleted.',
                                        'success'
                                    ).then((result) => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'Something went wrong. Please try again.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
