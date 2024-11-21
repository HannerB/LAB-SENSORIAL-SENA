$(document).ready(function () {
    function cambiarFormulario(formIr, formActual) {
        // Ocultar sección actual
        $(`#${formActual}`).addClass('hidden');
        // Mostrar siguiente sección
        $(`#${formIr}`).removeClass('hidden');

        // Actualizar barra de progreso
        actualizarProgreso(formIr);
    }

    function actualizarProgreso(seccionActual) {
        const progressBar = document.querySelector('.absolute.top-0.left-0.h-full.bg-sena-green');
        const steps = document.querySelectorAll('.rounded-full');
        let width = 0;
        let paso = 1;

        switch (seccionActual) {
            case 'sect1':
                width = "0%";
                paso = 1;
                break;
            case 'sect2':
                width = "50%";
                paso = 2;
                break;
            case 'sect3':
                width = "100%";
                paso = 3;
                break;
        }

        progressBar.style.width = width;

        // Actualizar estados de los círculos
        steps.forEach((step, index) => {
            if (index < paso) {
                step.classList.remove('bg-gray-300');
                step.classList.add('bg-sena-green');
            } else {
                step.classList.remove('bg-sena-green');
                step.classList.add('bg-gray-300');
            }
        });
    }

    // Eventos de navegación
    $("#btnsiguiente1").on('click', function () {
        cambiarFormulario('sect2', 'sect1');
    });

    $("#btnsiguiente2").on('click', function () {
        cambiarFormulario('sect3', 'sect2');
    });

    // Botones de retroceso
    $("[onclick*='cambiarFormulario']").on('click', function (e) {
        e.preventDefault();
        const [formIr, formActual] = $(this).attr('onclick')
            .match(/cambiarFormulario\('(.+)',\s*'(.+)'\)/i)
            .slice(1);
        cambiarFormulario(formIr, formActual);
    });

    // PRUEBA DE ORDENAMIENTO - EVITAR DUPLICADOS EN SELECCIÓN
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
    $('#btnguardar-todo').on('click', function () {
        guardarTodasLasPruebas();
    });

    function guardarTodasLasPruebas() {
        const nombrePanelista = $('#nombrePanelista1').val();
        const fechaPanelista = $('#fechaPanelista1').val();
        const productoID = $('#productoIDPrueba1').val();
        const cabinaSeleccionada = $('#cabina').val();

        if (!nombrePanelista || !fechaPanelista) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, completa el nombre y la fecha del panelista.',
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
                    guardarCalificaciones(response.idpane, productoID, fechaPanelista, cabinaSeleccionada);
                } else {
                    Swal.fire('Error', 'No se pudo guardar la información del panelista', 'error');
                }
            },
            error: function (xhr) {
                Swal.fire('Error', 'No se pudo guardar la información del panelista', 'error');
            }
        });
    }

    function guardarCalificaciones(idpane, productoID, fechaPanelista, cabinaSeleccionada) {
        $.ajax({
            url: '/muestras/' + productoID,
            type: 'GET',
            success: function (response) {
                const atributos = {
                    1: response.triangular.length > 0 ? response.triangular[0].atributo : 'Dulzura',
                    2: response.duo_trio.length > 0 ? response.duo_trio[0].atributo : 'Similaridad',
                    3: response.ordenamiento.length > 0 ? response.ordenamiento[0].atributo : 'Dulzura'
                };

                const calificaciones = [{
                    prueba: 1,
                    codMuestras: $('input[name="muestra_diferente"]:checked').val(),
                    atributo: atributos[1],
                    comentario: $('#comentario-triangular').val(),
                    cabina: cabinaSeleccionada
                }, {
                    prueba: 2,
                    codMuestras: $('input[name="muestra_igual_referencia"]:checked').val(),
                    atributo: atributos[2],
                    comentario: $('#comentario-duo').val(),
                    cabina: cabinaSeleccionada
                }, {
                    prueba: 3,
                    codMuestras: formatearResultadosOrdenamiento(),
                    atributo: atributos[3],
                    comentario: $('#comentario-orden').val(),
                    cabina: cabinaSeleccionada
                }];

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
                            success: function () {
                                calificacionesGuardadas++;
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
                            error: function (xhr) {
                                Swal.fire('Error', 'Hubo un problema al guardar los resultados', 'error');
                            }
                        });
                    }
                });
            },
            error: function (xhr) {
                Swal.fire('Error', 'Hubo un problema al obtener los atributos de las muestras', 'error');
            }
        });
    }

    function formatearResultadosOrdenamiento() {
        const resultadosTemp = [];
        const selects = document.querySelectorAll('.orden-muestra');

        selects.forEach(function (select) {
            const codMuestra = select.closest('tr').querySelector('td:first-child').textContent.trim();
            const orden = parseInt(select.value);
            if (orden) {
                resultadosTemp.push({
                    codigo: codMuestra,
                    orden: orden,
                });
            }
        });

        resultadosTemp.sort((a, b) => a.orden - b.orden);
        return resultadosTemp.map(item => item.codigo).join(',');
    }

    // Inicialización
    actualizarOpciones();
    actualizarProgreso('sect1');
});