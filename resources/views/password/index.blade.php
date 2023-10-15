<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unlock Your Link</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
</head>

<body class="hold-transition lockscreen">
    <div class="lockscreen-wrapper">
        @if (session()->has('message'))
            <div class="alert alert-danger">{{ session('message') }}</div>
        @endif
        <div class="lockscreen-logo">
        </div>
        <div class="lockscreen-name mb-4">{{ env('APP_URL') . '/' . $short_url }}</div>
        <div class="lockscreen-item">
            <form action="{{ route('redirect.password', ['id' => $id]) }}" method="get">
                <div class="input-group">
                    <input type="password" name="password" id="password"
                        class="form-control @if (session()->has('message')) is-invalid @endif" placeholder="Password">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-primary" onclick="password({{ $id }})">
                            <i class="bi bi-unlock-fill"></i> Unlock
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="help-block text-center">
            Enter your password to redirect to original page
        </div>
        <div class="lockscreen-footer text-center">
            Copyright &copy; 2014-2021 <b><a href="https://adminlte.io" class="text-black">AdminLTE.io</a></b><br>
            All rights reserved
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
</body>

</html>
