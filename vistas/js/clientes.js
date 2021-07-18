/*=============================================
EDITAR CLIENTE
=============================================*/
$(document).on("click", ".btnEditarCliente", function(){

	var idCliente = $(this).attr("idcliente");
	var datos = new FormData();
    datos.append("idCliente", idCliente);
    $("#idCliente").val('');
    $("#idCliente").val(idCliente);
    $.ajax({

      url:"ajax/clientes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta)
      {
      
          $("#idCliente").val(respuesta["codcliente"]);
          $("#editarCliente").val(respuesta["nomrznsocial"]);
          $("#editarTipoDocIdentidad").val(respuesta["codtipodocumento"]);
          $("#editarNombreComercial").val(respuesta["nomrznsocial"]);
          $("#editarDocumentoId").val(respuesta["docidentidad"]);
          $("#editarDireccion").val(respuesta["direccion"]);
          
	    }

  	})

})

/*=============================================
ELIMINAR CLIENTE
=============================================*/
$(document).on("click", ".btnEliminarCliente", function(){

	var idCliente = $(this).attr("idCliente");
	
	swal({
        title: '¿Está seguro de borrar el cliente?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar cliente!'
      }).then(function(result){
        if (result.value) {
          
            window.location = "index.php?ruta=clientes&idCliente="+idCliente;
        }

  })

});
$(document).on("click", ".btnAnotacionesCliente", function(){
  
  var mostrarListado = function (idCliente) {
    $("#listaClientes").html('');
    $("#listaClientes").html('<table class="table table-bordered table-striped dt-responsive" id="tablaAnotacionesClientes" width="100%">'+
    '<thead>'+
    '  <tr>'+
    '	<th>Fecha</th>'+
    '	<th>Descripcion</th>'+
    ' </tr>'+
    '</thead>'+
    '<tfoot>'+
    '	<tr>'+
    '	<th>Fecha</th>'+
    '	<th>Descripcion</th>'+
    '	</tr>'+
    '</tfoot>'+
    '</table>'
    );
    $('#tablaAnotacionesClientes').DataTable( {
        "ajax" : 
        {
            "url"  : "ajax/clientes.ajax.php",
            "data" : 
            {
                "listarAnotacionesPorCliente" : "listarAnotacionesPorCliente",
                "idCliente" : idCliente
            },
            "method" : "POST"
        },
        "processing" : true,
        "serverSide" : false,
        "searchable" : false,
        "ordering"   : true,
        "destroy"    : false,
        "order": [[ 0, "desc" ]], //ESTO ES PARA ORDENAR DESCENDEMENTE DE ULTIMO A MÁS NUEVO
        "language" : 
        {
            "sProcessing"    : "Procesando...",
            "sLengthMenu"    : "Mostrar _MENU_ registros",
            "sZeroRecords"   : "No se encontraron resultados",
            "sEmptyTable"    : "Ningún dato disponible en esta tabla",
            "sInfo"          : "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty"     : "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered"  : "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix"   : "",
            "sSearch"        : "Buscar:",
            "sUrl"           : "",
            "sInfoThousands" : ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": 
            {
                "sFirst"   : "Primero",
                "sLast"    : "Último",
                "sNext"    : "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": 
            {
                "sSortAscending"  : ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending" : ": Activar para ordenar la columna de manera descendente"
            }

        },

        "responsive": "true"
    });
  };

  var idCliente = $(this).attr("idCliente");
  var nombre_comercial = $(this).attr("nombre_comercial");
  mostrarListado(idCliente);
  
  $("#TituloAnotacionesCliente").empty().html( nombre_comercial );

	$("#formAnotaciones").off().on("submit",function(ev){
		ev.preventDefault();
		var datos = new FormData();
		datos.append("anotacionesCliente", "yes");
		datos.append("idCliente", idCliente);
		datos.append("descripcionNota", $("#descripcionNota").val());
		datos.append("id_usuario", $("#id_usuario").val());
		$.ajax({
  
			url:"ajax/clientes.ajax.php",
			method: "POST",
			data       : datos,
			cache      : false,
			contentType: false,
			processData: false,
			success:function(respuesta)
			{
    
				let resp = JSON.parse(respuesta);
				if(resp.data == 1)
				{
					swal({
						type: "success",
						title: resp.mensaje,
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result)
					{
						if (result.value ) {
              mostrarListado( idCliente );
              descripcionNota
						}
					});
				}
				else
				{
					swal({
						type: "error",
						title: resp.mensaje,
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					});
				}

			}
  
    });

  });

});