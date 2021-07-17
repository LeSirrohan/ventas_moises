
/*=============================================
  CARGAR LA TABLA DINÁMICA DE PRODUCTOS PARA LAS VENTAS
=============================================*/
$(document).ready(function() {
var url = $("#url_archivo_productos").val();
  $('#tablaVentas').DataTable( {
          "ajax": url,
          "defenRender":true,
          "retrieve":true,
          "processing":true,
            "language": {

    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
    "sFirst":    "Primero",
    "sLast":     "Último",
    "sNext":     "Siguiente",
    "sPrevious": "Anterior"
    },
    "oAria": {
      "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }

  }
  } );




} );


/*=============================================
CUANDO CARGUE LA TABLA CADA QUE SE HAYA NAVEGADO EN ELLA.
=============================================*/


$(document).on("draw.dt","#tablaVentas" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.


//NO SÉ QUE HACE ESTO
  if(localStorage.getItem('nuevaVentaquitarProducto')  != null) {

    var listaIdProductos = JSON.parse(localStorage.getItem('nuevaVentaquitarProducto'));

    for(var i = 0 ; i < listaIdProductos.length ; i++){
      $('.nuevaVentaRecuperarVenta[idProducto="' +listaIdProductos[i]["idProductoRestablecer"] + '"]').removeClass("btn-default");
      $('.nuevaVentaRecuperarVenta[idProducto="' +listaIdProductos[i]["idProductoRestablecer"] + '"]').addClass("btn-primary nuevaVentaAgregarProducto");

      //  console.log(listaIdProductos);
    }
}
//NO SÉ QUE HACE ESO ANTERIOR




//LO QUE VIENE ES PARA HACER QUE CUANDO SE FILTRE Y APAREZCA UN SOLO PRODUCTO SE AGREGUE A LA LISTA DE PEDIDOS :)

var table = $('#tablaVentas').DataTable();  


//AQUI AQUI AQUI LIBERAR
 
   if(   table.search().substring(0,2)   == '23' &&  table.search().length == 13 && table.rows( { filter : 'applied'} ).nodes().length == 0 ) {

      let nueva_cantidad = table.search().substr(7,5);
      
      console.log('nueva_cantidad', nueva_cantidad);
      
      nueva_cantidad =  nueva_cantidad / 1000;
      
      //omitimos esto si es que el ultimo no es del mismo id del producto

//      console.log("1", table.search().substring(0,7));
//      console.log("2", $(".nuevaVentaDescripcionProducto").last().attr('codigoBarra'));
      if( $(".nuevaVentaDescripcionProducto").last().attr('codigoBarra') ===  table.search().substring(0,7)  )
        { 
          $(".nuevaVentaModificarCantidadProducto").last().val(nueva_cantidad);
          calcularSubtotalFilaProducto( $(".nuevaVentaModificarCantidadProducto").last() );
        }
      
      
      
//      console.log("MODIFCAMOS LA CANTIDAD");
      
      $('.form-control-sm').val('');
      
      listarProductos();

   }

//AQUI AQUI AQUI LIBERAR   





//if(table.rows( { filter : 'applied'} ).nodes().length == 1 ){
  if(table.rows( { filter : 'applied'} ).nodes().length == 1  &&   (table.search().substring(0,2)   != '23'  ||  table.search().length == 7   )   ){
//  alert('solo 1 ');
 /*
console.log('solo1');
    //number of filtered rows
     console.log('entre');
    //filtered rows data as arrays
    console.log('tipo string',table.rows( { filter : 'applied'} ).data()[0][6] );

    console.log('modo objeto',$(table.rows( { filter : 'applied'} ).data()[0][6]) );
*/
    //console.log   (  $(table.rows( { filter : 'applied'} ).data()[0][6]).children().click('nuevaVentaAgregarProducto'));

  let idProducto= $(table.rows( { filter : 'applied'} ).data()[0][6]).attr("idProducto") ;
  let descripcion = $(table.rows( { filter : 'applied'} ).data()[0][6]).attr("descripcion") ;
  let stock = $(table.rows( { filter : 'applied'} ).data()[0][6]).attr("stock") ;
  let precio_venta = $(table.rows( { filter : 'applied'} ).data()[0][6]).attr("precio_venta") ;

  let codigo_producto_sunat = $(table.rows( { filter : 'applied'} ).data()[0][6]).attr("codigo_producto_sunat") ;
  let unidad_medida_sunat = $(table.rows( { filter : 'applied'} ).data()[0][6]).attr("unidad_medida_sunat") ;
  let tipo_afectacion_sunat = $(table.rows( { filter : 'applied'} ).data()[0][6]).attr("tipo_afectacion_sunat") ;
    console.log(codigo_producto_sunat);
  let codigo_barra =  table.search() ;




 //CODIGO PARA EVITAR EL DOBLE INGRESO DE UN MISMO PRODUCTO
 let repetido = false;
 var itemsGuardados = $(".nuevaVentaDescripcionProducto");
for(var i = 0; i < itemsGuardados.length; i++){
  if($(itemsGuardados[i]).attr('idProducto') == idProducto )
    repetido = true;
}

if(repetido && table.search().substring(0,2)   != '23')
{
  console.log('REPETIDO');
  $('.form-control-sm').val('');
  return;
}
//CODIGO PARA EVITAR EL DOBLE INGRESO DE UN MISMO PRODUCTO






/*
   console.log(idProducto);
   console.log(descripcion);
   console.log(stock);
   console.log(precio_venta);*/


  $(".tablaProductosPedido").append(

                      '<tr>'+

                        '<td>'+
                          '<button type="button" class="btn btn-default nuevaVentaDescripcionProducto" idProducto="'+idProducto+'" codigoBarra="'+codigo_barra+'"  codigo_producto_sunat="'+codigo_producto_sunat+'"  unidad_medida_sunat="'+unidad_medida_sunat+'" descripcion="'+descripcion+'"  descuento=0 descuento_motivo="no hay motivo descuento" tipo_afectacion_sunat="'+tipo_afectacion_sunat+'" data-toggle = "modal" data-target =".modalVariarProducto" style="width:100%; text-align: left">'+descripcion+'</button>'+
                        '</td>'+
                        
                        '<td><div class="mt-2 nuevoPrecioProducto" precioOriginal="'+precio_venta+'" modificacion_precio_motivo="no hay motivo modificacion precio" comentario_producto="">'+precio_venta+'</div></td>'+
                        
                        '<td><input type="number" class="form-control nuevaVentaModificarCantidadProducto" min="0" value="1"></td>'+
                        
                        '<td>'+

                          '<div class="mt-2 nuevaVentaSubtotal">'+precio_venta+'</div></td>'+
                        
                        '<td>'+
                          
                          '<div class="btn-group" style="padding: 0px 2px">'+
                            
                            '<button class="btn btn-danger nuevaVentaquitarProducto" idProductoRestablecer="'+idProducto+'" style="padding: 0px 2px;"><i class="fas fa-times" style="margin-top: 6px"></i>'+
                                 
                             '</button>'+
    
                          '</div>'+
    
                         '</td>'+
    
                      '</tr>'

    ); 



quitarAgregarProducto();
//Calcular Total de Ventas

if(   table.search().substring(0,2) != '23' )  // si es 23 esperamos a que tenga 13  ||  AQUI AQUI AQUI LIBERAR
   {
      console.log("este es");
    $('.form-control-sm').val(''); 
 
 }


 
 //borramos una vez se quedo 1 solo producto
    

listarProductos();

 //LO ANTERIOR ES PARA HACER QUE CUANDO SE FILTRE Y APAREZCA UN SOLO PRODUCTO SE AGREGUE A LA LISTA DE PEDIDOS :) 

 //  console.log('termine');

}
 


 

});
 
 
function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}



