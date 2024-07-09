
$("#fecha-filtro").on('change',function(){
    document.querySelector("#resultados-pruebas").classList.remove('active');
    document.querySelector("#resultado-pruebas-personas").classList.remove('active');1
    const fecha = $("#fecha-filtro").val();
    $.ajax({
        url: '../clases/peticiones.php',
        type: 'POST',
        data: {peticion: 'consultraProductosFecha',fecha},
        success: function (data) {
            try {
                const productos = JSON.parse(data);
                let template =  `<option value="select">Seleccione producto</option>`;
                productos.forEach(element => {
                    template += `<option value="${element.id}">${element.nombre}</option>`;
                });
                $("#productos-filtro").html(template);
            } catch (error) {
                console.log(data);
            }
        }
    })
})

$("#filtro-resultados").submit(function(e){
    const cabina =  $("#cabinas-filtro").val();
    const fecha =  $("#fecha-filtro").val();
    const producto =  $("#productos-filtro").val();
    if (cabina != "select" && producto != "select") {
        /* activarContenidos("resultado-pruebas",true);
        activarContenidos("resultado-pruebas-personas",true); */
        $.ajax({
            url: '../clases/peticiones.php',
            type: 'POST',
            data: {peticion: 'realizarResultadosEstadisticos',cabina,fecha,producto},
            success: function (data) {
                try {
                    const resultados = JSON.parse(data);
                    let template = ``;
                    //RESULTADOS DE TRIANGULAR
                    const triangular = resultados.triangular

                    template = `<tr><th scope="col">#</th>`;
                    triangular[0].forEach(element => {
                        template += `<th scope="col">${element}</th>`
                    });
                    template += `</tr>`;

                    $("#head-triangular").html(template);
                    
                    template= `<tr><th scope="row">personas</th>`
                    triangular[1].forEach(element => {
                        template += `<td>${element}</td>`
                    });
                    template+=`</tr>`

                    $("#body-triangular").html(template);

                    //RESULTADOS DE DUO
                    const duo = resultados.duo
                    
                    template = `<tr><th scope="col">#</th>`;

                    duo[0].forEach(element => {
                        template += `<th scope="col">${element}</th>`
                    });
                    template += `</tr>`;
                    
                    $("#head-duo").html(template);
                    
                    template= `<tr><th scope="row">personas</th>`
                    duo[1].forEach(element => {
                        template += `<td>${element}</td>`
                    });
                    template+=`</tr>`

                    $("#body-duo").html(template);

                    //RESULTADOS DE ORDENAMIENTO
                    const orden = resultados.orden
                    $("#preferencia-ordenamiento").html(orden[0]);
                    $("#atributo-prueba").html(orden[1]);
                    
                    console.log(resultados);


                } catch (error) {
                    console.log(data);
                }
            }
        })
        document.querySelector("#resultados-pruebas").classList.add('active');
        document.querySelector("#resultado-pruebas-personas").classList.add('active');
        
    }else{
        document.querySelector("#resultados-pruebas").classList.remove('active');
        document.querySelector("#resultado-pruebas-personas").classList.remove('active');
        Swal.fire(
            'informacion',
            'Debe seleccionar todas las opciones del formulario, si uno de las casillas no tiene niguna opcion a elegir entonces no se ha realizado nada a la fecha!',
            'info'
        )
    }


    e.preventDefault();
})

$("#tipo-prueba-resultado").on('change',function(){
    const cabina =  $("#cabinas-filtro").val();
    const fecha =  $("#fecha-filtro").val();
    const producto =  $("#productos-filtro").val();

    const prueba = $("#tipo-prueba-resultado").val();
    if (prueba != "select") {
        $.ajax({
            url: '../clases/peticiones.php',
            type: 'POST',
            data: {peticion: 'consultarResultadosPersonas',cabina,fecha,producto,prueba},
            success: function (data) {
                try {
                    const listado = JSON.parse(data);
                    let template = ``;
                    let num = 1;
                    listado.forEach(element => {
                        template+= `
                        <tr>
                        <th scope="row">${num}</th>
                        <td>${element.nombre}</td>
                        <td>${element.muestras}</td>
                        </tr>`
                        num++
                    });
                    $("#listado-personas-prueba").html(template)
                
                } catch (error) {
                    console.log(data);
                }
            }
        })
    }else{
        $("#listado-personas-prueba").html(``)
    }
})

//FUNCIONES
function consultarCabinasHabilitadas() {
    $.ajax({
        url: '../clases/peticiones.php',
        type: 'POST',
        data: {peticion: 'consultarCabinas'},
        success: function (data) {
            try {
                const cabinas = JSON.parse(data)

                let template = ``;
                cabinas.forEach(element => {
                    template += `
                        <option value="${element}">${element}</option>
                    `
                });

                $("#cabinas-filtro").html(template)

            } catch (error) {
                console.log(data);
            }
        }
    })
}

consultarCabinasHabilitadas();