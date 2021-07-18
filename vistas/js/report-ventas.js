/*=============================================
=         BOTON EDITAR VENTA      =
=============================================*/


	
	var date = new Date(); // Or your date here
	var fin =  date.getFullYear()   +  '-'+  (date.getMonth() + 1) + '-' + date.getDate()   ;
	date.setDate(date.getDate()-8000); // el 8000 es para que en la tabla de report de ventas me aparezca desde hace 21 años.
	var inicio =  date.getFullYear()   +  '-'+  (date.getMonth() + 1) + '-' + date.getDate()   ;
	var sesion_caja = $("#sesion_caja").val();

	var datos = new FormData();
	datos.append("mostrarListadoVentas", 'yes');
	datos.append("dataInicio", inicio );
	datos.append("dataFin",   fin);
	datos.append("sesion_caja",  sesion_caja);
	$("#tablaDinamicaProducto").html('');
	$("#tablaDinamicaProducto").html('<table class="table table-bordered table-striped dt-responsive" id="tablaListadoVentas" width="100%">'+
	'<thead>'+
	'  <tr>'+
	'	<th style="width:10px">#</th>'+
	'	<th>Fecha</th>'+
	'	<th>Tipo. Doc.</th>'+
	'	<th>Identificación</th>'+
	'	<th>Razón Social</th>'+
	'	<th>Comprobante</th>'+
	'	<th>Comentario</th>'+
	'	<th>Total</th>'+
	'	<th>Acciones</th>'+
	' </tr>'+
	'</thead>'+
	'<tfoot>'+
	'	<tr>'+
	'	<th style="width: 10px">#</th>'+
	'	<th>Fecha</th>'+
	'	<th>Tipo. Doc.</th>'+
	'	<th>Identificación</th>'+
	'	<th>Razón Social</th>'+
	'	<th>Comprobante</th>'+
	'	<th>Comentario</th>'+
	'	<th>Total</th>'+
	'	<th>Acciones</th>'+
	'	</tr>'+
	'</tfoot>'+
	'</table>'
	);

	$.ajax({
		url: "ajax/report-ventas.ajax.php",
		method : "POST",
		data: datos,
		cache: false,
		contentType : false,
		processData : false,
		dataType : "json" ,
		success: function(respuesta){
			
		
			$('#tablaListadoVentas').DataTable( {

							
				"data": respuesta,
				"deferRender": true,
				"retrieve":true,
				"order": [[ 1, "desc" ]], //ESTO ES PARA ORDENAR DESCENDEMENTE DE ULTIMO A MÁS NUEVO
				"processing":true,
				"language": {
						"lengthMenu": "Mostrar _MENU_ registros",
						"zeroRecords": "No se encontraron resultados",
						"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
						"infoFiltered": "(filtrado de un total de _MAX_ registros)",
						"sSearch": "Buscar:",
						"oPaginate": {
							"sFirst": "Primero",
							"sLast":"Último",
							"sNext":"Siguiente",
							"sPrevious": "Anterior"
						},
						"sProcessing":"Procesando...",
					},
				//para usar los botones   
				"responsive": "true",
				"dom": 'Bfrtilp',       
				"buttons":[ 
					{
						extend:    'excelHtml5',
						text:      'Excel <i class="fas fa-file-excel"></i>',
						titleAttr: 'Exportar a Excel',
						className: 'btn btn-success'
					},
					{
						extend:    'pdfHtml5',
						text:      'PDF <i class="fas fa-file-pdf"></i>',
						titleAttr: 'Exportar a PDF',
						className: 'btn btn-danger'
					},
					{
						extend:    'print',
						text:      'Imprimir <i class="fa fa-print"></i>',
						titleAttr: 'Imprimir',
						className: 'btn btn-warning'
					},
				]	        
		} );
		}
	})


 