/*=============================================
FUNCIÓN PARA DESACTIVAR LOS BOTONES AGREGAR CUANDO EL PRODUCTO YA HABÍA SIDO SELECCIONADO EN LA CARPETA
=============================================*/

function quitarAgregarProducto(){

  //Capturamos todos los id de productos que fueron elegidos en la venta
  var idProductos = $(".nuevaVentaquitarProducto");

  //Capturamos todos los botones de agregar que aparecen en la tabla
  var botonesTabla = $(".nuevaVentaAgregarProducto");

  //Recorremos en un ciclo para obtener los diferentes idProductos que fueron agregados a la venta
  for(var i = 0; i < idProductos.length; i++){

    //Capturamos los Id de los productos agregados a la venta
    var boton = $(idProductos[i]).attr("idProductoRestablecer");

    //Hacemos un recorrido por la tabla que aparece para desactivar los botones de agregar
    for(var j = 0; j < botonesTabla.length; j ++){

      if($(botonesTabla[j]).attr("idProducto") == boton){

        $(botonesTabla[j]).removeClass("btn-primary nuevaVentaAgregarProducto");
        $(botonesTabla[j]).addClass("btn-default");

      }
    }

  }

}
/*=============================================
        EDITAR CATEGORIA ACTUALIZADO
=============================================*/
$(document).on("click",".nuevaVentaAgregarProducto" , function(){


 
  var idProducto = $(this).attr("idProducto");
  var descripcion = $(this).attr("descripcion");
  var stock = $(this).attr("stock");
  var precio_venta = $(this).attr("precio_venta");

  var codigo_producto_sunat = $(this).attr("codigo_producto_sunat") == '' ? $(this).attr("codigo_producto_sunat") : '' ;
  var unidad_medida_sunat = $(this).attr("unidad_medida_sunat") ;
  var tipo_afectacion_sunat = $(this).attr("tipo_afectacion_sunat");

 
 //console.log('asi debe quedar',$(this));


   $(this).removeClass("btn-primary nuevaVentaAgregarProducto");
   $(this).addClass("btn-default");

  $(".tablaProductosPedido").append(

                      '<tr>'+

                        '<td>'+
                          '<button type="button" class="btn btn-default nuevaVentaDescripcionProducto" idProducto='+idProducto+'  codigo_producto_sunat="'+codigo_producto_sunat+'"  unidad_medida_sunat="'+unidad_medida_sunat+'"   tipo_afectacion_sunat="'+tipo_afectacion_sunat+'" descripcion="'+descripcion+'" descuento=0 descuento_motivo="no hay motivo de descuento" data-toggle = "modal" data-target =".modalVariarProducto" style="width:100%; text-align: left">'+descripcion+'</button>'+
                        '</td>'+
                        
                        '<td><div class="mt-2 nuevoPrecioProducto" precioOriginal="'+precio_venta+'" modificacion_precio_motivo="no hay motivo modificacion precio" comentario_producto="">'+precio_venta+'</div></td>'+
                        
                        '<td><input type="number" class="form-control nuevaVentaModificarCantidadProducto" min="0" value="1"></td>'+
                        
                        '<td>'+

                          '<div class="mt-2 nuevaVentaSubtotal">'+precio_venta+'</div></td>'+
                        
                        '<td>'+
                          
                          '<div class="btn-group" style="padding: 0px 2px">'+
                            
                            '<button class="btn btn-danger nuevaVentaquitarProducto" idProductoRestablecer='+idProducto+' style="padding: 0px 2px;"><i class="fas fa-times" style="margin-top: 6px"></i>'+
                                 
                             '</button>'+
    
                          '</div>'+
    
                         '</td>'+
    
                      '</tr>'



    ); 


//Calcular Total de Ventas
listarProductos()
});



