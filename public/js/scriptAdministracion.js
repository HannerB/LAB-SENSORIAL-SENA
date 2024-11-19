document.addEventListener('DOMContentLoaded', function () {
    const formProductoModal = document.getElementById('form-producto-modal');
    const btnSubmit = document.getElementById('btn-submit');
    const formMuestras = document.getElementById('form-muestras');
    const modalConfiguracion = document.getElementById('modalConfiguracion');
    const btnCerrarModal = modalConfiguracion.querySelector('[data-bs-dismiss="modal"]');

    function validarCantidadMuestrasOrdenamiento() {
        const cantidadMuestras = document.querySelectorAll('#cuerpo-table-tres tr').length;
        const cantidadesValidas = [0, 3, 6, 10];
        return cantidadesValidas.includes(cantidadMuestras);
    }

    modalConfiguracion.addEventListener('hide.bs.modal', function (e) {
        if (!validarCantidadMuestrasOrdenamiento()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Cantidad de muestras incorrecta',
                text: 'Para cerrar el modal, la prueba de ordenamiento debe tener 0, 3, 6 o 10 muestras.',
                confirmButtonColor: '#198754'
            });
        }
    });

    // Mostrar/ocultar selector de atributo según el tipo de prueba
    $('#tipo-prueba').on('change', function () {
        const tipoPrueba = $(this).val();
        const atributoContainer = $('#atributo-container');

        if (tipoPrueba === '3') { // Prueba de ordenamiento
            atributoContainer.show();
        } else {
            atributoContainer.hide();
            $('#atributo').val(''); // Resetear valor
        }
    });

    // Manejar la actualización de atributos
    $('#atributo').on('change', function () {
        const atributo = $(this).val();
        const productoId = $('#producto-id-muestra').val();

        if (atributo && productoId) {
            actualizarAtributoMuestras(productoId, atributo);
        }
    });

    function abrirConfiguracion(idProducto, nombreProducto) {
        document.getElementById('productoId').value = idProducto;
        document.getElementById('nombreProductoModal').value = nombreProducto;
        document.getElementById('producto-id-muestra').value = idProducto;

        formProductoModal.action = formProductoModal.action.replace(/\/\d+$/, '/' + idProducto);
        new bootstrap.Modal(document.getElementById('modalConfiguracion')).show();
    }

    btnSubmit.addEventListener('click', function () {
        const productoId = document.getElementById('productoId').value;
        const nombre = document.getElementById('nombreProductoModal').value;

        fetch(`/productos/${productoId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                _method: 'PUT',
                nombre: nombre
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Nombre del producto actualizado');
                    const productoRow = document.querySelector(`tr[data-id="${productoId}"]`);
                    if (productoRow) {
                        productoRow.querySelector('td:nth-child(2)').textContent = nombre;
                    }
                } else {
                    console.error('Error al actualizar el nombre del producto:', data.message);
                    alert('Error al actualizar el nombre del producto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el nombre del producto');
            });

        bootstrap.Modal.getInstance(document.getElementById('modalConfiguracion')).hide();
        setTimeout(() => location.reload(), 1000);
    });

    formProductoModal.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            btnSubmit.click();
        }
    });

    document.querySelectorAll('.btn-configuracion').forEach(btn => {
        btn.addEventListener('click', function () {
            const idProducto = this.dataset.id;
            const nombreProducto = this.dataset.nombre;
            abrirConfiguracion(idProducto, nombreProducto);
            cargarMuestras(idProducto);
        });
    });

    document.querySelectorAll('.btn-habilitar').forEach(btn => {
        btn.addEventListener('click', function () {
            const idProducto = this.dataset.id;
            const habilitado = this.dataset.habilitado === 'true';
            const nuevoEstado = !habilitado;

            document.querySelectorAll('.btn-habilitar').forEach(button => {
                if (button !== this) {
                    button.textContent = 'Habilitar';
                    button.dataset.habilitado = 'false';
                }
            });

            fetch(rutaActualizarConfiguracion, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    _method: 'PUT',
                    producto_habilitado: nuevoEstado ? idProducto : null
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Estado del producto actualizado');
                        this.textContent = nuevoEstado ? 'Deshabilitar' : 'Habilitar';
                        this.dataset.habilitado = nuevoEstado;
                    } else {
                        console.error('Error al actualizar el estado del producto:', data.message);
                        alert('Error al actualizar el estado del producto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el estado del producto');
                });
        });
    });

    formMuestras.addEventListener('submit', function (e) {
        e.preventDefault();

        const tipoPrueba = document.getElementById('tipo-prueba').value;
        const cantidadMuestras = document.querySelectorAll('#cuerpo-table-tres tr').length;

        // Validar cantidad de muestras para prueba de ordenamiento
        if (tipoPrueba === '3') {
            if (cantidadMuestras >= 10) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite alcanzado',
                    text: 'Ya has alcanzado el máximo de 10 muestras para la prueba de ordenamiento.',
                    confirmButtonColor: '#198754'
                });
                return;
            }

            // Validar si la siguiente muestra excedería los límites permitidos
            const siguienteCantidad = cantidadMuestras + 1;
            if (![3, 6, 10].includes(siguienteCantidad) && siguienteCantidad > 3) {
                const siguienteValido = [3, 6, 10].find(num => num > cantidadMuestras);
                Swal.fire({
                    icon: 'info',
                    title: 'Cantidad de muestras',
                    text: `Para la prueba de ordenamiento, debes agregar muestras hasta llegar a ${siguienteValido} muestras.`,
                    confirmButtonColor: '#198754'
                });
            }
        }

        const formData = new FormData(formMuestras);

        fetch(formMuestras.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formMuestras.reset();
                    cargarMuestras(document.getElementById('producto-id-muestra').value);
                    Swal.fire('Éxito', 'Muestra agregada correctamente', 'success');
                } else {
                    console.error('Error al agregar la muestra:', data);
                    Swal.fire('Error', `No se pudo agregar la muestra: ${data.message}`, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Ocurrió un error inesperado al agregar la muestra', 'error');
            });
    });

    function cargarMuestras(idProducto) {
        fetch(`/muestras/${idProducto}`)
            .then(response => response.json())
            .then(data => {
                // Limpiar tablas
                document.getElementById('cuerpo-table-uno').innerHTML = '';
                document.getElementById('cuerpo-table-dos').innerHTML = '';
                document.getElementById('cuerpo-table-tres').innerHTML = '';

                // Cargar datos prueba triangular
                data.triangular.forEach((muestra, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${muestra.cod_muestra}</td>
                        <td>
                            <button class="btn btn-danger btn-eliminar-muestra" data-id="${muestra.id_muestras}">Eliminar</button>
                        </td>
                    `;
                    document.getElementById('cuerpo-table-uno').appendChild(row);
                });

                // Cargar datos prueba duo-trio
                data.duo_trio.forEach((muestra, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${muestra.cod_muestra}</td>
                        <td>
                            <button class="btn btn-danger btn-eliminar-muestra" data-id="${muestra.id_muestras}">Eliminar</button>
                        </td>
                    `;
                    document.getElementById('cuerpo-table-dos').appendChild(row);
                });

                // Cargar datos prueba ordenamiento
                data.ordenamiento.forEach((muestra, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${muestra.cod_muestra}</td>
                        <td>${muestra.atributo || 'No especificado'}</td>
                        <td>
                            <button class="btn btn-danger btn-eliminar-muestra" data-id="${muestra.id_muestras}">Eliminar</button>
                        </td>
                    `;
                    document.getElementById('cuerpo-table-tres').appendChild(row);
                });

                // Asignar eventos a los nuevos botones
                document.querySelectorAll('.btn-eliminar-muestra').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.dataset.id;
                        eliminarMuestra(id);
                    });
                });
            })
            .catch(error => console.error('Error al cargar las muestras:', error));
    }

    function eliminarMuestra(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/muestra/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Eliminado!',
                                'La muestra ha sido eliminada.',
                                'success'
                            );
                            cargarMuestras(document.getElementById('producto-id-muestra').value);
                        } else {
                            Swal.fire(
                                'Error',
                                'No se pudo eliminar la muestra: ' + data.message,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error',
                            'Ocurrió un error al eliminar la muestra',
                            'error'
                        );
                    });
            }
        });
    }

    function actualizarAtributoMuestras(productoId, atributo) {
        $.ajax({
            url: '/muestras/actualizar-atributo',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                producto_id: productoId,
                atributo: atributo
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Atributo actualizado',
                        text: 'El atributo ha sido actualizado para todas las muestras de ordenamiento',
                        confirmButtonColor: '#198754'
                    });

                    // Recargar las muestras en la tabla
                    cargarMuestras(productoId);
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el atributo de las muestras',
                    confirmButtonColor: '#198754'
                });
            }
        });
    }

    // Validación al intentar cerrar el panel
    btnCerrarPanel.addEventListener('click', function (e) {
        e.preventDefault();

        const modalAbierto = document.querySelector('.modal.show');
        if (modalAbierto && !validarCantidadMuestrasOrdenamiento()) {
            Swal.fire({
                icon: 'warning',
                title: 'Cantidad de muestras incorrecta',
                text: 'Antes de cerrar el panel, la prueba de ordenamiento debe tener 0, 3, 6 o 10 muestras.',
                confirmButtonColor: '#198754'
            });
            return;
        }

        window.location.href = "/";
    });

    // Agregar manejador para el botón de guardar cambios
    $('#btnguardar').click(function (e) {
        e.preventDefault();

        const numCabina = parseInt($('#cabina').val());

        // Validar el número de cabina
        if (isNaN(numCabina) || numCabina < 1 || numCabina > 3) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El número de cabina debe estar entre 1 y 3',
                confirmButtonColor: '#198754'
            });
            return;
        }

        // Actualizar la configuración
        $.ajax({
            url: rutaActualizarConfiguracion,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _method: 'PUT',
                num_cabina: numCabina
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado exitoso!',
                        text: 'El número de cabina ha sido actualizado.',
                        confirmButtonColor: '#198754'
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el número de cabina.',
                    confirmButtonColor: '#198754'
                });
            }
        });
    });
});

$(document).ready(function () {
    // Agregar manejador para el botón de guardar cambios
    $('#btnguardar').click(function (e) {
        e.preventDefault();

        const numCabina = parseInt($('#cabina').val());

        // Validar el número de cabina
        if (isNaN(numCabina) || numCabina < 1 || numCabina > 3) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El número de cabina debe estar entre 1 y 3',
                confirmButtonColor: '#198754'
            });
            return;
        }

        // Actualizar la configuración
        $.ajax({
            url: rutaActualizarConfiguracion,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _method: 'PUT',
                num_cabina: numCabina
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado exitoso!',
                        text: 'El número de cabina ha sido actualizado.',
                        confirmButtonColor: '#198754'
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo actualizar el número de cabina.',
                    confirmButtonColor: '#198754'
                });
            }
        });
    });
});