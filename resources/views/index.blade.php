<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMATOS DE PRUEBAS</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_form.css') }}">
</head>

<body>

    <nav class="navbar bg-success">
        <div class="container-fluid">
            <a class="navbar-brand text-light" style="font-weight: bold; text-transform: uppercase; margin: 0;">
                <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.png') }}" alt="" width="50px">
                Laboratorio sensorial de alimentos - Sena Cedagro - Centro de Valor de Agregado
            </a>
            <form class="d-flex" role="search">
                <a href="{{ route('login') }}" class="btn btn-outline-light">ADMINISTRACION</a>
            </form>
        </div>
    </nav>

    <section class="text-center active" id="sect1">
        <div class="contenido">
            <h1 class="titulo-prueba mb-4"><b>prueba de triangulo</b></h1>
            <div class="formulario-prueba mb-3">
                <form id="datos-panelista" class="mb-4">
                    <label for="">NOMBRE COMPLETO:</label>
                    <input type="text" id="nombrePanelista1">
                    <label for="">FECHA:</label>
                    <input type="date" id="fechaPanelista1">
                </form>
                <form id="dato-producto">
                    <label for="">NOMBRE DE PRODUCTO:</label>
                    <input type="hidden" id="productoIDPrueba1"
                        value="{{ $productoHabilitado ? $productoHabilitado->id_producto : '' }}">
                    <input type="text" id="productoPrueba1" readonly
                        value="{{ $productoHabilitado ? $productoHabilitado->nombre : '' }}">
                </form>
                <p class="text-start mt-5 mb-5">Frente a usted hay tres muestras de (<span
                        class="nombre-producto-span">{{ $productoHabilitado ? $productoHabilitado->nombre : 'nombre del producto' }}</span>)
                    dos son iguales y una diferente, saboree cada una con cuidado y seleccione la muestra diferente.</p>
                <table class="table table-bordered table-hover table-secondary mb-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">MUESTRAS</th>
                            <th scope="col">MUESTRA DIFERENTE</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-prueba-triangular">
                        @forelse($muestrasTriangular as $muestra)
                            <tr>
                                <td>{{ $muestra->cod_muestra }}</td>
                                <td>
                                    <input required type="radio" name="muestra_diferente"
                                        value="{{ $muestra->cod_muestra }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No hay muestras disponibles para la prueba triangular.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <form id="form-comentarios" class="mb-5">
                    <label for="">COMENTARIOS:</label><br>
                    <textarea id="comentario-triangular"></textarea>
                </form>
                <hr>
                <h5>MUCHAS GRACIAS!</h5>
            </div>
            <div class="btns">
                {{-- <button class="btn btn-success" id="btnguardar-tri">Guardar</button> --}}
                <button class="btn btn-outline-primary" id="btnsiguiente1">Siguiente</button>
            </div>
            <br>
        </div>
    </section>

    <section class="text-center" id="sect2">
        <div class="contenido">
            <h1 class="titulo-prueba mb-4"><b>prueba de duo - trio</b></h1>
            <div class="formulario-prueba mb-3">
                <form id="dato-producto">
                    <label for="">NOMBRE DE PRODUCTO:</label>
                    <input type="text" id="productoPrueba2" readonly
                        value="{{ $productoHabilitado ? $productoHabilitado->nombre : '' }}">
                </form>
                <p class="text-start mt-5 mb-5">Frente a usted hay tres muestras de (<span
                        class="nombre-producto-span">{{ $productoHabilitado ? $productoHabilitado->nombre : 'nombre del producto' }}</span>)
                    una de referencia marcada con R y dos codificadas.</p>
                <p class="text-start mt-5 mb-5">Una de las muestras codificadas es igual a R.</p>
                <p class="text-start mt-5 mb-5">¿Cual de las muestras codificadas es diferente a la de referencia R?
                    Seleccione la muestra diferente.</p>
                <table class="table table-bordered table-hover table-secondary mb-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">MUESTRAS</th>
                            <th scope="col">MUESTRA IGUAL A LA REFERENCIA</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-prueba-duo">
                        @forelse($muestrasDuoTrio as $muestra)
                            <tr>
                                <td>{{ $muestra->cod_muestra }}</td>
                                <td>
                                    <input required type="radio" name="muestra_igual_referencia"
                                        value="{{ $muestra->cod_muestra }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No hay muestras disponibles para la prueba Duo-Trio.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <form id="form-comentarios" class="mb-5">
                    <label for="">COMENTARIOS:</label><br>
                    <textarea id="comentario-duo"></textarea>
                </form>
                <hr>
                <h5>MUCHAS GRACIAS!</h5>
            </div>
            <div class="btns">
                <button class="btn btn-outline-primary me-2"
                    onclick="cambiarFormulario('sect1','sect2')">Anterior</button>
                {{-- <button class="btn btn-success" id="btnguardar-duo">Guardar</button> --}}
                <button class="btn btn-outline-primary" id="btnsiguiente2">Siguiente</button>
            </div>
            <br>
        </div>
    </section>

    <section class="text-center" id="sect3">
        <div class="contenido">
            <h1 class="titulo-prueba mb-4"><b>Prueba de Ordenamiento</b></h1>
            <div class="formulario-prueba mb-3">
                <form id="dato-producto">
                    <label for="">Nombre de Producto:</label>
                    <input type="text" id="productoPrueba2" readonly
                        value="{{ $productoHabilitado ? $productoHabilitado->nombre : '' }}">
                </form>

                <p class="text-start mt-5 mb-5">
                    Frente a usted hay tres muestras de
                    <span
                        class="nombre-producto-span">{{ $productoHabilitado ? $productoHabilitado->nombre : 'nombre del producto' }}</span>
                    que usted debe ordenar en forma creciente de acuerdo al grado de <span
                        class="atributo-span">dulzura</span>.
                </p>
                <p class="text-start mt-5 mb-5">Cada muestra debe llevar un orden diferente. No se permite que dos
                    muestras tengan el mismo orden.</p>

                <table class="table table-bordered table-hover table-secondary mb-3">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Muestra</th>
                            <th scope="col">Orden</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpo-selectores-ordenamiento">
                        @forelse($muestrasOrdenamiento as $muestra)
                            <tr>
                                <td>{{ $muestra->cod_muestra }}</td>
                                <td>
                                    <select name="orden_muestra_{{ $muestra->cod_muestra }}"
                                        class="form-select orden-muestra" required>
                                        <option value="" selected disabled>Seleccione el orden</option>
                                        <option value="1">1 - Menos Dulce</option>
                                        <option value="2">2 - Intermedio</option>
                                        <option value="3">3 - Más Dulce</option>
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No hay muestras disponibles para la prueba de Ordenamiento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <form id="form-comentarios" class="mb-5">
                    <label for="">Comentarios:</label><br>
                    <textarea id="comentario-orden" class="form-control"></textarea>
                </form>

                <hr>
                <h5>¡Muchas gracias!</h5>
            </div>

            <div class="btns">
                <button class="btn btn-outline-primary me-2"
                    onclick="cambiarFormulario('sect2','sect3')">Anterior</button>
                <button class="btn btn-success" id="btnguardar-todo">Guardar Todo</button>
            </div>
            <br>
        </div>
    </section>

    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/scriptMain.js') }}"></script>
    <script>
        document.getElementById('btnguardar-todo').addEventListener('click', function() {
            guardarTodasLasPruebas();
        });

        function guardarTodasLasPruebas() {
            var nombrePanelista = document.getElementById('nombrePanelista1').value ||
                document.getElementById('nombrePanelista2').value ||
                document.getElementById('nombrePanelista3').value;
            var fechaPanelista = document.getElementById('fechaPanelista1').value ||
                document.getElementById('fechaPanelista2').value ||
                document.getElementById('fechaPanelista3').value;
            var productoID = document.getElementById('productoIDPrueba1').value;

            if (!nombrePanelista || !fechaPanelista) {
                alert('Por favor, completa el nombre y la fecha del panelista.');
                return;
            }

            if (!productoID) {
                alert('Error: El campo de producto está vacío.');
                return;
            }

            if (isNaN(productoID)) {
                alert('Error: El valor del producto no es un ID válido.');
                return;
            }

            // Guardar datos del panelista
            $.ajax({
                url: '{{ route('panelistas.store') }}',
                type: 'POST',
                data: {
                    nombres: nombrePanelista,
                    fecha: fechaPanelista,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.idpane) {
                        var idpane = response.idpane;
                        guardarCalificaciones(idpane, productoID, fechaPanelista);
                    } else {
                        console.error('ID de panelista no retornado');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error guardando datos del panelista:', xhr.responseText);
                }
            });
        }

        function guardarCalificaciones(idpane, productoID, fechaPanelista) {
            var calificaciones = [{
                    prueba: 1,
                    codMuestras: document.querySelector('input[name="muestra_diferente"]:checked')?.value,
                    atributo: 'Dulzura',
                    comentario: document.getElementById('comentario-triangular').value,
                    cabina: 1
                },
                {
                    prueba: 2,
                    codMuestras: document.querySelector('input[name="muestra_igual_referencia"]:checked')?.value,
                    atributo: 'Similaridad',
                    comentario: document.getElementById('comentario-duo').value,
                    cabina: 2
                },
                {
                    prueba: 3,
                    codMuestras: formatearResultadosOrdenamiento(),
                    atributo: 'Dulzura',
                    comentario: document.getElementById('comentario-orden').value,
                    cabina: 3
                }
            ];

            calificaciones.forEach(function(cal) {
                if (cal.codMuestras) {
                    $.ajax({
                        url: '{{ route('calificacion.store') }}',
                        type: 'POST',
                        data: {
                            idpane: idpane,
                            producto: productoID,
                            prueba: cal.prueba,
                            atributo: cal.atributo,
                            cod_muestras: cal.codMuestras,
                            comentario: cal.comentario,
                            fecha: fechaPanelista,
                            cabina: cal.cabina,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(
                                'Datos de calificación guardados correctamente para la prueba ' +
                                cal.prueba);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error guardando datos de calificación para la prueba ' + cal
                                .prueba + ':', xhr.responseText);
                        }
                    });
                }
            });

            alert('Todas las calificaciones han sido guardadas.');
        }

        function formatearResultadosOrdenamiento() {
            var resultados = [];
            var selects = document.querySelectorAll('.orden-muestra');

            // Crear un array para almacenar los resultados temporalmente
            var resultadosTemp = [];

            selects.forEach(function(select) {
                var codMuestra = select.closest('tr').querySelector('td:first-child').textContent.trim();
                var orden = parseInt(select.value);
                if (orden) {
                    resultadosTemp.push({
                        codigo: codMuestra,
                        orden: orden,
                    });
                }
            });

            // Ordenar los resultados por el orden seleccionado
            resultadosTemp.sort((a, b) => a.orden - b.orden);

            // Crear la cadena final en el orden correcto
            resultados = resultadosTemp.map(item => `${item.codigo}`);

            return resultados.join(',');
        }
    </script>
</body>

</html>
