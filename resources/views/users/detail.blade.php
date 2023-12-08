@extends('adminlte::page')

@section('title', 'Details')

@section('content_header')
    <h1>
        Detail User = <b>{{ $detail->name }}</b>
        @if ($detail->status == 1)
            <span style="color: green"><b>(Active)</b></span>
        @elseif ($detail->status == 0)
            <span style="color: red"><b>(Banned)</b></span>
        @endif
    </h1>
@endsection

@section('content')
    <x-adminlte-alert theme="info" title="Information">
        This profile page only use for <code>Admin</code> only. Member cannot seeing some options.
    </x-adminlte-alert>

    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Profile" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-sm fa-user"
                header-class="rounded-bottom border-info">
                <div id="profile_success"></div>
                <div id="profile_error"></div>
                <x-adminlte-input name="id" id="id" label="ID" type="text" placeholder="Your name here"
                    value="{{ $detail->id }}" readonly />
                <x-adminlte-input name="name" id="name" label="Name" type="text" placeholder="Your name here"
                    value="{{ $detail->name }}" />
                <x-adminlte-input name="email" id="email" label="Email" type="email"
                    placeholder="example@youremail.com" value="{{ $detail->email }}" readonly />
                <div class="d-flex">
                    <div class="ml-auto">
                        <x-adminlte-button class="btn-sm" label="Save" id="editProfile" theme="success"
                            onclick="editProfile({{ $detail->id }})" />
                    </div>
                </div>
            </x-adminlte-card>
            <x-adminlte-card title="Password" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-sm fa-lock"
                header-class="rounded-bottom border-info">
                <div id="password_success"></div>
                <div id="password_error"></div>
                <x-adminlte-input name="oldpassword" id="oldpassword" label="Old Password" type="password"
                    placeholder="****" />
                <x-adminlte-input name="newpassword" id="newpassword" label="New Password" type="password"
                    placeholder="****" />
                <x-adminlte-input name="newpassword2" id="newpassword2" label="Verify Password" type="password"
                    placeholder="****" />
                <div class="d-flex">
                    <div class="ml-auto">
                        <x-adminlte-button class="btn-sm" label="Save" id="editPassword" theme="success"
                            onclick="editPassword({{ $detail->id }})" />
                    </div>
                </div>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="API" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-sm fa-fire"
                header-class="text-uppercase rounded-bottom border-info">
                <div id="success"></div>
                <div id="error"></div>
                <div id="contents">
                    @if ($token)
                        <x-adminlte-input name="api" id="api" label="Your API Key" placeholder="API Key"
                            type="text" igroup-size="sm" value="{{ $token }}" readonly>
                            <x-slot name="appendSlot">
                                <x-adminlte-button theme="outline-secondary" label="Re-generate"
                                    onclick="regenerate({{ $detail->id }})" />
                                <x-adminlte-button theme="danger" label="Revoke" onclick="revoke({{ $detail->id }})" />
                            </x-slot>
                        </x-adminlte-input>
                    @else
                        <x-adminlte-input name="api" id="api" label="Your API Key"
                            placeholder="Generate your first API Key, click Generate API Button and enjoy" type="text"
                            igroup-size="sm" readonly>
                            <x-slot name="appendSlot">
                                <x-adminlte-button class="btn-sm" theme="outline-primary" label="Generate API"
                                    onclick="generate({{ $detail->id }})" />
                            </x-slot>
                        </x-adminlte-input>
                    @endif
                </div>
            </x-adminlte-card>
            <x-adminlte-card title="Admin Control" theme="primary" theme-mode="outline" class="mt-4"
                icon="fas fa-sm fa-cog" header-class="rounded-bottom border-info">
                <div id="edit_success"></div>
                <div id="edit_error"></div>
                <x-adminlte-select name="status" id="status" label="Status">
                    <option value="1" @if ($detail->status == 1) selected @endif>Active</option>
                    <option value="0" @if ($detail->status == 0) selected @endif>Banned</option>
                </x-adminlte-select>
                <x-adminlte-select name="role" id="role" label="Role">
                    <option value="1" @if ($detail->role_id == 1) selected @endif>Admin</option>
                    <option value="2" @if ($detail->role_id == 2) selected @endif>Member</option>
                </x-adminlte-select>
                <div class="d-flex">
                    <div class="ml-auto">
                        <x-adminlte-button class="btn-sm" label="Save" id="editUser" theme="success"
                            onclick="editUser({{ $detail->id }})" />
                    </div>
                </div>
            </x-adminlte-card>
            <x-adminlte-card title="Delete User" theme="danger" icon="fas fa-sm fa-exclamation-triangle">
                <div class="row">
                    <div class="col-md-6">
                        Delete User - <b>{{ $detail->name }}</b> ?
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="ml-auto">
                            @if ($detail->id == auth()->id())
                                <x-adminlte-button class="btn-sm" label="You cant delete user you logged in"
                                    theme="danger" disabled />
                            @else
                                <x-adminlte-button class="btn-sm" label="Delete" theme="danger"
                                    onclick="userDelete({{ $detail->id }})" />
                            @endif
                        </div>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const token = $('meta[name="csrf-token"]').attr('content');

        function generate(id) {
            $.ajax({
                url: '/generate/api/' + id,
                method: 'POST',
                data: {
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    $('#contents').load(' #contents');
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };

        function regenerate(id) {
            $.ajax({
                url: '/generate/api/regenerate/' + id,
                method: 'POST',
                data: {
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    $('#contents').load(' #contents');
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };

        function revoke(id) {
            $.ajax({
                url: '/generate/api/revoke/' + id,
                method: 'DELETE',
                data: {
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    $('#contents').load(' #contents');
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };

        function editProfile(id) {
            var name = $('#name').val();
            var email = $('#email').val();
            $.ajax({
                url: '/profile/edit/' + id,
                method: 'POST',
                data: {
                    name: name,
                    email: email,
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#profile_success').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                },
                error: function(xhr, status, error) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#profile_error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };

        function editPassword(id) {
            var oldpassword = $('#oldpassword').val();
            var newpassword = $('#newpassword').val();
            var newpassword2 = $('#newpassword2').val();
            $.ajax({
                url: '/profile/edit/password/' + id,
                method: 'POST',
                data: {
                    oldpassword: oldpassword,
                    newpassword: newpassword,
                    newpassword2: newpassword2,
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#password_success').html(successMessage);
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#password_error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };

        function editUser(id) {
            var role = $('#role').val();
            var status = $('#status').val();
            $.ajax({
                url: '/users/edit/' + id,
                method: 'POST',
                data: {
                    role: role,
                    status: status,
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#edit_success').html(successMessage);
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#edit_error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };

        function userDelete(id) {
            Swal.fire({
                title: 'Confirm',
                text: 'Are you sure want delete this ?',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/users/delete/' + id,
                        method: 'GET',
                        data: {
                            _token: token
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'User delete successfully.'
                            }).then(() => {
                                window.location.href = '{{ route('users.index') }}';
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Failed',
                                'User deleted failed.',
                                'error');
                        }
                    });
                }
            })
        };
    </script>
@endsection
