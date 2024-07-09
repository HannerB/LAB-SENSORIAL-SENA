//DATOS PRUEBA TRIANGULAR
const contenedor_sec_uno = document.getElementById('sect1');
const nombreP_Triangular = document.getElementById("nombrePanelista1");
const fechaP_Triangular = document.getElementById("fechaPanelista1");
const producto_Triangular = document.getElementById("productoPrueba1");
//DATOS PRUEBA DUO - TRIO
const contenedor_sec_dos = document.getElementById('sect2');
const nombreP_Duo = document.getElementById("nombrePanelista2");
const fechaP_Duo = document.getElementById("fechaPanelista2");
const producto_Duo = document.getElementById("productoPrueba2");
//DATOS PRUEBA ORDENAMIENTO
const contenedor_sec_tres = document.getElementById('sect3');
const nombreP_Ord = document.getElementById("nombrePanelista3");
const fechaP_Ord = document.getElementById("fechaPanelista3");
const producto_Ord = document.getElementById("productoPrueba3");


$("#btnsiguiente1").on('click',function(){
    cambiarFormulario('sect2','sect1')
    
})

$("#btnsiguiente2").on('click',function(){
    cambiarFormulario('sect3','sect2')
    /* const nombre_dos = nombreP_Duo.value;
    const fecha_dos = fechaP_Duo.value;
    const producto_dos = producto_Duo.value;
    const seleccionPruebaDuo = $('input:radio[name=rdduo]:checked').val();
    if (nombre_dos != "" && fecha_dos != "" && producto_dos != "" && seleccionPruebaDuo !=  null) {
    }else{
        Swal.fire(
            'ALERTA!',
            'Recuerda rellenar los campos de nombre y fecha, tambien verifica que la casilla de nombre de producto tenga un valor y por ultimo recuerda que debes seleccionar una muestra!',
            'warning'
        )
    } */
})


$("#btnguardar-respuesta").on('click',function(){
    /* const nombre_tres = nombreP_Ord.value;
    const fecha_tres = fechaP_Ord.value;
    const producto_tres = producto_Ord.value;

    if (nombre_tres != "" && fecha_tres != "" && producto_tres !=  null) {
        let numitem = 1;
        let ordenMuestras =  [];
        while (true) {
            const seleccion = document.getElementById("select"+numitem);
            if (seleccion != null) {
                let muestra = seleccion.value;
                ordenMuestras.push(muestra);
                numitem++
            }else{
                break
            }
        }

        if (!ordenMuestras.includes("select")) {
            guardarResultadosPanelista();
        }else{
            Swal.fire(
                'ESPERA!',
                'Te faltan valores por seleccionar, recuerda que debes posicionar todas las muestras en la casilla correspondiente!',
                'warning'
            )
        }

    }else{
        Swal.fire(
            'ALERTA!',
            'Recuerda rellenar los campos de nombre y fecha, tambien verifica que la casilla de nombre de producto tenga un valor!',
            'warning'
        )
    } */
})

$("#btnguardar-tri").on('click',function(){
    let nombre_uno = nombreP_Triangular.value;
    const fecha_uno = fechaP_Triangular.value;

    const producto_uno = producto_Triangular.value;
    const seleccionPruebaTriangular = $('input:radio[name=rdtriangular]:checked').val();
    const comentario_uno =  $("#comentario-triangular").val()
    
    if (nombre_uno != "" && fecha_uno != "" && producto_uno != "" && seleccionPruebaTriangular !=  null) {
        nombre_uno = corregirFomatodeNombre(nombre_uno);
        Swal.fire({
            title: 'Listo',
            text: "Estas seguro de tus respuestas?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Guardar',
            cancelButtonText: 'No, Quiero comprobar mis respuestas'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:'clases/peticiones.php',
                    type:'POST',
                    data: {peticion: 'guardarResultadosTrigular',
                           datos:{nombrePane: nombre_uno,fecha: fecha_uno},
                           prueba_uno: {resultadoPruebaTriangular: seleccionPruebaTriangular,comentarioTriangular: comentario_uno}
                        },
                    success: function(data){
                        if (data == "") {
                            Swal.fire({
                                title: 'PERFECTO',
                                text: "Muchas gracias por participar!",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                              }).then((result2) => {
                                if (result2.isConfirmed) {
                                    cargarInformacionFormulario();
                                    nombreP_Triangular.value = null;
                                    $("#comentario-triangular").val(null)
                                }
                            })
                        }
                        console.log(data);
                    }
                })
            }
          })
    }else{
        Swal.fire(
            'ALERTA!',
            'Recuerda rellenar los campos de nombre y fecha, tambien verifica que la casilla de nombre de producto tenga un valor y por ultimo recuerda que debes seleccionar una muestra!',
            'warning'
        )
    }
})

