@extends('admin.layout')
@section('title', 'Edit Call')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Calls</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('calls.index') }}">Calls</a></li>
                                    <li class="breadcrumb-item active">Edit Call</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- @include('include.message') --}}
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3>{{ __('Edit Call') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('calls.update', $call->id) }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()">
                                    @csrf
                                    @method('POST')
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="client_id">
                                                    {{ __('Client') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="client_id"
                                                    class="form-control select-category @error('client_id') is-invalid @enderror"
                                                    name="client_id">
                                                    <option value="">Select Client</option>
                                                    @foreach ($clients as $client)
                                                        <option value="{{ $client->id }}" {{ old('client_id', $call->client_id) == $client->id ? 'selected' : '' }}>
                                                            {{ $client->company_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('client_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="contact_person_id">
                                                    {{ __('Contact Person') }}
                                                </label>
                                                <select id="contact_person_id"
                                                    class="form-control select-category @error('contact_person_id') is-invalid @enderror"
                                                    name="contact_person_id">
                                                    <option value="">Select Contact Person</option>
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('contact_person_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="call_type_id">
                                                    {{ __('Call Type') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="call_type_id"
                                                    class="form-control select-category @error('call_type_id') is-invalid @enderror"
                                                    name="call_type_id">
                                                    <option value="">Select Call Type</option>
                                                    @foreach ($call_types as $type)
                                                        <option value="{{ $type->id }}" {{ old('call_type_id', $call->call_type_id) == $type->id ? 'selected' : '' }}>
                                                            {{ $type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('call_type_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="call_status_id">
                                                    {{ __('Call Status') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="call_status_id"
                                                    class="form-control select-category @error('call_status_id') is-invalid @enderror"
                                                    name="call_status_id">
                                                    <option value="">Select Status</option>
                                                    @foreach ($call_statuses as $status)
                                                        <option value="{{ $status->id }}" {{ old('call_status_id', $call->call_status_id) == $status->id ? 'selected' : '' }}>
                                                            {{ $status->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('call_status_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="call_summary">
                                                    {{ __('Call Summary') }}
                                                </label>
                                                <textarea name="call_summary" id="call_summary" cols="30" rows="5"
                                                    class="form-control @error('call_summary') is-invalid @enderror">{{ old('call_summary', $call->call_summary) }}</textarea>
                                                <div class="help-block with-errors"></div>
                                                @error('call_summary')
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
                                                class="btn btn-primary waves-effect waves-light">Update</button>
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

            const clientSelect = document.getElementById('client_id');
            const contactPersonSelect = document.getElementById('contact_person_id');

            // Initial fetch for contact persons based on current call data
            const initialClientId = clientSelect.value;
            const initialContactPersonId = "{{ old('contact_person_id', $call->contact_person_id) }}";

            if (initialClientId) {
                fetchContactPersons(initialClientId, initialContactPersonId);
            }

            clientSelect.addEventListener('change', function() {
                fetchContactPersons(this.value);
            });

            function fetchContactPersons(clientId, contactPersonId = null) {
                const url = "{{ route('clients.get_contact_persons', ':id') }}";
                const newUrl = url.replace(':id', clientId);
                if (clientId) {
                    fetch(newUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (contactPersonSelect.selectr) {
                                contactPersonSelect.selectr.destroy();
                            }
                            contactPersonSelect.innerHTML = '<option value="">Select Contact Person</option>';
                            data.contact_persons.forEach(element => {
                                contactPersonSelect.innerHTML += `<option value="${element.id}" ${element.id == contactPersonId ? 'selected' : ''}>${element.name}</option>`;
                            });
                            new Selectr(contactPersonSelect);
                        })
                        .catch(error => console.error('Error fetching contact persons:', error));
                }
            }
        });
    </script>
@endsection
