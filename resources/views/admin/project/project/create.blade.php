@extends('admin.layout')
@section('title', 'Create Project')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Projects</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                                    <li class="breadcrumb-item active">Add Project</li>
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
                                <h3>{{ __('Add Project') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('projects.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="start_date">
                                                    {{ __('Start Date') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                    data-default-date="{{ date('d/m/Y') }}" id="start_date" type="date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    name="start_date" value="{{ old('start_date') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('start_date')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="end_date">
                                                    {{ __('End Date') }}
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y" id="end_date"
                                                    type="date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    name="end_date" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('end_date')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="name">
                                                    {{ __('Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="name" type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    value="{{ old('name') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('name')
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
                                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                                                <label for="project_type_id">
                                                    {{ __('Project Type') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="project_type_id"
                                                    class="form-control select-category @error('project_type_id') is-invalid @enderror"
                                                    name="project_type_id">
                                                    <option value="">Select Project Type</option>
                                                    @foreach ($project_types as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('project_type_id')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="project_status_id">
                                                    {{ __('Project Status') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="project_status_id"
                                                    class="form-control select-category @error('project_status_id') is-invalid @enderror"
                                                    name="project_status_id">
                                                    <option value="">Select Status</option>
                                                    @foreach ($project_statuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('project_status_id')
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
                                                    {{ __('Note') }}
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
                                    <hr>
                                    <div id="phases-container">
                                        <div class="row mb-3 phase">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phase_name_0">
                                                        {{ __('Phase Name') }}
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input id="phase_name_0" type="text" class="form-control"
                                                        name="phases[0][name]" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phase_status_0">
                                                        {{ __('Phase Status') }}
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select id="phase_status_0" class="form-control"
                                                        name="phases[0][project_phase_status_id]">
                                                        <option value="">Select Status</option>
                                                        @foreach ($project_statuses as $status)
                                                            <option value="{{ $status->id }}">{{ $status->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="phase_amount_0">
                                                        {{ __('Amount') }}
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input id="phase_amount_0" type="text" class="form-control"
                                                        name="phases[0][amount]" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-2">
                                                <div class="form-group">
                                                    <label for="phase_start_date_0">
                                                        {{ __('Start Date') }}
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input id="phase_start_date_0" type="date" class="form-control"
                                                        name="phases[0][start_date]" placeholder=""
                                                        data-provider="flatpickr" data-date-format="d/m/Y"
                                                        data-default-date="{{ date('d/m/Y') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-2">
                                                <div class="form-group">
                                                    <label for="phase_end_date_0">
                                                        {{ __('End Date') }}
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input id="phase_end_date_0" type="date" class="form-control"
                                                        name="phases[0][end_date]" placeholder=""
                                                        data-provider="flatpickr" data-date-format="d/m/Y">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-2">
                                                <div class="form-group">
                                                    <label for="phase_description_0">
                                                        {{ __('Description') }}
                                                    </label>
                                                    <textarea id="phase_description_0" class="form-control" name="phases[0][description]" placeholder=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-success" id="add-phase">Add
                                            Phase</button>
                                    </div>
                                    <div class="form-group mt-5">
                                        <button id="submit" type="submit"
                                            class="btn btn-primary waves-effect waves-light">Submit</button>
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
            let phaseCount = 1;
            const addPhaseButton = document.querySelector('#add-phase');
            const phasesContainer = document.querySelector('#phases-container');

            addPhaseButton.addEventListener('click', function() {
                const phaseTemplate = `
            <div class="row mb-3 phase">
                <div class="col-md-12 mb-2">
                    <h5 class="phase-title">Phase ${phaseCount + 1}</h5>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phase_name_${phaseCount}">
                            {{ __('Phase Name') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input id="phase_name_${phaseCount}" type="text"
                            class="form-control" name="phases[${phaseCount}][name]"
                            placeholder="Enter phase name" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phase_status_${phaseCount}">
                            {{ __('Phase Status') }}
                            <span class="text-danger">*</span>
                        </label>
                        <select id="phase_status_${phaseCount}"
                            class="form-control" name="phases[${phaseCount}][project_phase_status_id]" required>
                            <option value="">Select Status</option>
                            @foreach ($project_statuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phase_amount_${phaseCount}">
                            {{ __('Amount') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input id="phase_amount_${phaseCount}" type="number" step="0.01"
                            class="form-control" name="phases[${phaseCount}][amount]"
                            placeholder="Enter amount" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phase_start_date_${phaseCount}">
                            {{ __('Start Date') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input id="phase_start_date_${phaseCount}" type="date"
                            class="form-control" name="phases[${phaseCount}][start_date]"
                            data-provider="flatpickr" data-date-format="d/m/Y" data-default-date="{{ date('d/m/Y') }}"
                            required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phase_end_date_${phaseCount}">
                            {{ __('End Date') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input id="phase_end_date_${phaseCount}" type="date"
                            class="form-control" name="phases[${phaseCount}][end_date]"
                            data-provider="flatpickr" data-date-format="d/m/Y"
                            required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phase_description_${phaseCount}">
                            {{ __('Description') }}
                        </label>
                        <textarea id="phase_description_${phaseCount}" class="form-control" name="phases[${phaseCount}][description]" placeholder="Enter phase description"></textarea>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="button" class="btn btn-danger btn-sm delete-phase">Delete Phase</button>
                </div>
            </div>
        `;



                phasesContainer.insertAdjacentHTML('beforeend', phaseTemplate);

                new Selectr(document.getElementById(`phase_status_${phaseCount}`));

                const newPhaseStartDate = document.getElementById(`phase_start_date_${phaseCount}`);
                const newPhaseEndDate = document.getElementById(`phase_end_date_${phaseCount}`);
                if (newPhaseStartDate) {
                    new flatpickr(newPhaseStartDate, {
                        dateFormat: 'd/m/Y',
                        defaultDate: new Date(),
                    });
                }
                if (newPhaseEndDate) {
                    new flatpickr(newPhaseEndDate, {
                        dateFormat: 'd/m/Y',
                    });
                }
                
                phaseCount++;
                updatePhaseNumbers();
            });

            // Event delegation for delete buttons
            phasesContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-phase')) {
                    e.target.closest('.phase').remove();
                    updatePhaseNumbers();
                }
            });

            function updatePhaseNumbers() {
                const phases = document.querySelectorAll('.phase');
                phases.forEach((phase, index) => {
                    const phaseTitle = phase.querySelector('.phase-title');
                    if (phaseTitle) {
                        phaseTitle.textContent = `Phase ${index + 1}`;
                    }
                });
            }

            const selectCategory = document.querySelectorAll(".select-category");

            selectCategory.forEach(element => {
                new Selectr(element);
            });
        });
    </script>
@endsection
