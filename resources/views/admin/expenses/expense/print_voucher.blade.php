@inject('settings', 'App\Services\SettingsService')

@php
    $settings = $settings->getSettings();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Voucher</title>
    <script src="https://kit.fontawesome.com/34590e0ca8.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        #credit_voucher_badge {
            margin-top: 10px;
            padding: 8px 12px;
            font-size: 20px;
            background-color: #e75b34;
        }

        .sidebar-brand-full {
            height: 70px;
        }

        .date {
            text-align: left;
        }

        .md-heading {
            text-align: center;
            padding-top: 10px;
            margin-bottom: 10px;
        }

        .print {
            cursor: pointer;
            background: #00aa9a;
            color: #fff;
        }

        @media print {
            .print {
                display: none;
            }

            #back {
                display: none;
            }
        }

        th,
        td {
            padding: 3px !important;

        }

        .table-head {
            background: rgb(68, 68, 68);
            color: white;
        }

        .taka-border-head {
            width: 15%;
        }

        .taka-border {
            width: 85%;
            border-bottom: 2px dashed black;
            text-align: left;
            font-weight: bold;
        }

        .approved_by {
            border-bottom: 1px solid black;
            width: 90%;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sub2-row16">
            <div class="l26 md-heading">
                <a href="#" class="print btn btn-sm btn-info" onclick="window.print()"
                    class="btn btn-inverse waves-effect waves-light"><label for="">Print</label> <i
                        class="fa fa-print f-16"></i></a>
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-dark " id="back">Back to list</a>
            </div>
        </div>
    </div>

    <div class="container text-center mt-5">
        <div class="row mb-4">
            <div class="col-4">
                <div class="date">
                    <span> V.NO . <strong>{{ $expense->expense_no }}</strong></span><br>
                    <span>Date : {{ date('d/m/Y', strtotime($expense->date)) }}</span>
                    <span>Warehouse: {{ $warehouse->name }}</span><br>
                    <span>Address: {{ $warehouse->address }}</span><br>
                    <span>Contact No: {{ $warehouse->contact_no }}</span>
                </div>

            </div>
            <div class="col-4">
                <div id="credit_voucher">
                    <span class="badge  " id="credit_voucher_badge">Debit Voucher</span>
                </div>
            </div>
            <div class="col-4 ">
                <div>
                    <img src="{{ $settings->logo->absolute_path ?? asset('public/admin-assets/images/logo-sm.png') }}"
                        alt="" height="40"> <br>
                </div>
            </div>
            {{-- <div class="d-flex flex-row my-3">
                    <div class="taka-border-head">Purpose

                    </div>
                    <div class="ms-3 taka-border">

                    </div>
                </div> --}}

        </div>
        <div class="d-flex flex-row my-3">

        </div>
    </div>
    <table class="table table-bordered border-dark">
        <thead class="table-head">
            <tr>
                <th width="15%">PURPOSE</th>
                <th width="69%">DETAILS</th>
                <th width="13%">AMOUNT IN BDT</th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-start">{{ $expense->expenseCategory->name }}</td>
                <td class="text-start">{{ $expense->note }}</td>
                <td class="text-end">{{ number_format($expense->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <div class="row mb-4">
        <div class="d-flex flex-row mb-3">
            <div class=" taka-border-head">Taka (in words)</div>
            <div class="ms-3 taka-border">
                {{ $amount_in_words }} Taka Only
            </div>
        </div>
    </div>
    <div class="container text-center">
        <div class="row">
            <div class="col-4 ">
                <div class="ps-3 mt-4">
                    <p class="approved_by"> </p>
                    {{-- {{ $expense->finalizedBy->first_name . ' ' . $expense->finalizedBy->last_name }}</p> --}}
                    <span class="">Created By</span>
                </div>
            </div>
            <div class="col-4 ">
                <div class="ps-3 mt-4">
                    <p class="approved_by"></p>
                    <span class="">Received By</span>
                </div>
            </div>
            <div class="col-4 ">
                <div class="ps-3 mt-4">
                    <p class="approved_by">
                    </p>
                    <span class="">Approved By</span>
                </div>
            </div>
        </div>
    </div>
    {{-- <p class="mt-3">This Voucher is Automitically Generated By Software . No Need Any Signature</p> --}}
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>
