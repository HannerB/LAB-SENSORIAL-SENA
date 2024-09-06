$("#btnsiguiente1").on('click',function(){
    cambiarFormulario('sect2','sect1')
    
})

$("#btnsiguiente2").on('click',function(){
    cambiarFormulario('sect3','sect2')
})

//FUNCIONES
function cambiarFormulario(formIr,formActual) {
    document.getElementById(formActual).classList.remove('active');
    document.getElementById(formIr).classList.add('active');
}

// PRUEBA DE ORDENAMIENTO - EVITAR DUPLICADOS EN SELECCIÓN
$(document).ready(function() {
    // Detectar cuando se cambia un valor en cualquier selector
    $('.orden-muestra').on('change', function() {
        actualizarOpciones();
    });

    function actualizarOpciones() {
        // Recoger todos los valores seleccionados actualmente
        let seleccionados = [];

        $('.orden-muestra').each(function() {
            let valor = $(this).val();
            if (valor) {
                seleccionados.push(valor); // Guardar los valores seleccionados
            }
        });

        // Actualizar las opciones de cada selector en base a los seleccionados
        $('.orden-muestra').each(function() {
            let $this = $(this);
            let valorSeleccionado = $this.val();

            // Limpiar las opciones disponibles, excepto la seleccionada
            $this.find('option').each(function() {
                let valorOpcion = $(this).attr('value');

                // Habilitar todas las opciones primero
                $(this).prop('disabled', false);

                // Deshabilitar las opciones seleccionadas en otros selects
                if (seleccionados.includes(valorOpcion) && valorOpcion !==
                    valorSeleccionado) {
                    $(this).prop('disabled', true);
                }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btnguardar-todo').addEventListener('click', function() {
        guardarTodasLasPruebas();
    });

    function guardarTodasLasPruebas() {
        var nombrePanelista = document.getElementById('nombrePanelista1').value ||
            document.getElementById('nombrePanelista2').value ||
            document.getElementById('nombrePanelista3').value;
        var fechaPanelista = document.getElementById('fechaPanelista1').value ||
            document.getElementById('fechaPanelista2').value ||
            document.getElementById('fechaPanelista3').value;
        var productoID = document.getElementById('productoIDPrueba1').value;

        if (!nombrePanelista || !fechaPanelista) {
            alert('Por favor, completa el nombre y la fecha del panelista.');
            return;
        }

        if (!productoID) {
            alert('Error: El campo de producto está vacío.');
            return;
        }

        if (isNaN(productoID)) {
            alert('Error: El valor del producto no es un ID válido.');
            return;
        }

        // Guardar datos del panelista
        $.ajax({
            url: window.routes.panelistasStore,
            type: 'POST',
            data: {
                nombres: nombrePanelista,
                fecha: fechaPanelista,
                _token: window.csrfToken
            },
            success: function(response) {
                if (response.idpane) {
                    var idpane = response.idpane;
                    guardarCalificaciones(idpane, productoID, fechaPanelista);
                } else {
                    console.error('ID de panelista no retornado');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error guardando datos del panelista:', xhr.responseText);
            }
        });
    }

    function guardarCalificaciones(idpane, productoID, fechaPanelista) {
        var calificaciones = [{
                prueba: 1,
                codMuestras: document.querySelector('input[name="muestra_diferente"]:checked')?.value,
                atributo: 'Dulzura',
                comentario: document.getElementById('comentario-triangular').value,
                cabina: 1
            },
            {
                prueba: 2,
                codMuestras: document.querySelector('input[name="muestra_igual_referencia"]:checked')?.value,
                atributo: 'Similaridad',
                comentario: document.getElementById('comentario-duo').value,
                cabina: 2
            },
            {
                prueba: 3,
                codMuestras: formatearResultadosOrdenamiento(),
                atributo: 'Dulzura',
                comentario: document.getElementById('comentario-orden').value,
                cabina: 3
            }
        ];

        calificaciones.forEach(function(cal) {
            if (cal.codMuestras) {
                $.ajax({
                    url: window.routes.calificacionStore,
                    type: 'POST',
                    data: {
                        idpane: idpane,
                        producto: productoID,
                        prueba: cal.prueba,
                        atributo: cal.atributo,
                        cod_muestras: cal.codMuestras,
                        comentario: cal.comentario,
                        fecha: fechaPanelista,
                        cabina: cal.cabina,
                        _token: window.csrfToken
                    },
                    success: function(response) {
                        console.log(
                            'Datos de calificación guardados correctamente para la prueba ' +
                            cal.prueba);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error guardando datos de calificación para la prueba ' + cal
                            .prueba + ':', xhr.responseText);
                    }
                });
            }
        });

        alert('Todas las calificaciones han sido guardadas.');
    }

    function formatearResultadosOrdenamiento() {
        var resultados = [];
        var selects = document.querySelectorAll('.orden-muestra');

        // Crear un array para almacenar los resultados temporalmente
        var resultadosTemp = [];

        selects.forEach(function(select) {
            var codMuestra = select.closest('tr').querySelector('td:first-child').textContent.trim();
            var orden = parseInt(select.value);
            if (orden) {
                resultadosTemp.push({
                    codigo: codMuestra,
                    orden: orden,
                });
            }
        });

        // Ordenar los resultados por el orden seleccionado
        resultadosTemp.sort((a, b) => a.orden - b.orden);

        // Crear la cadena final en el orden correcto
        resultados = resultadosTemp.map(item => `${item.codigo}`);

        return resultados.join(',');
    }
});
