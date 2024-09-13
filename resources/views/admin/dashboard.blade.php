@inject('permissions', 'App\Services\PermissionService')

@extends('admin.layout')


@section('title', 'Admin Dashboard')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                {{-- @if (auth()->user()->hasPermission('dashboard.index')) --}}
                {{-- <a href="{{ route('settings.deploy') }}"><button class="btn btn-primary">Deploy</button></a> --}}

                {{-- <div class="row">
                        @foreach ($blocks as $data)
                            <div class="col-xl-3 col-md-4">
                                <div class="card card-animate">
                                    <div class="card-body bg-soft-{{ $data['class'] }}" style="min-height: 100px">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-uppercase fw-medium text-dark mb-0">{{ $data['name'] }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-16 fw-semibold ff-dark mb-4">{{ $data['unit'] }}<span
                                                        class="counter"
                                                        data-count={{ $data['amount'] }}>{{ $data['amount'] }}</span></h4>
                                            </div>
                                            <div class="avatar-md">
                                                <span class="avatar-title bg-soft-{{ $data['class'] }} rounded fs-3">
                                                    <i class="bx bx-dollar-circle text-{{ $data['class'] }}"
                                                        style="font-size: 40px"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <input type="date" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                                class="form-control" id="date-range">
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($date_blocks as $data)
                            <div class="col-xl-3 col-md-4">
                                <div class="card card-animate">
                                    <div class="card-body bg-soft-{{ $data['class'] }}" style="min-height: 100px">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-uppercase fw-medium text-dark mb-0">{{ $data['name'] }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-16 fw-semibold ff-dark mb-4">{{ $data['unit'] }}<span
                                                        class="date-counter"
                                                        data-count={{ $data['amount'] }}>{{ $data['amount'] }}</span></h4>
                                            </div>
                                            <div class="avatar-md">
                                                <span class="avatar-title bg-soft-{{ $data['class'] }} rounded fs-3">
                                                    <i class="bx bx-dollar-circle text-{{ $data['class'] }}"
                                                        style="font-size: 40px"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div> --}}
                {{-- @else --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate animate__animated animate__bounce">
                            <div class="card-body">
                                <h4 class="text-center" style="font-size: 24px; color: #333;">
                                    Welcome <span class="text-primary">{{ auth()->user()->username }}</span>
                                    <i class="fas fa-star fa-spin star-icon"></i>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}
                <div class="row">
                    <div class="col-md-6">
                        <p class="session-active-time"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom-script')

    @include('admin.message')

    <script>
        $(document).on('input', '#date-range', function() {

            var date_range = $(this).val();

            var date_range_array = date_range.split(' to ');

            var start_date = date_range_array[0];

            var end_date = date_range_array[1];

            if (start_date !== '' && end_date !== '') {
                var url = "{{ route('admin-dashboard') }}" +
                    "?start_date=" + start_date + "&end_date=" + end_date;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        let dateBlocks = data.date_blocks;
                        console.log(dateBlocks);
                        $('.date-counter').each(function(index, element) {
                            if (dateBlocks[index].amount) {
                                (dateBlocks[index].amount);
                                $(element).text(dateBlocks[index].amount);
                            } else {
                                $(element).text(0);
                            }
                        });
                    }
                });
            }


        });


        document.addEventListener("DOMContentLoaded", function() {
            const dateRange = document.querySelector("#date-range");

            const today = new Date();

            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

            const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            flatpickr(dateRange, {
                mode: "range",
                dateFormat: "Y-m-d",
                defaultDate: [firstDayOfMonth, lastDayOfMonth],
            });

            const counters = document.querySelectorAll('.counter');
            const dateCounter = document.querySelectorAll('.date-counter');
            const speed = 200;

            // counters.forEach(counter => {
            //     const updateCount = () => {
            //         const target = +counter.getAttribute('data-count');
            //         const count = +counter.innerText;

            //         console.log(count);
            //         const inc = target / speed;

            //         if (count < target) {
            //             counter.innerText = (count + inc).toFixed(2);
            //             setTimeout(updateCount, 1);
            //         } else {
            //             counter.innerText = target.toLocaleString('en-US', {
            //                 minimumFractionDigits: 2,
            //                 maximumFractionDigits: 2
            //             });
            //         }
            //     };

            //     updateCount();
            // });
        });
    </script>

    <script>
        function updateSessionActiveTime() {
            const sessionActiveElement = document.querySelector('.session-active-time');
            setInterval(() => {

                const sessionDuration = (new Date() - new Date('{{ session('session_start') }}')) / 1000;

                const hours = Math.floor((sessionDuration % (60 * 60 * 24)) / (60 * 60));
                const minutes = Math.floor((sessionDuration % (60 * 60)) / (60));
                const seconds = Math.floor((sessionDuration % (60)));

                const formattedTime = `${hours}h ${minutes}m ${seconds}s`;
                sessionActiveElement.textContent = `Session Active Time: ${formattedTime}`;

                sessionActiveElement.classList.add('animate__animated',
                    'animate__fadeIn');
                setTimeout(() => {
                    sessionActiveElement.classList.remove('animate__fadeIn');
                }, 1000);
            }, 1000);
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateSessionActiveTime();

            const star = document.querySelector('.star-icon');

            if (star) {
                setInterval(() => {
                    const randomColor = generateRandomHexColor();
                    star.style.color = randomColor;
                }, 100);
            }

            function generateRandomHexColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        });
    </script>

@endsection
