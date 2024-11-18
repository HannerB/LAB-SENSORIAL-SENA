<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RESULTADOS PANEL</title>
    <link rel="stylesheet" href="{{ asset('../bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/style_resultados.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <nav class="navbar bg-success">
        <div class="container-fluid">
            <a class="navbar-brand text-light" style="font-weight: bold; text-transform: uppercase; margin: 0;">
                <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.png') }}" alt="" width="50px">
                Laboratorio sensorial de alimentos - Sena Cedagro
            </a>
            <a href="{{ route('admin.panel') }}" class="btn btn-outline-light">panel de administracion</a>
        </div>
    </nav>

    <div class="contenedor">
        <h1 class="text-center">PANEL DE RESULTADOS</h1>
        <div class="filtros mb-5">
            <form id="filtro-resultados">
                @csrf
                <div class="mb-3">
                    <label for="" class="form-label">Numero de cabina</label>
                    <select name="" id="cabinas-filtro" class="form-select">
                        <option value="select">Seleccione cabina</option>
                        <option value="1">Cabina 1</option>
                        <option value="2">Cabina 2</option>
                        <option value="3">Cabina 3</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Fecha prueba</label>
                    <input type="date" name="" id="fecha-filtro" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Producto</label>
                    <select name="" id="productos-filtro" class="form-select">
                        <option value="select">Seleccione producto</option>
                        @if ($productoHabilitado)
                            <option value="{{ $productoHabilitado->id_producto }}" selected>
                                {{ $productoHabilitado->nombre }}</option>
                        @endif
                    </select>
                </div>

                <div class="text-end">
                    <button class="btn btn-outline-secondary me-2" type="submit">GENERAR RESULTADOS</button>
                    <button type="button" class="btn btn-success" id="btnExportar">
                        <i class="fas fa-file-excel"></i> Exportar a Excel
                    </button>
                </div>
            </form>
        </div>

        <div class="resultados resultados-pruebas mb-5" id="resultados-pruebas" style="display: none;">
            <hr>
            <!-- PRUEBA TRIANGULAR -->
            <h3 class="mt-4">PRUEBA TRIANGULAR</h3>
            <table class="table table-secondary table-bordered table-hover mb-4">
                <thead id="head-triangular">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Código Muestra</th>
                        <th scope="col">Resultado</th>
                    </tr>
                </thead>
                <tbody class="table-light" id="body-triangular">
                </tbody>
            </table>

            <!-- PRUEBA DUO - TRIO -->
            <h3>PRUEBA DUO - TRIO</h3>
            <table class="table table-secondary table-bordered table-hover mb-4">
                <thead id="head-duo">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Código Muestra</th>
                        <th scope="col">Resultado</th>
                    </tr>
                </thead>
                <tbody class="table-light" id="body-duo">
                </tbody>
            </table>

            <!-- PRUEBA ORDENAMIENTO -->
            <h3>PRUEBA ORDENAMIENTO - ( <span id="atributo-prueba">ATRIBUTO</span> )</h3>
            <table class="table table-secondary table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Resultado</th>
                    </tr>
                </thead>
                <tbody class="table-light">
                    <tr>
                        <th scope="row">Prefieren</th>
                        <td id="preferencia-ordenamiento"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="resultados resultado-pruebas-personas" id="resultado-pruebas-personas" style="display: none;">
            <hr>
            <div class="mb-4 mt-4">
                <form>
                    <label for="" class="mb-2">Seleccione la prueba para ver los resultados de los
                        panelistas</label>
                    <select name="" class="form-select" id="tipo-prueba-resultado">
                        <option value="select">SELECCIONE LA PRUEBA</option>
                        <option value="1">PRUEBA TRIANGULAR</option>
                        <option value="2">PRUEBA DUO - TRIO</option>
                        <option value="3">PRUEBA ORDENAMIENTO</option>
                    </select>
                </form>
            </div>
            <div class="tabla-personas">
                <table class="table table-dark table-bordered table-hover mb-4">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">RESPUESTA</th>
                        </tr>
                    </thead>
                    <tbody class="table-light" id="listado-personas-prueba">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('../bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('../js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('../js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('../js/scriptResultados.js') }}"></script>

    <!-- Script para el botón de exportación -->
    <script>
        document.getElementById('btnExportar').addEventListener('click', function() {
            const fecha = document.getElementById('fecha-filtro').value;
            const productoId = document.getElementById('productos-filtro').value;
            const tipoPrueba = document.getElementById('tipo-prueba-resultado').value;
            const cabina = document.getElementById('cabinas-filtro').value;

            if (!fecha || productoId === 'select' || cabina === 'select') {
                Swal.fire('Advertencia', 'Por favor, selecciona una fecha, producto y cabina.', 'warning');
                return;
            }

            const params = new URLSearchParams({
                fecha: fecha,
                producto_id: productoId,
                tipo_prueba: tipoPrueba !== 'select' ? tipoPrueba : '',
                cabina: cabina
            });

            window.location.href = `/resultados/exportar?${params.toString()}`;
        });
    </script>
</body>

</html>
