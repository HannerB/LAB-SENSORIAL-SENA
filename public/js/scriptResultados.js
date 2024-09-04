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
