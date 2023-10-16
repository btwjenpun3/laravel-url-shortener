@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Profile" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-lg fa-user"
                header-class="text-uppercase rounded-bottom border-info">
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="API" theme="primary" theme-mode="outline" class="mt-4" icon="fas fa-lg fa-fire"
                header-class="text-uppercase rounded-bottom border-info">
                <div id="success"></div>
                <div id="error"></div>
                @if (isset($token))
                    <x-adminlte-input name="api" id="api" label="Your API Key" placeholder="API Key"
                        type="text" igroup-size="sm" value="{{ $token }}" readonly>
                        <x-slot name="appendSlot">
                            <x-adminlte-button theme="outline-secondary" label="Re-generate" />
                        </x-slot>
                    </x-adminlte-input>
                @else
                    <x-adminlte-input name="api" id="api" label="Your API Key" placeholder="API Key"
                        type="text" igroup-size="sm" readonly>
                        <x-slot name="appendSlot">
                            <x-adminlte-button theme="outline-primary" label="Generate API" onclick="generate()" />
                        </x-slot>
                    </x-adminlte-input>
                @endif
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function generate() {
            $.ajax({
                url: '/api',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#success').html(successMessage);
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
    </script>
@endsection
