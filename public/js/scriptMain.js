$("#btnsiguiente1").on('click',function(){
    cambiarFormulario('sect2','sect1')
    
})

$("#btnsiguiente2").on('click',function(){
    cambiarFormulario('sect3','sect2')
})

//FUNCIONES
function cambiarFormulario(formIr,formActual) {
    document.getElementById(formActual).classList.remove('active');
    document.getElementById(formIr).classList.add('active');
}

// PRUEBA DE ORDENAMIENTO - EVITAR DUPLICADOS EN SELECCIÓN
$(document).ready(function() {
    // Detectar cuando se cambia un valor en cualquier selector
    $('.orden-muestra').on('change', function() {
        actualizarOpciones();
    });

    function actualizarOpciones() {
        // Recoger todos los valores seleccionados actualmente
        let seleccionados = [];

        $('.orden-muestra').each(function() {
            let valor = $(this).val();
            if (valor) {
                seleccionados.push(valor); // Guardar los valores seleccionados
            }
        });

        // Actualizar las opciones de cada selector en base a los seleccionados
        $('.orden-muestra').each(function() {
            let $this = $(this);
            let valorSeleccionado = $this.val();

            // Limpiar las opciones disponibles, excepto la seleccionada
            $this.find('option').each(function() {
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