/*=============================================
  CALCULAR EL TOTAL DE VENTAS A PARTIR DE LOS SUBTOTALES. ACTUALIZADO
=============================================*/

function sumarTotalPrecios(){


};

function split(str) {
 var i = str.indexOf("-");

 if(i > 0)
  return  str.slice(0, i);
 else
  return "";     
}



/*===================================================================================
              LISTAR TODOS LOS PRODUCTOS PARA SER MANDADOS POR BD = ACTUALIZADO
===================================================================================*/

// CADA VEZ QUE SE MODIFIQUE LA LINEA DLE PRODUCTO, VAMOS A CALCULAR SU TOTAL. IMPUESTOS O DESCUENTOS EFECTUADOS
function listarProductos(){

  var listaProductos = [];

  var descripcion = $(".nuevaVentaDescripcionProducto");

  var cantidad = $(".nuevaVentaModificarCantidadProducto");

  var precioUnitario = $(".nuevoPrecioProducto");

  var subTotal = $(".nuevaVentaSubtotal");

  var descuento = 0 ;

  var totalPedido = 0 ;

  var totalPedidoAfectadoPorIgv  = 0 ;

  var totalPedidoImpuestoBolsas  = 0 ;


  

  for(var i = 0; i < descripcion.length; i++){



    descuento =  Number(descuento)+   Number(    $(descripcion[i]).attr("descuento")   ); //deuscuento por cada uno de los productos

    //CALCULO DEL TOTAL DEL PEDIDO
    totalPedido =  Number(totalPedido)+   Number($(subTotal[i]).html());

    //CALCULAMOS EL TOTAL DEL PEDIDO APLICABLE IGV

    let tipo_de_igv = 1 ;
    let igv = 0 ;
    let impuesto_bolsas = 0 ;
    let valor_unitario = 0 ;
      let subtotal_aux= 0;

      if($(descripcion[i]).attr("tipo_afectacion_sunat").toUpperCase() == 'GRAVADO' ){
       

        totalPedidoAfectadoPorIgv =  Number(totalPedidoAfectadoPorIgv )+   Number($(subTotal[i]).html());

        //calculamos el monto final y luego regresamos al monto inicial
       subtotal_aux   = $(precioUnitario[i]).html() *  $(cantidad[i]).val();

        igv = Number(subtotal_aux -  (  subtotal_aux  / ((100+global_igv)/100) ) ).toFixed(2)  ;
       

        valor_unitario   =  ( subtotal_aux - igv ) / $(cantidad[i]).val() ;

 
        tipo_de_igv = 1 ;   


      }

      if($(descripcion[i]).attr("tipo_afectacion_sunat").toUpperCase() == 'EXONERADO'){
        //totalPedidoAfectadoPorIgv =  Number(totalPedidoAfectadoPorIgv )+   Number($(subTotal[i]).html());

     
      valor_unitario=   $(precioUnitario[i]).html();
        subtotal_aux   = $(precioUnitario[i]).html() *  $(cantidad[i]).val();


        tipo_de_igv = 8 ;   
      }
    
      if($(descripcion[i]).attr("tipo_afectacion_sunat").toUpperCase() == 'INAFECTO'){
        //totalPedidoAfectadoPorIgv =  Number(totalPedidoAfectadoPorIgv )+   Number($(subTotal[i]).html());
        
        valor_unitario=   $(precioUnitario[i]).html();
        subtotal_aux   = $(precioUnitario[i]).html() *  $(cantidad[i]).val();
        tipo_de_igv = 9 ;   
      }

      if($(descripcion[i]).attr("tipo_afectacion_sunat").toUpperCase() == 'IMPUESTO BOLSAS'){

        totalPedidoAfectadoPorIgv =  Number(totalPedidoAfectadoPorIgv )+   Number($(subTotal[i]).html());

        //calculamos el monto final y luego regresamos al monto inicial
       subtotal_aux   = $(precioUnitario[i]).html() *  $(cantidad[i]).val();

        igv = Number(subtotal_aux -  (  subtotal_aux  / ((100+global_igv)/100) ) ).toFixed(2)  ;
       

        valor_unitario   =  ( subtotal_aux - igv ) / $(cantidad[i]).val() ;

 
        tipo_de_igv = 1 ;  

        

        impuesto_bolsas = global_impuesto_bolsas * $(cantidad[i]).val()  ;   

        totalPedidoImpuestoBolsas = totalPedidoImpuestoBolsas + impuesto_bolsas ;

     
      }


    //  totalPedido = totalPedido + totalPedidoImpuestoBolsas;
    
//console.log($(descripcion[i]).attr("descripcion"));
      
  //    igv = (  Number($(precioUnitario[i]).html() ) -  Number($(precioUnitario[i]).html() / 1.18)  *  $(cantidad[i]).val() )   ; 

  var codigo_producto_sunat = $(descripcion[i]).attr("codigo_producto_sunat") != ""? $(descripcion[i]).attr("codigo_producto_sunat") : '-';
  var unidad_medida_sunat = $(descripcion[i]).attr("unidad_medida_sunat") != ""? $(descripcion[i]).attr("unidad_medida_sunat") : '-';
    listaProductos.push({ "id" : $(descripcion[i]).attr("idProducto"),
    "codigo_producto_interno" : ("00000000" + $(descripcion[i]).attr("idProducto")).slice(-8)   ,
      "codigo_producto_sunat" : codigo_producto_sunat,
      "unidad_de_medida" :     unidad_medida_sunat,
      "tipo_afectacion_sunat" : $(descripcion[i]).attr("tipo_afectacion_sunat"),
      "descuento" : $(descripcion[i]).attr("descuento"),
      "descuento_motivo" : $(descripcion[i]).attr("descuento_motivo"),
                  "descripcion" :  $(descripcion[i]).attr("descripcion"),
                "cantidad" : $(cantidad[i]).val(),
                "precio" : $(precioUnitario[i]).html(),
                "precio_original" : $(precioUnitario[i]).attr("precioOriginal"),
                "modificacion_precio_motivo" :   $(precioUnitario[i]).attr("modificacion_precio_motivo"),   
                "comentario_producto" :   $(precioUnitario[i]).attr("comentario_producto"),   
                "subTotal" : $(subTotal[i]).html(),
                  "valor_unitario" : valor_unitario.toString(),
                  "sub_total_facturacion" :  (subtotal_aux - igv ).toString(),
                  "tipo_de_igv" : tipo_de_igv.toString(),
                  "igv" : igv.toString() ,
                  "impuesto_bolsas" : impuesto_bolsas.toString() 
              }
    );



  }


  descuento = Number(descuento) + Number($("#nuevaVentaDescuentoTotalId").val(   ));  // al descuento de los productos le sumamos el descuento del total de la cuenta.

//  let impuestos_aux =  (  totalPedidoAfectadoPorIgv -  (    totalPedidoAfectadoPorIgv   / 1.18  )).toFixed(2) ;


  $(".nuevaVentaListaProductos").val(  JSON.stringify(listaProductos)   ); 

  $(".nuevaVentaDescuentoTotal").html(  Number( descuento.toFixed(2)) );
  $(".nuevaVentaDescuentoTotal").val(  Number( descuento.toFixed(2)) );

  $(".nuevaVentaDescuentoMotivo").val( $("#nuevaVentaDescuentoTotalMotivo").val(   ) );

  $(".nuevaVentaTotalPedido").html(  totalPedido.toFixed(2)      );

  //$(".nuevaVentaImpuestoTotal").html( impuestos_aux    );
  //console.log("totalPedidoImpuestoBolsas", totalPedidoImpuestoBolsas);

  $(".nuevaVentaImpuestoBolsasTotal").html(  totalPedidoImpuestoBolsas.toFixed(2)  ) ;
  
  $(".nuevaVentaTotalPagar").html(  (totalPedido  - descuento + totalPedidoImpuestoBolsas  )  .toFixed(2));  

  $(".cantidadTotalProductos").html(  (  descripcion.length )  .toFixed(0));  


  //sumarTotalPrecios();
}

 



