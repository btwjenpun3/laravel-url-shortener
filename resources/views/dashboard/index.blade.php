@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-info-box title="Shorlinks" text="0" icon="fas fa-lg fa-tasks text-orange" theme="warning"
                icon-theme="dark" description="You already have 0 shortlinks." />
        </div>
        <div class="col-md-6">
            <x-adminlte-info-box title="528" text="User Registrations" icon="fas fa-lg fa-user-plus text-primary"
                theme="gradient-primary" icon-theme="white" description="You already have 0 users." />
        </div>
    </div>
@stop
