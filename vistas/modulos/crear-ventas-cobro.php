

<?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/dist/js/blockui.js"></script>
<script src="vistas/js/dinamica-cobro.js?<?= date("smH") ?>"></script>
<script>

  //generamos la venta
  


/*=============================================
     BOTON PRINCIPAL DE GENERACION DE PAGOS.
=============================================*/
  $(document).on("click","#generarVenta" , function(){
 
console.log("si");

 listarCobrosRealizado();

 listarComprobante();

let facturacion_electronica = 0;
 
//Si es Factura debemos validar que los datos esten completos
if ( facturacion_electronica == 0 &&  ($('#collapseFactura').hasClass('show')  ||  $('#collapseBoleta').hasClass('show') ) )
{
    alert("NO ESTA HABILITADA LA FACTURACIÓN ELECTRÓNICA. ELIJE EL COMPROBANTE TIPO TICKET");
    return;

}


if ( $('#collapseFactura').hasClass('show') ) { // Factura;

 let identificador = $("#crearVentaClienteIdentificadorFactura").val();     
 let nombre = $("#crearVentaClienteNombreFactura").val();     
 var email  = $("#crearVentaClienteEmailFactura").val();     
 let direccion = $("#crearVentaClienteDireccionFactura").val(); 
 $("#crearVentaClienteEmailFactura").prop("required","true");
//console.log("nombre",nombre);
//console.log("identificador",identificador);
//console.log("direccion",direccion);





  if(identificador==""){
    alert("EL RUC NO PUEDE SER VACIO");
    return;
  }
  if(identificador.length != 11){
    alert("EL RUC DEBE CONTENER 11 CARACTERES");
    return;
  }

  if(nombre==""){
    alert("LA RAZON SOCIAL NO PUEDE ESTAR EN VACIO");
    return;
  }
  if(nombre.length<3){
    alert("LA RAZON SOCIAL NO PUEDE SER MENOR A 3 CARACTERES");
    return;
  }

    if(direccion==""){
    alert("LA DIRECCIÓN NO PUEDE ESTAR EN VACIO");
    return;
  }
  if(email != ""){
    var patron = '[a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*@[a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{1,5}';
    var pat = new RegExp(patron);
    if(!pat.test(email))
    {
      //console.log(pat.test(email));
      alert("ESCRIBA UNA DIRECCIÓN DE CORREO VÁLIDO");
      return;

    }
  }
  var doschar = identificador.substr(0,2);
  if(doschar != "10" && doschar != "15" && doschar != "17" && doschar != "20")
  {
    alert("INGRESE UN RUC VÁLIDO");
    return;
  }
  /*let patron = "[a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*@[a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{1,5}";
  if(patron.test(email))
  {
    console.log(patron.test(email));
    alert("ESCRIBA UN CORREO VALIDO");
    return;

  }*/

 
}

if ( $('#collapseBoleta').hasClass('show') ) { // Boleta;
//VALIDACIÓN, SI EL NOMBRE DEL CLIENTE ESTÁ VACÍO
  let nombre_boleta = $("#crearVentaClienteNombreBoleta").val();     
  let identificador_boleta = $("#crearVentaClienteIdentificadorBoleta").val();  
  if(identificador_boleta.length > 0 && nombre_boleta.length < 3){
    alert("EL NOMBRE DEL CLIENTE NO PUEDE SER MENOR A 3 CARACTERES");
    return;
  }  
  if(identificador_boleta.length != 8 && identificador_boleta.length != 0  && !identificador_boleta.includes("VAR-")){
    alert("EL DNI DEBE CONTENER 8 CARACTERES");
    return;
  }
 
}

//VALIDACIONES: NO PERMITIR DOS TIPO DE COBROS IGUALES
 let tipoCobros = $(".nuevaVentaTipoPago"); //       $(".nuevaVentaTipoPago").select2('data')[0].text  
 let listaCobros = []; 
  
for(let i = 0 ; i < tipoCobros.length ; i++) listaCobros.push( $(tipoCobros[i]).select2('data')[0].text ); 

if( findDuplicates(listaCobros).length >0 ){
 alert("No se permite dos tipos de cobro iguales") ;
event.preventDefault(); 
return;

}


//VALIDACIONES: NO PERMITIR MONTOS DE COBRO EN VACIO Y TAMPOCO EN NEGATIVOS
  let monto_vacio = false;
  let monto_negativo = false;

  let totalCobros = $(".nuevaVentaPagoMonto"); //total de cobros realizados
 
  for(let i = 0 ; i < totalCobros.length ; i++){
    
      if( $(totalCobros[i]).val() ===''  ){
          monto_vacio= true;
          break;
      }    

      if( $(totalCobros[i]).val() < 0   ){
          monto_negativo= true;
          break;
      }    
  }

if(monto_vacio){
    alert("No se permiten cobros en vacio") ;  
    event.preventDefault(); 
    return;
}
let cantidad_produc = $(".cantidadTotalProductos").html();
if(cantidad_produc<1){
    alert("No se permite venta sin productos") ;  
    event.preventDefault(); 
    return;
}


if(monto_negativo){
    alert("No se permiten cobros negativos") ;  
    event.preventDefault(); 
    return;
}



//VALIDACIONES: NO PERMITIR QUE EL TOTAL COBRADO SEA MENOR AL TOTAL DE LA CUENTA
if ( calculoDiferencia() < 0 ) {
  alert("No se ha completado el total de la cuenta") ;  
    event.preventDefault(); 
    return;
}



//VALIDACIONES: NO PERMITIR QUE METODOS DE COBRO QUE NO SON EFECTIVO SEA MAYOR AL TOTAL
  let totalCuenta = Number($(".nuevaVentaTotalPagar").html()); 
  
for(let i = 0 ; i < tipoCobros.length ; i++)
  if(  $(tipoCobros[i]).select2('data')[0].text.charAt(0) =='O' && $(totalCobros[i]).val() > totalCuenta  ){

          alert("No se permite cobros que no sean efectivo mayor al total de la cuenta") ;
          event.preventDefault(); 
          return;
        
  }     


//VALIDACIONES: NO PERMITIR GENERAR FACTURAS O BOLETAS SI ES QUE NO HAY IGV.


//VALIDACIONES: NO PERMITIR MONTOS DE COBRO EN VACIO Y TAMPOCO EN NEGATIVOS

  let total_productos_enviar = JSON.parse ( $(".nuevaVentaListaProductos").val() ) ; //total de cobros realizados
  let total_igv_aux = 0 ;
 

  for(let i = 0 ; i < total_productos_enviar.length ; i++)
   total_igv_aux  = total_igv_aux  +  total_productos_enviar[i]['igv'];


if ( total_igv_aux == 0 &&  ($('#collapseFactura').hasClass('show')  ||  $('#collapseBoleta').hasClass('show') ) )
{
    alert("SELECCIONE EL COMPROBANTE TIPO TICKET YA QUE NO TIENE IGV QUE PROCESAR EN LA VENTA");
    return;

}

  


// SI TODO FUE SATISFACTORIO, GUARDAMOS LA VENTA
 
    let id_sesion_caja_par =1 ;
    


    let listaProductos_par =   $('[name=nuevaVentaListaProductos]').val();
    let listaCobros_par =   $('[name=nuevaVentaFormaCobro]').val();
    let descuento_par =   $('[name=nuevaVentaDescuentoTotal]').val();
    let descuentoMotivo_par =   $('[name=nuevaVentaDescuentoMotivo]').val();
    let vuelto_par =   $('[name=nuevaVentaVuelto]').val();
    let comprobante_par =   $('[name=nuevaVentaComprobante]').val();
    let info_cliente =   $('[name=nuevaVentaInfoCliente]').val();
    let comentario_venta =   $('[name=comentario_venta]').val();




    let id_vendedor_par = "<?php echo $_SESSION["id"]   ?>" ;
    let nombre_vendedor_par = "<?php echo $_SESSION['nombre']   ?>" ;
    let id_local_par = 1;

    var datos = new FormData();
    datos.append("id_sesion_caja",id_sesion_caja_par);
    datos.append("listaProductos",listaProductos_par    );
    datos.append("listaCobros", listaCobros_par  );
    datos.append("descuento",descuento_par);
    datos.append("descuentoMotivo",descuentoMotivo_par);
    datos.append("vuelto",vuelto_par);
    datos.append("id_vendedor",id_vendedor_par);
    datos.append("nombre_vendedor",nombre_vendedor_par);
    datos.append("id_local",id_local_par);
    datos.append("comprobante",comprobante_par);
    datos.append("comentario_venta",comentario_venta);


//console.log("datos",datos);


  $(".modal").css("z-index","1000");
  $.blockUI({ message: "Generando Venta" });
  $.ajax({
    url        : "ajax/finalizar-ventas.ajax.php",
    method     : "POST",
    data       : datos,
    cache      : false,
    contentType: false,
    processData: false,
    dataType   : "json",
    success:function(respuesta){
      var id_venta = respuesta.venta;
      $(document).ajaxStop($.unblockUI); 
      swal({
        type              : "success",
        title             : "La venta ha sido guardada correctamente",
        showCancelButton  : true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor : "#d33",
        cancelButtonText  : "<i class='fa fa-ban'></i> Cerrar",
        confirmButtonText : "<i class='fa fa-print'></i> Imprimir comprobante!",
        allowOutsideClick : false
        }).then((result) => {
          /*if (result_.value) {
          swal({
            type              : "info",
            title             : "Formato del Comprobante?",
            showCancelButton  : true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor : "#d33",
            cancelButtonText  : "<i class='fa fa-file'></i> A4",
            confirmButtonText : "<i class='fa fa-print'></i> TICKET!",
            allowOutsideClick : false
            }).then((result) => {*/
              if (result.value) {
                $(".modal").css("z-index","1000");
                $.blockUI({ message: "Imprimiendo" });

                var datos_comprobante = new FormData();
                datos_comprobante.append("getComprobante", "yes");
                datos_comprobante.append("id_venta", respuesta.venta);
                datos_comprobante.append("id_comprobante", respuesta.comprobante);

                $.ajax({

                  url        : "./ajax/ventas.ajax.php",
                  method     : "POST",
                  data       : datos_comprobante,
                  cache      : false,
                  contentType: false,
                  processData: false,

                  success: function(respuesta_comprobante)
                  { 

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
                        datos_local.append("id_local", id_local_par);
                        $.ajax({
                          url        : "./ajax/locales.tabla.ajax.php",
                          method     : "POST",
                          data       : datos_local,
                          cache      : false,
                          contentType: false,
                          processData: false,

                          success: function(resp_local)
                          {
                            //bucle para imprimir en serie  a todos las impresoras de ticket
                            var impresoras = JSON.parse(respuesta_impresora);
                            impresoras.forEach(element_impresora => {
                              if(element_impresora.formato == "TICKET"){
                              var impresora = JSON.stringify(element_impresora);
                              var datos = new FormData();
                              datos.append("imprimir_comprobante", "yes");
                              datos.append("comprobantes",respuesta_comprobante);
                              datos.append("impresoras",impresora);
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
                                      }).then(function(result){
                                        window.location = "crear-ventas";

                                    });
                                  }
                                  else{
                                    window.location = "crear-ventas";

                                  }
                                }
                              });}else{
                                event.preventDefault();
                                var id_local = $("#id_local").val();
                                var ruta = "ajax/comprobante-formato.ajax.php?id_venta="+id_venta+"&id_local="+id_local;
                                window.open(ruta,"_NEW");
                                window.location = "crear-ventas";
                                return;

                              }

                            });
                          }
                        });
                      }
                    });    
                  }
                });
              /*}
              else
              {
                event.preventDefault();
                var id_local = $("#id_local").val();
                var ruta = "ajax/comprobante-formato.ajax.php?id_venta="+id_venta+"&id_local="+id_local;
                window.open(ruta,"_NEW");
                window.location = "crear-ventas";
                return;
              }
            
            });*/
          }
          else{
            window.location = "crear-ventas";

          }

        });
      }
    });


 });


</script>



 