@extends('admin.layout')
@section('title', 'Edit Project')
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
                                    <li class="breadcrumb-item active">Edit Project</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header">
                                <h3>{{ __('Edit Project') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('projects.update', $project->id) }}" method="post"
                                    class="form-group" onsubmit="return disableOnSubmit()">
                                    @csrf
                                    @method('POST')
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="start_date">
                                                    {{ __('Start Date') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                    data-default-date="{{ $project->start_date->format('d/m/Y') }}"
                                                    id="start_date" type="date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    name="start_date"
                                                    value="{{ old('start_date', $project->start_date->format('d/m/Y')) }}"
                                                    placeholder="">
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
                                                    name="end_date"
                                                    value="{{ old('end_date', optional($project->end_date)->format('d/m/Y')) }}"
                                                    placeholder="">
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
                                                    value="{{ old('name', $project->name) }}" placeholder="">
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
                                                            {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
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
                                                        <option value="{{ $type->id }}"
                                                            {{ old('project_type_id', $project->project_type_id) == $type->id ? 'selected' : '' }}>
                                                            {{ $type->name }}
                                                        </option>
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
                                                        <option value="{{ $status->id }}"
                                                            {{ old('project_status_id', $project->project_status_id) == $status->id ? 'selected' : '' }}>
                                                            {{ $status->name }}
                                                        </option>
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
                                                    class="form-control @error('note') is-invalid @enderror">{{ old('note', $project->note) }}</textarea>
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
                                        @foreach ($project->project_phases as $index => $phase)
                                            <div class="row mb-3 phase">
                                                <div class="col-md-12 mb-2">
                                                    <h5 class="phase-title">Phase {{ $index + 1 }}</h5>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phase_name_{{ $index }}">
                                                            {{ __('Phase Name') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input id="phase_name_{{ $index }}" type="text"
                                                            class="form-control" name="phases[{{ $index }}][name]"
                                                            value="{{ old('phases.' . $index . '.name', $phase->name) }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phase_status_{{ $index }}">
                                                            {{ __('Phase Status') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select id="phase_status_{{ $index }}"
                                                            class="form-control"
                                                            name="phases[{{ $index }}][project_phase_status_id]"
                                                            required>
                                                            <option value="">Select Status</option>
                                                            @foreach ($project_statuses as $status)
                                                                <option value="{{ $status->id }}"
                                                                    {{ old('phases.' . $index . '.project_phase_status_id', $phase->project_phase_status_id) == $status->id ? 'selected' : '' }}>
                                                                    {{ $status->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phase_amount_{{ $index }}">
                                                            {{ __('Amount') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input id="phase_amount_{{ $index }}" type="text"
                                                            class="form-control"
                                                            name="phases[{{ $index }}][amount]"
                                                            value="{{ old('phases.' . $index . '.amount', $phase->amount) }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phase_start_date_{{ $index }}">
                                                            {{ __('Phase Start Date') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                            id="phase_start_date_{{ $index }}" type="date"
                                                            class="form-control"
                                                            name="phases[{{ $index }}][start_date]"
                                                            data-default-date="{{ old('phases.' . $index . '.start_date', $phase->start_date->format('d/m/Y')) }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phase_end_date_{{ $index }}">
                                                            {{ __('Phase End Date') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input data-provider="flatpickr" data-date-format="d/m/Y"
                                                            id="phase_end_date_{{ $index }}" type="date"
                                                            class="form-control"
                                                            name="phases[{{ $index }}][end_date]"
                                                            data-default-date="{{ old('phases.' . $index . '.end_date', optional($phase->end_date)->format('d/m/Y')) }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="phase_description_{{ $index }}">
                                                            {{ __('Description') }}
                                                        </label>
                                                        <textarea name="phases[{{ $index }}][description]" id="phase_description_{{ $index }}" cols="30"
                                                            rows="3" class="form-control">{{ old('phases.' . $index . '.description', $phase->description) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-right mt-2">
                                                    <button type="button" class="btn btn-danger remove-phase"
                                                        onclick="removePhase(this)">Remove Phase</button>
                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-success" id="add-phase">Add
                                                Phase</button>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
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

    <script>
        let phaseIndex = {{ $project->project_phases->count() }};

        document.getElementById('add-phase').addEventListener('click', function() {
            let phasesContainer = document.getElementById('phases-container');
            let phaseHTML = `
                <div class="row mb-3 phase">
                    <div class="col-md-12 mb-2">
                        <h5 class="phase-title">Phase ${phaseIndex + 1}</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phase_name_${phaseIndex}">
                                {{ __('Phase Name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input id="phase_name_${phaseIndex}" type="text"
                                class="form-control" name="phases[${phaseIndex}][name]" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phase_status_${phaseIndex}">
                                {{ __('Phase Status') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select id="phase_status_${phaseIndex}"
                                class="form-control" name="phases[${phaseIndex}][project_phase_status_id]" required>
                                <option value="">Select Status</option>
                                @foreach ($project_statuses as $status)
                                    <option value="{{ $status->id }}">
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phase_amount_${phaseIndex}">
                                {{ __('Amount') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input id="phase_amount_${phaseIndex}" type="text"
                                class="form-control" name="phases[${phaseIndex}][amount]" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phase_start_date_${phaseIndex}">
                                {{ __('Phase Start Date') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input id="phase_start_date_${phaseIndex}" type="date"
                            class="form-control" name="phases[${phaseIndex}][start_date]"
                            data-provider="flatpickr" data-date-format="d/m/Y" data-default-date="{{ date('d/m/Y') }}"
                            required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phase_end_date_${phaseIndex}">
                                {{ __('Phase End Date') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input id="phase_end_date_${phaseIndex}" type="date"
                            class="form-control" name="phases[${phaseIndex}][end_date]"
                            data-provider="flatpickr" data-date-format="d/m/Y"
                            >
                        </div>
                    </div>
                    

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phase_description_${phaseIndex}">
                                {{ __('Description') }}
                            </label>
                            <textarea name="phases[${phaseIndex}][description]" id="phase_description_${phaseIndex}" cols="30" rows="3"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 text-right mt-2">
                        <button type="button" class="btn btn-danger remove-phase" onclick="removePhase(this)">Remove Phase</button>
                    </div>
                </div>
                <hr>`;
            phasesContainer.insertAdjacentHTML('beforeend', phaseHTML);
            const newPhaseStartDate = document.getElementById(`phase_start_date_${phaseIndex}`);
            const newPhaseEndDate = document.getElementById(`phase_end_date_${phaseIndex}`);
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
            phaseIndex++;
        });

        function removePhase(button) {
            button.closest('.phase').remove();
            updatePhaseTitles();
        }

        function updatePhaseTitles() {
            document.querySelectorAll('.phase').forEach((phase, index) => {
                phase.querySelector('.phase-title').innerText = `Phase ${index + 1}`;
            });
        }

        function disableOnSubmit() {
            document.querySelector('button[type="submit"]').disabled = true;
            return true;
        }
    </script>

@endsection
