/*=============================================
=         REPORTE CLIENTE VENTAS         =
=============================================*/
  

$(document).on("click",".btnRptProducto" , function(){
 
	var id_producto = $(this).attr("id_producto");
	var nombre_producto = $(this).attr("nombre_producto");
	localStorage.setItem("id_producto",id_producto);
	localStorage.setItem("nombre_producto",nombre_producto);
	
	window.location.href='report-prod-venta';
 

});

localStorage.setItem("id_producto","");
localStorage.setItem("nombre_producto","");

/*
=================================================
=            LISTADO CLIENTES                   =
=================================================*/


	
var date = new Date(); // Or your date here
var fin =  date.getFullYear()   +  '-'+  (date.getMonth() + 1) + '-' + date.getDate()   ;
date.setDate(date.getDate()-30);
var inicio =  date.getFullYear()   +  '-'+  (date.getMonth() + 1) + '-' + date.getDate()   ;

$("#tablaDinamicaProductos").html('');
$("#tablaDinamicaProductos").html('<table class="table table-bordered table-striped dt-responsive" id="tablaProductos" width="100%">'+
'<thead>'+
'  <tr>'+
'	<th>Cod. Venta</th>'+
'	<th>Producto</th>'+
'	<th>Nombre Producto</th>'+
'	<th>Precio Venta</th>'+
'	<th>Cod Sunat</th>'+
'	<th>Tipo Afectación</th>'+
'	<th>UM</th>'+
'	<th>Acciones</th>'+
' </tr>'+
'</thead>'+
'<tfoot>'+
'	<tr>'+
'	<th>Cod. Venta</th>'+
'	<th>Producto</th>'+
'	<th>Nombre Producto</th>'+
'	<th>Precio Venta</th>'+
'	<th>Cod Sunat</th>'+
'	<th>Tipo Afectación</th>'+
'	<th>UM</th>'+
'	<th>Acciones</th>'+
'	</tr>'+
'</tfoot>'+
'</table>'
);

var datos = new FormData();
datos.append("mostrarListadoProductos", 'yes');
datos.append("dataInicio", inicio );
datos.append("dataFin",   fin);
$.ajax({
    url: "ajax/repor-prod.ajax.php",
    method : "POST",
    data: datos,
    cache: false,
    contentType : false,
    processData : false,
    dataType : "json" ,
    success: function(respuesta){
        
    
        $('#tablaProductos').DataTable( {

                        
            "data": respuesta,
            "deferRender": true,
            "retrieve":true,
            "order": [[ 0, "asc" ]], //ESTO ES PARA ORDENAR DESCENDEMENTE DE ULTIMO A MÁS NUEVO
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
                    className: 'btn btn-success',
                    title:     'Reporte de Productos '
                },
                {
                    extend:    'pdfHtml5',
                    text:      'PDF <i class="fas fa-file-pdf"></i>',
                    titleAttr: 'Exportar a PDF',
                    className: 'btn btn-danger',
                    title:     'Reporte de Productos '
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
     


    $("#tablaDinamicaProductos").html('');
    $("#tablaDinamicaProductos").html('<table class="table table-bordered table-striped dt-responsive" id="tablaProductos" width="100%">'+
    '<thead>'+
    '  <tr>'+
    '	<th>Producto</th>'+
    '	<th>Nombre Producto</th>'+
    '	<th>Categoría</th>'+
    '	<th>Cód Barra</th>'+
    '	<th>Categoría</th>'+
    '	<th>Precio Venta</th>'+
    '	<th>Cod Sunat</th>'+
    '	<th>Tipo Afectación</th>'+
    '	<th>UM</th>'+
    '	<th>Última Venta</th>'+
    '	<th>Estado</th>'+
    '	<th>Acciones</th>'+
    ' </tr>'+
    '</thead>'+
    '<tfoot>'+
    '	<tr>'+
    '	<th>Producto</th>'+
    '	<th>Nombre Producto</th>'+
    '	<th>Categoría</th>'+
    '	<th>Cód Barra</th>'+
    '	<th>Categoría</th>'+
    '	<th>Precio Venta</th>'+
    '	<th>Cod Sunat</th>'+
    '	<th>Tipo Afectación</th>'+
    '	<th>UM</th>'+
    '	<th>Última Venta</th>'+
    '	<th>Estado</th>'+
    '	<th>Acciones</th>'+
    '	</tr>'+
    '</tfoot>'+
    '</table>'
    );

    

        var datos = new FormData();
        datos.append("mostrarListadoProductos", "yes");
        datos.append("id_producto", id_producto);
        datos.append("dataInicio", fechaInicial);
        datos.append("dataFin",  fechaFinal);

        $.ajax({
            url: "ajax/repor-prod.ajax.php",
            method : "POST",
            data: datos,
            cache: false,
            contentType : false,
            processData : false,
            dataType : "json" ,
            success: function(respuesta){
                
            
                $('#tablaProductos').DataTable( {
    
                                
                    "data": respuesta,
                    "deferRender": true,
                    "retrieve":true,
                    "order": [[ 0, "asc" ]], //ESTO ES PARA ORDENAR DESCENDEMENTE DE ULTIMO A MÁS NUEVO
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
                            className: 'btn btn-success',
                            title:     'Reporte de Productos '
                        },
                        {
                            extend:    'pdfHtml5',
                            text:      'PDF <i class="fas fa-file-pdf"></i>',
                            titleAttr: 'Exportar a PDF',
                            className: 'btn btn-danger',
                            title:     'Reporte de Productos '
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

