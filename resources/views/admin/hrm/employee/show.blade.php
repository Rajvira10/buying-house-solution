@extends('admin.layout')
@section('title', 'Employee')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0"></h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href={{ route('employees.index') }}>Employees</a></li>
                                    <li class="breadcrumb-item active">Employee Details</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="profile-foreground position-relative mx-n4 mt-n4">
                    <div class="profile-wid-bg">
                        <img src={{ asset('public/admin-assets/images/profile-bg.jpg') }} alt=""
                            class="profile-wid-img" />
                    </div>
                </div>
                <div class="pt-4 mb-5 mb-lg-3 pb-lg-4">
                    <div class="row g-4">
                        <div class="col-auto">
                            <div class="avatar-lg">
                                <img src={{ $employee->image_path }} alt="user-img"
                                    style="height: 120px; width: 110px; object-fit: cover" />
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="p-2">
                                <h3 class="text-white mb-1">{{ $employee->name }}</h3>
                                <p class="text-white-75">Employee</p>
                                {{-- <p class="text-white-75"><strong>Warehouse:</strong> {{ $employee->warehouse->name }}</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div>
                            <div class="d-flex">
                                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1"
                                    role="tablist">
                                    <li class="nav-item d-flex">
                                        <a class="nav-link fs-14 {{ session('tab') !== 'salary_structure' ? 'active show' : '' }}"
                                            data-bs-toggle="tab" href="#overview-tab" role="tab" id="overview-btn">
                                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Overview</span>
                                        </a>
                                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#job-duration-tab"
                                            role="tab" id="job-btn">
                                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Job Duration</span>
                                        </a>
                                        <a class="nav-link fs-14 {{ session('tab') === 'salary_structure' ? 'active show' : '' }}"
                                            data-bs-toggle="tab" href="#salary-structure-tab" role="tab"
                                            id="salary-btn">
                                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Salary Structure</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content pt-4 text-muted">
                                <div class="tab-pane {{ session('tab') !== 'salary_structure' ? 'active show' : '' }}"
                                    id="overview-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-3">Employee Information</h5>
                                                    <div class="flex-shrink-0">
                                                        <a href="{{ route('employees.print', $employee->id) }}">
                                                            <button class="btn btn-secondary">
                                                                Print
                                                            </button>
                                                        </a>
                                                        @if ($employee->cv_path)
                                                            <a href="{{ $employee->cv_path }}" download><button
                                                                    class="btn btn-primary">Download CV</button></a>
                                                        @endif
                                                        <a href={{ route('employees.edit', $employee->id) }}
                                                            class="btn btn-success"><i
                                                                class="ri-edit-box-line align-bottom"></i> Edit </a>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td><strong>Status :</strong></td>
                                                                <td>
                                                                    @if ($employee->status === 'Active')
                                                                        <div class="badge bg-success">
                                                                            {{ $employee->status }}</div>
                                                                    @else
                                                                        <div class="badge bg-danger">
                                                                            {{ $employee->status }}
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td><strong>Name :</strong></td>
                                                                <td>{{ $employee->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Unique ID :</strong></td>
                                                                <td>{{ $employee->unique_id }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Department :</strong></td>
                                                                <td>{{ $employee->department_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Designation :</strong></td>
                                                                <td>{{ $employee->designation }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>NID :</strong></td>
                                                                <td>{{ $employee->nid }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Email :</strong></td>
                                                                <td>{{ $employee->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Contact No :</strong></td>
                                                                <td>{{ $employee->contact_no }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Present Address :</strong></td>
                                                                <td>{{ $employee->present_address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Permanent Address :</strong></td>
                                                                <td>{{ $employee->permanent_address }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="job-duration-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-3">Job Duration</h5>
                                                    @if ($employee->status === 'Active')
                                                        <button class="btn btn-danger"
                                                            onclick="deactivate()">Deactivate</button>
                                                    @else
                                                        <button class="btn btn-success"
                                                            onclick="activate()">Activate</button>
                                                    @endif
                                                </div>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                            </tr>

                                                            @foreach ($employee->job_durations as $job_duration)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ date('M d, Y', strToTime($job_duration->start_date)) }}
                                                                    </td>
                                                                    @if ($job_duration->end_date == null)
                                                                        <td>Present</td>
                                                                    @else
                                                                        <td>{{ date('M d, Y', strToTime($job_duration->end_date)) }}
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane {{ session('tab') === 'salary_structure' ? 'active show' : '' }}"
                                    id="salary-structure-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-3">Salary Structure</h5>
                                                    <a
                                                        href={{ route('employees.create_salary_structure', $employee->id) }}><button
                                                            class="btn btn-success">Add</button></a>

                                                </div>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-10">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Basic Salary</th>
                                                                <th>House Rent Allowance</th>
                                                                <th>Medical Allowance</th>
                                                                <th>Conveyance Allowance</th>
                                                                <th>Gross Salary</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                            </tr>

                                                            @foreach ($employee->salary_structures as $salary_structure)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ number_format($salary_structure->gross - ($salary_structure->h_rent_percent * $salary_structure->gross) / 100 - ($salary_structure->med_percent * $salary_structure->gross) / 100 - ($salary_structure->conv_percent * $salary_structure->gross) / 100, 2) }}
                                                                    </td>
                                                                    <td>{{ $salary_structure->h_rent_percent }} %</td>
                                                                    <td>{{ $salary_structure->med_percent }} %</td>
                                                                    <td>{{ $salary_structure->conv_percent }} %</td>
                                                                    <td>{{ number_format($salary_structure->gross, 2) }}
                                                                    </td>
                                                                    <td>{{ date('M d, Y', strToTime($salary_structure->start_date)) }}
                                                                    </td>
                                                                    @if ($salary_structure->end_date == null)
                                                                        <td>Present</td>
                                                                    @else
                                                                        <td>{{ date('M d, Y', strToTime($salary_structure->end_date)) }}
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach

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

            @endsection

            @section('custom-script')
                @include('admin.message')

                <script>
                    function deactivate() {
                        Swal.fire({
                            title: 'Input End Date',
                            html: `<form id="deactivate-form"action="{{ route('employees.deactivate', $employee->id) }}" method="post">
                                        @csrf
                                        <label>End Date:</label>
                                        <input data-provider="flatpickr" 
                                        data-date-format="d/m/Y" 
                                        data-default-date="{{ date('d/m/Y') }}" 
                                        id="end_date" type="date" class="form-control" 
                                        name="end_date">        
                                    </form>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#405189',
                            cancelButtonColor: '#CC563D',
                            confirmButtonText: 'Yes, deactivate!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('deactivate-form').submit();
                            }
                        })
                    }

                    function activate() {
                        Swal.fire({
                            title: 'Input Start Date',
                            html: `<form id="activate-form"action="{{ route('employees.activate', $employee->id) }}" method="post">
                                        @csrf
                                        <label>Start Date:</label>
                                        <input data-provider="flatpickr" 
                                        data-date-format="d/m/Y" 
                                        data-default-date="{{ date('d/m/Y') }}" 
                                        id="start_date" type="date" class="form-control" 
                                        name="start_date">        
                                    </form>`,
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#405189',
                            cancelButtonColor: '#CC563D',
                            confirmButtonText: 'Yes, activate!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('activate-form').submit();
                            }
                        })
                    }
                </script>
            @endsection
