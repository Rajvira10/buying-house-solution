@extends('admin.layout')
@section('title', 'Create Query')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Queries</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('queries.index') }}">Queries</a></li>
                                    <li class="breadcrumb-item active">Add Query</li>
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
                                <h3>{{ __('Add Query') }}</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('queries.store') }}" method="post" class="form-group"
                                    onsubmit="return disableOnSubmit()" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="product_name">
                                                    {{ __('Product Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="product_name" type="text"
                                                    class="form-control @error('product_name') is-invalid @enderror"
                                                    name="product_name" value="{{ old('product_name') }}" placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('product_name')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="trim_ids">
                                                    {{ __('Trim') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select id="trim_ids"
                                                    class="form-control multiple-select-category @error('trim_ids') is-invalid @enderror"
                                                    name="trim_ids[]" multiple>
                                                    <option value="" disabled>Select a category</option>
                                                    @foreach ($trims as $trim)
                                                        <option class="expense-category-field" value="{{ $trim->id }}">
                                                            {{ $trim->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block with-errors"></div>
                                                @error('trim_ids')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="details">
                                                    {{ __('Details') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <textarea id="details" class="form-control @error('details') is-invalid @enderror" name="details" rows="4">{{ old('details') }}</textarea>
                                                <div class="help-block with-errors"></div>
                                                @error('details')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="approximate_quantity">
                                                    {{ __('Approximate Quantity') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="approximate_quantity" type="number"
                                                    class="form-control @error('approximate_quantity') is-invalid @enderror"
                                                    name="approximate_quantity" value="{{ old('approximate_quantity') }}"
                                                    placeholder="">
                                                <div class="help-block with-errors"></div>
                                                @error('approximate_quantity')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="query_images">
                                                    {{ __('Query Images') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="query_images" type="file"
                                                    class="form-control @error('query_images') is-invalid @enderror"
                                                    name="query_images[]" multiple>
                                                <div class="help-block with-errors"></div>
                                                @error('query_images')
                                                    <span class="text-danger-error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="query_measurements">
                                                    {{ __('Query Measurements') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input id="query_measurements" type="file"
                                                    class="form-control @error('query_measurements') is-invalid @enderror"
                                                    name="query_measurements[]" multiple>
                                                <div class="help-block with-errors"></div>
                                                @error('query_measurements')
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
            const selectCategory = document.querySelectorAll(".multiple-select-category");
            for (let i = 0; i < selectCategory.length; i++) {
                new Selectr(selectCategory[i], {
                    multiple: true,
                    placeholder: 'Select a category'
                });
            }

            const select = document.querySelectorAll('.select-category');
            for (let i = 0; i < select.length; i++) {
                new Selectr(select[i]);
            }
        });
    </script>
@endsection
