@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1>Settings</h1>
@stop

@section('plugins.BootstrapSwitch', true)

@section('content')
    @if ($redirect_page->value == '1')
        <x-adminlte-input-switch name="redirect_page" label="Redirect Page ?" data-on-text="YES" data-off-text="NO"
            data-on-color="teal" checked />
    @else
        <x-adminlte-input-switch name="redirect_page" label="Redirect Page ?" data-on-text="YES" data-off-text="NO"
            data-on-color="teal" />
    @endif
@stop