/*=============================================
QUITAR PRODUCTOS DE LA VENTA Y RECUPERAR EL BOTON
=============================================*/
var  idQuitarProducto ;
localStorage.removeItem("nuevaVentaquitarProducto");


$(document).on("click",".nuevaVentaquitarProducto" , function(){  // cuando este cargado
  var idProducto = $(this).attr("idProductoRestablecer");

 

// Almacenar en el local storage el producto que voy a quitar.
  if(localStorage.getItem('nuevaVentaquitarProducto')  == null)
     idQuitarProducto = [];
   else
    idQuitarProducto.concat(localStorage.getItem("quitarProducto"));

  idQuitarProducto.push({"idProductoRestablecer" : idProducto});
  localStorage.setItem("nuevaVentaquitarProducto" , JSON.stringify(idQuitarProducto));
   $('.nuevaVentaRecuperarVenta[idProducto="' +idProducto + '"]').removeClass("btn-default");
   $('.nuevaVentaRecuperarVenta[idProducto="' +idProducto + '"]').addClass("btn-primary nuevaVentaAgregarProducto");
$(this).parent().parent().parent().remove();

//Calcular Total de Ventas
listarProductos();


});






/*=============================================
FUNCIONA AUXILIAR PARA CALCULAR EL REDONDEO.
=============================================*/


