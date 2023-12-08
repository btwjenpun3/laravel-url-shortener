@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card class="mt-4" title="Activities Log">
                <div class="p-3">
                    <table class="table table-hover responsive" id="activities">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Log</th>
                                <th>Time</th>
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
            $('#activities').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('activity.activityDataTable') }}',
                order: [
                    // Menggunakan kolom 'id' dengan urutan descending (tertinggi ke terendah)
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'activities.id'
                    },
                    {
                        data: 'email',
                        name: 'users.email'
                    },
                    {
                        data: 'action',
                        name: 'activities.action',
                        render: function(data) {
                            if (data == 'Create') {
                                return '<span class="badge bg-lg bg-success">' + data + '</span>';
                            } else if (data == 'Edit') {
                                return '<span class="badge bg-lg bg-warning">' + data +
                                    '</span>';
                            } else if (data == 'Delete') {
                                return '<span class="badge bg-lg bg-danger">' + data +
                                    '</span>';
                            } else if (data == 'Set Password') {
                                return '<span class="badge bg-lg bg-primary">' + data +
                                    '</span>';
                            } else if (data == 'Remove Password') {
                                return '<span class="badge bg-lg bg-secondary">' + data +
                                    '</span>';
                            } else if (data == 'Set Time') {
                                return '<span class="badge bg-lg bg-primary">' + data +
                                    '</span>';
                            } else if (data == 'Remove Time') {
                                return '<span class="badge bg-lg bg-secondary">' + data +
                                    '</span>';
                            }
                        }
                    },
                    {
                        data: 'log',
                        name: 'activities.log',
                        render: function(data) {
                            return $('<div>').html(data).text();
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'activities.created_at',
                        render: function(data) {
                            return moment(data).format('dddd, DD MMMM YYYY HH:mm:ss');
                        }
                    }
                ]
            });
        });
    </script>
@endsection
