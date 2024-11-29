$(document).ready(function () {
    // Inicialización de elementos ocultos
    $('#resultados-pruebas').hide();
    $('#resultado-pruebas-personas').hide();
    $('.tabla-personas').hide();

    $('#filtro-resultados').submit(function (e) {
        e.preventDefault();

        var fecha = $('#fecha-filtro').val();
        var productoId = $('#productos-filtro').val();
        var cabina = $('#cabinas-filtro').val();

        if (productoId === 'select') {
            Swal.fire('Advertencia', 'Por favor, selecciona un producto.', 'warning');
            return;
        }

        if (cabina === 'select') {
            Swal.fire('Advertencia', 'Por favor, selecciona una cabina.', 'warning');
            return;
        }

        // Mostrar indicador de carga
        Swal.fire({
            title: 'Procesando...',
            text: cabina === 'all' ? 'Obteniendo resultados de todas las cabinas' : 'Obteniendo resultados',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/resultado/generar',
            method: 'POST',
            data: {
                fecha: fecha,
                producto_id: productoId,
                cabina: cabina,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    Swal.close();
                    mostrarResultados(response);
                    $('#resultados-pruebas').show();
                    $('#resultado-pruebas-personas').show();
                } else {
                    Swal.fire('Error', response.message || 'Error al generar resultados', 'error');
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr);
                Swal.fire('Error', 'Ocurrió un error al generar los resultados. Intenta nuevamente.', 'error');
            }
        });
    });

    // Manejo de resultados por panelista
    $('#tipo-prueba-resultado').change(function () {
        var testType = $(this).val();
        var fecha = $('#fecha-filtro').val();
        var productoId = $('#productos-filtro').val();
        var cabina = $('#cabinas-filtro').val();

        if (testType === 'select') {
            $('#listado-personas-prueba').empty();
            $('.tabla-personas').hide();
            return;
        }

        // Mostrar indicador de carga
        Swal.fire({
            title: 'Cargando resultados...',
            text: 'Obteniendo datos de los panelistas',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/mostrar-resultados-panelistas',
            method: 'GET',
            data: {
                test_type: testType,
                fecha: fecha,
                producto_id: productoId,
                cabina: cabina
            },
            success: function (response) {
                Swal.close();
                $('#listado-personas-prueba').empty();

                if (response.success && response.data && response.data.length > 0) {
                    response.data.forEach(function (item, index) {
                        $('#listado-personas-prueba').append(`
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-sm font-medium text-gray-700">
                                        ${index + 1}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span class="h-10 w-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                ${item.nombre_panelista}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Evaluación del ${new Date(item.fecha_evaluacion).toLocaleDateString()}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-door-open mr-2"></i>
                                        Cabina ${item.cabina}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        ${formatearRespuesta(item.respuesta)}
                                    </div>
                                </td>
                            </tr>
                        `);
                    });
                    $('#empty-state').addClass('hidden');
                } else {
                    $('#empty-state').removeClass('hidden');
                }

                $('.tabla-personas').show();
            },
            error: function (xhr) {
                Swal.fire('Error', 'Ocurrió un error al obtener los resultados de los panelistas.', 'error');
            }
        });
    });

    // Función auxiliar para formatear las respuestas
    function formatearRespuesta(respuesta) {
        if (respuesta.includes('|')) {
            // Es una prueba de ordenamiento
            return respuesta.split('|').map(attr =>
                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2 mb-1">
                ${attr.trim()}
             </span>`
            ).join('');
        } else {
            // Es una prueba triangular o duo-trio
            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    ${respuesta}
                </span>`;
        }
    }

    function mostrarResultados(response) {
        // Limpiar las tablas existentes
        $('#body-triangular').empty();
        $('#body-duo').empty();
        $('#ordenamiento-results-container').empty();

        // Mostrar resultados de prueba triangular
        if (response.data.triangulares && response.data.triangulares.length > 0) {
            response.data.triangulares.forEach(function (item, index) {
                const porcentaje = item.total_evaluaciones > 0
                    ? ((item.resultado / item.total_evaluaciones) * 100).toFixed(2)
                    : 0;

                $('#body-triangular').append(`
                <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${item.cod_muestra}</td>
                    <td>${item.resultado} votos (${porcentaje}%)</td>
                </tr>
            `);
            });
        } else {
            $('#body-triangular').append(`
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                    No hay resultados disponibles para la prueba triangular
                </td>
            </tr>
        `);
        }

        // Mostrar resultados de prueba duo-trio
        if (response.data.duoTrio && response.data.duoTrio.length > 0) {
            response.data.duoTrio.forEach(function (item, index) {
                const porcentaje = item.total_evaluaciones > 0
                    ? ((item.resultado / item.total_evaluaciones) * 100).toFixed(2)
                    : 0;

                $('#body-duo').append(`
                <tr>
                    <th scope="row">${index + 1}</th>
                    <td>${item.cod_muestra}</td>
                    <td>${item.resultado} votos (${porcentaje}%)</td>
                </tr>
            `);
            });
        } else {
            $('#body-duo').append(`
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                    No hay resultados disponibles para la prueba duo-trio
                </td>
            </tr>
        `);
        }

        // Mostrar resultados de prueba de ordenamiento
        if (response.data.ordenamiento) {
            const container = $('#ordenamiento-results-container');
            container.empty();

            Object.entries(response.data.ordenamiento).forEach(([atributo, resultados]) => {
                const section = `
            <div class="mb-6 border-b pb-4">
                <h4 class="text-lg font-semibold mb-3 flex items-center">
                    <span class="bg-gray-100 px-3 py-1 rounded-lg">
                        ${atributo}
                    </span>
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Código Muestra
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Calificación Promedio
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Evaluaciones
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Posición
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${resultados.map((resultado, index) => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${resultado.cod_muestra}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${resultado.promedio}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${resultado.total_evaluaciones}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium ${index === 0 ? 'text-green-600' :
                        index === resultados.length - 1 ? 'text-red-600' : 'text-gray-600'
                    }">
                                        ${index + 1}°
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
                container.append(section);
            });
        } else {
            $('#ordenamiento-results-container').html(`
        <div class="text-center text-gray-500 py-4">
            No hay resultados disponibles para la prueba de ordenamiento
        </div>
    `);
        }

        // Resetear el selector de tipo de prueba
        $('#tipo-prueba-resultado').val('select');
        $('#listado-personas-prueba').empty();
    }

    // Script para el botón de exportación
    $('#btnExportar').click(function () {
        const fecha = $('#fecha-filtro').val();
        const productoId = $('#productos-filtro').val();
        const tipoPrueba = $('#tipo-prueba-resultado').val();
        const cabina = $('#cabinas-filtro').val();

        if (!fecha || productoId === 'select' || cabina === 'select') {
            Swal.fire('Advertencia', 'Por favor, selecciona una fecha, producto y cabina.', 'warning');
            return;
        }

        const params = new URLSearchParams({
            fecha: fecha,
            producto_id: productoId,
            tipo_prueba: tipoPrueba !== 'select' ? tipoPrueba : '',
            cabina: cabina
        });

        window.location.href = `/resultados/exportar?${params.toString()}`;
    });

    // Script para el botón de exportar todo
    $('#btnExportarTodo').click(function () {
        const fecha = $('#fecha-filtro').val();
        const productoId = $('#productos-filtro').val();

        if (!fecha || productoId === 'select') {
            Swal.fire('Advertencia', 'Por favor, selecciona una fecha y producto.', 'warning');
            return;
        }

        const params = new URLSearchParams({
            fecha: fecha,
            producto_id: productoId
        });

        window.location.href = `/resultados/exportar-todas?${params.toString()}`;
    });
});