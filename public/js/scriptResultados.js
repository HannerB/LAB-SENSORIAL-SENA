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
                // Limpiar las tablas existentes
                $('#body-triangular').empty();
                $('#body-duo').empty();
                $('#preferencia-ordenamiento').empty();

                if (response.data) {
                    // Llenar la tabla de la Prueba Triangular
                    response.data.triangulares.forEach(function (item, index) {
                        $('#body-triangular').append(`
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${item.cod_muestra}</td>
                                <td>${item.resultado}</td>
                            </tr>
                        `);
                    });

                    // Llenar la tabla de la Prueba Duo-Trio
                    response.data.duoTrio.forEach(function (item, index) {
                        $('#body-duo').append(`
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${item.cod_muestra}</td>
                                <td>${item.resultado}</td>
                            </tr>
                        `);
                    });

                    // Llenar el resultado de la Prueba de Ordenamiento
                    if (response.data.ordenamiento) {
                        $('#preferencia-ordenamiento').text(response.data.ordenamiento.resultado);
                    }
                } else {
                    Swal.fire('Sin resultados', 'No se encontraron resultados para los criterios seleccionados.', 'info');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'Ocurrió un error al generar los resultados. Intenta nuevamente.', 'error');
            }
        });
    });
});

$(document).ready(function() {
    $('#filtro-resultados').submit(function(e) {
        e.preventDefault();
        
        var cabina = $('#cabinas-filtro').val();
        var fecha = $('#fecha-filtro').val();
        var producto = $('#productos-filtro').val();

        $.ajax({
            url: '/mostrar-resultados',
            method: 'GET',
            data: {
                cabina: cabina,
                fecha: fecha,
                producto: producto
            },
            success: function(response) {
                actualizarResultados(response);
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al obtener los resultados. Por favor, intente de nuevo.'
                });
            }
        });
    });

    function actualizarResultados(data) {
        // Actualizar prueba triangular
        $('#head-triangular').html(`
            <tr>
                <th scope="col">#</th>
                <th scope="col">${data.triangular.muestras[0]}</th>
                <th scope="col">${data.triangular.muestras[1]}</th>
                <th scope="col">${data.triangular.muestras[2]}</th>
            </tr>
        `);
        $('#body-triangular').html(`
            <tr>
                <th scope="row">personas</th>
                <td>${data.triangular.resultados[0]}</td>
                <td>${data.triangular.resultados[1]}</td>
                <td>${data.triangular.resultados[2]}</td>
            </tr>
        `);

        // Actualizar prueba duo-trio
        $('#head-duo').html(`
            <tr>
                <th scope="col">#</th>
                <th scope="col">${data.duoTrio.muestras[0]}</th>
                <th scope="col">${data.duoTrio.muestras[1]}</th>
                <th scope="col">${data.duoTrio.muestras[2]}</th>
            </tr>
        `);
        $('#body-duo').html(`
            <tr>
                <th scope="row">personas</th>
                <td>${data.duoTrio.resultados[0]}</td>
                <td>${data.duoTrio.resultados[1]}</td>
                <td>${data.duoTrio.resultados[2]}</td>
            </tr>
        `);

        // Actualizar prueba de ordenamiento
        $('#atributo-prueba').text(data.ordenamiento.atributo);
        $('#preferencia-ordenamiento').text(data.ordenamiento.preferencia);

        // Mostrar el contenedor de resultados
        $('#resultados-pruebas').show();
    }
});