function round(value, precision) {
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(value * multiplier) / multiplier;
}





/*=============================================
FUNCIONA AUXILIAR PARA CALCULAR EL REDONDEO.
=============================================*/
/*=============================================
   MODIFICAR LA CANTIDAD AL MOMENTO DE LA VENTA. ACTUALIZADO
=============================================*/
$(document).on("change",".nuevaVentaModificarCantidadProducto" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.

calcularSubtotalFilaProducto($(this));

});


/*=============================================
   MODIFICAR LA CANTIDAD AL MOMENTO DE LA VENTA. ACTUALIZADO
=============================================*/
$(document).on("keyup",".nuevaVentaModificarCantidadProducto" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.

calcularSubtotalFilaProducto($(this));

});


// CADA VEZ QUE COLOCABA ENTER AL CAMBIAR LA CANTIDAD 
// SE PRESIONABA EL BOTON DE ELIMINAR LA FILA . CON ESTO SE EVITA ESE CASO.

$(document).on("keypress", '.nuevaVentaModificarCantidadProducto', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();

calcularSubtotalFilaProducto($(this));



        return false;
    }
});




function calcularSubtotalFilaProducto(parametro) {
   
  let cantidadProducto = parametro.val();
  let precioUnitarioProducto =  parametro.parent().parent().children().next().children().html();
  let subtotal = parametro.parent().parent().children().next().next().next().children().html();

 

 let subototal = cantidadProducto * precioUnitarioProducto;

 
parametro.parent().parent().children().next().next().next().children('.nuevaVentaSubtotal').html(round(subototal, 2).toFixed(2) );

//$(this).attr("stock" , $(this).attr("stockOriginal") -  cantidadProducto );
//round(subototal, 1).toFixed(2)



  //}


//Calcular Total de Ventas
listarProductos();



}