$(document).on("click",".modificarModoPago" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.


	var idVenta = $(this).attr("codigoVenta");




	console.log("idVenta", idVenta);


	var datos = new FormData();
	datos.append("mostrarFormasCobroVentas", "yes");
	datos.append("id_venta", idVenta);
	

	$.ajax({
		url: "ajax/ventas.ajax.php",
		method : "POST",
		data: datos,
		cache: false,
		contentType : false,
		processData : false,
		dataType : "json" ,
		success: function(respuesta){


  	


	  $(".listaMetodoPago").html("");

			let arreglo_cobros_venta = respuesta[0] ;
			let arreglo_cobros_tipo_venta = respuesta[1] ;
			let venta_total_cuenta =  0 ;
			let venta_total_cobrado =  0 ;




			
			console.log("arreglo_cobros_venta" , JSON.stringify(arreglo_cobros_venta));
		//	console.log("arreglo_cobros_tipo_venta", JSON.stringify(arreglo_cobros_tipo_venta));
 			
  		
			let tipo_cobro ="";
			let auxiliar ="";
  




		  	for (var i = 0; i < arreglo_cobros_venta.length; i++) {

			tipo_cobro = "";


  			for (var j = 0; j < arreglo_cobros_tipo_venta.length; j++) {

				
  				if( arreglo_cobros_tipo_venta[j]['efectivo'] == 1 )  auxiliar= 'E'; else  auxiliar = 'O';



  				if(arreglo_cobros_venta[i]['id_tipo_cobro'] ==  arreglo_cobros_tipo_venta[j]['id'] )

					tipo_cobro = '<option value="'+arreglo_cobros_tipo_venta[j]['id']+'" selected="selected" >'+  auxiliar +'-'+  arreglo_cobros_tipo_venta[j]['nombre'].toUpperCase()+'</option>' +tipo_cobro;
  				else 
					tipo_cobro = '<option value="'+arreglo_cobros_tipo_venta[j]['id']+'">' +auxiliar +'-'+arreglo_cobros_tipo_venta[j]['nombre'].toUpperCase()+'</option>' +tipo_cobro;
					
		  				
		  	}

//		  	console.log("tipo_cobro",tipo_cobro);


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
				                    '<input type="number" class="form-control input-lg nuevaVentaPagoMonto" step = "any" idPago="'+arreglo_cobros_venta[i]['id']+'" min ="0" placeholder="Monto Pago" value="'+arreglo_cobros_venta[i]['monto_cobro']+'" >'+
				                '</div>'+

				                '</div>'+
				              
				              '<div class  = "col-xs-12 col-md-6">'+


				                '<label>Tipo</label>'+
				 

				          '<div class = "form-group">'+
				             '<select class ="form-control select2 nuevaVentaTipoPago" style="width: 100%;" required>'+
				              
				              '</select>'+
				          '</div>'+


				              '</div>'+
				              '</div>'+
				              '<div class = "form-group row" style="margin-top:-15px">'+
				                '<div class  = "col-lg-10">'+
				                  '<label for="">Nota de pago</label>'+
				                  '<input type="text" name="" value="'+arreglo_cobros_venta[i]['nota']+'" class="form-control nuevaVentaNotaPago">'+

				                '</div>'+
				             
				              '<div class  = "col-lg-2">'+
				                '<label for=""></label>'+
				                '<button type="button" class="btn btn-danger mt-4 ml-2 "><i class="far fa-trash-alt"></i></button>'+
				              '</div>'+
				              '</div>'+
				            '</div>'


				    ); 

				  //$(".nuevaVentaTipoPago").last().html($("#nuevaVentaTipoPagoOriginal").html());
				  $(".nuevaVentaTipoPago").last().html(  tipo_cobro );

				  $("#nuevaVentaTipoPagoOriginal").select2();
				  
				  $(".nuevaVentaTipoPago").last().select2();

		    

		    venta_total_cuenta = venta_total_cuenta +arreglo_cobros_venta[i]['monto_cobro'] -arreglo_cobros_venta[i]['monto_vuelto']  ; 

		    venta_total_cobrado  =  venta_total_cobrado  +  arreglo_cobros_venta[i]['monto_cobro']  ;


			}




//  $(".nuevaVentaTipoPago").last().html( );


$(".nuevaVentaTotalPagar").html(  (  venta_total_cuenta  )  .toFixed(2));  
$(".nuevaVentaTotalCobrado").html(  (  venta_total_cobrado  )  .toFixed(2));  
$(".nuevaVentaDiferencia").html(  (  venta_total_cobrado  -  venta_total_cuenta)  .toFixed(2));  
 $('[name=actualizacionVentaID]').val(idVenta);



		}



	})





	



 


});





