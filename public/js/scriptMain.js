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

    // Manejo de ordenamiento
    $('.orden-muestra').on('change', actualizarOpciones);

    function actualizarOpciones() {
        let seleccionados = [];
        $('.orden-muestra').each(function () {
            let valor = $(this).val();
            if (valor) seleccionados.push(valor);
        });

        $('.orden-muestra').each(function () {
            let $this = $(this);
            let valorSeleccionado = $this.val();
            $this.find('option').each(function () {
                let valorOpcion = $(this).attr('value');
                $(this).prop('disabled', seleccionados.includes(valorOpcion) && valorOpcion !== valorSeleccionado);
            });
        });
    }

    // Validación de datos básicos
    function validarDatosBasicos() {
        const nombrePanelista = $('#nombrePanelista1').val();
        const fechaPanelista = $('#fechaPanelista1').val();

        if (!nombrePanelista || !fechaPanelista) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, completa el nombre y la fecha del panelista.',
                confirmButtonColor: '#198754'
            });
            return false;
        }
        return true;
    }

    // Guardado individual de pruebas
    $("#btnguardar-triangular").on('click', function () {
        if (!validarDatosBasicos()) return;
        const muestraSeleccionada = $('input[name="muestra_diferente"]:checked').val();
        if (!muestraSeleccionada) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, selecciona una muestra.',
                confirmButtonColor: '#198754'
            });
            return;
        }
        guardarPrueba(1, muestraSeleccionada, $('#comentario-triangular').val());
    });

    $("#btnguardar-duo").on('click', function () {
        if (!validarDatosBasicos()) return;
        const muestraSeleccionada = $('input[name="muestra_igual_referencia"]:checked').val();
        if (!muestraSeleccionada) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, selecciona una muestra.',
                confirmButtonColor: '#198754'
            });
            return;
        }
        guardarPrueba(2, muestraSeleccionada, $('#comentario-duo').val());
    });

    $("#btnguardar-ordenamiento").on('click', function () {
        if (!validarDatosBasicos()) return;
        const ordenMuestras = formatearResultadosOrdenamiento();
        if (!ordenMuestras) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, completa el orden de todas las muestras.',
                confirmButtonColor: '#198754'
            });
            return;
        }
        guardarPrueba(3, ordenMuestras, $('#comentario-orden').val());
    });

    function guardarPrueba(tipoPrueba, codMuestras, comentario) {
        const nombrePanelista = $('#nombrePanelista1').val();
        const fechaPanelista = $('#fechaPanelista1').val();
        const productoID = $('#productoIDPrueba1').val();
        const cabinaSeleccionada = $('#cabina').val();

        $.ajax({
            url: '/muestras/' + productoID,
            type: 'GET',
            success: function (response) {
                const atributo = response[tipoPrueba === 1 ? 'triangular' : tipoPrueba === 2 ? 'duo_trio' : 'ordenamiento'][0]?.atributo
                    || (tipoPrueba === 1 ? 'Dulzura' : tipoPrueba === 2 ? 'Similaridad' : 'Dulzura');

                guardarCalificacionIndividual({
                    nombrePanelista,
                    fechaPanelista,
                    productoID,
                    tipoPrueba,
                    codMuestras,
                    comentario,
                    cabinaSeleccionada,
                    atributo
                });
            },
            error: function () {
                Swal.fire('Error', 'Hubo un problema al obtener los atributos de las muestras', 'error');
            }
        });
    }

    function guardarCalificacionIndividual(datos) {
        $.ajax({
            url: window.routes.panelistasStore,
            type: 'POST',
            data: {
                nombres: datos.nombrePanelista,
                fecha: datos.fechaPanelista,
                _token: window.csrfToken
            },
            success: function (response) {
                if (response.idpane) {
                    $.ajax({
                        url: window.routes.calificacionStore,
                        type: 'POST',
                        data: {
                            idpane: response.idpane,
                            producto: datos.productoID,
                            prueba: datos.tipoPrueba,
                            atributo: datos.atributo,
                            cod_muestras: datos.codMuestras,
                            comentario: datos.comentario,
                            fecha: datos.fechaPanelista,
                            cabina: datos.cabinaSeleccionada,
                            _token: window.csrfToken
                        },
                        success: function () {
                            limpiarFormularioPrueba(datos.tipoPrueba);
                        },
                        error: function () {
                            Swal.fire('Error', 'Hubo un problema al guardar la calificación', 'error');
                        }
                    });
                }
            },
            error: function () {
                Swal.fire('Error', 'No se pudo guardar la información del panelista', 'error');
            }
        });
    }

    function limpiarFormularioPrueba(tipoPrueba) {
        // Limpiar datos básicos del panelista
        $('#nombrePanelista1').val('');
        $('#fechaPanelista1').val('');

        // Limpiar datos específicos de cada prueba
        switch (tipoPrueba) {
            case 1:
                $('input[name="muestra_diferente"]').prop('checked', false);
                $('#comentario-triangular').val('');
                break;
            case 2:
                $('input[name="muestra_igual_referencia"]').prop('checked', false);
                $('#comentario-duo').val('');
                break;
            case 3:
                $('.orden-muestra').val('');
                $('#comentario-orden').val('');
                actualizarOpciones();
                break;
        }

        // Mostrar mensaje de éxito y confirmación de limpieza
        Swal.fire({
            icon: 'success',
            title: '¡Prueba guardada!',
            // text: 'Los datos han sido limpiados. Puede iniciar una nueva evaluación.',
            confirmButtonColor: '#198754',
            timer: 2000,
            timerProgressBar: true
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

        if (resultadosTemp.length === 0) return null;

        resultadosTemp.sort((a, b) => a.orden - b.orden);
        return resultadosTemp.map(item => item.codigo).join(',');
    }

    // Inicialización
    actualizarOpciones();
    actualizarProgreso('sect1');
});