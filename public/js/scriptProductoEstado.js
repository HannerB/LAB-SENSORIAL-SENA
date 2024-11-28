document.addEventListener('DOMContentLoaded', function () {
    const checkProductState = () => {
        const activeProductId = configuracion.producto_habilitado;

        document.querySelectorAll('.btn-habilitar').forEach(btn => {
            const productId = parseInt(btn.dataset.id);
            const isActive = productId === activeProductId;

            btn.dataset.habilitado = isActive ? 'true' : 'false';

            // Always set the correct CSS classes based on the actual state
            if (isActive) {
                btn.classList.remove('text-green-600', 'hover:bg-green-600');
                btn.classList.add('text-red-600', 'hover:bg-red-600');
                btn.querySelector('span').textContent = 'Deshabilitar';
            } else {
                btn.classList.remove('text-red-600', 'hover:bg-red-600');
                btn.classList.add('text-green-600', 'hover:bg-green-600');
                btn.querySelector('span').textContent = 'Habilitar';
            }
        });
    };

    // Check state initially
    checkProductState();

    // Click handler
    document.querySelectorAll('.btn-habilitar').forEach(btn => {
        btn.addEventListener('click', async function () {
            const idProducto = parseInt(this.dataset.id);
            const habilitado = this.dataset.habilitado === 'true';
            const nuevoEstado = !habilitado;

            try {
                const response = await fetch(rutaActualizarConfiguracion, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        producto_habilitado: nuevoEstado ? idProducto : null
                    })
                });

                const data = await response.json();
                if (data.success) {
                    // Update the configuracion object to match the server state
                    configuracion.producto_habilitado = nuevoEstado ? idProducto : null;

                    // Recheck all buttons state
                    checkProductState();

                    Swal.fire({
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
                    text: 'No se pudo actualizar el estado',
                    confirmButtonColor: '#EF4444'
                });
            }
        });
    });

    $('#form-producto-modal').on('submit', function (e) {
        e.preventDefault();

        const idProducto = $('#productoId').val();
        const nombreProducto = $('#nombreProductoModal').val();

        // Mostrar overlay de carga
        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');

        $.ajax({
            url: `${window.location.origin}/productos/${idProducto}`,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                nombre: nombreProducto
            }),
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message,
                        confirmButtonColor: '#10B981'
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el nombre del producto.',
                    confirmButtonColor: '#EF4444'
                });
            },
            complete: function () {
                loadingOverlay.classList.add('hidden');
                loadingOverlay.classList.remove('flex');
            }
        });
    });
});