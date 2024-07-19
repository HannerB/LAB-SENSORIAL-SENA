<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANEL DE ADMINISTRACION</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_config.css') }}">
    <style>
        /* Estilos personalizados para el modal */
        .modal-lg {
            max-width: 800px; /* Ancho máximo del modal */
        }

        .modal-body {
            max-height: calc(100vh - 210px); /* Altura máxima del cuerpo del modal */
            overflow-y: auto; /* Habilita el scroll vertical si el contenido es demasiado largo */
        }

        .modal-content {
            padding: 20px; /* Espaciado interno del contenido del modal */
        }

        .form-control {
            width: 100%; /* Ancho completo para los controles de formulario dentro del modal */
        }
    </style>
</head>

<body>

    <nav class="navbar bg-success">
        <div class="container-fluid">
            <a class="navbar-brand text-light" style="font-weight: bold; text-transform: uppercase; margin: 0;">
                <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.png') }}" alt="" width="50px">
                Laboratorio sensorial de alimentos - Sena Cedrago
            </a>
            <form class="d-flex" role="search">
                <a href="{{ route('index') }}" class="btn btn-outline-light">cerrar sesion</a>
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
                        {{-- <input type="number" id="cabina" min="1" max="3" value="{{ $cabina }}"> --}}
                        <input type="number" id="cabina" min="1" max="3" value="1">
                    </div>
                    <div>
                        <a href="{{ route('admin.resultados') }}" class="btn btn-outline-success">visualizar resultados
                            de cabina</a>
                    </div>
                </form>
                <hr>
                <form id="form-producto" class="mb-4" action="{{ route('producto.store') }}" method="POST">
                    @csrf
                    <label for="nombreProducto">NOMBRE DE PRODUCTO :</label>
                    <input type="text" class="form-control me-2" id="nombreProducto" name="nombre">
                    <button type="submit" id="btnAgregarProducto">Agregar</button>
                </form>

                <div class="mb-2">
                    <button class="btn btn-secondary" id="refrescar-productos">Actualizar registros</button>
                </div>
                <div class="tabla-productos mb-5">
                    <table class="table table-bordered table-light table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre Producto</th>
                                <th scope="col">Configuracion</th>
                            </tr>
                        </thead>
                        <tbody id="listado-productos">
                            @foreach ($productos as $producto)
                                <tr>
                                    <td>{{ $producto->id }}</td>
                                    <td>{{ $producto->nombre }}</td>
                                    <td><img src="{{ asset('icons/gear.svg') }}" alt="" width="5%"
                                            onclick="abrirConfiguracion('{{ $producto->id }}', '{{ $producto->nombre }}', {{ $producto->habilitado ? 'true' : 'false' }})"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Otras partes del HTML -->
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
                            <form id="form-producto-modal" class="mb-4">
                                <label for="nombreProductoModal">NOMBRE PRODUCTO</label>
                                <input type="text" id="nombreProductoModal" class="form-control mb-2"
                                    value="" required>
                                <label for="habilitadoModal" class="me-2 mb-3">
                                    <input type="checkbox" id="habilitadoModal" >
                                    Realizar pruebas con este producto
                                </label>
                                <div class="d-flex justify-content-center">
                                    <input type="submit" class="btn btn-success" value="Actualizar Producto">
                                </div>
                            </form>
                        </section>

                        <section class="sect-muestras mb-5">
                            <h2>Mis Muestras</h2>
                            <form class="mb-5" id="form-muestras">
                                <div class="mb-3">
                                    <label for="codigo-muestra" class="form-label">Codigo Muestra</label>
                                    <input type="text" class="form-control" id="codigo-muestra" required>
                                </div>
                                <div class="text-center">
                                    <button id="btn-generar-codigo" type="button">Generar un código de muestra</button>
                                </div>
                                <div class="mb-3">
                                    <label for="tipo-prueba" class="form-label">Tipo de Prueba</label>
                                    <select id="tipo-prueba" class="form-select">
                                        <option value="1">PRUEBA TRIANGULAR</option>
                                        <option value="2">PRUEBA DUO-TRIO</option>
                                        <option value="3">PRUEBA ORDENAMIENTO</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="cont-atributos" style="display: none;">
                                    <label for="atributos-prueba" class="form-label">Tipo de atributos</label>
                                    <select id="atributos-prueba" class="form-select">
                                        <option value="sabor">SABOR</option>
                                        <option value="olor">OLOR</option>
                                        <option value="color">COLOR</option>
                                        <option value="textura">TEXTURA</option>
                                        <option value="apariencia">APARIENCIA</option>
                                    </select>
                                </div>
                                <div class="mb-3 text-center">
                                    <button class="btn btn-outline-success" type="submit">GUARDAR</button>
                                    <button class="btn btn-danger" type="button" id="btn-cancelar">CANCELAR</button>
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

                        <!-- PRUEBA ORDENAMIENTO -->
                        <h3>MUESTRAS DE PRUEBA ORDENAMIENTO - (<span id="atributo-prueba-span">ATRIBUTO</span>)</h3>
                        <div class="table-prueba-orden mb-5">
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
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/scriptAdministracion.js') }}"></script>

    <script>
        function abrirConfiguracion(idProducto, nombreProducto, habilitado) {
            // Simulación de carga de datos del producto
            $('#nombreProductoModal').val(nombreProducto);
            $('#habilitadoModal').prop('checked', habilitado);

            // Abre el modal usando Bootstrap
            $('#modalConfiguracion').modal('show');
        }
    </script>
</body>

</html>
