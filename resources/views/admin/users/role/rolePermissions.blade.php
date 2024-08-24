@extends('admin.layout')

@section('title', 'Assign Permission')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Role Permissions</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                                    <li class="breadcrumb-item active">Assign Permissions</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Assign Permissions to <span
                                        class="font-weight-bold">{{ $role->name }}</span></h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('roles.assign_permissions', $role->id) }}" method="POST">
                                    @csrf
                                    <table class="table " style="width: 80%">
                                        <thead>
                                            <tr>
                                                <th>Permission Group</th>
                                                <th>Permissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $section => $sectionPermissions)
                                                <tr class="mt-5">
                                                    <td style="vertical-align: middle"><b>{{ $section }}</b></td>
                                                    <td>
                                                        <div class="mb-3 mt-2 form-check">
                                                            <input type="checkbox" id="select_all_{{ $section }}"
                                                                class="form-check-input select-all-checkbox">
                                                            <label for="select_all_{{ $section }}"><strong>Select
                                                                    All</strong></label>
                                                        </div>

                                                        <table class="table table-borderless">
                                                            <colgroup>
                                                                <col style="width: 50%;">
                                                                <col style="width: 50%;">
                                                            </colgroup>
                                                            <tbody>
                                                                @foreach ($sectionPermissions as $permission)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check">
                                                                                <input type="checkbox" name="permissions[]"
                                                                                    value="{{ $permission->id }}"
                                                                                    class="form-check-input permission_{{ $permission->permission_group }}"
                                                                                    @if (in_array($permission->id, $role_permissions_array)) checked @endif>
                                                                                <label class="form-check-label"
                                                                                    for="permission_{{ $permission->id }}">{{ $permission->alias }}</label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-start">
                                                                            {{ $permission->description }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Save Permissions</button>
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
        $(document).ready(function() {
            $('.select-all-checkbox').click(function() {
                var sectionId = $(this).attr('id').replace('select_all_', '');
                var checkboxes = $('.permission_' + sectionId);
                checkboxes.prop('checked', $(this).prop('checked'));
            });
        });
    </script>
@endsection
