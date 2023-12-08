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
                                <th>Role</th>
                                <th>Status</th>
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
                        data: 'role_id',
                        render: function(data) {
                            if (data == 1) {
                                return '<span class="badge bg-warning">Admin</span>';
                            } else if (data == 2) {
                                return '<span class="badge bg-primary">Member</span>';
                            }
                        }
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Active</span>'
                            }
                            if (data == 0) {
                                return '<span class="badge bg-danger">Banned</span>'
                            }
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format('dddd, DD MMMM YYYY HH:mm');
                        }
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            var id = data;
                            return '<a href="/users/detail/' + id +
                                '"><button class="btn btn-xs btn-primary mr-2">Details</button></a>';
                        }
                    }
                ]
            });
        });
    </script>
@endsection
