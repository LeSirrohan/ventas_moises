


//========================================================================================================================================================================
//========================================================================================================================================================================
//===============================================================DINÁMICA DEL COBRO=======================================================================================
//========================================================================================================================================================================
//========================================================================================================================================================================


/*=============================================
        AGREGAR METODO DE PAGO , DINÁMICA DEL COBRO
=============================================*/
$(document).on("click",".agregarMetodoPago" , function(){
 

  $(".listaMetodoPago").append(
   '<div class="container mt-1 py-1 bg-default" style="border: 1px solid #c4c4c4; border-radius: 5px">'+
      '<label for="" >Medio de pago</label>'+
      '<div class = "form-group row">'+
        '<div class  = "col-xs-12 col-sm-6">'+
          '<label for="">Importe</label>'+
          '<div class = "input-group mb-3">'+
            '<div class="input-group-prepend">'+
              '<span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>'+
            '</div>'+
            '<input type="number" class="form-control input-lg nuevaVentaPagoMonto"  idPago="0"  step = "any"  min ="0" placeholder="Monto Pago">'+
          '</div>'+
        '</div>'+      
        '<div class  = "col-xs-12 col-md-6">'+
          '<label>Tipo Pago</label>'+
          '<div class = "form-group">'+
            '<select class ="form-control select2 nuevaVentaTipoPago" style="width: 100%;" required>'+
            '</select>'+
          '</div>'+
        '</div>'+  
        '<div class  = "col-xs-12 col-md-6">'+
          '<label>Forma Pago</label>'+
          '<div class = "form-group">'+
            '<select class ="form-control select2 nuevaVentaFormaPago" style="width: 100%;" required>'+
            '</select>'+
          '</div>'+
        '</div>'+
      '</div>'+
      '<div class = "form-group row" style="margin-top:-15px">'+
        '<div class  = "col-lg-10">'+
          '<label for="">Nota de pago</label>'+
          '<input type="text" name="" value="" class="form-control nuevaVentaNotaPago">'+
        '</div>'+      
        '<div class  = "col-lg-2">'+
          '<label for=""></label>'+
          '<button type="button" class="btn btn-danger mt-4 ml-2 eliminarFormaDePago"><i class="far fa-trash-alt"></i></button>'+
        '</div>'+
      '</div>'+
    '</div>'


    ); 

 /* $(".nuevaVentaTipoPago").last().html($("#nuevaVentaTipoPagoOriginal").html());

  $("#nuevaVentaTipoPagoOriginal").select2();
  
  $(".nuevaVentaTipoPago").last().select2();*/


    $(".nuevaVentaTipoPago").last().html($(".nuevaVentaTipoPago").html());

  $(".nuevaVentaTipoPago").select2();
  
  $(".nuevaVentaTipoPago").last().select2();


//Calcular Total de Ventas
//sumarTotalPrecios();
});



/*=============================================
        ELIMINAR FORMA DE PAGO
=============================================*/
$(document).on("click",".eliminarFormaDePago" , function(){

$(this).parent().parent().parent().remove();

});


/*=============================================
        DINAMICA PARA LOS TIPOS DE COBRO, QUE SE ABRAN UNOS U OTROS CUANDO SE OPRIMAN
=============================================*/
$(document).on("click",".tipoComprobanteBoton" , function(){

 $('.tipoComprobanteBoton').prop('disabled', true);
 
$(".tipoComprobanteBoton").removeClass("btn-success");
$(".tipoComprobanteBoton").addClass("btn-secondary");
 
//$("#collapseExample1").removeClass("show");
//$("#collapseExample2").removeClass("show");

$(this).addClass("btn-success");
$(this).attr("aria-expanded",true);

  //Your ajax code

//remove disable attribute after 2 second.
  setTimeout(function() {
  $('.tipoComprobanteBoton').prop('disabled', false);

  }, 400);

});


/*=============================================
        CUANDO APARECE EL MODAL HACER LO SIGUIENTE.
=============================================*/
 
    listarClientes();





/*=============================================
  CALCULAR EL TOTAL DE VENTAS A PARTIR DE LOS SUBTOTALES. ACTUALIZADO
=============================================*/