$(document).on("click",".btnEditarVenta" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.

  var idVenta = $(this).attr("idVenta");
  window.location = "index.php?ruta=editar-ventas&idVenta="+ idVenta;

});


/*=============================================
=         BOTON EDITAR VENTA      =
=============================================*/

$(document).on("click",".btnEliminarVenta" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.

  var idVenta = $(this).attr("idVenta");
 
 		swal({
		      title: "¿Está seguro que desea eliminar la venta?",
		      text: "¡Si no lo está puede cancelar está acción!",
		      type: "warning",
		      showCancelButton: true,
		      confirmButtonColor: "#3085d6",
		      cancelButtonColor: "#d33",
		      cancelButtonText: "Cancelar",
		      confirmButtonText: "Si, borrar venta!"
		}).then(function(result){
			if(result.value){
				window.location ='index.php?ruta=ventas&idVenta='+idVenta; 
			}


		});  


});



/*=============================================
=        IMPRIMIR FACTURA      =
=============================================*/

$(document).on("click",".btnVentasImprimirFacturaPDF" , function(){ // cuando este listo el documento en la clase clic salga todo ok.

/*
$pdf = new TCPDF('p','mm','A4');
//add page
$pdf ->AddPage();

//output
$pdf->Output();
*/

var idVenta = $(this).attr("codigoVenta");
 
window.open("vistas/pdf/factura.php?codigo="+idVenta, "_blank");

});

/*=============================================
=        RANGO DE FEHCAS      =
=============================================*/


    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Hoy'       : [moment(), moment()],
          'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
          'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
          'Este Mes'  : [moment().startOf('month'), moment().endOf('month')],
          'Mes Pasado'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))

        var fechaInicial = start.format('YYYY-M-D');
        var fechaFinal = end.format('YYYY-M-D');
		var sesion_caja = $("#sesion_caja").val();
		 

		$("#tablaDinamicaProducto").html('<table class="table table-bordered table-striped dt-responsive" id="tablaListadoVentas" width="100%">'+
		'<thead>'+
		'  <tr>'+
		'	<th style="width:10px">#</th>'+
		'	<th>Fecha</th>'+
		'	<th>Identificación</th>'+
		'	<th>Cliente</th>'+
		'	<th>Comprobante</th>'+
		'	<th>Vendedor</th>'+
		'	<th>Descuento</th>'+
		'	<th>Comentario</th>'+
		'	<th>Total</th>'+
		'	<th>Estado</th>'+
		'	<th>Acciones</th>'+
		' </tr>'+
		'</thead>'+
		'<tfoot>'+
		'	<tr>'+
		'	<th style="width: 10px">#</th>'+
		'	<th>Fecha</th>'+
		'	<th>Identificación</th>'+
		'	<th>Cliente</th>'+
		'	<th>Comprobante</th>'+
		'	<th>Vendedor</th>'+
		'	<th>Descuento</th>'+
		'	<th>Comentario</th>'+
		'	<th>Total</th>'+
		'	<th>Estado</th>'+
		'	<th>Acciones</th>'+
		'	</tr>'+
		'</tfoot>'+
		'</table>'
		);
 
 

		

			var datos = new FormData();
			datos.append("mostrarListadoVentas", "yes");
			datos.append("dataInicio", fechaInicial);
			datos.append("dataFin",  fechaFinal);

			$.ajax({
				url: "ajax/report-ventas.ajax.php",
				method : "POST",
				data: datos,
				cache: false,
				contentType : false,
				processData : false,
				dataType : "json" ,
				success: function(respuesta){
					console.log(respuesta);
		
						$('#tablaListadoVentas').DataTable( {

							
							"data": respuesta,
							"deferRender": true,
							"retrieve":true,
							"order": [[ 1, "desc" ]], //ESTO ES PARA ORDENAR DESCENDEMENTE DE ULTIMO A MÁS NUEVO
							"processing":true,
							"language": {
									"lengthMenu": "Mostrar _MENU_ registros",
									"zeroRecords": "No se encontraron resultados",
									"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
									"infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
									"infoFiltered": "(filtrado de un total de _MAX_ registros)",
									"sSearch": "Buscar:",
									"oPaginate": {
										"sFirst": "Primero",
										"sLast":"Último",
										"sNext":"Siguiente",
										"sPrevious": "Anterior"
									},
									"sProcessing":"Procesando...",
								},
							//para usar los botones   
							"responsive": "true",
							"dom": 'Bfrtilp',       
							"buttons":[ 
								{
									extend:    'excelHtml5',
									text:      'Excel <i class="fas fa-file-excel"></i>',
									titleAttr: 'Exportar a Excel',
									className: 'btn btn-success'
								},
								{
									extend:    'pdfHtml5',
									text:      'PDF <i class="fas fa-file-pdf"></i>',
									titleAttr: 'Exportar a PDF',
									className: 'btn btn-danger'
								},
								{
									extend:    'print',
									text:      'Imprimir <i class="fa fa-print"></i>',
									titleAttr: 'Imprimir',
									className: 'btn btn-warning'
								},
							]	        
					} );

				}

			})

      }
)


