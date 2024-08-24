@section('title', 'Salary Sheet Details')

<head>
    <link href="{{ asset('public/admin-assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<style>
    @media print {
        table {
            font-size: 12px;
        }
    }
</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            @include('admin.company_header')
                        </div>
                        <div class="card-body">
                            <h4 class="text-center" style="border: 1px solid black; margin: 0px 220px 10px 220px">Salary
                                Pay Slip
                            </h4>
                            <table width="100%" class="mb-2">
                                <tr>
                                    <th>Employee Name:</th>
                                    <td>{{ $payroll_detail->employee->name }}</td>
                                    <th>Date:</th>
                                    <td> {{ date('d/m/Y', strtotime($payroll_detail->payment_date)) }}</td>
                                </tr>
                                <tr>
                                    <th>Designation:</th>
                                    <td>{{ $payroll_detail->employee->designation }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Month & Year</th>
                                    <td>{{ date('F Y', strtotime($payroll_detail->payroll->month)) }}</td>
                                </tr>
                            </table>
                            <table class="table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th class="p-1 text-center">Earning Areas</th>
                                        <th class="p-1 text-center">Amount</th>
                                        <th class="p-1 text-center">Deductions Areas</th>
                                        <th class="p-1 text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-1">Basic Pay</td>
                                        <td class="text-end p-1">{{ number_format($payroll_detail->basic, 2) }}</td>
                                        <td class="p-1">Absence Deduct</td>
                                        <td class="text-end p-1">{{ number_format($payroll_detail->deduction, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">House Rent</td>
                                        <td class="text-end p-1">{{ number_format($payroll_detail->h_rent, 2) }}</td>
                                        <td class="p-1"></td>
                                        <td class="p-1"></td>
                                    </tr>
                                    <tr>
                                        <td class="p-1">Medial Allowance</td>
                                        <td class="text-end p-1">{{ number_format($payroll_detail->h_rent, 2) }}</td>
                                        <td class="p-1"></td>
                                        <td class="p-1"></td>
                                    </tr>
                                    <tr>
                                        <td class="p-1"> Conveyance Allowance</td>
                                        <td class="text-end p-1">{{ number_format($payroll_detail->conv, 2) }}</td>
                                        <td class="p-1"></td>
                                        <td class="p-1"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold p-2"><b>Total Earnings</b></td>
                                        <td class="text-end font-weight-bold p-1">
                                            <b>{{ number_format($payroll_detail->gross_salary, 2) }}</b>
                                        </td>
                                        <td class="font-weight-bold p-2"><b>Total Deduction</b></td>
                                        <td class="text-end font-weight-bold p-2">
                                            <b>{{ number_format($payroll_detail->deduction, 2) }}</b>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 16px">
                                        <td colspan="3" class="text-end font-weight-bold p-2"><b>Net Salary</b></td>
                                        <td class="text-end font-weight-bold p-1"><b>
                                                {{ number_format($payroll_detail->net_salary) }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="mt-4"><span style="font-weight: bold">Amount in Word:</span> <span
                                    id="amount_in_words"></span>
                            </p>
                            <p class=""><span style="font-weight: bold">Payment Method:</span>
                                {{ $payroll_detail->payment_method }}
                            </p>
                            <div class=" d-flex justify-content-between p-1"
                                style="border: 1px solid black; margin-top: 100px">
                                <span style="font-weight: bold">Signature of Employee</span>
                                <span style="font-weight: bold">Approved By</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        var a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ',
            'Eleven ',
            'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '
        ];
        var b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function inWords(num) {
            if ((num = num.toString()).length > 9) return 'overflow';
            n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return;
            var str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) :
                '';
            return str + 'Only';
        }

        var amount = "{{ $payroll_detail->net_salary }}";

        document.getElementById('amount_in_words').innerHTML = inWords(amount);

        window.print()
    }
</script>