/*=============================================
     BOTON PRINCIPAL DE GENERACION DE PAGOS.
=============================================*/
 

//dinamica de descuento

 

$(document).on("click","#btnActualizarDescuentoTotal" , function(){

    let montoCuenta  =  Number($(".nuevaVentaTotalPagar").html());
    let efectivo  = $('#checkBoxDescuentoEfectivo').is(':checked'); //para saber si es efectivo o porcentaje
    let valor = $("#descuentoTotalCuenta").val();
    let comentario = $("#descuentoTotalComentario").val();

    
// VALIDACION QUE TODO ESTE COMPLETO
    if(comentario ==='' )
    {
        alert("TIENE QUE INSERTAR EL COMENTARIO AL DESCUENTO");
        return ;

    }
 

// VALIDACION QUE TODO ESTE COMPLETO
    if(valor ==='' )
    {
        alert("TIENE QUE INSERTAR EL VALOR DEL DESCUENTO");
        return ;

    }




    if(efectivo){
    //alert(efectivo);  
      //$(".nuevaVentaDescuentoTotal").html(Number(valor).toFixed(2));
      $("#nuevaVentaDescuentoTotalId").val(valor);
    }
    else 
        {

          //alert(montoCuenta+''+valor);  
          console.log('montoCuenta', montoCuenta);
          valor = Number(valor)/100;
          console.log('valor', valor);
          let montoCuentaFinal = Number(montoCuenta) * valor;
          console.log('montoCuentaFinal', montoCuentaFinal);
          $("#nuevaVentaDescuentoTotalId").val(montoCuentaFinal);

        }

$("#nuevaVentaDescuentoTotalMotivo").val(comentario);

listarProductos();

//CERRAMOS EL MODAL
 $('#modalAgregarDescuentoTotalPedido').modal('toggle');
    
});
   
 

