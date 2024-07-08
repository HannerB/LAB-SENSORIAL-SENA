$('#btnguardar-tri').on('click', function() {
    $.post('{{ route('pruebas.guardarTri') }}', {
        nombre: $('#nombrePanelista1').val(),
        fecha: $('#fechaPanelista1').val(),
        producto: $('#productoPrueba1').val(),
        comentario: $('#comentario-triangular').val(),
        // otros datos
    }, function(response) {
        Swal.fire('Guardado!', 'Datos guardados correctamente.', 'success');
    });
});

$('#btnguardar-duo').on('click', function() {
    $.post('{{ route('pruebas.guardarDuo') }}', {
        nombre: $('#nombrePanelista2').val(),
        fecha: $('#fechaPanelista2').val(),
        producto: $('#productoPrueba2').val(),
        comentario: $('#comentario-duo').val(),
        // otros datos
    }, function(response) {
        Swal.fire('Guardado!', 'Datos guardados correctamente.', 'success');
    });
});

$('#btnguardar-respuesta-orden').on('click', function() {
    $.post('{{ route('pruebas.guardarOrden') }}', {
        nombre: $('#nombrePanelista3').val(),
        fecha: $('#fechaPanelista3').val(),
        producto: $('#productoPrueba3').val(),
        comentario: $('#comentario-orden').val(),
        // otros datos
    }, function(response) {
        Swal.fire('Guardado!', 'Datos guardados correctamente.', 'success');
    });
});