$("#btnguardar-duo").on('click',function(){
    let nombre_dos = nombreP_Duo.value;
    const fecha_dos = fechaP_Duo.value;

    const producto_dos = producto_Duo.value;
    const seleccionPruebaDuo = $('input:radio[name=rdduo]:checked').val();

    const comentario_dos =  $("#comentario-duo").val()

    if (nombre_dos != "" && fecha_dos != "" && producto_dos != "" && seleccionPruebaDuo !=  null) {
        nombre_dos = corregirFomatodeNombre(nombre_dos);
        Swal.fire({
            title: 'Listo',
            text: "Estas seguro de tus respuestas?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Guardar',
            cancelButtonText: 'No, Quiero comprobar mis respuestas'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:'clases/peticiones.php',
                    type:'POST',
                    data: {peticion: 'guardarResultadosDuo',
                           datos:{nombrePane: nombre_dos,fecha: fecha_dos},
                           prueba_dos: {resultadoPruebaDuo: seleccionPruebaDuo,comentarioDuo: comentario_dos},
                        },
                    success: function(data){
                        if (data == "") {
                            Swal.fire({
                                title: 'PERFECTO',
                                text: "Muchas gracias por participar!",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                              }).then((result2) => {
                                if (result2.isConfirmed) {
                                    cargarInformacionFormulario();
                                    cambiarFormulario('sect2','sect1')
                                    nombreP_Duo.value = null;
                                    $("#comentario-duo").val(null)
                                }
                            })
                        }
                        console.log(data);
                    }
                })
            }
        })

    }else{
        Swal.fire(
            'ALERTA!',
            'Recuerda rellenar los campos de nombre y fecha, tambien verifica que la casilla de nombre de producto tenga un valor y por ultimo recuerda que debes seleccionar una muestra!',
            'warning'
        )
    } 

})

$("#btnguardar-respuesta-orden").on('click',function(){
    let nombre_tres = nombreP_Ord.value;
    const fecha_tres = fechaP_Ord.value;
    const producto_tres = producto_Ord.value;

    if (nombre_tres != "" && fecha_tres != "" && producto_tres !=  null) {
        let numitem = 1;
        let ordenMuestras =  [];
        while (true) {
            const seleccion = document.getElementById("select"+numitem);
            if (seleccion != null) {
                let muestra = seleccion.value;
                ordenMuestras.push(muestra);
                numitem++
            }else{
                break
            }
        }

        if (!ordenMuestras.includes("select")) {
            nombre_tres = corregirFomatodeNombre(nombre_tres);
            const resultadoOrden = ""+ordenMuestras+""
            const comentario_tres =  $("#comentario-orden").val()
            const atributo = document.querySelector(".atributo-span").innerHTML;

            Swal.fire({
                title: 'Listo',
                text: "Estas seguro de tus respuestas?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Guardar',
                cancelButtonText: 'No, Quiero comprobar mis respuestas'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:'clases/peticiones.php',
                        type:'POST',
                        data: {peticion: 'guardarResultadoOrden',
                               datos:{nombrePane: nombre_tres,fecha: fecha_tres},
                               prueba_tres: {resultadoOrden,comentario_tres,atributo}
                            },
                        success: function(data){
                            if (data == "") {
                                Swal.fire({
                                    title: 'PERFECTO',
                                    text: "Muchas gracias por participar!",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Aceptar'
                                  }).then((result2) => {
                                    if (result2.isConfirmed) {
                                        cargarInformacionFormulario();
                                        cambiarFormulario('sect3','sect1')
                                        nombreP_Ord.value = null;
                                        $("#comentario-orden").val(null)
                                    }
                                })
                            }
                            console.log(data);
                        }
                    })
                }
            })


        }else{
            Swal.fire(
                'ESPERA!',
                'Te faltan valores por seleccionar, recuerda que debes posicionar todas las muestras en la casilla correspondiente!',
                'warning'
            )
        }

    }else{
        Swal.fire(
            'ALERTA!',
            'Recuerda rellenar los campos de nombre y fecha, tambien verifica que la casilla de nombre de producto tenga un valor!',
            'warning'
        )
    }

})


