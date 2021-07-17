 
$(document).on("click",".btnSincronizarProductos" , function(){
	var url_tienda = $(this).attr("url_tienda");
	var id_local = $("#id_local").val();
	swal({
		title: "¿Quiere sincronizar los productos?",
		text: "¡Si no lo está puede cancelar está acción!",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		cancelButtonText: "Cancelar",
		confirmButtonText: "Sincronizar!"
	}).then(function(result){
		if(result.value){
			var datos = new FormData();
			datos.append("getProductos","getProductos");
			datos.append("id_local",id_local);
			$.ajax({
				url : "ajax/productos.ajax.php",
				method: "POST",
				data : datos,
				cache: false,
				contentType : false,
				processData : false ,
				success: function(respuesta){
					var listaProductos = respuesta;
					var datos_producto = new FormData();
					datos_producto.append("sincronizarProductos","sincronizarProductos");
					datos_producto.append("listaProductos",listaProductos);
					$.ajax({
						url : url_tienda+"/ajax/productos.ajax.php",
						method: "POST",
						data : datos_producto,
						cache: false,
						contentType : false,
						processData : false ,
						success: function(respuesta){
							
								swal({
									title: "Sincronización",
									html: respuesta,
									type: "info",
									confirmButtonText: "¡Cerrar!"
							});
						}
					});
				}
			});	
		}
	});
});

/*=============================================
	CARGAR LA TABLA DINÁMICA DE PRODUCTOS
=============================================*/
$(document).ready(function() {
	
    var crear_productos = $("#crear_productos").val();
    if( crear_productos == 1){
        $("#obtenerProductosLocal").hide();
        $("#btnAgregarProducto").show();
    }
    else{
        $("#obtenerProductosLocal").show();
        $("#btnAgregarProducto").hide();
    }
	$('#tablaProductos').DataTable( {
		"ajax" : 
		{
			"url"  : "ajax/productos-tabla.ajax.php",
			"data" : 
			{
				"crear_productos" : crear_productos
			},
			"method" : "POST"
		},
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
=         SUBIENDO LA FOTO DEL PRODUCTO        =
=============================================*/
 
//Si suben una nueva imagen la validamos.
$("#cargarImagenProductoCreacion").change(function(){
	var imagen = this.files[0];
	//console.log("imagen", imagen);
	// validamos que la imagen sea jpg o png
	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
		$("#cargarImagenProductoCreacion").val("");
		swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		});
	} else 	if(imagen["size"] > 2000000 ){
		$("#cargarImagenProductoCreacion").val("");
		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen no debe pesar más de 2MB!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });	
	} else  { //caso de exito
 		var datosImagen = new FileReader;
 		datosImagen.readAsDataURL(imagen);
 		$(datosImagen).on("load", function(event){
 			var rutaImagen = event.target.result;
 			$("#previsualizarImagenProductoCreacion").attr("src", rutaImagen);
 		})
	}
});


/*=============================================
=  EDITANDO EL PRODUCTO - PREVISUALIZAR LA IMAGEN       =
=============================================*/

$("#cargarImagenProductoEdicion").change(function(){
	var imagen = this.files[0];
	console.log("imagen", imagen);
	// validamos que la imagen sea jpg o png
	if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
		$("#cargarImagenProductoEdicion").val("");
		swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen debe estar en formato JPG o PNG!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		});
	} else 	if(imagen["size"] > 2000000 ){
		$("#cargarImagenProductoEdicion").val("");
		 swal({
		      title: "Error al subir la imagen",
		      text: "¡La imagen no debe pesar más de 2MB!",
		      type: "error",
		      confirmButtonText: "¡Cerrar!"
		    });	
	} else  { //caso de exito
 		var datosImagen = new FileReader;
 		datosImagen.readAsDataURL(imagen);
 		$(datosImagen).on("load", function(event){
 			var rutaImagen = event.target.result;
 			$("#previsualizarImagenProductoEdicion").attr("src", rutaImagen);
 		})
	}
});


/*=============================================
=         EDITAR PRODUCTO         =
=============================================*/
 

