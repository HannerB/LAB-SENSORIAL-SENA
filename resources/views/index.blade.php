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
          <a href="" class="btn btn-outline-light">ADMINISTRACION</a>
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
                    <input type="text" id="productoPrueba1" readonly>
                </form>
                <p class="text-start mt-5 mb-5">Frente a usted hay tres muestras de (<span class="nombre-producto-span">nombre del producto</span>) dos son iguales y una diferente, saboree cada una con cuidado y seleccione la muestra diferente.</p>
                <table class="table table-bordered table-hover table-secondary mb-3">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">MUESTRAS</th>
                        <th scope="col">MUESTRA DIFERENTE</th>
                      </tr>
                    </thead>
                    <tbody id="cuerpo-prueba-triangular"></tbody>
                  </table>
                  <form id="form-comentarios" class="mb-5">
                    <label for="">COMENTARIOS:</label><br>
                    <textarea id="comentario-triangular"></textarea>
                  </form>
                  <hr>
                  <h5>MUCHAS GRACIAS!</h5>
            </div>
            <div class="btns">
                <button class="btn btn-success" id="btnguardar-tri" >Guardar</button>
                <button class="btn btn-outline-primary" id="btnsiguiente1" >Siguiente</button>
            </div>
            <br>
        </div>
    </section>

    <section class="text-center" id="sect2">
        <div class="contenido">
            <h1 class="titulo-prueba mb-4"><b>prueba de duo - trio</b></h1>
            <div class="formulario-prueba mb-3">
                <form id="datos-panelista" class="mb-4">
                    <label for="">NOMBRE COMPLETO:</label>
                    <input type="text" id="nombrePanelista2">
                    <label for="">FECHA:</label>
                    <input type="date" id="fechaPanelista2">
                </form>
                <form id="dato-producto">
                    <label for="">NOMBRE DE PRODUCTO:</label>
                    <input type="text" id="productoPrueba2" readonly>
                </form>
                <p class="text-start mt-5 mb-5">Frente a usted hay tres muestras de (<span class="nombre-producto-span">nombre del producto</span>) una de referencia marcada con R y dos codificadas.</p>
                <p class="text-start mt-5 mb-5">Una de las muestras codificadas es igual a R.</p>
                <p class="text-start mt-5 mb-5">¿Cual de las muestras codificadas es diferente a la de referencia R? Seleccione la muestra diferente.</p>
                <table class="table table-bordered table-hover table-secondary mb-3">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">MUESTRAS</th>
                        <th scope="col">MUESTRA IGUAL A LA REFERENCIA</th>
                      </tr>
                    </thead>
                    <tbody id="cuerpo-prueba-duo"></tbody>
                  </table>
                  <form id="form-comentarios" class="mb-5">
                    <label for="">COMENTARIOS:</label><br>
                    <textarea id="comentario-duo"></textarea>
                  </form>
                  <hr>
                  <h5>MUCHAS GRACIAS!</h5>
            </div>
            <div class="btns">
                <button class="btn btn-outline-primary me-2" onclick="cambiarFormulario('sect1','sect2')">Anterior</button>
                <button class="btn btn-success" id="btnguardar-duo" >Guardar</button>
                <button class="btn btn-outline-primary" id="btnsiguiente2">Siguiente</button>
            </div>
            <br>
        </div>
    </section>

    <section class="text-center" id="sect3">
        <div class="contenido">
            <h1 class="titulo-prueba mb-4"><b>prueba de ordenamiento</b></h1>
            <div class="formulario-prueba mb-3">
                <form id="datos-panelista" class="mb-4">
                    <label for="">NOMBRE COMPLETO:</label>
                    <input type="text" id="nombrePanelista3">
                    <label for="">FECHA:</label>
                    <input type="date" id="fechaPanelista3">
                </form>
                <form id="dato-producto">
                    <label for="">NOMBRE DE PRODUCTO:</label>
                    <input type="text" id="productoPrueba3" readonly>
                </form>
                <p class="text-start mt-5 mb-5">Frente a usted hay tres muestras de (<span class="nombre-producto-span">nombre del producto</span>) que usted debe ordenar en forma creciente de acuerdo al grado de <span class="atributo-span">dulzura<span>.</p>
                <p class="text-start mt-5 mb-5">Cada Muestra debe llevar un orden diferente, dos muestras no deben tener el mismo orden.</p>
                <table class="table table-bordered table-hover table-secondary mb-3">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">ORDEN DE LAS MUESTRAS</th>
                        <th scope="col">GRADO DE <span class="atributo-span" id="tipo-atributo">DULZURA</span></th>
                      </tr>
                    </thead>
                    <tbody id="cuerpo-selectores-odenamiento"></tbody>
                  </table>
                  <form id="form-comentarios" class="mb-5">
                    <label for="">COMENTARIOS:</label><br>
                    <textarea id="comentario-orden"></textarea>
                  </form>
                  <hr>
                  <h5>MUCHAS GRACIAS!</h5>
            </div>
            <div class="btns">
                <button class="btn btn-outline-primary me-2" onclick="cambiarFormulario('sect2','sect3')">Anterior</button>
                <button class="btn btn-success" id="btnguardar-respuesta-orden">GUARDAR</button>
            </div>
            <br>
        </div>
    </section>

</body>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ asset('js/scriptMain.js') }}"></script>
</html>
