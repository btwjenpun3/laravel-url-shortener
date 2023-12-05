@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card class="mt-4" title="Users Management">
                <div class="p-3">
                    <table class="table table-hover responsive" id="users">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th>#</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#users').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.userDataTable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return '<button class="btn btn-xs btn-primary mr-2" onclick="showQr">Details</button><button class="btn btn-xs btn-danger" onclick="showQr">Delete</button>';
                        }
                    }
                ]
            });
        });
    </script>
@endsection
