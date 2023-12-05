@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Profile" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-user"
                header-class="rounded-bottom border-info">
                <div id="profile_success"></div>
                <div id="profile_error"></div>
                <x-adminlte-input name="name" id="name" label="Name" type="text" placeholder="Your name here"
                    value="{{ $name }}" />
                <x-adminlte-input name="email" id="email" label="Email" type="email"
                    placeholder="example@youremail.com" value="{{ $email }}" readonly />
                <div class="d-flex">
                    <div class="ml-auto">
                        <x-adminlte-button label="Save" id="editProfile" theme="success"
                            onclick="editProfile({{ $id }})" />
                    </div>
                </div>
            </x-adminlte-card>
            <x-adminlte-card title="Password" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-lock"
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
                        <x-adminlte-button label="Save" id="editPassword" theme="success"
                            onclick="editPassword({{ $id }})" />
                    </div>
                </div>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="API" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-lg fa-fire"
                header-class="text-uppercase rounded-bottom border-info">
                <div id="success"></div>
                <div id="error"></div>
                <div id="contents">
                    @if ($token)
                        <x-adminlte-input name="api" id="api" label="Your API Key" placeholder="API Key"
                            type="text" igroup-size="sm" value="{{ $token }}" readonly>
                            <x-slot name="appendSlot">
                                <x-adminlte-button theme="outline-secondary" label="Re-generate" onclick="regenerate()" />
                                <x-adminlte-button theme="danger" label="Revoke" onclick="revoke()" />
                            </x-slot>
                        </x-adminlte-input>
                    @else
                        <x-adminlte-input name="api" id="api" label="Your API Key"
                            placeholder="Generate your first API Key, click Generate API Button and enjoy" type="text"
                            igroup-size="sm" readonly>
                            <x-slot name="appendSlot">
                                <x-adminlte-button theme="outline-primary" label="Generate API" onclick="generate()" />
                            </x-slot>
                        </x-adminlte-input>
                    @endif
                </div>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const token = $('meta[name="csrf-token"]').attr('content');

        function generate() {
            $.ajax({
                url: '/generate/api',
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

        function regenerate() {
            $.ajax({
                url: '/generate/api/regenerate',
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

        function revoke() {
            $.ajax({
                url: '/generate/api/revoke',
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
    </script>
@endsection
