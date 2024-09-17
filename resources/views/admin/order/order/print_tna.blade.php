@include('admin.head-links')

<h2 class="text-center mt-5">Order Details</h2>

<div class="table-responsive mx-auto" style="width: 80%">
    <table class="table mt-5 mx-auto">
        <tbody>
            <tr>
                <td colspan="3"><strong>Order Date:</strong> {{ $order->order_date->format('d-m-Y') }}</td>
                <td colspan="3"><strong>Query No:</strong> {{ $order->queryModel->query_no }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Total Quantity:</strong> {{ $order->total_quantity }}</td>
                <td colspan="3"><strong>Product Type:</strong> {{ $order->queryModel->product_type->name }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Buyer:</strong> {{ $order->queryModel->buyer->user->username }}</td>
                <td colspan="3"><strong>Merchandiser:</strong> {{ $order->queryModel->merchandiser->user->username }}
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-hover mt-5">
        <thead class="thead-light sticky-header">
            <tr>
                <th>#</th>
                <th>DETAIL WORK'S</th>
                <th>PLAN DATE</th>
                <th>ACTUAL DATE</th>
                <th>REMARKS</th>
        <tbody>
            @foreach ($order->tnas as $tna)
                @if ($tna->plan_date)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tna->tna->name }}</td>
                        <td>{{ $tna->plan_date ? $tna->plan_date->format('d-m-Y') : '' }}</td>
                        <td>{{ $tna->actual_date ? $tna->actual_date->format('d-m-Y') : '' }}</td>
                        <td>{{ $tna->remarks }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

<style>
    body {
        font-size: 12px;
        /* Reduced font size for printing */
        line-height: 1.2;
        /* Reduced line height */
    }

    .table td,
    .table th {
        padding: 0.5rem;
        /* Reduced padding for easier printing */
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody+tbody {
        border-top: 2px solid #dee2e6;
    }

    .table .thead-light th {
        color: #495057;
        background-color: #f1f5f8;
        border-color: #dee2e6;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table-bordered thead th,
    .table-bordered thead td {
        border-bottom-width: 2px;
    }

    .table-dark {
        color: #fff;
        background-color: #343a40;
    }

    .table-dark th,
    .table-dark td,
    .table-dark thead th {
        border-color: #454d55;
    }

    .table-dark.table-bordered {
        border: 0;
    }

    .table-dark.table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .table-dark.table-hover tbody tr:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.075);
    }

    @media print {

        .table td,
        .table th {
            padding: 0.4rem;
        }

        h2 {
            font-size: 16px;
        }
    }
</style>


@include('admin.scripts')

<script>
    window.onload = function() {
        window.print();
    }
</script>
