<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANEL DE ADMINISTRACION</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_config.css') }}">
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
                      <input type="number" id="cabina" min="1" max="3" value="">
                    </div>
                    <div>
                      <a href="{{ route('admin.resultados') }}" class="btn btn-outline-success">visualizar resultados de cabina</a>
                    </div>
                </form>
                <hr>
                <form id="form-producto" class="mb-4">
                    <label for="">NOMBRE DE PRODUCTO :</label>
                    <input type="text" class="form-control me-2" id="nombreProducto">
                    <button type="submit">Agregar</button>
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
                        <!-- Aquí se mostrarían los productos -->
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

    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/scriptAdministracion.js') }}"></script>
</body>
</html>