$(document).on("click","#btnPago" , function(e){

  // VALIDACIONES ANTES DE PROCEDER CON EL PAGO.
  

  //VALIDACION QUE NO SE VAYA A COBRAR SI ES QUE EL MONTO ESTA EN NEGATIVO PRODUCTO DE LOS DESCUENTOS
   let totalPagar  = Number($(".nuevaVentaTotalPagar").html()) ;
   //console.log("totalPagar", totalPagar);
   if(totalPagar < 0 )
   {
        alert("No se puede proceder con la venta debido a que el monto es negativo");
        return;

   }

  $('#modalPago').modal({backdrop: 'static', keyboard: false})  

});


$(document).on("click",".nuevaVentaDescripcionProducto" , function(){

  var idProducto = $(this).attr("idProducto");
  var tipo_afectacion_sunat = $(this).attr("tipo_afectacion_sunat");
  var descripcion =$(this).parent().parent().children().children().html() ;
  var precio_original = $(this).parent().parent().children().next().children().attr("precioOriginal");
  var precio_nuevo    = $(this).parent().parent().children().next().children().html();
  var modificacion_precio_motivo = $(this).parent().parent().children().next().children().attr("modificacion_precio_motivo");

  $(".modalVariarProductoDescripcion").html(descripcion); 
  $(".modalVariarProductoPrecioOriginal").val(precio_original);  
  $(".modalVariarProductoAfectacionSunat").val(tipo_afectacion_sunat);  
  $("#modalVariarProductoID").val(idProducto);

  if(precio_original == precio_nuevo){
    $("#modalVariarProductoModificacionPrecio").val(precio_original);
  }else{
    $("#modalVariarProductoModificacionPrecio").val(precio_nuevo);
  }

  if(modificacion_precio_motivo != "")
    $("#modalVariarProductoModificacionComentario").val(modificacion_precio_motivo);
  else
    $("#modalVariarProductoModificacionComentario").val('');  

  //$("#modalVariarProductoModificacionPrecio").val('');  
  $("#modalVariarComentarioVentaProducto").val('');  

});


