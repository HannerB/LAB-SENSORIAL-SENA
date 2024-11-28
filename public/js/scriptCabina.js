document.addEventListener('DOMContentLoaded', function () {
    // Manejo del botón guardar configuración
    const btnGuardar = document.getElementById('btnguardar');
    const cabina = document.getElementById('cabina');
    const loadingOverlay = document.getElementById('loadingOverlay');

    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function (e) {
            e.preventDefault();

            // Mostrar overlay de carga
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');

            try {
                const numCabina = parseInt(cabina.value);

                if (isNaN(numCabina) || numCabina < 1) {
                    throw new Error('Por favor ingrese un número de cabina válido mayor a 0');
                }

                const response = await fetch(rutaActualizarConfiguracion, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        num_cabina: numCabina
                    })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: '¡La configuración ha sido actualizada correctamente!',
                        confirmButtonColor: '#198754'
                    });
                } else {
                    throw new Error(data.message || 'Error al actualizar la configuración');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo actualizar la configuración.',
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                // Ocultar overlay de carga
                loadingOverlay.classList.add('hidden');
                loadingOverlay.classList.remove('flex');
            }
        });
    }
});