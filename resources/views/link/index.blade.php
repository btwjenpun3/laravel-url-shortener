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
        </div>
    </div>

    <div id="create_link"></div>
    <div id="edit_success"></div>

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
                                <x-adminlte-button label="Edit" theme="secondary" icon="fas fa-edit" data-toggle="modal"
                                    data-target="#editModal{{ $link->id }}" />
                            </div>
                        </div>
                    </div>

                    <x-slot name="footerSlot">
                        <div class="row">
                            <div class="col-md-4">
                                <i class="fa fa-folder"></i> {{ $link->created_at }}
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <div class="ml-auto">
                                        <x-adminlte-button class="btn-sm" theme="outline-secondary" label="Set Time"
                                            icon="fas fa-clock" />
                                        <x-adminlte-button class="btn-sm" theme="outline-secondary" label="Set Password"
                                            icon="fas fa-lock" />
                                        <x-adminlte-button id="delete{{ $link->id }}" class="btn-sm delete-button"
                                            theme="outline-danger" label="Delete" onclick="hapus({{ $link->id }})"
                                            icon="fas fa-trash" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                </x-adminlte-card>

                <x-adminlte-modal id="editModal{{ $link->id }}" title="Edit" icon="fas fa-edit" size='lg'
                    theme="primary">
                    <div id="edit_fail"></div>
                    <h5>Title (Optional)</h5>
                    <x-adminlte-input type="text" id="edited_title{{ $link->id }}" name="edited_title"
                        placeholder="{{ $link->title }}" value="{{ $link->title }}" igroup-size="lg">
                    </x-adminlte-input>
                    <h5>Short URL</h5>
                    <x-adminlte-input type="text" id="edited_short_url{{ $link->id }}" name="edited_short_url"
                        placeholder="{{ $link->short_url }}" value="{{ $link->short_url }}" igroup-size="lg">
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                {{ env('APP_URL') }}
                            </div>
                        </x-slot>
                        <x-slot name="bottomSlot">
                            <span class="text-sm text-gray">
                                <i class="fas fa-info-circle"></i> Changing links also changing QR Code information.
                            </span>
                        </x-slot>
                    </x-adminlte-input>
                    <h5>Original URL</h5>
                    <x-adminlte-input type="text" id="edited_original_url{{ $link->id }}" name="edited_original_url"
                        placeholder="{{ $link->original_url }}" value="{{ $link->original_url }}" igroup-size="lg"
                        readonly>
                    </x-adminlte-input>
                    <x-slot name="footerSlot">
                        <x-adminlte-button class="ml-auto" theme="danger" label="Dismiss" data-dismiss="modal" />
                        <x-adminlte-button id="edit" class="edit-button" theme="primary" label="Save"
                            onclick="edit({{ $link->id }})" />
                    </x-slot>
                </x-adminlte-modal>
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
                    '_token': csrfToken
                },
                success: function(response) {
                    var successMessage = document.createElement("div");
                    successMessage.className = "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#contents').load(' #contents');
                    $('#create_link').html(successMessage);
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                    return false;
                },
                error: function(xhr, error, status) {
                    var errorMessage = document.createElement("div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.message;
                    $('#create_link').html(errorMessage);
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
                    $('#edit_success').html(successMessage);
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
                    $('#edit_fail').html(errorMessage);
                }
            });
        };
    </script>
@endsection