$(document).on("click","#btnActualizarPrecio" , function(){


  let precioModificado = $("#modalVariarProductoModificacionPrecio").val(); 
  let comentario = $("#modalVariarProductoModificacionComentario").val(); 
  let comentario_producto = $("#modalVariarComentarioVentaProducto").val(); 
  let idProducto = $("#modalVariarProductoID").val(); 

 

 

// VALIDACION QUE TODO ESTE COMPLETO
    if(precioModificado !=='' )
    {
      // VALIDACION QUE TODO ESTE COMPLETO
      
          if(comentario ==='' )
          {
              alert("TIENE QUE INSERTAR EL COMENTARIO POR LA VARIACIÓN DEL PRECIO DEL PRODUCTO");
              return ;
      
          }

    }
 
 //debemos ubicar el producto para el cuál variamos el precio de venta y mostrarlo aquí

 

 
  var descripcion = $(".nuevaVentaDescripcionProducto");

  var cantidad = $(".nuevaVentaModificarCantidadProducto");

  var precioUnitario = $(".nuevoPrecioProducto");

  var subTotal = $(".nuevaVentaSubtotal"); 

  let idProducto_aux = 0;

  for(var i = 0; i < descripcion.length; i++){

      idProducto_aux = $(descripcion[i]).attr("idProducto");
            // console.log("idProducto_aux" , idProducto_aux);
      let descripcion_producto = $(descripcion[i]).attr("descripcion");

      if(idProducto_aux == idProducto) {
        if(precioModificado !=='' )
        {
          var calc = $(cantidad[i]).val() *  precioModificado;
          $(subTotal[i]).html(   calc.toFixed(2)  )  ;
          
          $(precioUnitario[i]).html(precioModificado);

          $(precioUnitario[i]).attr("modificacion_precio_motivo" , comentario);
        }
        $(precioUnitario[i]).attr("comentario_producto" , comentario_producto);
       $(descripcion[i]).html(descripcion_producto+' ('+comentario_producto+')');


       
      }


  }

listarProductos();

//CERRAMOS EL MODAL
 $('.modalVariarProducto').modal('toggle');

});
/*=============================================
              IMPRIMIR ORDEN
=============================================*/
$(document).on("click","#ImprimirOrden" , function(){
  event.preventDefault();
//JSON.stringify(listaProductos) 
var listaProductos = $(".nuevaVentaListaProductos").val();
/*var chequearImpresoras = false;
var check = new FormData();
check.append("accion", "chequearImpresoras");
check.append("id_local", $("#id_local").val() );
$.ajax({
  url        : "./ajax/impresoras.ajax.php",
  method     : "POST",
  data       : check,
  cache      : false,
  contentType: false,
  processData: false,

  success: function(resp_check_impresora)
  {
    var resp = JSON.parse(resp_check_impresora);
    chequearImpresoras= resp.chequearImpresora;
    if(chequearImpresoras == true ){*/
      $(document).ajaxStop($.unblockUI); 
      swal({
        type              : "question",
        title             : "Desea imprimir la orden?",
        showCancelButton  : true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor : "#d33",
        cancelButtonText  : "<i class='fa fa-ban'></i> Cerrar",
        confirmButtonText : "<i class='fa fa-print'></i> Imprimir Orden!",
        allowOutsideClick : false
        }).then((result) => {
          if (result.value) {
            $.blockUI({ message: "Imprimiendo" });
      
              var datos_impresora = new FormData();
              datos_impresora.append("accion", "obtenerImpresoras");
              datos_impresora.append("id_usuario", $("#usuario").val());
              $.ajax({
                url        : "./ajax/impresoras.ajax.php",
                method     : "POST",
                data       : datos_impresora,
                cache      : false,
                contentType: false,
                processData: false,
      
                success: function(respuesta_impresora)
                {
      
                  var datos_local = new FormData();
                  datos_local.append("getLocal", "yes");
                  datos_local.append("id_local", $("#id_local").val());
                  $.ajax({
                    url        : "./ajax/locales.tabla.ajax.php",
                    method     : "POST",
                    data       : datos_local,
                    cache      : false,
                    contentType: false,
                    processData: false,
      
                    success: function(resp_local)
                    {
      
                      var datos = new FormData();
                      datos.append("imprimir_orden", "yes");
                      datos.append("productos",listaProductos);
                      datos.append("impresoras",respuesta_impresora);
                      datos.append("local",resp_local);
                      $.ajax({
      
                        url        : "http://localhost/abcventas_impresion/ajax/impresion.ajax.php",
                        method     : "POST",
                        data       : datos,
                        cache      : false,
                        contentType: false,
                        processData: false,
      
                        success: function(respuesta)
                        {
                          
                          $(document).ajaxStop($.unblockUI); 
                          if(respuesta != "OK")
                          {
                            swal({
                              type            : "error",
                              title           : respuesta,
                              showCancelButton: false
                              })
                          }
                        }
                      });
      
                    }
                  });
                }
            });
          }
      });
    
    /*}
    else{
    
      swal({
        type            : "error",
        title           : "No hay ticketeras registradas",
        showCancelButton: false
        })
    }
  }
});*/
});