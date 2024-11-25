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
                Swal.close();
                mostrarResultados(response);
                $('#resultados-pruebas').show();
                $('#resultado-pruebas-personas').show();
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

        if (testType !== 'select') {
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
                    $('#listado-personas-prueba').empty();
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function (item, index) {
                            $('#listado-personas-prueba').append(`
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${item.nombre_panelista}</td>
                                <td>${item.cabina === 'all' ? 'Todas' : 'Cabina ' + item.cabina}</td>
                                <td>${item.respuesta}</td>
                            </tr>
                        `);
                        });
                        $('.tabla-personas').show();
                    } else {
                        $('#listado-personas-prueba').append(`
                        <tr>
                            <td colspan="4" class="text-center">No se encontraron resultados para esta prueba.</td>
                        </tr>
                    `);
                        $('.tabla-personas').show();
                    }
                },
                error: function (xhr) {
                    console.error('Error:', xhr);
                    Swal.fire('Error', 'Ocurrió un error al obtener los resultados de los panelistas.', 'error');
                }
            });
        } else {
            $('#listado-personas-prueba').empty();
            $('.tabla-personas').hide();
        }
    });
    
    function mostrarResultados(response) {
        // Limpiar las tablas existentes
        $('#body-triangular').empty();
        $('#body-duo').empty();
        $('#ordenamiento-results-container').empty();

        // Mostrar resultados de prueba triangular
        if (response.data.triangulares && response.data.triangulares.length > 0) {
            response.data.triangulares.forEach(function (item, index) {
                $('#body-triangular').append(`
                    <tr>
                        <th scope="row">${index + 1}</th>
                        <td>${item.cod_muestra}</td>
                        <td>${item.resultado} votos</td>
                    </tr>
                `);
            });
        }

        // Mostrar resultados de prueba duo-trio
        if (response.data.duoTrio && response.data.duoTrio.length > 0) {
            response.data.duoTrio.forEach(function (item, index) {
                $('#body-duo').append(`
                    <tr>
                        <th scope="row">${index + 1}</th>
                        <td>${item.cod_muestra}</td>
                        <td>${item.resultado} votos</td>
                    </tr>
                `);
            });
        }

        // Mostrar resultados de prueba de ordenamiento
        if (response.data.ordenamiento && response.data.ordenamiento.length > 0) {
            const container = $('#ordenamiento-results-container');
            container.empty();

            // Group results by attribute using lodash
            const groupedByAttribute = _.groupBy(response.data.ordenamiento, 'atributo');

            // Create a section for each attribute
            Object.entries(groupedByAttribute).forEach(([atributo, resultados]) => {
                const section = `
                    <div class="mb-6 border-b pb-4">
                        <h4 class="text-md font-semibold mb-3">
                            Atributo: ${atributo}
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Código Muestra
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Votos
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    ${resultados.map(resultado => `
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ${resultado.cod_muestra}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${resultado.resultado} votos
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