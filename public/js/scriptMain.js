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
