@extends('admin.layout')
@section('title', 'Factory Details')
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
                                    <li class="breadcrumb-item"><a href={{ route('factories.index') }}>Factories</a></li>
                                    <li class="breadcrumb-item active">Factory Details</li>
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
                <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
                    <div class="row g-4">
                        <div class="col-auto">
                            {{-- <div class="avatar-lg">
                                <img src={{ asset('public/admin-assets/images/user-dummy-img.jpg') }} alt="user-img"
                                    class="img-thumbnail rounded-circle" />
                            </div> --}}
                        </div>
                        <div class="col">
                            <div class="p-2">
                                <h3 class="text-white mb-1">{{ $factory->name }}</h3>
                                <p class="text-white-75">Factory</p>
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
                                    <li class="nav-item">
                                        <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab"
                                            role="tab">
                                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                                class="d-none d-md-inline-block">Overview</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content pt-4 text-muted">
                                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                    <div class="col-xxl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title mb-3">About</h5>
                                                    <div class="flex-shrink-0">
                                                        <div class="d-flex justify-content-end">
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addContactPersonModal">
                                                                <i class="ri-user-add-line align-bottom"></i>
                                                                Add Contact Person
                                                            </button>
                                                            <a href={{ route('factories.edit', $factory->id) }}
                                                                class="btn btn-success ms-3"><i
                                                                    class="ri-edit-box-line align-bottom "></i> Edit
                                                            </a>
                                                        </div>

                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row text-black">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td><strong>Email :</strong></td>
                                                                <td>{{ $factory->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Phone :</strong></td>
                                                                <td>{{ $factory->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Address :</strong></td>
                                                                <td>{{ $factory->address }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <h5 class="card-title mb-3">Contact People</h5>
                                                <div class="row">
                                                    @foreach ($factory->contact_people as $contact_person)
                                                        <div class="col-md-12">
                                                            <table class="table table-borderless">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th><strong>Name :</strong></th>
                                                                    <th><strong>Email :</strong></th>
                                                                    <th><strong>Phone :</strong></th>
                                                                    <th><strong>Designation :</strong></th>
                                                                    <th><strong>Action :</strong></th>
                                                                </tr>
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $contact_person->name }}</td>
                                                                    <td>{{ $contact_person->email }}</td>
                                                                    <td>{{ $contact_person->phone }}</td>
                                                                    <td>{{ $contact_person->designation }}</td>
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            <button type="button"
                                                                                class="btn btn-warning me-2 btn-sm"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#editContactPersonModal"
                                                                                data-id="{{ $contact_person->id }}"
                                                                                data-name="{{ $contact_person->name }}"
                                                                                data-email="{{ $contact_person->email }}"
                                                                                data-phone="{{ $contact_person->phone }}"
                                                                                data-designation="{{ $contact_person->designation }}">
                                                                                <i class="ri-edit-line align-bottom"></i>
                                                                                Edit
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm"
                                                                                onclick="deleteContactPerson({{ $contact_person->id }})">
                                                                                <i
                                                                                    class="ri-delete-bin-line align-bottom"></i>
                                                                                Delete
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    @endforeach
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
        </div>
    </div>

    <!-- Modal for Adding Contact Person -->
    <div class="modal fade" id="addContactPersonModal" tabindex="-1" aria-labelledby="addContactPersonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContactPersonModalLabel">Add Contact Person</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addContactPersonForm">
                        @csrf
                        <input type="hidden" name="factory_id" value="{{ $factory->id }}">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Contact Person -->
    <div class="modal fade" id="editContactPersonModal" tabindex="-1" aria-labelledby="editContactPersonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContactPersonModalLabel">Edit Contact Person</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editContactPersonForm">
                        @csrf
                        <input type="hidden" name="contact_person_id" id="editContactPersonId">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editPhone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="editDesignation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="editDesignation" name="designation">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('custom-script')
    <script>
        $(document).ready(function() {
            // Add Contact Person form submission
            $('#addContactPersonForm').on('submit', function(event) {
                event.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('factories.store_contact_person') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toaster('Contact Person added successfully', 'success');
                            location.reload();
                        } else if (response.error) {
                            toaster('Something went wrong', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Show edit contact person modal with data
            $('#editContactPersonModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                var email = button.data('email');
                var phone = button.data('phone');
                var designation = button.data('designation');

                var modal = $(this);
                modal.find('#editContactPersonId').val(id);
                modal.find('#editName').val(name);
                modal.find('#editEmail').val(email);
                modal.find('#editPhone').val(phone);
                modal.find('#editDesignation').val(designation);
            });

            // Edit Contact Person form submission
            $('#editContactPersonForm').on('submit', function(event) {
                event.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('factories.update_contact_person') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toaster('Contact Person updated successfully', 'success');
                            location.reload();
                        } else if (response.error) {
                            toaster('Something went wrong', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        // Function to delete contact person with SweetAlert confirmation
        function deleteContactPerson(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('factories.delete_contact_person') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            contact_person_id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Contact person has been deleted.',
                                    'success'
                                );
                                location.reload();
                            } else if (response.error) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong while deleting.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire(
                                'Error!',
                                'An error occurred.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
@endsection
