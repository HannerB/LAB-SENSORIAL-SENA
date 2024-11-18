$("#btnsiguiente1").on('click', function () {
    cambiarFormulario('sect2', 'sect1')

})

$("#btnsiguiente2").on('click', function () {
    cambiarFormulario('sect3', 'sect2')
})

//FUNCIONES
function cambiarFormulario(formIr, formActual) {
    document.getElementById(formActual).classList.remove('active');
    document.getElementById(formIr).classList.add('active');
}

// PRUEBA DE ORDENAMIENTO - EVITAR DUPLICADOS EN SELECCIÓN
$(document).ready(function () {
    // Detectar cuando se cambia un valor en cualquier selector
    $('.orden-muestra').on('change', function () {
        actualizarOpciones();
    });

    function actualizarOpciones() {
        // Recoger todos los valores seleccionados actualmente
        let seleccionados = [];

        $('.orden-muestra').each(function () {
            let valor = $(this).val();
            if (valor) {
                seleccionados.push(valor); // Guardar los valores seleccionados
            }
        });

        // Actualizar las opciones de cada selector en base a los seleccionados
        $('.orden-muestra').each(function () {
            let $this = $(this);
            let valorSeleccionado = $this.val();

            // Limpiar las opciones disponibles, excepto la seleccionada
            $this.find('option').each(function () {
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

// Variable global para la cabina
let cabinaSeleccionada = null;

// Eventos de navegación
$("#btnsiguiente1").on('click', function () {
    if (!validarCabina()) return;
    cambiarFormulario('sect2', 'sect1');
});

$("#btnsiguiente2").on('click', function () {
    cambiarFormulario('sect3', 'sect2');
});

// Función para validar selección de cabina
function validarCabina() {
    if (!cabinaSeleccionada) {
        Swal.fire({
            icon: 'warning',
            title: 'Cabina no seleccionada',
            text: 'Por favor, seleccione una cabina antes de continuar.',
            confirmButtonColor: '#198754'
        });
        return false;
    }
    return true;
}

// Función para cambiar entre formularios
function cambiarFormulario(formIr, formActual) {
    document.getElementById(formActual).classList.remove('active');
    document.getElementById(formIr).classList.add('active');
}

// PRUEBA DE ORDENAMIENTO - EVITAR DUPLICADOS EN SELECCIÓN
$(document).ready(function () {
    // Evento para el selector de cabina
    cabinaSeleccionada = parseInt($('#cabina').val());

    // Detectar cuando se cambia un valor en cualquier selector de orden
    $('.orden-muestra').on('change', function () {
        actualizarOpciones();
    });

    function actualizarOpciones() {
        let seleccionados = [];

        $('.orden-muestra').each(function () {
            let valor = $(this).val();
            if (valor) {
                seleccionados.push(valor);
            }
        });

        $('.orden-muestra').each(function () {
            let $this = $(this);
            let valorSeleccionado = $this.val();

            $this.find('option').each(function () {
                let valorOpcion = $(this).attr('value');
                $(this).prop('disabled', false);

                if (seleccionados.includes(valorOpcion) && valorOpcion !== valorSeleccionado) {
                    $(this).prop('disabled', true);
                }
            });
        });
    }

    // Manejo del guardado de datos
    document.getElementById('btnguardar-todo').addEventListener('click', function () {
        if (!validarCabina()) return;
        guardarTodasLasPruebas();
    });

    function guardarTodasLasPruebas() {
        var nombrePanelista = document.getElementById('nombrePanelista1').value;
        var fechaPanelista = document.getElementById('fechaPanelista1').value;
        var productoID = document.getElementById('productoIDPrueba1').value;

        if (!nombrePanelista || !fechaPanelista) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, completa el nombre y la fecha del panelista.',
                confirmButtonColor: '#198754'
            });
            return;
        }

        if (!productoID || isNaN(productoID)) {
            Swal.fire({
                icon: 'error',
                title: 'Error de producto',
                text: 'El producto seleccionado no es válido.',
                confirmButtonColor: '#198754'
            });
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
            success: function (response) {
                if (response.idpane) {
                    guardarCalificaciones(response.idpane, productoID, fechaPanelista);
                } else {
                    console.error('ID de panelista no retornado');
                    Swal.fire('Error', 'No se pudo guardar la información del panelista', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error guardando datos del panelista:', xhr.responseText);
                Swal.fire('Error', 'No se pudo guardar la información del panelista', 'error');
            }
        });
    }

    function guardarCalificaciones(idpane, productoID, fechaPanelista) {
        var calificaciones = [{
            prueba: 1,
            codMuestras: document.querySelector('input[name="muestra_diferente"]:checked')?.value,
            atributo: 'Dulzura',
            comentario: document.getElementById('comentario-triangular').value,
            cabina: cabinaSeleccionada
        },
        {
            prueba: 2,
            codMuestras: document.querySelector('input[name="muestra_igual_referencia"]:checked')?.value,
            atributo: 'Similaridad',
            comentario: document.getElementById('comentario-duo').value,
            cabina: cabinaSeleccionada
        },
        {
            prueba: 3,
            codMuestras: formatearResultadosOrdenamiento(),
            atributo: 'Dulzura',
            comentario: document.getElementById('comentario-orden').value,
            cabina: cabinaSeleccionada
        }
        ];

        let calificacionesGuardadas = 0;
        const totalCalificaciones = calificaciones.filter(cal => cal.codMuestras).length;

        calificaciones.forEach(function (cal) {
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
                    success: function (response) {
                        calificacionesGuardadas++;
                        console.log('Datos guardados para prueba ' + cal.prueba);

                        if (calificacionesGuardadas === totalCalificaciones) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Guardado exitoso!',
                                text: 'Todas las calificaciones han sido guardadas correctamente.',
                                confirmButtonColor: '#198754'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error en prueba ' + cal.prueba + ':', xhr.responseText);
                        Swal.fire('Error', 'Hubo un problema al guardar los resultados', 'error');
                    }
                });
            }
        });
    }

    function formatearResultadosOrdenamiento() {
        var resultados = [];
        var resultadosTemp = [];
        var selects = document.querySelectorAll('.orden-muestra');

        selects.forEach(function (select) {
            var codMuestra = select.closest('tr').querySelector('td:first-child').textContent.trim();
            var orden = parseInt(select.value);
            if (orden) {
                resultadosTemp.push({
                    codigo: codMuestra,
                    orden: orden,
                });
            }
        });

        resultadosTemp.sort((a, b) => a.orden - b.orden);
        resultados = resultadosTemp.map(item => item.codigo);
        return resultados.join(',');
    }
});