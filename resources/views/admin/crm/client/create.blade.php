@extends('admin.layout')
@section('title', 'Create Client')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Clients</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                                    <li class="breadcrumb-item active">Add Client</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- @include('include.message') --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ __('Add Client') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('clients.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="company_name">
                                                    {{ __('Company Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="company_name" type="text"
                                                    class="form-control @error('company_name') is-invalid @enderror"
                                                    name="company_name" value="{{ old('company_name') }}"
                                                    placeholder="Company Name">
                                                <div class="help-block with-errors"></div>
                                                @error('company_name')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="contact_no">
                                                    {{ __('Contact Number') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="contact_no" type="text"
                                                    class="form-control @error('contact_no') is-invalid @enderror"
                                                    name="contact_no" value="{{ old('contact_no') }}"
                                                    placeholder="Contact Number">
                                                <div class="help-block with-errors"></div>
                                                @error('contact_no')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="email">
                                                    {{ __('Email') }}
                                                </label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" placeholder="Email">
                                                <div class="help-block with-errors"></div>
                                                @error('email')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="client_source_id">
                                                    {{ __('Client Source') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="client_source_id"
                                                    class="form-control select-category @error('client_source_id') is-invalid @enderror"
                                                    name="client_source_id">
                                                    <option value="">Select Client Source</option>
                                                    @foreach ($client_sources as $source)
                                                        <option value="{{ $source->id }}">{{ $source->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('country_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="business_category_id">
                                                    {{ __('Business Category') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="business_category_id"
                                                    class="form-control select-category @error('business_category_id') is-invalid @enderror"
                                                    name="business_category_id">
                                                    <option value="">Select Business Category</option>
                                                    @foreach ($business_categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('business_category_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="client_status_id">
                                                    {{ __('Client Status') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="client_status_id"
                                                    class="form-control select-category @error('client_status_id') is-invalid @enderror"
                                                    name="client_status_id">
                                                    <option value="">Select Client Status</option>
                                                    @foreach ($client_statuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('client_status_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="interested_in_id">
                                                    {{ __('Interested In') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="interested_in_id"
                                                    class="form-control select-category @error('interested_in_id') is-invalid @enderror"
                                                    name="interested_in_id">
                                                    <option value="">Select Client Source</option>
                                                    @foreach ($interested_ins as $interest)
                                                        <option value="{{ $interest->id }}">{{ $interest->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('interested_in_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="country_id">
                                                    {{ __('Country') }}
                                                </label>
                                                <select id="country_id"
                                                    class="form-control select-category @error('country_id') is-invalid @enderror"
                                                    name="country_id">
                                                    <option value="">Select Country</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('country_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="state_id">
                                                    {{ __('State') }}
                                                </label>
                                                <select id="state_id"
                                                    class="form-control select-category @error('state_id') is-invalid @enderror"
                                                    name="state_id">
                                                    <option value="">Select State</option>
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('state_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="city_id">
                                                    {{ __('City') }}
                                                </label>
                                                <select id="city_id"
                                                    class="form-control select-category @error('city_id') is-invalid @enderror"
                                                    name="city_id">
                                                    <option value="">Select City</option>
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('city_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">
                                                    {{ __('Address') }}
                                                </label>
                                                <textarea id="address" rows="10" type="text" class="form-control @error('address') is-invalid @enderror"
                                                    name="address" placeholder="Address">{{ old('address') }}</textarea>
                                                <div class="help-block with-errors"></div>
                                                @error('address')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="note">
                                                    {{ __('Note') }}
                                                </label>
                                                <textarea id="note" rows="10" type="text" class="form-control @error('note') is-invalid @enderror"
                                                    name="note" placeholder="Note">{{ old('note') }}</textarea>
                                                <div class="help-block with-errors"></div>
                                                @error('note')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button id="submit" type="submit"
                                                class="btn btn-primary waves-effect waves-light">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-script')
    <script>
        const disableOnSubmit = () => {
            const button = document.querySelector('#submit');
            button.disabled = true;
            button.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
            return true;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const selectCategory = document.querySelectorAll(".select-category");

            selectCategory.forEach(element => {
                new Selectr(element);
            });

            const countrySelect = document.querySelector('#country_id');
            const stateSelect = document.querySelector('#state_id');
            const citySelect = document.querySelector('#city_id');

            countrySelect.addEventListener('change', function() {
                fetchStates(this.value);
            });

            stateSelect.addEventListener('change', function() {
                fetchCities(countrySelect.value, this.value);
            });

            function fetchStates(countryId) {
                if (countryId) {
                    fetch('{{ route('clients.get_states') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                country_id: countryId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            stateSelect.selectr.destroy();
                            citySelect.selectr.destroy();

                            stateSelect.innerHTML = '<option value="">Select State</option>';
                            citySelect.innerHTML = '<option value="">Select City</option>';
                            data.states.forEach(state => {
                                stateSelect.innerHTML +=
                                    `<option value="${state.id}">${state.name}</option>`;
                            });

                            //reinitialize selectr instance
                            new Selectr(stateSelect);
                            new Selectr(citySelect);
                        })
                        .catch(error => console.log(error));
                }
            }

            function fetchCities(countryId, stateId) {
                if (countryId && stateId) {
                    fetch('{{ route('clients.get_cities') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                country_id: countryId,
                                state_id: stateId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            citySelect.selectr.destroy();
                            citySelect.innerHTML = '<option value="">Select City</option>';
                            data.cities.forEach(city => {
                                citySelect.innerHTML +=
                                    `<option value="${city.id}">${city.name}</option>`;
                            });

                            //reinitialize selectr instance
                            new Selectr(citySelect);
                        })
                        .catch(error => console.error('Error fetching cities:', error));
                }
            }
        });
    </script>
@endsection
