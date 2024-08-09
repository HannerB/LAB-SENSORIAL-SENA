{{-- resources/views/calificacion/index.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <title>Calificaciones</title>
</head>
<body>
    <h1>Lista de Calificaciones</h1>

    @if($calificaciones->isEmpty())
        <p>No hay calificaciones disponibles.</p>
    @else
        <ul>
            @foreach($calificaciones as $calificacion)
                <li>{{ $calificacion->atributo }} - {{ $calificacion->comentario }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