$(document).on("click",".btnEditarProducto" , function(){
 
var idProducto = $(this).attr("idProducto");
var descripcionEditarProducto = $(this).attr("descripcionEditarProducto");
var codigoEditarProducto = $(this).attr("codigoEditarProducto");
var precVentaEditarProducto = $(this).attr("precVentaEditarProducto");
var categNombreEditarProducto = $(this).attr("categNombreEditarProducto");
var categIDEditarProducto = $(this).attr("categIDEditarProducto");
var imagenEditarProducto = $(this).attr("imagenEditarProducto");
var codigo_producto_sunat = $(this).attr("codigo_producto_sunat");

var unidad_medida_sunat = $(this).attr("unidad_medida_sunat");

var tipo_afectacion_sunat = $(this).attr("tipo_afectacion_sunat");


/*	console.log(idProducto);
	console.log(descripcionEditarProducto);
	console.log(codigoEditarProducto);
	console.log(precCompraEditarProducto);
	console.log(precVentaEditarProducto);

	console.log(imagenEditarProducto);*/

 //console.log('xx');
 //console.log(imagenEditarProducto);
//$('#editarProductoCategoria').select2('data', {id: categIDEditarProducto , text: categNombreEditarProducto });
 
//Seteamos la categoria
$("#editarProductoCategoria").select2();
$("#editarProductoCategoria").val(categIDEditarProducto).trigger("change");


$("#editarCodigoBarras").val(codigoEditarProducto);
$("#editarCodigoSunat").val(codigo_producto_sunat);

$("#editarDescripcion").val(descripcionEditarProducto);

 
$('[name=editarProductoUnidadSunat]').select2();
$('[name=editarProductoUnidadSunat]').val(unidad_medida_sunat).trigger("change");
 
 

$('[name=editarProductoAfectacion]').select2();
$('[name=editarProductoAfectacion]').val(tipo_afectacion_sunat).trigger("change");
 

$('[name=editarPrecioVenta]').val(precVentaEditarProducto);

$('[name=editarIdProducto]').val(idProducto);
 

$("#previsualizarImagenProductoEdicion").attr("src",  imagenEditarProducto );
 $('[name=previsualizarCargarImagenProductoEdicion]').val(imagenEditarProducto); 
//$(".previsualizarProductos").attr("src",  imagenEditarProducto );


});
 



/*=============================================
=         ELIMINAR PRODUCTO         =
=============================================*/
 


 

$(document).on("click",".btnEliminarUnidadMedidaProducto" , function(){
 
	var idUnidadMedidaProducto = $(this).attr("idUnidadMedidaProducto");
	 
	//console.log(idUnidadMedidaProducto);
		 
	 		swal({
		      title: "¿Está seguro que desea eliminar el producto?",
		      text: "¡Si no lo está puede cancelar está acción!",
		      type: "warning",
		      showCancelButton: true,
		      confirmButtonColor: "#3085d6",
		      cancelButtonColor: "#d33",
		      cancelButtonText: "Cancelar",
		      confirmButtonText: "Si, borrar el producto!"
		}).then(function(result){
			if(result.value){
						var datos = new FormData();
						datos.append("idUnidadMedidaProducto",idUnidadMedidaProducto);
						$.ajax({
							url : "ajax/productos.ajax.php",
							method: "POST",
							data : datos,
							cache: false,
							contentType : false,
							processData : false ,
							success: function(respuesta){
								window.location ='productos'; 
							}
						});	
			}
		});

 

});
 
 
 
 

$(document).on("click",".btnEliminarProducto" , function(){
 
	var idProducto = $(this).attr("idProducto");
	var imagen = $(this).attr("imagen");

		console.log(idProducto);
		console.log(imagen);



	 		swal({
		      title: "¿Está seguro que desea eliminar el producto?",
		      text: "¡Si no lo está puede cancelar está acción!",
		      type: "warning",
		      showCancelButton: true,
		      confirmButtonColor: "#3085d6",
		      cancelButtonColor: "#d33",
		      cancelButtonText: "Cancelar",
		      confirmButtonText: "Si, borrar el producto!"
		}).then(function(result){
			if(result.value){
						var datos = new FormData();
						datos.append("idEliminarProducto",idProducto);
						datos.append("imagen",imagen);
						$.ajax({
							url : "ajax/productos.ajax.php",
							method: "POST",
							data : datos,
							cache: false,
							contentType : false,
							processData : false ,
							success: function(respuesta){
								window.location ='productos'; 
							}
						});	
			}
		});


});
 
 



