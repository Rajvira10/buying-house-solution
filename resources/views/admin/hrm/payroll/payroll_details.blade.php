@extends('admin.layout')
@section('title', 'Salary Sheet Details')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Salary Sheet of {{ $month }}</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Salary Sheet</a>
                                    </li>
                                    <li class="breadcrumb-item active">Salary Sheet Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">Employee Name</th>
                                            <th rowspan="2">Designation</th>
                                            <th rowspan="2">Basic</th>
                                            <th colspan="3" class="text-center">Allowances</th>
                                            <th rowspan="2">Gross Salary</th>
                                            <th rowspan="2">Bonus</th>
                                            <th rowspan="2">Absent</th>
                                            <th rowspan="2">Deduction</th>
                                            <th rowspan="2">Net Pay</th>
                                            <th rowspan="2">Payment Status</th>
                                            <th rowspan="2">Payment Date</th>
                                            <th rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th>H.Rent</th>
                                            <th>Medical</th>
                                            <th>Conveyance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payroll_details as $detail)
                                            <form action="{{ route('payrolls.disburse', $detail->id) }}"
                                                id="form_{{ $detail->id }}" method="post">
                                                @csrf
                                                <tr id="{{ $detail->id }}">
                                                    <input type="hidden" name="detail_id" id="detail_id"
                                                        value="{{ $detail->id }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $detail->employee->name }}</td>
                                                    <td>{{ $detail->employee->designation }}</td>
                                                    <td id="basic_{{ $detail->id }}">{{ $detail->basic }}</td>
                                                    <td id="h_rent_{{ $detail->id }}">{{ $detail->h_rent }}</td>
                                                    <td id="med_{{ $detail->id }}">{{ $detail->med }}</td>
                                                    <td id="conv_{{ $detail->id }}">{{ $detail->conv }}</td>
                                                    <td id="gross_{{ $detail->id }}">{{ $detail->gross }}</td>
                                                    <td><input type="number" class="form-control"
                                                            id="bonus_{{ $detail->id }}"
                                                            value="{{ $detail->bonus ?? 0 }}"
                                                            oninput="calculateSalary({{ $detail->id }})" min="0"
                                                            name="bonus">
                                                    </td>
                                                    <td><input type="number" class="form-control"
                                                            id="absent_{{ $detail->id }}"
                                                            value="{{ $detail->days_absent }}"
                                                            oninput="calculateSalary({{ $detail->id }})" min="0"
                                                            max="31" name="days_absent">
                                                    </td>
                                                    <td id="deduction_{{ $detail->id }}"></td>
                                                    <td id="net_{{ $detail->id }}"></td>
                                                    @if ($detail->payment_status == 'Paid')
                                                        <td>{{ $detail->payment_status }} via
                                                            {{ $detail->payment_method }}</td>
                                                    @else
                                                        <td>{{ $detail->payment_status }}</td>
                                                    @endif
                                                    @if ($detail->payment_status == 'Paid')
                                                        <td>{{ date('d/m/Y', strtotime($detail->payment_date)) }}</td>
                                                        <td>
                                                            <button class="btn btn-success btn-sm"
                                                                disabled>Disburse</button>
                                                        </td>
                                                    @else
                                                        <td>
                                                            <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                                data-default-date="{{ $detail->payment_date }}"
                                                                id="payment_date" type="date"
                                                                class="form-control @error('payment_date') is-invalid @enderror"
                                                                name="payment_date" placeholder="Date">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-sm"
                                                                onclick="confirmDisburse({{ $detail->id }})">Disburse</button>
                                                        </td>
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
@endsection

@section('custom-script')

    @include('admin.message')
    <script>
        function calculateSalary(detailId) {

            var bonus = parseFloat(document.getElementById('bonus_' + detailId).value);
            var gross = parseFloat(document.getElementById('gross_' + detailId).textContent);
            var h_rent = parseFloat(document.getElementById('h_rent_' + detailId).textContent);
            var med = parseFloat(document.getElementById('med_' + detailId).textContent);
            var conv = parseFloat(document.getElementById('conv_' + detailId).textContent);
            var absent = parseFloat(document.getElementById('absent_' + detailId).value);

            if (bonus == null || isNaN(bonus)) {
                bonus = 0;
            }

            if (absent == null || isNaN(absent)) {
                absent = 0;
            }

            var basicSalary = gross - h_rent - med - conv;

            document.getElementById('basic_' + detailId).textContent = basicSalary.toFixed(2);

            var deduction = (gross / {{ $daysInMonth }}) * absent;
            document.getElementById('deduction_' + detailId).textContent = deduction.toFixed(2);

            // Calculate Net Pay
            var netPay = gross - deduction + bonus;
            document.getElementById('net_' + detailId).textContent = netPay.toFixed(2);
        }

        function calculateAllSalaries() {
            @foreach ($payroll_details as $detail)
                calculateSalary({{ $detail->id }});
            @endforeach
        }

        function confirmDisburse(detailId) {
            const accounts = {!! json_encode($accounts) !!};

            Swal.fire({
                title: 'Select Account',
                html: `
            <label for="account_id">Account:</label>
            <select name="account_id" id="account_id" class="form-control">
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>
        `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#405189',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Disburse it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedPaymentMethod = document.getElementById('account_id').value;
                    if (selectedPaymentMethod) {
                        const form = document.getElementById('form_' + detailId);
                        document.getElementById('detail_id').value = detailId;
                        // Add an additional hidden input for payment method
                        const paymentMethodInput = document.createElement('input');
                        paymentMethodInput.type = 'hidden';
                        paymentMethodInput.name = 'account_id';
                        paymentMethodInput.value = selectedPaymentMethod;
                        form.appendChild(paymentMethodInput);
                        form.submit();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please select a payment method!',
                        });
                    }
                }
            });
        }


        window.onload = function() {
            calculateAllSalaries();
        };
    </script>

@endsection