/*==========================================
|			VER DESCUENTO VENTAS			|
============================================*/
 

$(document).on("click",".btnVerDescuento" , function(){

	event.preventDefault;
	
    $('#MontoDescuento').val("");    
	$('#MotivoDescuento').val("");
	
    var MontoDescuento = $(this).attr("descuento");
    var MotivoDescuento = $(this).attr("motivo");
    
    $('#MontoDescuento').val(MontoDescuento);    
    $('#MotivoDescuento').val(MotivoDescuento);
});
$(document).on("click",".CerrarModalVerDescuento" , function(){
    $('#MontoDescuento').val("");    
	$('#MotivoDescuento').val("");
    $("#modalVerDescuento").modal("hide");
});
/*==========================================
|			VER DETALLE VENTAS			|
============================================*/
$(document).on("click",".btnVerDetalle" , function(){

	event.preventDefault;

	var idVenta = $(this).attr("idVenta");
	$("#tituloModalVerDetalle").empty();
	$("#tituloModalVerDetalle").append("#"+idVenta);
    $("#modalVerDetalle").modal("show");
	var html = "";
	html += '<table id="tablaListaVentasDetalle" class="table table-bordered table-striped" style="width:100%">';
	html += '<thead>';
	html += '<tr>';
	html += '<th style="width:10px">#</th>';
	html += '<th>Cantidad</th>';
	html += '<th>U.M.</th>';
	html += '<th>Descripción</th>';
	html += '<th>Precio Producto</th>';
	html += '<th>Precio Venta O.</th>';
	html += '<th>Comentario</th>';
	html += '<th>Monto</th>';
	html += '</tr>';
	html += '</thead>';
	html += '<tfoot>';
	html += '<tr>';
	html += '<th style="width:10px">#</th>';
	html += '<th>Cantidad</th>';
	html += '<th>U.M.</th>';
	html += '<th>Descripción</th>';
	html += '<th>Precio Producto</th>';
	html += '<th>Precio Venta O.</th>';
	html += '<th>Comentario</th>';
	html += '<th>Monto</th>';
	html += '</tr>';
	html += '</tfoot>';
	html += '</table>';
	
	$("#listaVentasDetalle").html(html);

	var datos = new FormData();
	datos.append("mostrarListadoVentasDetalles", "yes");
	datos.append("idVenta",  idVenta);

	$.ajax({
		url: "ajax/report-ventas.ajax.php",
		method : "POST",
		data: datos,
		cache: false,
		contentType : false,
		processData : false,
		dataType : "json" ,
		success: function(respuesta){
			var table = $('#tablaListaVentasDetalle').DataTable( {

				"data": respuesta,
				"footerCallback": function ( row, data, start, end, display ) {
					var api = this.api(), data;
		 
					var intVal = function ( i ) {
						return typeof i === 'string' ?
							i.replace(/[\$,]/g, '')*1 :
							typeof i === 'number' ?
								i : 0;
					};
		 
					totalFiltrado = api
						.column( 7, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );
		 
					total = api
						.column( 7, )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );
		 
		 
					$( api.column( 7 ).footer() ).html(
						'Total: '+totalFiltrado.toFixed(2)+'('+total.toFixed(2)+')'
					);
				}
			} );
		}
	});
});