//FUNCIONES
function cambiarFormulario(formIr,formActual) {
    asignarValorCampos();
    document.getElementById(formActual).classList.remove('active');
    document.getElementById(formIr).classList.add('active');
}

function asignarValorCampos(){
    const contenedores =  [contenedor_sec_uno,contenedor_sec_dos,contenedor_sec_tres];
    for (let c = 0; c < contenedores.length; c++) {
        const element = contenedores[c];
        if (element.classList.contains('active')) {
            let pos =  c + 1;
            const fechaP =  document.getElementById('fechaPanelista'+pos).value;
            const producto =  document.getElementById('productoPrueba'+pos).value;

            if (fechaP != null && producto != null) {
                for (let j = 0; j < contenedores.length; j++) {
                    let pos2 =  j + 1
                    document.getElementById('fechaPanelista'+pos2).value = fechaP
                    document.getElementById('productoPrueba'+pos2).value = producto
                }
            }

            break;
        }
    }
}


function actualizarCampos(elementoSeleccionado,cantidadMuestras) {
    const valorElementoSeleccionado = document.getElementById(elementoSeleccionado).value;

    for (let e = 1; e <= cantidadMuestras; e++) {
        const idElementos =  "select"+e;

        if (idElementos != elementoSeleccionado) {
            const valorEncontrado =  document.getElementById(idElementos).value;

            if (valorEncontrado == valorElementoSeleccionado) {
                document.getElementById(idElementos).value = "select";
            }

        }
    }
}

function corregirFomatodeNombre(nombre) {
    let nuevoNombre = "";
    
    const partes = nombre.split(' ')

    partes.forEach(element => {
        let cambiarNombre =  element.toLowerCase()
        cambiarNombre =  cambiarNombre.replace(cambiarNombre[0],cambiarNombre[0].toUpperCase())
        nuevoNombre+= cambiarNombre;
        if (element !=  partes[partes.length-1]) {
            nuevoNombre += " ";
        }
    });

    return nuevoNombre;
}

function cargarInformacionFormulario() {
    //MOSTRAR DATOS INICIALES
    $.ajax({
        url:'clases/peticiones.php',
        type:'POST',
        data: {peticion: 'consultar-info-configuracion'},
        success: function(data){
            try {
                const json = JSON.parse(data);
                document.querySelectorAll(".nombre-producto-span").forEach(element => {
                    element.innerHTML = json.nombreproduto;
                });
                producto_Triangular.value = json.nombreproduto;
                fechaP_Triangular.value = json.fecha;

                consultarListadoMuestrasPorPruebas(json.id);

            } catch (error) {
                console.log(data);
            }
        }
    })
}

