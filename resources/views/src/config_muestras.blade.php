@extends('layouts.app')

@section('content')
<div class="contenedor">
    <section>
        <h1 class="mt-4">CONFIGURACION DE PRODUCTO</h1>
        <form id="form-producto" class="mb-4">
            <label for="">NOMBRE PRODUCTO</label>
            <input type="text" id="nombreProducto" class="form-control mb-2" value="{{ $nombreProducto }}" required>
            <label for="" class="me-2 mb-3">
                <input type="checkbox" id="habilitado" {{ $habilitado ? 'checked' : '' }}> Realizar pruebas con este producto
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
                <label for="" class="form-label">Codigo Muestra</label>
                <input type="text" class="form-control" id="codigo-muestra" required>
            </div>
            <div class="text-center">
                <button id="btn-codigo" type="button">Generar un codigo de muestra</button>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Tipo de Prueba</label>
                <select id="tipo-prueba" class="form-select">
                    <option value="1">PRUEBA TRIANGULAR</option>
                    <option value="2">PRUEBA DUO-TRIO</option>
                    <option value="3">PRUEBA ORDENAMIENTO</option>
                </select>
            </div>
            <div class="mb-3" id="cont-atributos" style="display: none;">
                <label for="" class="form-label">Tipo de atributos</label>
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
                <button class="btn btn-danger" type="button" id="btncancelar">CANCELAR</button>
            </div>
        </form>

        <div class="text-center mb-4">
            <!-- <a href="">Desea realizar nuevas muestras con este producto?</a> -->
        </div>
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
@endsection

@push('scripts')
<script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/scriptMuestras.js') }}"></script>
@endpush