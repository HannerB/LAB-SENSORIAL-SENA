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
    <style>
        .modal-lg {
            max-width: 1200px;
        }

        .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
        }

        .modal-content {
            padding: 20px;
        }

        .form-control {
            width: 100%;
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
                        <input type="number" id="cabina" min="1" max="3" value="1">
                    </div>
                    <div>
                        <a href="{{ route('admin.resultados') }}" class="btn btn-outline-success">visualizar resultados
                            de cabina</a>
                    </div>
                </form>
                <hr>
                <!-- Formulario para agregar productos -->
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
                                    <td>{{ $producto->id_producto }}</td>
                                    <td>{{ $producto->nombre }}</td>
                                    <td><img src="{{ asset('icons/gear.svg') }}" alt="" width="5%"
                                            onclick="abrirConfiguracion('{{ $producto->id_producto }}', '{{ $producto->nombre }}')">
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
                                action="{{ route('producto.update', ':id') }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="productoId" name="id_producto">
                                <label for="nombreProductoModal">NOMBRE PRODUCTO</label>
                                <input type="text" id="nombreProductoModal" class="form-control mb-2" name="nombre"
                                    required>
                            </form>
                            <!-- Formulario para habilitar producto -->
                            <form id="form-habilitar-producto" class="mb-4" method="POST"
                                action="{{ route('configuracion.update', 1) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="productoHabilitadoId" name="producto_habilitado">
                                <label for="habilitadoModal" class="me-2 mb-3">
                                    <input type="checkbox" id="habilitadoModal" name="habilitado">
                                    Realizar pruebas con este producto
                                </label>
                            </form>
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-success" id="btn-submit">GUARDAR
                                    CAMBIOS</button>
                            </div>
                        </section>

                        <!-- Aquí va el resto del contenido del modal -->

                        <section class="sect-muestras mb-5">
                            <h2>Mis Muestras</h2>
                            <form class="mb-5" id="form-muestras">
                                <div class="mb-3">
                                    <label for="codigo-muestra" class="form-label">Codigo Muestra</label>
                                    <input type="text" class="form-control" id="codigo-muestra" required>
                                </div>
                                <div class="text-center">
                                    <button id="btn-generar-codigo" type="button">Generar un código de
                                        muestra</button>
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
                                    <button class="btn btn-outline-success" type="submit"
                                        id="btn-guardar-muestra">GUARDAR</button>
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
                        <h3>MUESTRAS DE PRUEBA ORDENAMIENTO</h3>
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

                        <hr>
                        <!-- ATRIBUTOS -->
                        <h3>ATRIBUTOS</h3>
                        <div class="table-atributos mb-5">
                            <table class="table table-success table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">CODIGO</th>
                                        <th scope="col">ELIMINAR ATRIBUTO</th>
                                    </tr>
                                </thead>
                                <tbody class="table-light" id="cuerpo-table-cuatro">
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

    <script>
        function abrirConfiguracion(idProducto, nombreProducto, habilitado) {
            document.getElementById('productoId').value = idProducto;
            document.getElementById('nombreProductoModal').value = nombreProducto;
            document.getElementById('habilitadoModal').checked = habilitado;

            // Actualizar la acción del formulario
            const formProductoModal = document.getElementById('form-producto-modal');
            formProductoModal.action = formProductoModal.action.replace(/\/\d+$/, '/' + idProducto);

            // Mostrar el modal
            new bootstrap.Modal(document.getElementById('modalConfiguracion')).show();
        }

        $(document).ready(function() {
            $('#refrescar-productos').on('click', function() {
                // Aquí puedes implementar la lógica para actualizar la tabla de productos
                location.reload(); // Solo para refrescar la página, ajustar si usas AJAX
            });

            $('#form-producto-modal').on('submit', function(event) {
                event.preventDefault();
                const idProducto = $('#productoId').val();
                const nombre = $('#nombreProductoModal').val();
                const habilitado = $('#habilitadoModal').is(':checked');

                fetch(`{{ route('producto.update', '') }}/${idProducto}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            nombre: nombre,
                            habilitado: habilitado
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Producto actualizado con éxito');
                            location.reload(); // Para refrescar la tabla
                        } else {
                            alert('Error al actualizar producto');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al actualizar producto');
                    });
            });
        });
    </script>

    <script>
        document.getElementById('btnguardar').addEventListener('click', function() {
            document.getElementById('form-producto').submit();
        });

        document.getElementById('btn-submit').addEventListener('click', function() {
            if (document.getElementById('habilitadoModal').checked) {
                document.getElementById('productoHabilitadoId').value = document.getElementById('productoId').value;
            } else {
                document.getElementById('productoHabilitadoId').value = '';
            }
            document.getElementById('form-producto-modal').submit();
            document.getElementById('form-habilitar-producto').submit();
        });

        function abrirConfiguracion(id, nombre) {
            // Rellenar los campos del modal con los datos del producto
            document.getElementById('productoId').value = id;
            document.getElementById('nombreProductoModal').value = nombre;

            // Abrir el modal
            new bootstrap.Modal(document.getElementById('modalConfiguracion')).show();
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formProductoModal = document.getElementById('form-producto-modal');
            const formHabilitarProducto = document.getElementById('form-habilitar-producto');
            const btnSubmit = document.getElementById('btn-submit');

            function abrirConfiguracion(idProducto, nombreProducto, habilitado) {
                document.getElementById('productoId').value = idProducto;
                document.getElementById('nombreProductoModal').value = nombreProducto;
                document.getElementById('habilitadoModal').checked = habilitado;
                formProductoModal.action = formProductoModal.action.replace(':id', idProducto);

                // Mostrar el modal (asumiendo que estás usando Bootstrap)
                new bootstrap.Modal(document.getElementById('modalConfiguracion')).show();
            }

            btnSubmit.addEventListener('click', function() {
                const productoId = document.getElementById('productoId').value;
                const nombre = document.getElementById('nombreProductoModal').value;
                const habilitado = document.getElementById('habilitadoModal').checked;

                // Actualizar nombre del producto
                fetch(formProductoModal.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            nombre: nombre
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Nombre del producto actualizado');
                            // Actualizar el nombre en la tabla de productos
                            const productoRow = document.querySelector(`tr[data-id="${productoId}"]`);
                            if (productoRow) {
                                productoRow.querySelector('td:nth-child(2)').textContent = nombre;
                            }
                        } else {
                            console.error('Error al actualizar el nombre del producto:', data.message);
                            alert('Error al actualizar el nombre del producto');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al actualizar el nombre del producto');
                    });

                // Actualizar producto habilitado
                document.getElementById('productoHabilitadoId').value = habilitado ? productoId : '';
                fetch(formHabilitarProducto.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            producto_habilitado: habilitado ? productoId : null
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Configuración actualizada correctamente');
                            // Aquí puedes actualizar la interfaz de usuario si es necesario
                        } else {
                            console.error('Error al actualizar la configuración:', data.message);
                            alert('Error al actualizar la configuración');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al actualizar la configuración');
                    });

                // Cerrar el modal
                bootstrap.Modal.getInstance(document.getElementById('modalConfiguracion')).hide();

                // Recargar la página después de un breve retraso
                setTimeout(() => {
                    location.reload();
                }, 1000);
            });

            // Asignar la función abrirConfiguracion a los botones de configuración
            document.querySelectorAll('.btn-configuracion').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idProducto = this.dataset.id;
                    const nombreProducto = this.closest('tr').querySelector('td:nth-child(2)')
                        .textContent;
                    const habilitado = this.dataset.habilitado === 'true';
                    abrirConfiguracion(idProducto, nombreProducto, habilitado);
                });
            });
        });
    </script>
</body>

</html>
