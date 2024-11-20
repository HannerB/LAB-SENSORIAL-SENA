<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administración - SENA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-gradient-to-br from-gray-100 to-gray-200">
    <!-- Navbar -->
    <nav class="bg-sena-green shadow-lg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <img src="{{ asset('img/logo-de-Sena-sin-fondo-Blanco.png') }}" alt="SENA Logo"
                        class="h-10 w-auto transform transition hover:scale-105">
                    <div class="hidden md:block">
                        <h1 class="ml-4 text-white font-semibold text-lg">
                            Laboratorio Sensorial de Alimentos
                            <span class="block text-sm text-green-100 opacity-90">SENA Cedagro</span>
                        </h1>
                    </div>
                </div>

                <a href="{{ route('index') }}"
                    class="group flex items-center px-4 py-2 border-2 border-white text-white rounded-lg
                          hover:bg-white hover:text-sena-green transition-all duration-300 ease-in-out
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2 transform group-hover:scale-110 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al Formulario
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex items-center justify-center min-h-[calc(100vh-4rem)] p-4">
        <div class="w-full max-w-md transform transition-all">
            <!-- Login Card -->
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="bg-sena-green px-6 py-8 text-center">
                    <h2 class="text-2xl font-bold text-white">
                        Acceso Administración
                    </h2>
                    <p class="mt-2 text-green-100">
                        Ingrese sus credenciales para continuar
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('authenticate') }}" method="POST" class="px-6 py-8 space-y-6">
                    @csrf

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="contra-access" class="block text-sm font-medium text-gray-700">
                            Contraseña de acceso
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="contra-access"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                          focus:ring-2 focus:ring-sena-green focus:border-sena-green
                                          transition-all duration-200 ease-in-out
                                          text-gray-900 placeholder-gray-400"
                                placeholder="Ingrese su contraseña" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Message -->
                    @if (session('alerta'))
                        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700 flex items-center
                                  animate-fade-in-down"
                            role="alert">
                            <svg class="w-5 h-5 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ session('alerta') }}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center items-center px-6 py-3 
                                       border border-transparent text-base font-medium rounded-lg
                                       text-white bg-sena-green hover:bg-green-700 
                                       focus:outline-none focus:ring-2 focus:ring-offset-2 
                                       focus:ring-sena-green transition-all duration-200 
                                       ease-in-out transform hover:scale-[1.02]">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <p class="mt-4 text-center text-sm text-gray-600">
                Sistema de Evaluación Sensorial
                <span class="block text-xs mt-1">
                    © {{ date('Y') }} SENA - Todos los derechos reservados
                </span>
            </p>
        </div>
    </main>
</body>

</html>
