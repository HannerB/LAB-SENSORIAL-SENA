<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación Sensorial - SENA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.routes = {
            panelistasStore: "{{ route('panelistas.store') }}",
            calificacionStore: "{{ route('calificacion.store') }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <style>
        .hidden {
            display: none;
        }

        .transform {
            transition-property: all;
            transition-duration: 500ms;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-sena-green shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.webp') }}" alt="SENA Logo"
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
                <a href="{{ route('login') }}"
                    class="group flex items-center px-4 py-2 text-sm font-medium rounded-md text-white 
                          bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 
                          focus:ring-offset-2 focus:ring-green-500 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    Administración
                </a>
            </div>
        </div>
    </nav>

    <!-- Progress Steps -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="relative">
            <div class="flex items-center justify-between mb-16">
                <!-- Step 1: Triangular -->
                <div class="flex flex-col items-center relative z-10 w-1/3">
                    <div
                        class="w-10 h-10 rounded-full bg-sena-green text-white flex items-center justify-center
                               transition-all duration-500 ease-in-out relative">
                        <span class="text-sm font-bold">1</span>
                    </div>
                    <p class="text-sm font-medium text-gray-700 mt-2">Prueba Triangular</p>
                </div>

                <!-- Step 2: Duo-Trio -->
                <div class="flex flex-col items-center relative z-10 w-1/3">
                    <div
                        class="w-10 h-10 rounded-full bg-gray-300 text-white flex items-center justify-center
                               transition-all duration-500 ease-in-out">
                        <span class="text-sm font-bold">2</span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mt-2">Prueba Duo-Trio</p>
                </div>

                <!-- Step 3: Ordering -->
                <div class="flex flex-col items-center relative z-10 w-1/3">
                    <div
                        class="w-10 h-10 rounded-full bg-gray-300 text-white flex items-center justify-center
                               transition-all duration-500 ease-in-out">
                        <span class="text-sm font-bold">3</span>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mt-2">Prueba de Ordenamiento</p>
                </div>

                <!-- Progress Bar -->
                <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-200 -z-10">
                    <div class="absolute top-0 left-0 h-full bg-sena-green transition-all duration-500 ease-in-out"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Main Content Sections -->
        <div id="test-sections" class="space-y-8">
            <!-- Triangular Test -->
            <section id="sect1" class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-500">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-gray-900 text-center mb-8 uppercase tracking-wide">
                        Prueba Triangular
                    </h2>

                    <!-- Panelist Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Número de Cabina
                            </label>
                            <input type="number" id="cabina" value="{{ $numeroCabina }}" readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 
                                          shadow-sm focus:border-sena-green focus:ring-sena-green">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nombre Completo
                            </label>
                            <input type="text" id="nombrePanelista1"
                                class="mt-1 block w-full rounded-md border-gray-300 
                                          shadow-sm focus:border-sena-green focus:ring-sena-green
                                          placeholder-gray-400"
                                placeholder="Ingrese su nombre completo">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Fecha
                            </label>
                            <input type="date" id="fechaPanelista1"
                                class="mt-1 block w-full rounded-md border-gray-300 
                                          shadow-sm focus:border-sena-green focus:ring-sena-green">
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow">
                                <label class="block text-sm font-medium text-gray-700">
                                    Producto a Evaluar
                                </label>
                                <input type="hidden" id="productoIDPrueba1"
                                    value="{{ $productoHabilitado ? $productoHabilitado->id_producto : '' }}">
                                <input type="text" id="productoPrueba1" readonly
                                    value="{{ $productoHabilitado ? $productoHabilitado->nombre : '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100
                                              shadow-sm text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Frente a usted hay tres muestras de
                                    <span class="font-medium">
                                        {{ $productoHabilitado ? $productoHabilitado->nombre : 'nombre del producto' }}
                                    </span>.
                                    Dos son iguales y una diferente. Por favor, saboree cada una con cuidado y
                                    seleccione la muestra diferente.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Samples Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg mb-8">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Muestra
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Selección
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($muestrasTriangular as $muestra)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {{ $muestra->cod_muestra }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <label class="inline-flex items-center space-x-3 cursor-pointer group">
                                                <input type="radio" name="muestra_diferente"
                                                    value="{{ $muestra->cod_muestra }}"
                                                    class="h-4 w-4 text-sena-green border-gray-300 
                                                              focus:ring-sena-green cursor-pointer
                                                              transition-all duration-200">
                                                <span
                                                    class="text-sm text-gray-700 group-hover:text-sena-green
                                                           transition-colors duration-200">
                                                    Diferente
                                                </span>
                                            </label>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-4 text-sm text-gray-500 text-center">
                                            No hay muestras disponibles para la prueba triangular.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Comments Section -->
                    <div class="space-y-2 mb-8">
                        <label for="comentario-triangular" class="block text-sm font-medium text-gray-700">
                            Comentarios
                        </label>
                        <textarea id="comentario-triangular" rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm 
                                         focus:border-sena-green focus:ring-sena-green 
                                         transition-colors duration-200
                                         placeholder-gray-400"
                            placeholder="Ingrese sus observaciones aquí..."></textarea>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-end">
                        <button id="btnsiguiente1"
                            class="inline-flex items-center px-6 py-3 border border-transparent 
                                       text-base font-medium rounded-md shadow-sm text-white 
                                       bg-sena-green hover:bg-green-700 focus:outline-none 
                                       focus:ring-2 focus:ring-offset-2 focus:ring-sena-green
                                       transition-all duration-200 transform hover:scale-105">
                            Siguiente
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Duo-Trio Test -->
            <section id="sect2"
                class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-500 hidden">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-gray-900 text-center mb-8 uppercase tracking-wide">
                        Prueba Duo-Trio
                    </h2>

                    <!-- Product Information -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow">
                                <label class="block text-sm font-medium text-gray-700">
                                    Producto a Evaluar
                                </label>
                                <input type="text" id="productoPrueba2" readonly
                                    value="{{ $productoHabilitado ? $productoHabilitado->nombre : '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100
                               shadow-sm text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8 space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Frente a usted hay tres muestras de
                                    <span
                                        class="font-medium">{{ $productoHabilitado ? $productoHabilitado->nombre : 'nombre del producto' }}</span>:
                                    una de referencia marcada con R y dos codificadas.
                                </p>
                                <p class="text-sm text-blue-700 mt-2">
                                    Una de las muestras codificadas es igual a R.
                                </p>
                                <p class="text-sm text-blue-700 mt-2">
                                    Por favor, identifique cuál de las muestras codificadas es igual a la muestra de
                                    referencia.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Samples Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg mb-8">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Muestra
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Igual a Referencia
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($muestrasDuoTrio as $muestra)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {{ $muestra->cod_muestra }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <label class="inline-flex items-center space-x-3 cursor-pointer group">
                                                <input type="radio" name="muestra_igual_referencia"
                                                    value="{{ $muestra->cod_muestra }}"
                                                    class="h-4 w-4 text-sena-green border-gray-300 
                                               focus:ring-sena-green cursor-pointer
                                               transition-all duration-200">
                                                <span
                                                    class="text-sm text-gray-700 group-hover:text-sena-green
                                               transition-colors duration-200">
                                                    Igual a R
                                                </span>
                                            </label>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-4 text-sm text-gray-500 text-center">
                                            No hay muestras disponibles para la prueba Duo-Trio.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Comments Section -->
                    <div class="space-y-2 mb-8">
                        <label for="comentario-duo" class="block text-sm font-medium text-gray-700">
                            Comentarios
                        </label>
                        <textarea id="comentario-duo" rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm 
                       focus:border-sena-green focus:ring-sena-green 
                       transition-colors duration-200
                       placeholder-gray-400"
                            placeholder="Ingrese sus observaciones aquí..."></textarea>
                    </div>

                    <!-- Navigation -->
                    <div class="flex justify-between">
                        <button onclick="cambiarFormulario('sect1','sect2')"
                            class="inline-flex items-center px-6 py-3 border border-gray-300
                       text-base font-medium rounded-md shadow-sm text-gray-700
                       bg-white hover:bg-gray-50 focus:outline-none focus:ring-2
                       focus:ring-offset-2 focus:ring-sena-green
                       transition-all duration-200">
                            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            Anterior
                        </button>
                        <button id="btnsiguiente2"
                            class="inline-flex items-center px-6 py-3 border border-transparent
                       text-base font-medium rounded-md shadow-sm text-white
                       bg-sena-green hover:bg-green-700 focus:outline-none
                       focus:ring-2 focus:ring-offset-2 focus:ring-sena-green
                       transition-all duration-200 transform hover:scale-105">
                            Siguiente
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Ordenamiento Test -->
            <section id="sect3"
                class="bg-white rounded-xl shadow-lg p-8 transform transition-all duration-500 hidden">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-gray-900 text-center mb-8 uppercase tracking-wide">
                        Prueba de Ordenamiento
                    </h2>

                    <!-- Product Information -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="flex-grow">
                                <label class="block text-sm font-medium text-gray-700">
                                    Producto a Evaluar
                                </label>
                                <input type="text" id="productoPrueba3" readonly
                                    value="{{ $productoHabilitado ? $productoHabilitado->nombre : '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100
                               shadow-sm text-gray-700">
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8 space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 space-y-2">
                                <p class="text-sm text-blue-700">
                                    Frente a usted hay {{ count($muestrasOrdenamiento) }} muestras de
                                    <span
                                        class="font-medium">{{ $productoHabilitado ? $productoHabilitado->nombre : 'nombre del producto' }}</span>
                                    que debe ordenar en forma creciente de acuerdo al grado de
                                    <span
                                        class="font-medium lowercase">{{ $muestrasOrdenamiento->first() ? $muestrasOrdenamiento->first()->atributo : 'no especificado' }}</span>.
                                </p>
                                <p class="text-sm text-blue-700">
                                    Cada muestra debe llevar un orden diferente. No se permite que dos muestras tengan
                                    el mismo orden.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Samples Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg mb-8">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                        Muestra
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Orden de Intensidad
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white" id="cuerpo-selectores-ordenamiento">
                                @forelse($muestrasOrdenamiento as $muestra)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            {{ $muestra->cod_muestra }}
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500">
                                            <select name="orden_muestra_{{ $muestra->cod_muestra }}"
                                                class="orden-muestra mt-1 block w-full rounded-md border-gray-300 
                                           focus:border-sena-green focus:ring-sena-green text-sm
                                           transition-colors duration-200"
                                                required>
                                                <option value="" selected disabled>Seleccione el orden</option>
                                                @for ($i = 1; $i <= count($muestrasOrdenamiento); $i++)
                                                    <option value="{{ $i }}">
                                                        {{ $i }} -
                                                        @if (count($muestrasOrdenamiento) == 3)
                                                            @switch($i)
                                                                @case(1)
                                                                    Intensidad baja
                                                                @break

                                                                @case(2)
                                                                    Intensidad media
                                                                @break

                                                                @case(3)
                                                                    Intensidad alta
                                                                @break
                                                            @endswitch
                                                        @elseif(count($muestrasOrdenamiento) == 6)
                                                            @switch($i)
                                                                @case(1)
                                                                    Muy baja intensidad
                                                                @break

                                                                @case(2)
                                                                    Baja intensidad
                                                                @break

                                                                @case(3)
                                                                    Media-baja intensidad
                                                                @break

                                                                @case(4)
                                                                    Media-alta intensidad
                                                                @break

                                                                @case(5)
                                                                    Alta intensidad
                                                                @break

                                                                @case(6)
                                                                    Muy alta intensidad
                                                                @break
                                                            @endswitch
                                                        @else
                                                            @switch($i)
                                                                @case(1)
                                                                    Intensidad mínima
                                                                @break

                                                                @case(2)
                                                                    Muy baja intensidad
                                                                @break

                                                                @case(3)
                                                                    Baja intensidad
                                                                @break

                                                                @case(4)
                                                                    Media-baja intensidad
                                                                @break

                                                                @case(5)
                                                                    Media intensidad
                                                                @break

                                                                @case(6)
                                                                    Media-alta intensidad
                                                                @break

                                                                @case(7)
                                                                    Alta intensidad
                                                                @break

                                                                @case(8)
                                                                    Muy alta intensidad
                                                                @break

                                                                @case(9)
                                                                    Intensidad extrema
                                                                @break

                                                                @case(10)
                                                                    Intensidad máxima
                                                                @break
                                                            @endswitch
                                                        @endif
                                                    </option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-3 py-4 text-sm text-gray-500 text-center">
                                                No hay muestras disponibles para la prueba de Ordenamiento.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Comments Section -->
                        <div class="space-y-2 mb-8">
                            <label for="comentario-orden" class="block text-sm font-medium text-gray-700">
                                Comentarios
                            </label>
                            <textarea id="comentario-orden" rows="4"
                                class="block w-full rounded-md border-gray-300 shadow-sm 
                       focus:border-sena-green focus:ring-sena-green 
                       transition-colors duration-200
                       placeholder-gray-400"
                                placeholder="Ingrese sus observaciones aquí..."></textarea>
                        </div>

                        <!-- Navigation and Submit -->
                        <div class="flex justify-between">
                            <button onclick="cambiarFormulario('sect2','sect3')"
                                class="inline-flex items-center px-6 py-3 border border-gray-300
                       text-base font-medium rounded-md shadow-sm text-gray-700
                       bg-white hover:bg-gray-50 focus:outline-none focus:ring-2
                       focus:ring-offset-2 focus:ring-sena-green
                       transition-all duration-200">
                                <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                                </svg>
                                Anterior
                            </button>
                            <button id="btnguardar-todo"
                                class="inline-flex items-center px-6 py-3 border border-transparent
                       text-base font-medium rounded-md shadow-sm text-white
                       bg-sena-green hover:bg-green-700 focus:outline-none
                       focus:ring-2 focus:ring-offset-2 focus:ring-sena-green
                       transition-all duration-200 transform hover:scale-105">
                                <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar Todo
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </body>

    </html>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('js/scriptMain.js') }}"></script>

    <!-- Script for ordering test intensity descriptions -->
    <script>
        function getIntensidadTexto(posicion, total) {
            if (total === 3) {
                const descripciones = {
                    1: 'Intensidad baja',
                    2: 'Intensidad media',
                    3: 'Intensidad alta'
                };
                return descripciones[posicion];
            } else if (total === 6) {
                const descripciones = {
                    1: 'Muy baja intensidad',
                    2: 'Baja intensidad',
                    3: 'Media-baja intensidad',
                    4: 'Media-alta intensidad',
                    5: 'Alta intensidad',
                    6: 'Muy alta intensidad'
                };
                return descripciones[posicion];
            } else if (total === 10) {
                const descripciones = {
                    1: 'Intensidad mínima',
                    2: 'Muy baja intensidad',
                    3: 'Baja intensidad',
                    4: 'Media-baja intensidad',
                    5: 'Media intensidad',
                    6: 'Media-alta intensidad',
                    7: 'Alta intensidad',
                    8: 'Muy alta intensidad',
                    9: 'Intensidad extrema',
                    10: 'Intensidad máxima'
                };
                return descripciones[posicion];
            }
            return 'Intensidad no especificada';
        }

        function actualizarEstadosPruebas() {
            const progressBar = document.querySelector('.bg-sena-green');
            const steps = document.querySelectorAll('.step');

            function actualizarProgreso(paso) {
                const width = ((paso - 1) / 2) * 100;
                progressBar.style.width = `${width}%`;

                steps.forEach((step, index) => {
                    if (index < paso) {
                        step.classList.remove('bg-gray-300');
                        step.classList.add('bg-sena-green');
                    } else {
                        step.classList.remove('bg-sena-green');
                        step.classList.add('bg-gray-300');
                    }
                });
            }

            // Actualizar cuando se cambia de sección

        }
    </script>
