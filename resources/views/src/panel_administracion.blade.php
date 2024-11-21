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

            <!-- Tabla de Productos -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Listado de Productos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre Producto
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($productos as $producto)
                                <tr data-id="{{ $producto->id_producto }}" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $producto->id_producto }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $producto->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex space-x-3">
                                            <button class="btn-configuracion text-blue-600 hover:text-blue-800"
                                                data-id="{{ $producto->id_producto }}"
                                                data-nombre="{{ $producto->nombre }}">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button
                                                class="btn-habilitar {{ $producto->habilitado ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}"
                                                data-id="{{ $producto->id_producto }}"
                                                data-habilitado="{{ $producto->habilitado }}">
                                                {{ $producto->habilitado ? 'Deshabilitar' : 'Habilitar' }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal de Configuración -->
            <div id="modalConfiguracion" class="fixed inset-0 z-50 hidden overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Overlay de fondo -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

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
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                        <input type="text" id="nombreProductoModal" name="nombre" required
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
                                            <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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
                                <form id="form-muestras" method="POST" action="{{ route('muestra.store') }}"
                                    class="bg-gray-50 rounded-lg p-6 space-y-4">
                                    @csrf
                                    <input type="hidden" id="producto-id-muestra" name="producto_id">

                                    <!-- Código de Muestra -->
                                    <div class="space-y-2">
                                        <label for="codigo-muestra" class="block text-sm font-medium text-gray-700">
                                            Código de Muestra
                                        </label>
                                        <div class="flex space-x-2">
                                            <input type="text" id="codigo-muestra" name="cod_muestra" required
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
                                        <label for="tipo-prueba" class="block text-sm font-medium text-gray-700">
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
                                            <svg class="mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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
                                            <h5 class="text-sm font-medium text-gray-700">Muestras de Prueba Triangular
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
                                            <h5 class="text-sm font-medium text-gray-700">Muestras de Prueba Duo-Trio
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
</body>

</html>