function calculoDiferencia(){
 
  let totalCobros = $(".nuevaVentaPagoMonto"); //total de cobros realizados


  var listaCobros = [];

  let montoTotal=    Number($(".nuevaVentaTotalPagar").html())
  
  let totalCobrado = 0 ;
  
  let diferencia = 0 ;


 

  for(var i = 0 ; i < totalCobros.length ; i++){
    totalCobrado = Number(totalCobrado)+   Number($(totalCobros[i]).val());
  }

  diferencia = Number(totalCobrado) -  Number(montoTotal);

  //console.log( 'Total Cuenta' , montoTotal);
  //console.log( 'Total Cobrado' , totalCobrado);
  //console.log( 'Diferencia' , diferencia);

$(".nuevaVentaTotalCobrado").html(totalCobrado.toFixed(2));
$(".nuevaVentaDiferencia").html(  diferencia.toFixed(2)  );

$("[name=nuevaVentaVuelto]").val(  diferencia.toFixed(2)  );

 

 return diferencia;
 
};


 





const findDuplicates = (arr) => {
  let sorted_arr = arr.slice().sort(); // You can define the comparing function here. 
  // JS by default uses a crappy string compare.
  // (we use slice to clone the array so the
  // original array won't be modified)
  let results = [];
  for (let i = 0; i < sorted_arr.length - 1; i++) {
    if (sorted_arr[i + 1] == sorted_arr[i]) {
      results.push(sorted_arr[i]);
    }
  }
  return results;
}




/*=============================================
  GUARDAR CLIENTES. ACTUALIZADO
=============================================*/

function  listarComprobante(){

  
    var comprobante = [];

let identificador = "";     
let nombre = "";     
let email  = "";     
let direccion = ""; 
let formaPago = ""; 
let tipoPago = ""; 
let codcliente = ""; 
let tipoComprobante = "No Info";
 
codcliente = $("#crearVentaCodCliente").val();     
identificador = $("#crearVentaClienteIdentificadorFactura").val();     
nombre = $("#crearVentaClienteNombreFactura").val();     
email  = $("#crearVentaClienteEmailFactura").val();     
direccion = $("#crearVentaClienteDireccionFactura").val(); 
tipoComprobante = $("#nuevaVentaTipoCpeOriginal").val();
formaPago = $("#nuevaVentaFormaPagoOriginal").val();
tipoPago = $("#nuevaVentaTipoPagoOriginal").val();


         comprobante.push({  
                            "identificador" : identificador ,

                           "nombre" :  nombre ,

                           "email" :  email  ,
                            
                            "direccion" : direccion , 
      
                            "tipo_comprobante" : tipoComprobante  , 
      
                            "forma_pago" : formaPago , 
      
                            "codcliente" : codcliente , 
      
                            "tipo_pago" : tipoPago 

 
                          });

 
console.log(comprobante);

//LISTAMOS LOS METODOS DE PAGO
$('[name=nuevaVentaComprobante]').val( JSON.stringify(comprobante)     );

 

};




/*=============================================
  LISTAR COBROS REALIZADOS. ACTUALIZADO
=============================================*/

function  listarCobrosRealizado(){

  let totalCobros = $(".nuevaVentaPagoMonto"); //total de cobros realizados

  let tipoCobros = $(".nuevaVentaTipoPago"); //       $(".nuevaVentaTipoPago").select2('data')[0].text  

  let notaCobros = $(".nuevaVentaNotaPago"); //tipo de cobros realizados
  
//

  var listaCobros = [];
 
  for(var i = 0 ; i < totalCobros.length ; i++){
    
        listaCobros.push({  
                            "id_pago" : $(totalCobros[i]).attr("idPago"),

                            "montoCobro" : $(totalCobros[i]).val(),

                           "tipoCobros_id" :  $(tipoCobros[i]).select2('data')[0].id  ,

                           "tipoCobros_nombre" :  $(tipoCobros[i]).select2('data')[0].text  ,
                            
                            "notaCobro" : $(notaCobros[i]).val()
 
                          });


  }

  //console.log( 'Total Cuenta' , montoTotal);
  //console.log( 'Total Cobrado' , totalCobrado);
  //console.log( 'Diferencia' , diferencia);
 

//LISTAMOS LOS METODOS DE PAGO
$('[name=nuevaVentaFormaCobro]').val( JSON.stringify(listaCobros)     );


};



 
 $(document).on("change",".nuevaVentaPagoMonto" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.
//alert('x');
calculoDiferencia();

});







/*
 $(document).on("focus",".nuevaVentaPagoMonto" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.
console.log('x');


});*/



/*=============================================
   MODIFICAR LA CANTIDAD AL MOMENTO DE LA VENTA. ACTUALIZADO
=============================================*/
$(document).on("keyup",".nuevaVentaPagoMonto" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.
//alert('x');
calculoDiferencia();

});


