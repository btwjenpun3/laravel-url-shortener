@extends('adminlte::page')

@section('title', 'Links')

@section('content')
    <x-adminlte-input name="link" id="link" placeholder="https://your-domain/very-long-text" igroup-size="lg"
        igroup-class="pt-4">
        <x-slot name="appendSlot">
            <x-adminlte-button theme="outline-danger" id="create" label="Short It!" onclick="create()" />
            <x-adminlte-button theme="outline-primary" label="More+" data-toggle="collapse" data-target="#collapseMore" />
        </x-slot>
        <x-slot name="prependSlot">
            <div class="input-group-text text-danger">
                <i class="fas fa-link"></i>
            </div>
        </x-slot>
    </x-adminlte-input>
    <div class="collapse" id="collapseMore">
        <div class="card card-body">
            <x-adminlte-input name="title" label="Title (Optional)" id="title" igroup-size="lg" />
            <x-adminlte-input type="password" name="title" label="Password (Optional)" id="setPassword"
                igroup-size="lg" />
        </div>
    </div>

    <div id="success"></div>
    <div id="error"></div>

    <div id="contents">
        @if ($links->isEmpty())
            <x-adminlte-callout>Lets start create new shortlink! Paste your long URL into form then click <code> Short
                    It!</code></x-adminlte-callout>
        @else
            @foreach ($links as $link)
                <x-adminlte-card id="links">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><strong>{{ $link->title }}</strong></h5>
                            <h4>{{ env('APP_URL') . '/' . $link->short_url }}</h4>
                            <span class="text-muted">{{ $link->original_url }}</span>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="ml-auto">
                                <x-adminlte-button label="Share" theme="primary" icon="fas fa-share" />
                                <x-adminlte-button theme="outline-primary" icon="fas fa-qrcode" data-toggle="modal"
                                    data-target="#qrModal{{ $link->id }}" />
                                <x-adminlte-button label="Edit" theme="outline-primary" icon="fas fa-edit"
                                    data-toggle="modal" data-target="#editModal{{ $link->id }}" />
                            </div>
                        </div>
                    </div>
                    <x-slot name="footerSlot">
                        <div class="row">
                            <div class="col-md-4">
                                <i class="far fa-folder"></i>
                                {{ Carbon\Carbon::parse($link->created_at)->format('d M Y H:i') }}
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <div class="ml-auto">
                                        <x-adminlte-button class="btn-sm" theme="outline-secondary" label="Set Time"
                                            icon="fas fa-clock" />
                                        @if (isset($link->password))
                                            <x-adminlte-button class="btn-sm" theme="outline-primary" label="Locked"
                                                icon="fas fa-lock" data-toggle="modal"
                                                data-target="#passwordModal{{ $link->id }}" />
                                        @else
                                            <x-adminlte-button class="btn-sm" theme="outline-secondary" label="Set Password"
                                                icon="fas fa-unlock" data-toggle="modal"
                                                data-target="#passwordModal{{ $link->id }}" />
                                        @endif
                                        <x-adminlte-button id="delete{{ $link->id }}" class="btn-sm delete-button"
                                            theme="outline-danger" label="Delete" onclick="hapus({{ $link->id }})"
                                            icon="fas fa-trash" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-adminlte-card>
                @include('link.modals.password')
                @include('link.modals.qr')
                @include('link.modals.edit')
            @endforeach
            {{ $links->links() }}
        @endif
    </div>
@endsection

@section('js')
    <script>
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        function create(id) {
            $.ajax({
                url: '{{ route('link.store') }}',
                type: 'post',
                data: {
                    'title': $('#title').val(),
                    'link': $('#link').val(),
                    'password': $('#setPassword').val(),
                    '_token': csrfToken
                },
                success: function(response) {
                    var successMessage = document.createElement("div");
                    successMessage.className = "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#contents').load(' #contents');
                    $('#success').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                    $("#link").val('');
                    $("#title").val('');
                    $("#setPassword").val('');
                    return false;
                },
                error: function(xhr, error, status) {
                    var errorMessage = document.createElement("div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                    return false;
                }
            });
        };

        function hapus(id) {
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
                        url: '/links/' + id,
                        method: 'DELETE',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Link delete successfully.'
                            });
                            $('#contents').load(' #contents');
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Failed',
                                'Link deleted failed.',
                                'error');
                        }
                    });
                }
            })
        };


        function edit(id) {
            $.ajax({
                url: '/links/' + id,
                method: 'POST',
                data: {
                    id: id,
                    title: $('#edited_title' + id).val(),
                    original_url: $('#edited_original_url' + id).val(),
                    short_url: $('#edited_short_url' + id).val(),
                    _token: csrfToken
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    $('#editModal' + id).modal('hide');
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
                    $('#invalid' + id).html(errorMessage);
                    console.log(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                    return false;
                }
            });
        };

        function password(id) {
            $.ajax({
                url: '/links/password/' + id,
                method: 'POST',
                data: {
                    id: id,
                    password: $('#password' + id).val(),
                    _token: csrfToken
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    $('#passwordModal' + id).modal('hide');
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
                    $('#error' + id).html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };

        function removePassword(id) {
            $.ajax({
                url: '/links/password/' + id,
                method: 'DELETE',
                data: {
                    id: id,
                    _token: csrfToken
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
                    $('#passwordModal' + id).modal('hide');
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
                    $('#error' + id).html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };
    </script>
@endsection