/*=============================================
=           PRUEBAs      =
=============================================
  */


$(document).on("click",".btnUnidadMedidaProducto" , function(){
 


 	var idProducto = $(this).attr("idProducto");
	$('[name=idProductoUnidadMedida]').val(idProducto);

    
    console.log(idProducto);

    var datosx = new FormData();
  	datosx.append("idProducto", idProducto);
 


 $.ajax({
    url: "ajax/productos-medida.tabla.ajax.php",
    method : "POST",
    data: datosx,
    cache: false,
    contentType : false,
    processData : false,
    dataType : "json" ,
    success: function(respuesta){
 console.log(respuesta);
           $('.tablaUnidadMedidaProducto').DataTable( {
              "data": respuesta,
               "destroy": true,
    			"searching": false,
    			"paging": false
    } );



        
    }
  })





 });


 var generarCodigoBarras = function ( )
 {
 
	var datosx = new FormData();
	datosx.append("action","chequearEAN");

	$.ajax({
		url        : "ajax/productos.ajax.php",
		method     : "POST",
		data       : datosx,
		cache      : false,
		contentType: false,
		processData: false,
		dataType   : "json",
		success: function(respuesta)
		{
			$("#nuevoCodigo").val( respuesta[0].random_num );

		}
	});
 };

 var generarEditCodigoBarras = function ( )
 {
 
	var datosx = new FormData();
	datosx.append("action","chequearEAN");

	$.ajax({
		url        : "ajax/productos.ajax.php",
		method     : "POST",
		data       : datosx,
		cache      : false,
		contentType: false,
		processData: false,
		dataType   : "json",
		success: function(respuesta)
		{
			$("#editarCodigoBarras").val( respuesta[0].random_num );
		}
	});
 };
 /*==============================================
 =			GENERAR CÓDIGOS DE BARRAS			=
 ================================================*/ 
 
$(document).on("click",".btnGenerarCodBarras" , function()
{
	generarCodigoBarras( );	
});

/*==============================================
=			GENERAR CÓDIGOS DE BARRAS			=
================================================*/ 

$(document).on("click",".editGenerarCodBarras" , function()
{
   generarEditCodigoBarras( );	
});

/*=============================================
=           IMPRIMIR CODIGO DE BARRA      =
=============================================
  */


 $(document).on("click",".btnPrintLabel" , function(){
	var id_producto = $(this).attr("idproducto");
	var ruta = "ajax/etiquetas.ajax.php?codProd="+id_producto;
	window.open(ruta,"_NEW");

});

$(document).on("click","#obtenerProductosLocal" , function()
{
	$.blockUI({ message: "<img src='./content-download/loader_1.gif' width='25%' height='25%' /><br><h5>Enviando...</h5>" });
	$("div.blockUI.blockOverlay").css("z-index","1039");
	$("div.blockUI.blockMsg.blockPage").css("z-index","1040");
	var ruta         = $("#ruta").val();
	var id_local     = $("#id_local").val();
	var nombre_local = $("#nombre_local").val();

	var datos = new FormData();
	datos.append("getProductosApi","getProductosApi");
	datos.append("ruta",ruta);
	datos.append("id_local",id_local);
	datos.append("nombre_local",nombre_local);
	$.ajax({
		url        : "ajax/productos.ajax.php",
		method     : "POST",
		data       : datos,
		cache      : false,
		contentType: false,
		processData: false,
		success: function(respuesta){
				$(document).ajaxStop($.unblockUI); 
				$("div.blockUI.blockOverlay").css("z-index","1000");
				$("div.blockUI.blockMsg.blockPage").css("z-index","1001");
			respuesta = JSON.parse(respuesta);
			console.log(respuesta.data);
			if(respuesta.data == 1)
			{
				
				swal({
					type: "success",
					title: respuesta.mensaje,
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
				}).then(function(result){
					if(result.value){
						location.href = "productos"
					}
				});
			}
			else
			{
				
				swal({
					type: "error",
					title: respuesta.mensaje,
					showConfirmButton: true,
					confirmButtonText: "Cerrar"
				});
			}

		}
	});

});