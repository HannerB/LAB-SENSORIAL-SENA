// consultarListadoProductos();

// //GUARDAR PRODUCTO

// $("#form-producto").submit(function(e){
//     const producto = $("#nombreProducto").val();
//     $.ajax({
//         url: '../clases/peticiones.php',
//         type: 'POST',
//         data: {peticion: 'guardarProducto',producto},
//         success: function (response) {
//             $("#nombreProducto").val(null)
//             consultarListadoProductos();
//             console.log(response);
//         }
//     })
//     e.preventDefault();
// })

// //GUARDAR CONFIGURACION
// $("#btnguardar").on('click', function(){
//     const cabina =  $("#cabina").val();
//     if (cabina > 0) {
//         $.ajax({
//             url: '../clases/peticiones.php',
//             type: 'POST',
//             data: {peticion: 'guardarCabina',cabina},
//             success: function (response) {
//                 consultarListadoProductos();
//                 console.log(response);
//             }
//         })
//     }
// })

// //ACTUALIZAR TABLA PRODUCTOS
// $("#refrescar-productos").on('click', function(){
//     consultarListadoProductos();
// })

// // FUNCIONES
// function consultarListadoProductos() {
//     $.ajax({
//         url: '../clases/peticiones.php',
//         type: 'POST',
//         data: {peticion: 'consultarProductos'},
//         success: function (response) {
//             try {
//                 const listado  = JSON.parse(response);

//                 let template = ``;
//                 let num = 1;
//                 listado.forEach(element => {
//                     template+= `
//                     <tr>
//                         <th scope="row">${num}</th>
//                         <td>${element.nombre}</td>
//                         <td><img src="../icons/gear.svg" alt="" width="5%" onclick="abrirConfiguracion('${element.id}')"></td>
//                     </tr>
//                     `
//                     num++
//                 });

//                 $("#listado-productos").html(template);
//             } catch (error) {
//                 console.log(response);
//             }
//         }
//     })
// }

// function abrirConfiguracion(idProducto) {
//     var left = (screen.width - 1200) / 2;
//     var top = (screen.height - 650) / 4;
//     window.open("config_muestras.php?producto="+idProducto , "ventana1" , "width=1200,height=650,scrollbars=NO,Toolbar=NO,top="+ top + ", left="+ left+"")
// }