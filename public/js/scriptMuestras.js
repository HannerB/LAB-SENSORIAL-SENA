document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modalConfiguracion = document.getElementById('modalConfiguracion');

    // Función para mostrar el modal
    function mostrarModal() {
        const modal = document.getElementById('modalConfiguracion');
        if (!modal) return;

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // Animar la entrada del modal
        const modalContent = modal.querySelector('.transform');
        if (modalContent) {
            setTimeout(() => {
                modalContent.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                modalContent.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }
    }

    // Función para ocultar el modal
    function ocultarModal() {
        const modal = document.getElementById('modalConfiguracion');
        if (!modal) return;

        const modalContent = modal.querySelector('.transform');
        if (modalContent) {
            modalContent.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            modalContent.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    // Manejar botones de configuración
    document.querySelectorAll('.btn-configuracion').forEach(btn => {
        btn.addEventListener('click', async function () {
            const idProducto = this.dataset.id;
            const nombreProducto = this.dataset.nombre;

            // Actualizar campos del modal
            document.getElementById('productoId').value = idProducto;
            document.getElementById('nombreProductoModal').value = nombreProducto;

            // Sincronizar IDs
            sincronizarProductoId(idProducto);

            // Mostrar el modal
            mostrarModal();

            // Cargar las muestras
            await cargarMuestras(idProducto);
        });
    });

    // Manejar botón de cerrar modal
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', ocultarModal);
    });

    // Cerrar modal al hacer clic en el overlay
    if (modalConfiguracion) {
        modalConfiguracion.addEventListener('click', function (e) {
            if (e.target === this) {
                ocultarModal();
            }
        });
    }

    // Función para generar código aleatorio
    function generarCodigo() {
        return Math.floor(1000 + Math.random() * 9000);
    }

    // Función para actualizar contadores
    function actualizarContadores() {
        const triangularCount = document.querySelector('#cuerpo-table-uno').children.length;
        const duoTrioCount = document.querySelector('#cuerpo-table-dos').children.length;
        const ordenamientoCount = document.querySelector('#cuerpo-table-tres').children.length;

        document.querySelector('#triangular-count').textContent = `${triangularCount} muestras`;
        document.querySelector('#duo-trio-count').textContent = `${duoTrioCount} muestras`;
        document.querySelector('#ordenamiento-count').textContent = `${ordenamientoCount}/10 muestras`;
    }

    // Manejadores para botones de generar código
    document.querySelectorAll('.btn-generar-codigo').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('form');
            const input = form.querySelector('input[name="cod_muestra"]');
            input.value = generarCodigo();
        });
    });

    // Manejar formulario triangular
    document.getElementById('form-triangular').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_token', csrfToken);

        try {
            const response = await fetch('/muestra', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                this.reset();
                await cargarMuestras(document.getElementById('producto-id-triangular').value);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Muestra triangular agregada correctamente',
                    confirmButtonColor: '#10B981'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar la muestra triangular',
                confirmButtonColor: '#EF4444'
            });
        }
    });

    // Manejar formulario duo-trio
    document.getElementById('form-duo-trio').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_token', csrfToken);

        try {
            const response = await fetch('/muestra', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                this.reset();
                await cargarMuestras(document.getElementById('producto-id-duo-trio').value);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Muestra duo-trio agregada correctamente',
                    confirmButtonColor: '#10B981'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar la muestra duo-trio',
                confirmButtonColor: '#EF4444'
            });
        }
    });

    // Manejar formulario ordenamiento
    document.getElementById('form-ordenamiento').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_token', csrfToken);

        // Validar atributos seleccionados
        const atributos = formData.getAll('atributos[]');
        if (atributos.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe seleccionar al menos un atributo para la prueba de ordenamiento',
                confirmButtonColor: '#10B981'
            });
            return;
        }

        try {
            const response = await fetch('/muestra', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                this.reset();
                await cargarMuestras(document.getElementById('producto-id-ordenamiento').value);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Muestra de ordenamiento agregada correctamente',
                    confirmButtonColor: '#10B981'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar la muestra de ordenamiento',
                confirmButtonColor: '#EF4444'
            });
        }
    });

    // Actualizar atributos de ordenamiento
    document.getElementById('btn-actualizar-atributos').addEventListener('click', async function () {
        const productoId = document.getElementById('producto-id-ordenamiento').value;
        const atributos = Array.from(document.querySelectorAll('#form-ordenamiento input[name="atributos[]"]:checked'))
            .map(cb => cb.value);

        try {
            const response = await fetch('/muestras/actualizar-atributo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    producto_id: productoId,
                    atributos: atributos
                })
            });

            const data = await response.json();
            if (data.success) {
                await cargarMuestras(productoId);
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Atributos actualizados correctamente',
                    confirmButtonColor: '#10B981'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron actualizar los atributos',
                confirmButtonColor: '#EF4444'
            });
        }
    });

    // Sincronizar IDs de producto
    function sincronizarProductoId(id) {
        document.getElementById('producto-id-triangular').value = id;
        document.getElementById('producto-id-duo-trio').value = id;
        document.getElementById('producto-id-ordenamiento').value = id;
    }

    // Función para cargar muestras en las tablas
    async function cargarMuestras(idProducto) {
        try {
            const response = await fetch(`/muestras/${idProducto}`);
            const data = await response.json();

            // Limpiar tablas
            document.getElementById('cuerpo-table-uno').innerHTML = '';
            document.getElementById('cuerpo-table-dos').innerHTML = '';
            document.getElementById('cuerpo-table-tres').innerHTML = '';

            // Cargar muestras triangulares
            data.triangular.forEach((muestra, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors duration-200';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${muestra.cod_muestra}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="btn-eliminar-muestra inline-flex items-center px-3 py-2 border border-transparent 
                                     text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 
                                     focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                data-id="${muestra.id_muestras}">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </td>
                `;
                document.getElementById('cuerpo-table-uno').appendChild(row);
            });

            // Cargar muestras duo-trio
            data.duo_trio.forEach((muestra, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors duration-200';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${muestra.cod_muestra}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="btn-eliminar-muestra inline-flex items-center px-3 py-2 border border-transparent 
                                     text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 
                                     focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                data-id="${muestra.id_muestras}">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </td>
                `;
                document.getElementById('cuerpo-table-dos').appendChild(row);
            });

            // Cargar muestras ordenamiento
            data.ordenamiento.forEach((muestra, index) => {
                const atributos = [];
                if (muestra.tiene_sabor) atributos.push('Sabor');
                if (muestra.tiene_olor) atributos.push('Olor');
                if (muestra.tiene_color) atributos.push('Color');
                if (muestra.tiene_textura) atributos.push('Textura');
                if (muestra.tiene_apariencia) atributos.push('Apariencia');

                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors duration-200';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${muestra.cod_muestra}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${atributos.join(', ')}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button class="btn-eliminar-muestra inline-flex items-center px-3 py-2 border border-transparent 
                                     text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 
                                     focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                data-id="${muestra.id_muestras}">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </td>
                `;
                document.getElementById('cuerpo-table-tres').appendChild(row);
            });

            // Actualizar contadores
            actualizarContadores();

            // Agregar eventos a los nuevos botones de eliminar
            document.querySelectorAll('.btn-eliminar-muestra').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const muestraId = this.dataset.id;
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
                            const response = await fetch(`/muestra/${muestraId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            const data = await response.json();
                            if (data.success) {
                                await cargarMuestras(idProducto);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: 'La muestra ha sido eliminada correctamente',
                                    confirmButtonColor: '#10B981'
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar la muestra',
                                confirmButtonColor: '#EF4444'
                            });
                        }
                    }
                });
            });

        } catch (error) {
            console.error('Error al cargar muestras:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las muestras',
                confirmButtonColor: '#EF4444'
            });
        }
    }

    // Inicialización cuando se abre el modal
    document.querySelectorAll('.btn-configuracion').forEach(btn => {
        btn.addEventListener('click', async function () {
            const productoId = this.dataset.id;
            sincronizarProductoId(productoId);
            await cargarMuestras(productoId);
        });
    });
});