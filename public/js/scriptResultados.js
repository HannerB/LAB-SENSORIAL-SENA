$(document).ready(function () {
    $('#filtro-resultados').submit(function (e) {
        e.preventDefault();

        var fecha = $('#fecha-filtro').val();
        var productoId = $('#productos-filtro').val();

        if (productoId === 'select') {
            Swal.fire('Advertencia', 'Por favor, selecciona un producto.', 'warning');
            return;
        }

        $.ajax({
            url: '/resultado/generar',
            method: 'POST',
            data: {
                fecha: fecha,
                producto_id: productoId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#resultados-pruebas').show();

                // Limpiar las tablas existentes
                $('#body-triangular').empty();
                $('#body-duo').empty();
                $('#preferencia-ordenamiento').empty();

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

                if (response.data.ordenamiento && response.data.ordenamiento.length > 0) {
                    $('#atributo-prueba').text(response.data.ordenamiento[0].atributo);
                    if (response.data.muestraMasVotada) {
                        $('#preferencia-ordenamiento').append(` ${response.data.muestraMasVotada.cod_muestra}`);
                    }
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Ocurrió un error al generar los resultados. Intenta nuevamente.', 'error');
            }
        });
    });
});

$(document).ready(function () {
    $('#filtro-resultados').submit(function (e) {
        e.preventDefault();

        var fecha = $('#fecha-filtro').val();
        var productoId = $('#productos-filtro').val();

        if (productoId === 'select') {
            Swal.fire('Advertencia', 'Por favor, selecciona un producto.', 'warning');
            return;
        }

        $.ajax({
            url: '/resultado/generar',
            method: 'POST',
            data: {
                fecha: fecha,
                producto_id: productoId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#resultados-pruebas').show();
                $('#resultado-pruebas-personas').show();
                $('#tipo-prueba-resultado').val('select');
                $('#listado-personas-prueba').empty();
            },
            error: function (xhr) {
                console.error('Error:', xhr);
                Swal.fire('Error', 'Ocurrió un error al generar los resultados. Intenta nuevamente.', 'error');
            }
        });
    });

    // Event listener for the test type select
    $('#tipo-prueba-resultado').change(function() {
        var testType = $(this).val();
        var fecha = $('#fecha-filtro').val();
        var productoId = $('#productos-filtro').val();

        if (testType !== 'select') {
            $.ajax({
                url: '/mostrar-resultados-panelistas',
                method: 'GET',
                data: {
                    test_type: testType,
                    fecha: fecha,
                    producto_id: productoId
                },
                success: function(response) {
                    $('#listado-personas-prueba').empty();
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(item, index) {
                            $('#listado-personas-prueba').append(`
                                <tr>
                                    <th scope="row">${index + 1}</th>
                                    <td>${item.nombre_panelista}</td>
                                    <td>${item.respuesta}</td>
                                </tr>
                            `);
                        });
                        // Show the table after populating it
                        $('.tabla-personas').show();
                    } else {
                        $('#listado-personas-prueba').append(`
                            <tr>
                                <td colspan="3" class="text-center">No se encontraron resultados para esta prueba.</td>
                            </tr>
                        `);
                        $('.tabla-personas').show();
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    Swal.fire('Error', 'Ocurrió un error al obtener los resultados de los panelistas. Detalles: ' + xhr.responseText, 'error');
                }
            });
        } else {
            $('#listado-personas-prueba').empty();
            $('.tabla-personas').hide();
        }
    });

    $('#resultados-pruebas').hide();
    $('#resultado-pruebas-personas').hide();
    $('.tabla-personas').hide();
});