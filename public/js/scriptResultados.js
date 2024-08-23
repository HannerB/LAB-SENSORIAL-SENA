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