// CADA VEZ QUE COLOCABA ENTER AL CAMBIAR LA CANTIDAD 
// SE PRESIONABA EL BOTON DE ELIMINAR LA FILA . CON ESTO SE EVITA ESE CASO.

$(document).on("keypress", '.nuevaVentaPagoMonto', function (e) {
  //alert('x');

    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();

calculoDiferencia();

        return false;
    }
});





// DINAMICA DEL COBRO CUANDO PRESIONAN EL MONTO DEL EFECTIVO RAPIDO
 $(document).on("click","#cobroPagoTotal" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.

  $(".nuevaVentaPagoMonto").last().val(  $(this).html()  );
  calculoDiferencia();

});

 $(document).on("click",".cobroPagoMonto" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.

  let monto_actual =  Number($(".nuevaVentaPagoMonto").last().val() );
  let monto_pagado =  Number( $(this).html() );

  monto_actual = Number(monto_actual) +   Number(monto_pagado)  ;

  $(".nuevaVentaPagoMonto").last().val(  monto_actual  );

  calculoDiferencia();

});




// DINAMICA DEL COBRO CUANDO PRESIONAN EL MONTO DEL EFECTIVO RAPIDO
 $(document).on("click","#limpiarPago" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.

  $(".nuevaVentaPagoMonto").last().val(  ''  );

  calculoDiferencia();

});
// DINAMICA DEL COBRO CUANDO PRESIONAN EL MONTO DEL EFECTIVO RAPIDO
 $(document).on("click",".tipoComprobanteBoton" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.
  var tipoComprobante = $(this).attr("tipocomprobante");
  if(tipoComprobante === "ticket")
  {
    
  }

});
function listarClientes ()
{
  var datos = new FormData();
  datos.append("ListadoClientes","yes");
  
  $.ajax({
    url: "ajax/clientes.ajax.php",
    method : "POST",
    data: datos,
    cache: false,
    contentType : false,
    processData : false,
    dataType : "json" ,
    success: function(resp){
      var select = $("#nuevaVentaSeleccionarCliente1,#nuevaVentaSeleccionarCliente2,#nuevaVentaSeleccionarCliente3");
      var option = '<option value="">Seleccione...</option>';
      select.html('');
      resp.forEach(function (item, index, array) {
        //console.log(item[0]);
        option += '<option value="' +item.id_documento + '|' +item.nombre + '|' +item.direccion+ '|' +item.codcliente+ '">' + item.id_documento+"-"+ item.nombre_comercial + '</option>';
        
      });          
      select.append(option);
      select.select2({
        placeholder: "SELECCIONE"
      });
      
      var id_documento = $("#id_documento_cliente").val();
      if(id_documento != "")
      {         
        select.val(id_documento).trigger("change.select2");
        var id = $("#id_cliente").val();
        var nombre = $("#nombre_cliente").val();

        $("#crearVentaClienteIdentificadorTicket").val(id);
        $("#crearVentaClienteNombreTicket").val(nombre);

        $("#crearVentaClienteIdentificadorBoleta").val(id);
        $("#crearVentaClienteNombreBoleta").val(nombre);

        $("#crearVentaClienteIdentificadorFactura").val(id);
        $("#crearVentaClienteNombreFactura").val(nombre);
      }
      select.change(function(data) {
        //var select_id = "tipo_constancia"+ficha
        //var sel      = document.getElementById(select_id);
        //var selected = sel.options[sel.selectedIndex];
        //var tipo     = selected.getAttribute('data-id');
        //var archivo  = selected.getAttribute('data-arc');
      });
      //select.val(tipo_cobro_edit).trigger("change.select2");

    }
  });
}
$(document).on("change","#nuevaVentaSeleccionarCliente3" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.
  var cliente1 = $(this).val();
  var cliente = [];
  cliente = cliente1.split("|");
  console.log(cliente);
  $("#crearVentaClienteIdentificadorFactura").val("");
  $("#crearVentaClienteNombreFactura").val("");
  $("#crearVentaClienteDireccionFactura").val("");
  $("#crearVentaClienteEmailFactura").val("");
  $("#crearVentaCodCliente").val("");

  $("#crearVentaClienteIdentificadorFactura").val(cliente[0]);
  $("#crearVentaClienteNombreFactura").val(cliente[1]);
  $("#crearVentaClienteDireccionFactura").val(cliente[2]);
  $("#crearVentaCodCliente").val(cliente[3]);

});

