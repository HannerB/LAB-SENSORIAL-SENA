<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PANEL DE ADMINISTRACION</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_config.css') }}">
    <script>
        const rutaActualizarConfiguracion = "{{ route('configuracion.update', 1) }}";
    </script>    
</head>

<body>
    <nav class="navbar bg-success">
        <div class="container-fluid">
            <a class="navbar-brand text-light" style="font-weight: bold; text-transform: uppercase; margin: 0;">
                <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.png') }}" alt="" width="50px">
                Laboratorio sensorial de alimentos - Sena Cedagro
            </a>
            <form class="d-flex" role="search">
                <a href="{{ route('index') }}" class="btn btn-outline-light">Cerrar Sesión</a>
            </form>
        </div>
    </nav>

    <section class="text-center active" id="sect1">
        <div class="contenido">
            <h1 class="cont-title mb-4">PANEL DE ADMINISTRACION</h1>
            <div class="form-config">
                <form class="form-cabina mb-3 d-flex justify-content-between p-2">
                    <div class="div1">
                        <label for="cabina">NUMERO DE CABINA :</label>
                        <input type="number" id="cabina" min="1" max="3" value="1">
                    </div>
                    <div>
                        <a href="{{ route('admin.resultados') }}" class="btn btn-outline-success">Visualizar resultados
                            de cabina</a>
                    </div>
                </form>
                <hr>
                <!-- Formulario para agregar productos -->
                <form id="form-producto" class="mb-4" action="{{ route('producto.store') }}" method="POST">
                    @csrf
                    <label for="nombreProducto">NOMBRE DE PRODUCTO :</label>
                    <input type="text" class="form-control me-2" id="nombreProducto" name="nombre">
                    <button class="btn btn-outline-success" type="submit" id="btnAgregarProducto">Agregar</button>
                </form>
                <div class="tabla-productos mb-5">
                    <table class="table table-bordered table-light table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre Producto</th>
                                <th scope="col">Configuración</th>
                            </tr>
                        </thead>
                        <tbody id="listado-productos">
                            @foreach ($productos as $producto)
                                <tr data-id="{{ $producto->id_producto }}">
                                    <td>{{ $producto->id_producto }}</td>
                                    <td>{{ $producto->nombre }}</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-configuracion"
                                            data-id="{{ $producto->id_producto }}"
                                            data-nombre="{{ $producto->nombre }}">
                                            <img src="{{ asset('icons/gear.svg') }}" alt="Configurar" width="20">
                                        </button>
                                        <button class="btn btn-outline-success btn-habilitar"
                                            data-id="{{ $producto->id_producto }}"
                                            data-habilitado="{{ $producto->habilitado }}">
                                            {{ $producto->habilitado ? 'Deshabilitar' : 'Habilitar' }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="btns text-end">
                    <button class="btn btn-success" id="btnguardar">GUARDAR CAMBIOS</button>
                    <button class="btn btn-danger">CERRAR PANEL</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal de Configuración de Producto -->
    <div class="modal fade" id="modalConfiguracion" tabindex="-1" aria-labelledby="modalConfiguracionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfiguracionLabel">CONFIGURACION DE PRODUCTO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="contenedor">
                        <section>
                            <!-- Formulario para actualizar nombre del producto -->
                            <form id="form-producto-modal" class="mb-4" method="POST"
                                action="{{ route('productos.update', ':id') }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="productoId" name="id_producto">
                                <label for="nombreProductoModal">NOMBRE PRODUCTO</label>
                                <input type="text" id="nombreProductoModal" class="form-control mb-2" name="nombre"
                                    required>
                            </form>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-success" id="btn-submit">GUARDAR
                                    CAMBIOS</button>
                            </div>
                        </section>
                        <!-- Aquí va el resto del contenido del modal -->
                        <section class="sect-muestras mb-5">
                            <h2>Mis Muestras</h2>
                            <form class="mb-5" id="form-muestras" method="POST"
                                action="{{ route('muestra.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="codigo-muestra" class="form-label">Código Muestra</label>
                                    <input type="text" class="form-control" id="codigo-muestra"
                                        name="cod_muestra" required>
                                    <input type="hidden" id="producto-id-muestra" name="producto_id">
                                </div>
                                <div class="text-center">
                                    <button id="btn-generar-codigo" type="button">Generar un código de
                                        muestra</button>
                                </div>
                                <div class="mb-3">
                                    <label for="tipo-prueba" class="form-label">Tipo de Prueba</label>
                                    <select id="tipo-prueba" class="form-select" name="prueba">
                                        <option value="1">PRUEBA TRIANGULAR</option>
                                        <option value="2">PRUEBA DUO-TRIO</option>
                                        <option value="3">PRUEBA ORDENAMIENTO</option>
                                    </select>
                                </div>
                                <div class="mb-3 text-center">
                                    <button class="btn btn-outline-success" type="submit"
                                        id="btn-guardar-muestra">GUARDAR</button>
                                </div>
                            </form>
                        </section>
                        <hr>
                        <!-- PRUEBA TRIANGULAR -->
                        <h3>MUESTRAS DE PRUEBA TRIANGULAR</h3>
                        <div class="table-prueba-triangular mb-5">
                            <table class="table table-success table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">CODIGO</th>
                                        <th scope="col">ELIMINAR MUESTRA</th>
                                    </tr>
                                </thead>
                                <tbody class="table-light" id="cuerpo-table-uno">
                                </tbody>
                            </table>
                        </div>
                        <!-- PRUEBA DUO - TRIO -->
                        <h3>MUESTRAS DE PRUEBA DUO - TRIO</h3>
                        <div class="table-prueba-duo mb-5">
                            <table class="table table-success table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">CODIGO</th>
                                        <th scope="col">ELIMINAR MUESTRA</th>
                                    </tr>
                                </thead>
                                <tbody class="table-light" id="cuerpo-table-dos">
                                </tbody>
                            </table>
                        </div>
                        <!-- PRUEBA DE ORDENAMIENTO -->
                        <h3>MUESTRAS DE PRUEBA DE ORDENAMIENTO</h3>
                        <div class="table-prueba-ordenamiento mb-5">
                            <table class="table table-success table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">CODIGO</th>
                                        <th scope="col">ELIMINAR MUESTRA</th>
                                    </tr>
                                </thead>
                                <tbody class="table-light" id="cuerpo-table-tres">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CERRAR</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/scriptAdministracion.js') }}"></script>
    <script src="{{ asset('js/scriptMuestras.js') }}"></script>
</body>

</html>
