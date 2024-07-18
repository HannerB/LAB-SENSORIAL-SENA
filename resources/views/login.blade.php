<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administración</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .formulario {
            margin: auto;
            margin-top: 60px;
            width: 40%;
        }
        h2 {
            font-family: cursive;
            text-align: center;
            letter-spacing: 5px;
            font-size: 16pt;
        }
    </style>
</head>
<body>

    <nav class="navbar bg-success">
        <div class="container-fluid">
            <a class="navbar-brand text-light" style="font-weight: bold; text-transform: uppercase; margin: 0;">
                <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.png') }}" alt="" width="50px">
                Laboratorio sensorial de alimentos - Sena Cedagro
            </a>
            <form class="d-flex" role="search">
                {{-- <a href="{{ route('index') }}" class="btn btn-outline-light">VOLVER FORMULARIO</a> --}}
            </form>
        </div>
    </nav>

    <form action="{{ route('admin.authenticate') }}" method="POST" class="formulario border p-3">
        @csrf
        <h2 class="text-success mb-4">Acceso administración</h2>
        <div class="mb-3">
            <label for="contra-access" class="form-label">Contraseña de acceso</label>
            <input type="password" name="contra" id="contra-access" class="form-control">
        </div>
        @if(session('alerta'))
        <div class="alert alert-danger" role="alert">
            {{ session('alerta') }}
        </div>
        @endif
        <div class="text-center">
            <input type="submit" class="btn btn-outline-success">
        </div>
    </form>

</body>
</html>
