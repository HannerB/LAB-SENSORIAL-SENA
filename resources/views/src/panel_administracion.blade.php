<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Panel de Administración - SENA">
    <meta name="theme-color" content="#198754">
    <link rel="icon" href="{{ asset('img/favicon.ico') }}">

    <title>Panel de Administración - SENA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        const rutaActualizarConfiguracion = "{{ route('configuracion.update', 1) }}";
    </script>
</head>

<body class="bg-gray-50">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl flex items-center space-x-4">
            <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-gray-700 text-lg font-medium">Procesando...</span>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="bg-sena-green shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.png') }}" alt="SENA Logo"
                        class="h-12 w-auto transition-transform duration-300 hover:scale-105">
                    <div class="hidden md:block">
                        <h1 class="text-white font-semibold text-xl">
                            Laboratorio Sensorial de Alimentos
                            <span class="block text-sm text-green-100 mt-0.5">
                                SENA Cedagro - Centro de Valor de Agregado
                            </span>
                        </h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('index') }}"
                        class="inline-flex items-center px-4 py-2 border border-white 
                              text-sm font-medium rounded-md text-white hover:bg-white 
                              hover:text-green-700 transition-all duration-300">
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8 uppercase tracking-wide">
                Panel de Administración
            </h2>

            <!-- Configuración de Cabina -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <label for="cabina" class="text-sm font-medium text-gray-700">
                            Número de Cabina:
                        </label>
                        <input type="number" id="cabina" min="1" max="3" value="1"
                            class="w-20 rounded-md border-gray-300 shadow-sm 
                focus:border-green-500 focus:ring-green-500">
                    </div>
                    <a href="{{ route('admin.resultados') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent 
                  text-sm font-medium rounded-md text-white bg-green-600 
                  hover:bg-green-700 transition-all duration-200 shadow-sm">
                        <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                        Visualizar Resultados
                    </a>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4 mb-8">
                <button id="btnguardar"
                    class="inline-flex items-center px-4 py-2 border border-transparent 
                   text-sm font-medium rounded-md text-white bg-green-600 
                   hover:bg-green-700 transition-all duration-200 shadow-sm">
                    <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Guardar Cambios
                </button>
            </div>
            <!-- Formulario de Productos -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Agregar Nuevo Producto</h3>
                <form id="form-producto" class="flex flex-col md:flex-row items-end space-y-4 md:space-y-0 md:space-x-4"
                    action="{{ route('producto.store') }}" method="POST">
                    @csrf
                    <div class="flex-grow">
                        <label for="nombreProducto" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre del Producto
                        </label>
                        <input type="text" id="nombreProducto" name="nombre" required
                            class="w-full rounded-md border-gray-300 shadow-sm 
                          focus:border-green-500 focus:ring-green-500">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent 
                       text-sm font-medium rounded-md text-white bg-green-600 
                       hover:bg-green-700 transition-all duration-200 shadow-sm">
                        <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Agregar Producto
                    </button>
                </form>
            </div>

            <!-- Búsqueda -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" id="table-search"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg 
                      focus:ring-green-500 focus:border-green-500 
                      placeholder-gray-400"
                        placeholder="Buscar productos...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-sena-green to-green-600">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Listado de Productos
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="group px-6 py-3 text-left cursor-pointer" data-sort="id">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span>ID</span>
                                        <div class="flex flex-col">
                                            <svg class="w-4 h-4 sort-asc hidden" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-4 h-4 sort-desc hidden" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col" class="group px-6 py-3 text-left cursor-pointer"
                                    data-sort="nombre">
                                    <div
                                        class="flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <span>Nombre Producto</span>
                                        <div class="flex flex-col">
                                            <svg class="w-4 h-4 sort-asc hidden" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414l-3.293 3.293a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg class="w-4 h-4 sort-desc hidden" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 15.586l3.293-3.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($productos as $producto)
                                <tr class="hover:bg-gray-50 transition-duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap" data-id="{{ $producto->id_producto }}">
                                        <span class="px-2 py-1 text-sm text-gray-600 bg-gray-100 rounded-lg">
                                            {{ $producto->id_producto }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4" data-nombre="{{ $producto->nombre }}">
                                        <div class="text-sm font-medium text-gray-900">{{ $producto->nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-4">
                                            <button
                                                class="btn-configuracion group relative flex items-center justify-center p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-full transition-colors duration-200"
                                                data-id="{{ $producto->id_producto }}"
                                                data-nombre="{{ $producto->nombre }}">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span
                                                    class="absolute -top-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    Configurar
                                                </span>
                                            </button>

                                            <button
                                                class="btn-habilitar group relative flex items-center justify-center p-2 rounded-full transition-colors duration-200 {{ $producto->habilitado ? 'text-red-600 hover:bg-red-600' : 'text-green-600 hover:bg-green-600' }} hover:text-white"
                                                data-id="{{ $producto->id_producto }}"
                                                data-habilitado="{{ $producto->habilitado }}">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                <span
                                                    class="absolute -top-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    {{ $producto->habilitado ? 'Deshabilitar' : 'Habilitar' }}
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Modal de Configuración -->
                    <div id="modalConfiguracion" class="fixed inset-0 z-50 hidden overflow-y-auto"
                        aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <!-- Overlay de fondo -->
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                aria-hidden="true"></div>

                            <!-- Contenedor del modal -->
                            <div
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden 
       shadow-xl transform transition-all duration-300 ease-out 
       opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 
       sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                                <!-- Cabecera del Modal -->
                                <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Configuración de Producto
                                        </h3>
                                        <button type="button"
                                            class="btn-close text-gray-400 hover:text-gray-500 
                                           transition-colors duration-200"
                                            data-bs-dismiss="modal" aria-label="Close">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Contenido del Modal -->
                                <div class="px-6 py-4">
                                    <!-- Formulario de Actualización de Nombre -->
                                    <form id="form-producto-modal" class="mb-8" method="POST"
                                        action="{{ route('productos.update', ':id') }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" id="productoId" name="id_producto">

                                        <div class="space-y-4">
                                            <div>
                                                <label for="nombreProductoModal"
                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                    Nombre del Producto
                                                </label>
                                                <input type="text" id="nombreProductoModal" name="nombre"
                                                    required
                                                    class="w-full rounded-md border-gray-300 shadow-sm 
                                     focus:border-green-500 focus:ring-green-500 
                                     transition duration-150 ease-in-out">
                                            </div>

                                            <div class="flex justify-end">
                                                <button type="button" id="btn-submit"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent 
                                      text-sm font-medium rounded-md text-white bg-green-600 
                                      hover:bg-green-700 focus:outline-none focus:ring-2 
                                      focus:ring-offset-2 focus:ring-green-500 
                                      transition-all duration-200">
                                                    <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Guardar Cambios
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Sección de Muestras -->
                                    <div class="space-y-6">
                                        <h4 class="text-lg font-medium text-gray-900">Gestión de Muestras</h4>

                                        <!-- Formulario de Nueva Muestra -->
                                        <form id="form-muestras" method="POST"
                                            action="{{ route('muestra.store') }}"
                                            class="bg-gray-50 rounded-lg p-6 space-y-4">
                                            @csrf
                                            <input type="hidden" id="producto-id-muestra" name="producto_id">

                                            <!-- Código de Muestra -->
                                            <div class="space-y-2">
                                                <label for="codigo-muestra"
                                                    class="block text-sm font-medium text-gray-700">
                                                    Código de Muestra
                                                </label>
                                                <div class="flex space-x-2">
                                                    <input type="text" id="codigo-muestra" name="cod_muestra"
                                                        required
                                                        class="flex-1 rounded-md border-gray-300 shadow-sm 
                                         focus:border-green-500 focus:ring-green-500">
                                                    <button type="button" id="btn-generar-codigo"
                                                        class="inline-flex items-center px-4 py-2 border border-gray-300 
                                          text-sm font-medium rounded-md text-gray-700 bg-white 
                                          hover:bg-gray-50 focus:outline-none focus:ring-2 
                                          focus:ring-offset-2 focus:ring-green-500">
                                                        Generar Código
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Tipo de Prueba -->
                                            <div class="space-y-2">
                                                <label for="tipo-prueba"
                                                    class="block text-sm font-medium text-gray-700">
                                                    Tipo de Prueba
                                                </label>
                                                <select id="tipo-prueba" name="prueba" required
                                                    class="w-full rounded-md border-gray-300 shadow-sm 
                                      focus:border-green-500 focus:ring-green-500">
                                                    <option value="1">Prueba Triangular</option>
                                                    <option value="2">Prueba Duo-Trio</option>
                                                    <option value="3">Prueba Ordenamiento</option>
                                                </select>
                                            </div>

                                            <!-- Atributo (solo visible para prueba de ordenamiento) -->
                                            <div id="atributo-container" class="space-y-2 hidden">
                                                <label for="atributo" class="block text-sm font-medium text-gray-700">
                                                    Atributo
                                                </label>
                                                <select id="atributo" name="atributo"
                                                    class="w-full rounded-md border-gray-300 shadow-sm 
                                      focus:border-green-500 focus:ring-green-500">
                                                    <option value="">Seleccione un atributo</option>
                                                    <option value="Sabor">Sabor</option>
                                                    <option value="Olor">Olor</option>
                                                    <option value="Color">Color</option>
                                                    <option value="Textura">Textura</option>
                                                    <option value="Apariencia">Apariencia</option>
                                                </select>
                                            </div>

                                            <!-- Botón de Guardar -->
                                            <div class="flex justify-end">
                                                <button type="submit" id="btn-guardar-muestra"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent 
                                      text-sm font-medium rounded-md text-white bg-green-600 
                                      hover:bg-green-700 focus:outline-none focus:ring-2 
                                      focus:ring-offset-2 focus:ring-green-500 
                                      transition-all duration-200">
                                                    <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Guardar Muestra
                                                </button>
                                            </div>
                                        </form>

                                        <!-- Tablas de Muestras -->
                                        <div class="space-y-6">
                                            <!-- Tabla Prueba Triangular -->
                                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                                    <h5 class="text-sm font-medium text-gray-700">Muestras de Prueba
                                                        Triangular
                                                    </h5>
                                                </div>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    #</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    Código</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="cuerpo-table-uno"
                                                            class="bg-white divide-y divide-gray-200"></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Tabla Prueba Duo-Trio -->
                                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                                    <h5 class="text-sm font-medium text-gray-700">Muestras de Prueba
                                                        Duo-Trio
                                                    </h5>
                                                </div>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    #</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    Código</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="cuerpo-table-dos"
                                                            class="bg-white divide-y divide-gray-200"></tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Tabla Prueba Ordenamiento -->
                                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                                                    <h5 class="text-sm font-medium text-gray-700">Muestras de Prueba de
                                                        Ordenamiento</h5>
                                                </div>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    #</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    Código</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    Atributo</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                    Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="cuerpo-table-tres"
                                                            class="bg-white divide-y divide-gray-200"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pie del Modal -->
                                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                    <div class="flex justify-end">
                                        <button type="button"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 
                              text-sm font-medium rounded-md text-gray-700 bg-white 
                              hover:bg-gray-50 focus:outline-none focus:ring-2 
                              focus:ring-offset-2 focus:ring-green-500"
                                            data-bs-dismiss="modal">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/scriptAdministracion.js') }}"></script>
    <script src="{{ asset('js/scriptMuestras.js') }}"></script>

    <script>
        // Configuración inicial de la tabla
        const tableConfig = {
            searchInput: document.getElementById('table-search'),
            productsTable: document.querySelector('table'),
            sortButtons: document.querySelectorAll('[data-sort]'),
            sortState: {
                column: null,
                isAsc: true
            }
        };

        function initializeTableFeatures() {
            // Búsqueda en tiempo real
            if (tableConfig.searchInput) {
                tableConfig.searchInput.addEventListener('input', debounce(function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = tableConfig.productsTable.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        const id = row.querySelector('td[data-id]')?.textContent || '';
                        const nombre = row.querySelector('td[data-nombre]')?.textContent || '';
                        const text = `${id} ${nombre}`.toLowerCase();

                        row.classList.toggle('hidden', !text.includes(searchTerm));
                    });

                    updateEmptyState();
                }, 300));
            }

            // Ordenamiento de columnas
            tableConfig.sortButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const column = this.dataset.sort;
                    const isAsc = tableConfig.sortState.column === column ? !tableConfig.sortState.isAsc :
                        true;

                    // Actualizar estado
                    tableConfig.sortState.column = column;
                    tableConfig.sortState.isAsc = isAsc;

                    sortTable(column, isAsc);
                    updateSortIcons();
                });
            });

            // Inicializar estado vacío
            updateEmptyState();
        }

        function sortTable(column, isAsc) {
            const tbody = tableConfig.productsTable.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-state)'));

            rows.sort((a, b) => {
                const aVal = a.querySelector(`td[data-${column}]`)?.textContent || '';
                const bVal = b.querySelector(`td[data-${column}]`)?.textContent || '';

                return isAsc ?
                    aVal.localeCompare(bVal, undefined, {
                        numeric: true
                    }) :
                    bVal.localeCompare(aVal, undefined, {
                        numeric: true
                    });
            });

            tbody.append(...rows);
        }

        function updateSortIcons() {
            tableConfig.sortButtons.forEach(button => {
                const column = button.dataset.sort;
                const isCurrentColumn = column === tableConfig.sortState.column;
                const ascIcon = button.querySelector('.sort-asc');
                const descIcon = button.querySelector('.sort-desc');

                if (isCurrentColumn) {
                    ascIcon.classList.toggle('hidden', !tableConfig.sortState.isAsc);
                    descIcon.classList.toggle('hidden', tableConfig.sortState.isAsc);
                } else {
                    ascIcon.classList.add('hidden');
                    descIcon.classList.add('hidden');
                }
            });
        }

        function updateEmptyState() {
            const tbody = tableConfig.productsTable.querySelector('tbody');
            const rows = tbody.querySelectorAll('tr:not(.empty-state)');
            const visibleRows = Array.from(rows).filter(row => !row.classList.contains('hidden'));
            let emptyState = tbody.querySelector('.empty-state');

            if (visibleRows.length === 0) {
                if (!emptyState) {
                    emptyState = document.createElement('tr');
                    emptyState.className = 'empty-state';
                    emptyState.innerHTML = `
               <td colspan="3" class="px-6 py-8 text-center">
                   <div class="flex flex-col items-center">
                       <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                 d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                       </svg>
                       <span class="mt-2 text-gray-500">No se encontraron productos</span>
                   </div>
               </td>
           `;
                }
                tbody.appendChild(emptyState);
            } else if (emptyState) {
                emptyState.remove();
            }
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Inicializar características de la tabla
        document.addEventListener('DOMContentLoaded', initializeTableFeatures);
    </script>
</body>

</html>
