document.addEventListener('DOMContentLoaded', function () {
    // Configuración inicial y selectores principales
    const formProductoModal = document.getElementById('form-producto-modal');
    const btnSubmit = document.getElementById('btn-submit');
    const formMuestras = document.getElementById('form-muestras');
    const modalConfiguracion = document.getElementById('modalConfiguracion');
    const btnGuardar = document.getElementById('btnguardar');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Configuración de tablas
    const tableBodies = {
        uno: document.getElementById('cuerpo-table-uno'),
        dos: document.getElementById('cuerpo-table-dos'),
        tres: document.getElementById('cuerpo-table-tres')
    };

    // Función helper para fetch
    const fetchWithCsrf = (url, options = {}) => {
        return fetch(url, {
            ...options,
            headers: {
                ...options.headers,
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
    };

    // Función para validar cantidad de muestras de ordenamiento
    function validarCantidadMuestrasOrdenamiento() {
        const cantidadMuestras = document.querySelectorAll('#cuerpo-table-tres tr').length;
        const cantidadesValidas = [0, 3, 6, 10];
        return cantidadesValidas.includes(cantidadMuestras);
    }

    // Función para manejar estado de carga
    function setLoadingState(button, loading) {
        if (loading) {
            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            const originalContent = button.innerHTML;
            button.setAttribute('data-original-content', originalContent);
            button.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Procesando...
            `;
        } else {
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
            const originalContent = button.getAttribute('data-original-content');
            if (originalContent) button.innerHTML = originalContent;
        }
    }

    // Funciones del modal
    function mostrarModal() {
        modalConfiguracion.classList.remove('hidden');
        setTimeout(() => {
            const modalContent = modalConfiguracion.querySelector('.transform');
            modalContent.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            modalContent.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
        document.body.classList.add('overflow-hidden');
    }

    function ocultarModal() {
        if (!validarCantidadMuestrasOrdenamiento()) {
            Swal.fire({
                icon: 'warning',
                title: 'Cantidad de muestras incorrecta',
                text: 'Para cerrar el modal, la prueba de ordenamiento debe tener 0, 3, 6 o 10 muestras.',
                confirmButtonColor: '#10B981'
            });
            return;
        }

        const modalContent = modalConfiguracion.querySelector('.transform');
        modalContent.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        modalContent.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

        setTimeout(() => {
            modalConfiguracion.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    // Event listeners para el modal
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', ocultarModal);
    });

    // Manejo del tipo de prueba
    const tipoPrueba = document.getElementById('tipo-prueba');
    const atributoContainer = document.getElementById('atributo-container');

    tipoPrueba.addEventListener('change', function () {
        if (this.value === '3') {
            atributoContainer.classList.remove('hidden');
        } else {
            atributoContainer.classList.add('hidden');
            document.getElementById('atributo').value = '';
        }
    });

    // Manejo de atributos
    document.getElementById('atributo').addEventListener('change', async function () {
        const atributo = this.value;
        const productoId = document.getElementById('producto-id-muestra').value;

        if (atributo && productoId) {
            await actualizarAtributoMuestras(productoId, atributo);
        }
    });

    // Funciones principales
    async function abrirConfiguracion(idProducto, nombreProducto) {
        document.getElementById('productoId').value = idProducto;
        document.getElementById('nombreProductoModal').value = nombreProducto;
        document.getElementById('producto-id-muestra').value = idProducto;

        formProductoModal.action = formProductoModal.action.replace(/\/\d+$/, '/' + idProducto);
        mostrarModal();
        await cargarMuestras(idProducto);
    }

    // Event listeners para botones de configuración
    document.querySelectorAll('.btn-configuracion').forEach(btn => {
        btn.addEventListener('click', function () {
            abrirConfiguracion(this.dataset.id, this.dataset.nombre);
        });
    });

    // Actualizar nombre de producto
    btnSubmit.addEventListener('click', async function () {
        setLoadingState(this, true);
        const productoId = document.getElementById('productoId').value;
        const nombre = document.getElementById('nombreProductoModal').value;

        try {
            const response = await fetchWithCsrf(`/productos/${productoId}`, {
                method: 'POST',
                body: JSON.stringify({
                    _method: 'PUT',
                    nombre: nombre
                })
            });

            const data = await response.json();
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Nombre actualizado correctamente',
                    confirmButtonColor: '#10B981'
                });
                ocultarModal();
                location.reload();
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo actualizar el nombre',
                confirmButtonColor: '#EF4444'
            });
        } finally {
            setLoadingState(this, false);
        }
    });

    // Habilitar/Deshabilitar producto
    // Habilitar/Deshabilitar producto
    document.querySelectorAll('.btn-habilitar').forEach(btn => {
        btn.addEventListener('click', async function () {
            const idProducto = this.dataset.id;
            const habilitado = this.dataset.habilitado === 'true';
            const nuevoEstado = !habilitado;
            setLoadingState(this, true);

            try {
                // Actualizar otros botones
                document.querySelectorAll('.btn-habilitar').forEach(button => {
                    if (button !== this) {
                        button.dataset.habilitado = 'false';
                        button.classList.remove('text-red-600', 'hover:bg-red-600');
                        button.classList.add('text-green-600', 'hover:bg-green-600');
                        const tooltip = button.querySelector('span');
                        if (tooltip) {
                            tooltip.textContent = 'Habilitar';
                        }
                    }
                });

                const response = await fetchWithCsrf(rutaActualizarConfiguracion, {
                    method: 'POST',
                    body: JSON.stringify({
                        _method: 'PUT',
                        producto_habilitado: nuevoEstado ? idProducto : null
                    })
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Error al actualizar el estado');
                }

                if (data.success) {
                    this.dataset.habilitado = nuevoEstado;
                    const tooltip = this.querySelector('span');
                    if (tooltip) {
                        tooltip.textContent = nuevoEstado ? 'Deshabilitar' : 'Habilitar';
                    }

                    if (nuevoEstado) {
                        this.classList.remove('text-green-600', 'hover:bg-green-600');
                        this.classList.add('text-red-600', 'hover:bg-red-600');
                    } else {
                        this.classList.remove('text-red-600', 'hover:bg-red-600');
                        this.classList.add('text-green-600', 'hover:bg-green-600');
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Estado actualizado',
                        text: `Producto ${nuevoEstado ? 'habilitado' : 'deshabilitado'} correctamente`,
                        confirmButtonColor: '#10B981'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo actualizar el estado del producto',
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                setLoadingState(this, false);
            }
        });
    });

    // Manejo del formulario de muestras
    formMuestras.addEventListener('submit', async function (e) {
        e.preventDefault();
        setLoadingState(document.getElementById('btn-guardar-muestra'), true);

        const tipoPrueba = document.getElementById('tipo-prueba').value;
        const cantidadMuestras = document.querySelectorAll('#cuerpo-table-tres tr').length;

        if (tipoPrueba === '3') {
            if (cantidadMuestras >= 10) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite alcanzado',
                    text: 'Ya has alcanzado el máximo de 10 muestras para la prueba de ordenamiento.',
                    confirmButtonColor: '#10B981'
                });
                setLoadingState(document.getElementById('btn-guardar-muestra'), false);
                return;
            }

            const siguienteCantidad = cantidadMuestras + 1;
            if (![3, 6, 10].includes(siguienteCantidad) && siguienteCantidad > 3) {
                const siguienteValido = [3, 6, 10].find(num => num > cantidadMuestras);
                await Swal.fire({
                    icon: 'info',
                    title: 'Cantidad de muestras',
                    text: `Debes agregar muestras hasta llegar a ${siguienteValido} muestras.`,
                    confirmButtonColor: '#10B981'
                });
            }
        }

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                this.reset();
                await cargarMuestras(document.getElementById('producto-id-muestra').value);
                await Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Muestra agregada correctamente',
                    confirmButtonColor: '#10B981'
                });
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar la muestra',
                confirmButtonColor: '#EF4444'
            });
        } finally {
            setLoadingState(document.getElementById('btn-guardar-muestra'), false);
        }
    });

    // Función para cargar muestras
    async function cargarMuestras(idProducto) {
        try {
            const response = await fetchWithCsrf(`/muestras/${idProducto}`, {
                method: 'GET'
            });
            const data = await response.json();

            // Limpiar tablas existentes
            Object.values(tableBodies).forEach(tbody => {
                tbody.innerHTML = '';
            });

            // Cargar nuevos datos
            await Promise.all([
                cargarTablaMuestras(data.triangular, 'uno'),
                cargarTablaMuestras(data.duo_trio, 'dos'),
                cargarTablaMuestras(data.ordenamiento, 'tres', true)
            ]);

            // Asignar eventos a botones de eliminar
            document.querySelectorAll('.btn-eliminar-muestra').forEach(btn => {
                btn.addEventListener('click', () => eliminarMuestra(btn.dataset.id));
            });
        } catch (error) {
            console.error('Error al cargar las muestras:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las muestras',
                confirmButtonColor: '#EF4444'
            });
        }
    }

    // Función para cargar tabla de muestras
    function cargarTablaMuestras(muestras, tableId, includeAtributo = false) {
        const tbody = document.getElementById(`cuerpo-table-${tableId}`);

        muestras.forEach((muestra, index) => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors duration-200';

            let html = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${muestra.cod_muestra}</td>
            `;

            if (includeAtributo) {
                html += `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${muestra.atributo || 'No especificado'}
                    </td>
                `;
            }

            html += `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <button class="btn-eliminar-muestra inline-flex items-center px-3 py-2 
                                 border border-transparent text-sm leading-4 font-medium 
                                 rounded-md text-white bg-red-600 hover:bg-red-700 
                                 focus:outline-none focus:ring-2 focus:ring-offset-2 
                                 focus:ring-red-500 transition-colors duration-200"
                            data-id="${muestra.id_muestras}"  style="background-color: rgb(239, 68, 68);">
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </td>
            `;

            row.innerHTML = html;
            tbody.appendChild(row);
        });
    }

    async function eliminarMuestra(id) {
        const cantidadMuestras = document.querySelectorAll('#cuerpo-table-tres tr').length;
        const esMuestraOrdenamiento = document.querySelector(`#cuerpo-table-tres button[data-id="${id}"]`) !== null;

        if (esMuestraOrdenamiento) {
            const nuevaCantidad = cantidadMuestras - 1;
            if (nuevaCantidad > 0 && ![3, 6, 10].includes(nuevaCantidad)) {
                const siguienteValido = nuevaCantidad > 6 ? 6 : (nuevaCantidad > 3 ? 3 : 0);
                await Swal.fire({
                    title: 'Advertencia',
                    text: `Debes continuar eliminando hasta llegar a ${siguienteValido} muestras`,
                    icon: 'info',
                    confirmButtonColor: '#10B981'
                });
            }
        }

        // Continuar con la eliminación
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetchWithCsrf(`/muestra/${id}`, {
                    method: 'DELETE'
                });

                const data = await response.json();
                if (data.success) {
                    await cargarMuestras(document.getElementById('producto-id-muestra').value);
                }
            } catch (error) {
                await Swal.fire({
                    title: 'Error',
                    text: error.message || 'No se pudo eliminar la muestra',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            }
        }
    }

    // Función para actualizar atributos de muestras
    async function actualizarAtributoMuestras(productoId, atributo) {
        try {
            const response = await fetchWithCsrf('/muestras/actualizar-atributo', {
                method: 'POST',
                body: JSON.stringify({
                    producto_id: productoId,
                    atributo: atributo
                })
            });

            const data = await response.json();
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Atributo actualizado',
                    text: 'El atributo ha sido actualizado para todas las muestras de ordenamiento',
                    confirmButtonColor: '#10B981'
                });
                await cargarMuestras(productoId);
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo actualizar el atributo de las muestras',
                confirmButtonColor: '#EF4444'
            });
        }
    }

    // Manejo del botón guardar configuración
    btnGuardar.addEventListener('click', async function (e) {
        e.preventDefault();
        setLoadingState(this, true);

        try {
            const numCabina = parseInt(document.getElementById('cabina').value);

            if (isNaN(numCabina) || numCabina < 1 || numCabina > 3) {
                throw new Error('El número de cabina debe estar entre 1 y 3');
            }

            const response = await fetchWithCsrf(rutaActualizarConfiguracion, {
                method: 'POST',
                body: JSON.stringify({
                    _method: 'PUT',
                    num_cabina: numCabina
                })
            });

            const data = await response.json();
            if (data.success) {
                await Swal.fire({
                    icon: 'success',
                    title: '¡Guardado exitoso!',
                    text: 'La configuración ha sido actualizada.',
                    confirmButtonColor: '#10B981'
                });
            }
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'No se pudo actualizar la configuración.',
                confirmButtonColor: '#EF4444'
            });
        } finally {
            setLoadingState(this, false);
        }
    });

    // Generador de códigos de muestra
    const btnGenerarCodigo = document.getElementById('btn-generar-codigo');
    btnGenerarCodigo?.addEventListener('click', function () {
        const codigoGenerado = Math.floor(1000 + Math.random() * 9000);
        document.getElementById('codigo-muestra').value = codigoGenerado;
    });

    // Enter key en el modal
    formProductoModal?.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            btnSubmit.click();
        }
    });

    // Click fuera del modal para cerrar
    modalConfiguracion?.addEventListener('click', function (e) {
        if (e.target === this) {
            ocultarModal();
        }
    });

    // Gestión de errores global
    window.addEventListener('unhandledrejection', function (event) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ha ocurrido un error inesperado. Por favor, intente nuevamente.',
            confirmButtonColor: '#EF4444'
        });
    });
});