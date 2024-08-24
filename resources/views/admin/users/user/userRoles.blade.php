@extends('admin.layout')

@section('title', 'Assign Roles')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">User Roles</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i
                                                class="ri-home-5-fill"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                                    <li class="breadcrumb-item active">Assign Roles</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Assign Roles to <span
                                        class="font-weight-bold">{{ $user->first_name }} {{ $user->last_name }}</span></h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('users.assign_roles', $user->id) }}" method="POST">
                                    @csrf
                                    <table class="table" style="width: 80%">
                                        <thead>
                                            <tr>
                                                <th>Roles</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $role)
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="roles[]"
                                                                value="{{ $role->id }}" class="form-check-input"
                                                                @if (in_array($role->id, $user_roles_array)) checked @endif>
                                                            <label class="form-check-label">{{ $role->name }}</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $role->description }}</strong>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Save Roles</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
