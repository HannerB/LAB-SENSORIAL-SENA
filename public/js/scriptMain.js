$(document).ready(function () {
    function cambiarFormulario(formIr, formActual) {
        $(`#${formActual}`).addClass('hidden');
        $(`#${formIr}`).removeClass('hidden');
        actualizarProgreso(formIr);
    }

    function actualizarProgreso(seccionActual) {
        const progressBar = document.querySelector('.absolute.top-0.left-0.h-full.bg-sena-green');
        const steps = document.querySelectorAll('.rounded-full');
        let width = 0;
        let paso = 1;

        switch (seccionActual) {
            case 'sect1': width = "0%"; paso = 1; break;
            case 'sect2': width = "50%"; paso = 2; break;
            case 'sect3': width = "100%"; paso = 3; break;
        }

        progressBar.style.width = width;
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

    function validarDatosBasicos(prueba) {
        const datos = {
            1: {
                nombre: $('#nombrePanelistaTriangular').val(),
                fecha: $('#fechaPanelistaTriangular').val()
            },
            2: {
                nombre: $('#nombrePanelistaDuo').val(),
                fecha: $('#fechaPanelistaDuo').val()
            },
            3: {
                nombre: $('#nombrePanelistaOrden').val(),
                fecha: $('#fechaPanelistaOrden').val()
            }
        };

        const data = datos[prueba];
        if (!data.nombre || !data.fecha) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, completa nombre y fecha.',
                confirmButtonColor: '#198754'
            });
            return false;
        }
        return data;
    }

    $("#btnsiguiente1").on('click', function () {
        cambiarFormulario('sect2', 'sect1');
    });

    $("#btnsiguiente2").on('click', function () {
        cambiarFormulario('sect3', 'sect2');
    });

    $("[data-nav]").on('click', function (e) {
        e.preventDefault();
        const [formIr, formActual] = $(this).data('nav').split(',');
        cambiarFormulario(formIr, formActual);
    });

    $("#btnguardar-triangular").on('click', function () {
        if (!validarDatosBasicos(1)) return;
        const esDiferente = $('input[name="muestra_diferente"]:checked').val();
        if (!esDiferente) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, selecciona una muestra.',
                confirmButtonColor: '#198754'
            });
            return;
        }
        guardarCalificacion(1, esDiferente, 'es_diferente', $('#comentario-triangular').val());
    });

    $("#btnguardar-duo").on('click', function () {
        if (!validarDatosBasicos(2)) return;
        const esIgual = $('input[name="muestra_igual_referencia"]:checked').val();
        if (!esIgual) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, selecciona una muestra.',
                confirmButtonColor: '#198754'
            });
            return;
        }
        guardarCalificacion(2, esIgual, 'es_igual_referencia', $('#comentario-duo').val());
    });

    $("#btnguardar-ordenamiento").on('click', function () {
        if (!validarDatosBasicos(3)) return;
        const resultados = formatearResultadosOrdenamiento();
        if (!resultados) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, evalúa todos los atributos de cada muestra',
                confirmButtonColor: '#198754'
            });
            return;
        }
        guardarPruebaOrdenamiento(resultados, $('#comentario-orden').val());
    });

    function formatearResultadosOrdenamiento() {
        const resultados = [];
        let todasCalificadas = true;

        $('#cuerpo-selectores-ordenamiento tr').each(function () {
            const muestra = $(this).find('td:first').text().trim();
            const valores = {
                cod_muestra: muestra,
                valor_sabor: $(`input[name="sabor_${muestra}"]:checked`).val(),
                valor_olor: $(`input[name="olor_${muestra}"]:checked`).val(),
                valor_color: $(`input[name="color_${muestra}"]:checked`).val(),
                valor_textura: $(`input[name="textura_${muestra}"]:checked`).val(),
                valor_apariencia: $(`input[name="apariencia_${muestra}"]:checked`).val()
            };

            Object.entries(valores).forEach(([attr, val]) => {
                if (attr !== 'cod_muestra' &&
                    $(`input[name="${attr.replace('valor_', '')}_${muestra}"]`).length > 0 &&
                    !val) {
                    todasCalificadas = false;
                }
            });

            resultados.push(valores);
        });

        return todasCalificadas ? resultados : null;
    }

    function guardarPruebaOrdenamiento(resultados, comentario) {
        const datos = validarDatosBasicos(3);
        if (!datos) return;

        $.ajax({
            url: window.routes.panelistasStore,
            method: 'POST',
            data: {
                nombres: datos.nombre,
                fecha: datos.fecha,
                _token: window.csrfToken
            },
            success: function (response) {
                if (response.idpane) {
                    let guardadas = 0;
                    resultados.forEach(muestra => {
                        $.ajax({
                            url: window.routes.calificacionStore,
                            method: 'POST',
                            data: {
                                idpane: response.idpane,
                                producto: $('#productoIDPrueba1').val(),
                                prueba: 3,
                                cod_muestra: muestra.cod_muestra,
                                valor_sabor: muestra.valor_sabor,
                                valor_olor: muestra.valor_olor,
                                valor_color: muestra.valor_color,
                                valor_textura: muestra.valor_textura,
                                valor_apariencia: muestra.valor_apariencia,
                                comentario: comentario,
                                fecha: datos.fecha,
                                cabina: $('#cabina').val(),
                                _token: window.csrfToken
                            },
                            success: function () {
                                guardadas++;
                                if (guardadas === resultados.length) {
                                    limpiarFormularioPrueba(3);
                                }
                            },
                            error: () => Swal.fire('Error', 'Error al guardar las calificaciones', 'error')
                        });
                    });
                }
            },
            error: () => Swal.fire('Error', 'Error al guardar el panelista', 'error')
        });
    }

    function guardarCalificacion(prueba, valor, campo, comentario) {
        const datos = validarDatosBasicos(prueba);
        if (!datos) return;

        $.ajax({
            url: window.routes.panelistasStore,
            method: 'POST',
            data: {
                nombres: datos.nombre,
                fecha: datos.fecha,
                _token: window.csrfToken
            },
            success: function (response) {
                if (response.idpane) {
                    const data = {
                        idpane: response.idpane,
                        producto: $('#productoIDPrueba1').val(),
                        prueba: prueba,
                        cod_muestra: valor,
                        comentario: comentario,
                        fecha: datos.fecha,
                        cabina: $('#cabina').val(),
                        _token: window.csrfToken
                    };
                    data[campo] = true;

                    $.ajax({
                        url: window.routes.calificacionStore,
                        method: 'POST',
                        data: data,
                        success: () => limpiarFormularioPrueba(prueba),
                        error: () => Swal.fire('Error', 'Error al guardar la calificación', 'error')
                    });
                }
            },
            error: () => Swal.fire('Error', 'Error al guardar el panelista', 'error')
        });
    }

    function limpiarFormularioPrueba(prueba) {
        switch (prueba) {
            case 1:
                $('#nombrePanelistaTriangular').val('');
                $('#fechaPanelistaTriangular').val('');
                $('input[name="muestra_diferente"]').prop('checked', false);
                $('#comentario-triangular').val('');
                break;
            case 2:
                $('#nombrePanelistaDuo').val('');
                $('#fechaPanelistaDuo').val('');
                $('input[name="muestra_igual_referencia"]').prop('checked', false);
                $('#comentario-duo').val('');
                break;
            case 3:
                $('#nombrePanelistaOrden').val('');
                $('#fechaPanelistaOrden').val('');
                $('input[type="radio"]').prop('checked', false);
                $('#comentario-orden').val('');
                break;
        }

        Swal.fire({
            icon: 'success',
            title: '¡Prueba guardada!',
            confirmButtonColor: '#198754',
            timer: 2000,
            timerProgressBar: true
        });
    }

    actualizarProgreso('sect1');
});