function consultarListadoMuestrasPorPruebas(producto) {
    //MOSTRAR DATOS DE LAS PRUEBA
    $.ajax({
        url:'clases/peticiones.php',
        type:'POST',
        data: {peticion: 'consultarMuestras',producto},
        success: function(data){
            try {

                let template = ``;

                const json = JSON.parse(data);
                const nuevoOrden =  (arr) => arr.sort(() => Math.random()-0.5)

                let triangular = json.triangular
                triangular = nuevoOrden(triangular);

                let duo = json.duo
                duo = nuevoOrden(duo);

                let ordenamiento = json.ordenamiento
                ordenamiento = nuevoOrden(ordenamiento);

                const atributoPruebaOrdenamiento =  json.atributo

                // CARGAR EN TABLA FORMULARIO TRIANGULAR
                triangular.forEach(element => {
                    template+= `
                    <tr>
                        <td>${element.codigo}</td>
                        <td><input type="radio" name="rdtriangular" value="${element.codigo}"></td>
                    </tr>
                    `
                });                
                $("#cuerpo-prueba-triangular").html(template);
                
                // CARGAR EN TABLA FORMULARIO DUO - TRIO
                template = ``;
                duo.forEach(element => {
                    template+= `
                    <tr>
                        <td>${element.codigo}</td>
                        <td><input type="radio" name="rdduo" value="${element.codigo}"></td>
                    </tr>
                    `
                });                
                $("#cuerpo-prueba-duo").html(template);

                // CARGAR EN TABLA FORMULARIO ORDENAMIENTO
                template = ``;
                const listado_codigos_muestras_ordenamiento =  ordenamiento.map(valor => valor.codigo);

                document.querySelectorAll(".atributo-span").forEach(element => {
                    element.innerHTML = atributoPruebaOrdenamiento;
                });
                
                let num = 1;
                let estado = {1: "Me gusta mucho",2:"Me gusta ligeramente",3:"Ni me gusta ni me disgusta",4:"Me gusta poco",5:"Me disgusta mucho"}

                for (let i = 0; i < ordenamiento.length; i++) {
                    template+= `
                    <tr>
                        <td>${estado[num]}</td>
                        <td>${num}. <select class="select-ordenamiento" id="select${num}" onchange="actualizarCampos('select${num}',${ordenamiento.length})">
                    `;
                    template+= `<option value="select"></option>`;
                    listado_codigos_muestras_ordenamiento.forEach(element2 => {
                        template+= `<option value="${element2}">${element2}</option>`;
                    });
                    template+= `
                        </select></td>
                    </tr>
                    `
                    num++
                }

                $("#cuerpo-selectores-odenamiento").html(template)

            } catch (error) {
                console.log(data);
                console.log(error);
            }
        }
    })
}
/* BRAYAN JESUS CHARRIS CANTILLO */
function guardarResultadosPanelista() {
    // CAPTURAR INFORMACION PERSONALES
    const nombre_tres = corregirFomatodeNombre(nombreP_Ord.value);
    const fecha_tres = fechaP_Ord.value;

    //RESPUESTA PRUEBA TRIANGULAR
    const resultadoPruebaTriangular = $('input:radio[name=rdtriangular]:checked').val();
    const comentario_uno =  $("#comentario-triangular").val()
    
    //RESPUESTA PRUEBA DUO - TRIO
    const resultadoPruebaDuo = $('input:radio[name=rdduo]:checked').val();
    const comentario_dos =  $("#comentario-duo").val()
    
    //RESPUESTA PRUEBA ORDENAMIENTO
    let numitem = 1;
    let ordenMuestras =  [];
    while (true) {
        const seleccion = document.getElementById("select"+numitem);
        if (seleccion != null) {
            let muestra = seleccion.value;
            ordenMuestras.push(muestra);
            numitem++
        }else{
            break
        }
    }

    const resultadoOrden = ""+ordenMuestras+""
    const comentario_tres =  $("#comentario-orden").val()
    const atributo = document.querySelector(".atributo-span").innerHTML;

    //GUARDAR RESULTADOS

    Swal.fire({
        title: 'Listo',
        text: "Estas seguro de tus respuestas?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Guardar',
        cancelButtonText: 'No, Quiero comprobar mis respuestas'
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:'clases/peticiones.php',
                type:'POST',
                data: {peticion: 'guardarResultados',
                       datos:{nombrePane: nombre_tres,fecha: fecha_tres},
                       prueba_uno: {resultadoPruebaTriangular,comentarioTriangular: comentario_uno},
                       prueba_dos: {resultadoPruebaDuo,comentarioDuo: comentario_dos},
                       prueba_tres: {resultadoOrden,comentario_tres,atributo}
                    },
                success: function(data){
                    if (data == "") {
                        Swal.fire({
                            title: 'PERFECTO',
                            text: "Muchas gracias por participar!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                          }).then((result2) => {
                            if (result2.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                    console.log(data);
                }
            })
        }
    })


}

cargarInformacionFormulario();