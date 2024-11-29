<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel de Resultados - SENA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-sena-green shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.webp') }}" alt="SENA Logo"
                        class="h-12 w-auto transition-transform duration-300 hover:scale-105">
                    <div>
                        <h1 class="text-white font-semibold text-xl">
                            Laboratorio Sensorial de Alimentos
                            <span class="block text-sm text-green-100 mt-0.5">
                                SENA Cedagro
                            </span>
                        </h1>
                    </div>
                </div>
                <a href="{{ route('admin.panel') }}"
                    class="group flex items-center px-4 py-2 text-sm font-medium rounded-md text-white 
                           bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 
                           focus:ring-offset-2 focus:ring-green-500 transition-all duration-300">
                    <i class="fas fa-cogs mr-2 transition-transform duration-300 group-hover:scale-110"></i>
                    Panel de Administración
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Título -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">
                Panel de Resultados
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Sistema de Gestión de Evaluaciones Sensoriales
            </p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form id="filtro-resultados" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Selector de Cabina -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-door-open mr-2"></i>Número de Cabina
                        </label>
                        <select id="cabinas-filtro"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                focus:border-sena-green focus:ring-sena-green">
                            <option value="select">Seleccione cabina</option>
                            <option value="all">Todas las cabinas</option>
                            @foreach ($cabinasDisponibles as $cabina)
                                <option value="{{ $cabina }}">
                                    Cabina {{ $cabina }}
                                    @if (in_array($cabina, $cabinasUsadas ?? []))
                                        (Con datos)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selector de Fecha -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-calendar mr-2"></i>Fecha de Prueba
                        </label>
                        <input type="date" id="fecha-filtro" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-sena-green focus:ring-sena-green">
                    </div>

                    <!-- Selector de Producto -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fas fa-box mr-2"></i>Producto
                        </label>
                        <select id="productos-filtro"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-sena-green focus:ring-sena-green">
                            <option value="select">Seleccione producto</option>
                            @if ($productoHabilitado)
                                <option value="{{ $productoHabilitado->id_producto }}" selected>
                                    {{ $productoHabilitado->nombre }}
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent
                                   text-sm font-medium rounded-md text-white bg-blue-600
                                   hover:bg-blue-700 focus:outline-none focus:ring-2
                                   focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Generar Resultados
                    </button>
                    <button type="button" id="btnExportar"
                        class="inline-flex items-center px-4 py-2 border border-transparent
                                   text-sm font-medium rounded-md text-white bg-green-600
                                   hover:bg-green-700 focus:outline-none focus:ring-2
                                   focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-file-excel mr-2"></i>
                        Exportar Selección
                    </button>
                    <button type="button" id="btnExportarTodo"
                        class="inline-flex items-center px-4 py-2 border border-transparent
                                   text-sm font-medium rounded-md text-white bg-green-600
                                   hover:bg-green-700 focus:outline-none focus:ring-2
                                   focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-file-excel mr-2"></i>
                        Exportar Todo
                    </button>
                </div>
            </form>
        </div>

        <!-- Sección de Resultados -->
        <div id="resultados-pruebas"
            class="bg-white rounded-lg shadow-lg p-6 mb-8 transform transition-all duration-500 hidden">
            <!-- Prueba Triangular -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-triangle mr-2 text-sena-green"></i>
                    PRUEBA TRIANGULAR
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código Muestra
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Resultado
                                </th>
                            </tr>
                        </thead>
                        <tbody id="body-triangular" class="bg-white divide-y divide-gray-200">
                            <!-- Los resultados se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Prueba Duo-Trio -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-equals mr-2 text-sena-green"></i>
                    PRUEBA DUO-TRIO
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código Muestra
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Resultado
                                </th>
                            </tr>
                        </thead>
                        <tbody id="body-duo" class="bg-white divide-y divide-gray-200">
                            <!-- Los resultados se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Prueba Ordenamiento -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sort-amount-up mr-2 text-sena-green"></i>
                    PRUEBA ORDENAMIENTO
                </h3>
                <!-- Container for dynamic attribute sections -->
                <div id="ordenamiento-results-container">
                    <!-- Los resultados se cargarán dinámicamente por atributo -->
                </div>
            </div>
        </div>

        <!-- Sección de Resultados por Panelista -->
        <div id="resultado-pruebas-personas" class="bg-white rounded-lg shadow-lg p-6 hidden">
            <!-- Encabezado con icono y título -->
            <div class="mb-8 text-center">
                <div class="inline-block p-3 bg-green-50 rounded-full mb-4">
                    <i class="fas fa-users text-2xl text-sena-green"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Resultados Individuales por Panelista</h3>
                <p class="text-sm text-gray-600 mt-1">Visualización detallada de las evaluaciones realizadas</p>
            </div>

            <!-- Selector de Prueba con Diseño Mejorado -->
            <div class="mb-8">
                <div class="max-w-lg mx-auto bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-3 items-center">
                        <i class="fas fa-flask mr-2 text-sena-green"></i>
                        Tipo de Prueba a Visualizar
                    </label>
                    <select id="tipo-prueba-resultado"
                        class="block w-full rounded-lg border-gray-300 shadow-sm 
                       focus:border-sena-green focus:ring-sena-green
                       bg-white py-2.5">
                        <option value="select">Seleccione el tipo de prueba</option>
                        <option value="1">📐 Prueba Triangular</option>
                        <option value="2">⚖️ Prueba Duo-Trio</option>
                        <option value="3">📊 Prueba de Ordenamiento</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de Resultados con Diseño Mejorado -->
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                #
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Panelista
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Cabina
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Resultado de Evaluación
                            </th>
                        </tr>
                    </thead>
                    <tbody id="listado-personas-prueba" class="bg-white divide-y divide-gray-200">
                        <!-- Los resultados se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío mejorado -->
            <div id="empty-state" class="hidden text-center py-12">
                <div class="inline-block p-4 bg-gray-50 rounded-full mb-4">
                    <i class="fas fa-clipboard-list text-4xl text-gray-400"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900">No hay resultados disponibles</h4>
                <p class="text-sm text-gray-500 mt-1">Seleccione una prueba para ver los resultados de los panelistas
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('../bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('../js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('../js/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
    <script src="{{ asset('../js/scriptResultados.js') }}"></script>

</body>

</html>
