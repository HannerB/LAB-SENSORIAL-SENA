// GENERADOR DE CÓDIGOS

document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar el botón y el campo donde se generará el código
    const btnCodigo = document.getElementById('btn-generar-codigo');
    const inputCodigoMuestra = document.getElementById('codigo-muestra');

    // Lógica para generar un código de muestra único
    function generarCodigoUnico() {
        // Generar un código aleatorio de 4 dígitos numéricos
        return Math.floor(1000 + Math.random() * 9000); // Genera un número entre 1000 y 9999
    }

    // Asignar la función al botón de generación de código
    btnCodigo.addEventListener('click', function() {
        const codigoGenerado = generarCodigoUnico();
        inputCodigoMuestra.value = codigoGenerado; // Establece el valor generado en el input
    });
});