//ACTUALIZAR PRODUCTO
$("#form-producto").submit(function(e){
    const producto =  obtenerProducto()
    const nombreProducto = $('#nombreProducto').val();
    const habilitarProducto = $('#habilitado').is(':checked')

    $.ajax({
        url: '../clases/peticiones.php',
        type: 'POST',
        data: {peticion: 'actualizarProducto',producto,nombreProducto},
        success: function (response) {
            if (response == "") {
                alertaSweetTimer("Producto actualizado con exito!");
            }
            console.log(response);
        }
    })

    if (habilitarProducto) {

        $.ajax({
            url: '../clases/peticiones.php',
            type: 'POST',
            data: {peticion: 'actualizarConfiguracionProducto',producto},
            success: function (response) {
                if (response == "") {
                    alertaSweetTimer("Producto actualizado con exito!");
                }
                console.log(response);
            }
        })

    }

    e.preventDefault();
})

//Generar Codigos

$("#btn-codigo").on('click',function(){
    $("#codigo-muestra").val(generarCodigosAleatorios(4));
})
//GUARDAR MUESTRA

$("#form-muestras").submit(function(e){
    const codigo =  $("#codigo-muestra").val()
    const producto =  obtenerProducto()
    const tipo =  $("#tipo-prueba").val()
    let atributo =  null;

    if (tipo ==  3) {
        atributo = $("#atributos-prueba").val();
    }
    
    $.ajax({
        url: '../clases/peticiones.php',
        type: 'POST',
        data: {peticion: 'guardarMuestra',codigo,producto,tipo,atributo},
        success: function (response) {
            $("#codigo-muestra").val(null)
            consultarMuestras();
            console.log(response);
        }
    })

    e.preventDefault();
})

$("#btncancelar").on('click',function(){
    $("#codigo-muestra").val(null)
    $("#tipo-prueba").val("1")
    document.querySelector("#cont-atributos").style.display = "none";
})


// PRUEBA DE ORDENAMIENTO

$("#tipo-prueba").on('change',function(){
    const prueba = $("#tipo-prueba").val();
    console.log(prueba);
    if (prueba == 3) {
        document.querySelector("#cont-atributos").style.display = "block";
    } else {
        document.querySelector("#cont-atributos").style.display = "none";
    }
})

//FUNCIONES
function obtenerProducto() {
    const url = window.location.href.split('=');
    let id = 0;
    if (url.length == 2) {
        id = url[1];
    }
    return id;
}

function generarCodigosAleatorios(ndigitos) {
    let codigo = "";
    for (let i = 0; i < ndigitos; i++) {
       codigo+=""+Math.floor(Math.random() * 9)
    }
    return codigo;
}

function consultarMuestras() {
    const producto =  obtenerProducto()
    $.ajax({
        url: '../clases/peticiones.php',
        type: 'POST',
        data: {peticion: 'consultarMuestras',producto},
        success: function (response) {
            try {
                const pruebas_muestras = JSON.parse(response);

                /* TABLA UNO */
                const triangular = pruebas_muestras.triangular
                let template =  ``;
                let num = 1;
                triangular.forEach(element => {
                    template+= `
                    <tr>
                        <th scope="row">${num}</th>
                        <td>${element.codigo}</td>
                        <td><img src="../icons/trash.svg" alt="" onclick='eliminarMuestra(${element.id})'></td>
                    </tr>
                    `
                    num++
                });
                $("#cuerpo-table-uno").html(template);

                /* TABALA DOS */
                const duo_trio = pruebas_muestras.duo
                template =  ``
                num = 1
                duo_trio.forEach(element => {
                    template+= `
                    <tr>
                        <th scope="row">${num}</th>
                        <td>${element.codigo}</td>
                        <td><img src="../icons/trash.svg" alt="" onclick='eliminarMuestra(${element.id})'></td>
                    </tr>
                    `
                    num++
                });
                $("#cuerpo-table-dos").html(template);

                /* TABAL 3 */
                const ordenamiento = pruebas_muestras.ordenamiento
                template =  ``
                num = 1
                ordenamiento.forEach(element => {
                    template+= `
                    <tr>
                        <th scope="row">${num}</th>
                        <td>${element.codigo}</td>
                        <td><img src="../icons/trash.svg" alt="" onclick='eliminarMuestra(${element.id})'></td>
                    </tr>
                    `
                    num++
                });
                $("#cuerpo-table-tres").html(template);
                
                /* TIPO ATRIBUTO */
                const atributo = pruebas_muestras.atributo
                $("#atributo-prueba-span").html(atributo);
                
                
            } catch (error) {
                console.log(response);
            }
        }
    })
}

function eliminarMuestra(idMuestra) {
    console.log(idMuestra);
    Swal.fire({
        title: 'Estas seguro?',
        text: "Estas seguro de eliminar esta muestra!",
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, seguro',
        showCancelButton: true,
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../clases/peticiones.php',
                type: 'POST',
                data: {peticion: 'eliminarMuestra',idMuestra},
                success: function (response) {
                    consultarMuestras()
                    if (response == "") {
                        alertaSweetTimer("Muestra eliminada con exito!")
                    }
                    console.log(response);
                }
            })
        }
    })
}

function alertaSweetTimer(mensaje) {
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: mensaje,
        showConfirmButton: false,
        timer: 1200
    })
}

consultarMuestras();