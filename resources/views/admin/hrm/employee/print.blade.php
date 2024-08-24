<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="text-center">Employee Details</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $employee->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Warehouse</th>
                                            <td>{{ $employee->warehouse->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $employee->department->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Designation</th>
                                            <td>{{ $employee->designation }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact No</th>
                                            <td>{{ $employee->contact_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $employee->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>NID</th>
                                            <td>{{ $employee->nid }}</td>
                                        </tr>
                                        <tr>
                                            <th>Present Address</th>
                                            <td>{{ $employee->present_address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Permanent Address</th>
                                            <td>{{ $employee->permanent_address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Photo</th>
                                            <td><img src="{{ $employee->image_path }}" alt="Employee Photo"
                                                    style="height: 100px; width: 100px;"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.print();

        window.onafterprint = function() {
            window.location.href = "{{ route('employees.index') }}";
        }
    });
</script>

</html>
