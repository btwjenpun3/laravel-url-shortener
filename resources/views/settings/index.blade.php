@extends('adminlte::page')

@section('title', 'Settings')

@section('plugins.BootstrapSwitch', true)

@section('content')
    <x-adminlte-card title="Settings" theme="lightblue" theme-mode="outline" class="mt-4" icon="fas fa-lg fa-cogs"
        header-class="text-uppercase rounded-bottom border-info">
        <div id="success"></div>
        <div id="error"></div>
        @if ($redirect_page->value == 'true')
            <x-adminlte-input-switch id="redirect_page" name="redirect_page" label="Redirect Page ?" data-on-text="YES"
                data-off-text="NO" data-on-color="teal" checked />
        @else
            <x-adminlte-input-switch id="redirect_page" name="redirect_page" label="Redirect Page ?" data-on-text="YES"
                data-off-text="NO" data-on-color="teal" />
        @endif
        <x-slot name="footerSlot">
            <x-adminlte-button class="d-flex ml-auto" theme="primary" label="Save" onclick="save()" />
        </x-slot>
    </x-adminlte-card>
@stop

@section('js')
    <script>
        function save() {
            var redirectPage = $('#redirect_page').is(':checked') ? 'true' : 'false';
            $.ajax({
                url: '/setting',
                method: 'POST',
                data: {
                    redirect_page: redirectPage,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(redirect_page);
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
                    $('#error').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        };
    </script>
@endsection
