@extends('admin.layout')
@section('title', 'Create Meeting')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Meetings</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('meetings.index') }}">Meetings</a></li>
                                    <li class="breadcrumb-item active">Add Meeting</li>
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
                                <h3>{{ __('Add Meeting') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('meetings.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="date">
                                                    {{ __('Date') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                    data-default-date="{{ date('d/m/Y') }}" id="date" type="date"
                                                    class="form-control @error('date') is-invalid @enderror" name="date"
                                                    value="{{ old('date') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('date')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="time">
                                                    {{ __('Time') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-enable-time="true"
                                                    data-no-calendar="true" data-date-format="h:i K"
                                                    data-default-date="00:00" type="time"
                                                    class="form-control @error('time') is-invalid @enderror" name="time"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('time')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="meeting_title_id">
                                                    {{ __('Meeting Title') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="meeting_title_id"
                                                    class="form-control select-category @error('meeting_title_id') is-invalid @enderror"
                                                    name="meeting_title_id">
                                                    <option value="">Select Meeting Title</option>
                                                    @foreach ($meeting_titles as $value)
                                                        <option value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('meeting_title_id')
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
                                                <label for="client_id">
                                                    {{ __('Client') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="client_id"
                                                    class="form-control select-category @error('client_id') is-invalid @enderror"
                                                    name="client_id">
                                                    <option value="">Select Client</option>
                                                    @foreach ($clients as $client)
                                                        <option value="{{ $client->id }}"
                                                            {{ $client_id == $client->id ? 'selected' : '' }}>
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
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="meeting_type_id">
                                                    {{ __('Meeting Type') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="meeting_type_id"
                                                    class="form-control select-category @error('meeting_type_id') is-invalid @enderror"
                                                    name="meeting_type_id">
                                                    <option value="">Select Meeting Type</option>
                                                    @foreach ($meeting_types as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('meeting_type_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="meeting_status_id">
                                                    {{ __('Meeting Status') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="meeting_status_id"
                                                    class="form-control select-category @error('meeting_status_id') is-invalid @enderror"
                                                    name="meeting_status_id">
                                                    <option value="">Select Status</option>
                                                    @foreach ($meeting_statuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('meeting_status_id')
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
                                                <label for="note">
                                                    {{ __('Meeting Summary') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <textarea name="note" id="note" cols="30" rows="5"
                                                    class="form-control @error('note') is-invalid @enderror"></textarea>
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
        });

        document.getElementById('client_id').addEventListener('change', function() {
            const client_id = this.value;
            const contact_person_id = document.getElementById('contact_person_id');
            const url = "{{ route('clients.get_contact_persons', ':id') }}";
            const newUrl = url.replace(':id', client_id);
            if (client_id) {
                fetch(newUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (contact_person_id.selectr) {
                            contact_person_id.selectr.destroy();
                        }
                        contact_person_id.innerHTML = '';
                        contact_person_id.innerHTML = '<option value="">Select Contact Person</option>';
                        data.contact_persons.forEach(element => {
                            const option = document.createElement('option');
                            option.value = element.id;
                            option.text = element.name;
                            contact_person_id.appendChild(option);
                        });
                        new Selectr(contact_person_id);
                    });
            }
        });
    </script>
@endsection
