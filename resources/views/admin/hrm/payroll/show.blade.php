@section('title', 'Salary Sheet Details')

<head>
    <link href="{{ asset('public/admin-assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
</head>


<style>
    @media print {
        table {
            font-size: 13px;
        }
    }
</style>
<div class="main-content mt-5">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0">Salary Sheet of {{ $month }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Employee Name</th>
                                        <th rowspan="2">Designation</th>
                                        <th rowspan="2">Basic</th>
                                        <th colspan="3" class="text-center">Allowances</th>
                                        <th rowspan="2">Bonus</th>
                                        <th rowspan="2">Gross Salary</th>
                                        <th rowspan="2">Absent</th>
                                        <th rowspan="2">Deduction</th>
                                        <th rowspan="2">Net Pay</th>
                                        <th rowspan="2">Payment Status</th>
                                        <th rowspan="2">Payment Method</th>
                                        <th rowspan="2">Payment Date</th>
                                    </tr>
                                    <tr>
                                        <th>H.Rent</th>
                                        <th>Medical</th>
                                        <th>Conveyance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payroll_details as $detail)
                                        <form action="{{ route('payrolls.disburse', $detail->id) }}" method="post">
                                            @csrf
                                            <tr id="{{ $detail->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $detail->employee->name }}</td>
                                                <td>{{ $detail->employee->designation }}</td>
                                                <td id="basic_{{ $detail->id }}" class="text-end">
                                                    {{ number_format(floatval($detail->basic), 2) }}</td>
                                                <td id="h_rent_{{ $detail->id }}" class="text-end">
                                                    {{ number_format(floatval($detail->h_rent), 2) }}</td>
                                                <td id="med_{{ $detail->id }}" class="text-end">
                                                    {{ number_format(floatval($detail->med), 2) }}</td>
                                                <td id="conv_{{ $detail->id }}" class="text-end">
                                                    {{ number_format(floatval($detail->conv), 2) }}</td>
                                                <td class="text-end">{{ number_format(floatval($detail->bonus), 2) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format(floatval($detail->gross_salary), 2) }}</td>
                                                <td class="text-end">{{ $detail->days_absent }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($detail->gross_salary - $detail->net_salary, 2) }}
                                                </td>
                                                <td class="text-end">{{ number_format($detail->net_salary, 2) }}</td>
                                                <td>{{ $detail->payment_status }}</td>
                                                <td>{{ $detail->payment_method }}</td>
                                                @if ($detail->payment_date !== null)
                                                    <td>{{ date('d/m/Y', strtotime($detail->payment_date)) }}</td>
                                                @else
                                                    <td></td>
                                                @endif

                                            </tr>
                                        </form>
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

@section('custom-script')

    @include('admin.message')
@endsection
