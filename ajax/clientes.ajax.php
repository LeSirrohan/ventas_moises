<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

require_once "../controladores/configuracion.controlador.php";
require_once "../modelos/configuracion.modelo.php";



class AjaxClientes{

	/*=============================================
	EDITAR CLIENTE
	=============================================*/	

	public $idCliente;

	public function ajaxEditarCliente(){

		$item = "id";
		$valor = $this->idCliente;

		$respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

		echo json_encode($respuesta);


	}


	public function ajaxTraerTodosClientesBuscador(){

		
		$respuesta = ControladorClientes::ctrMostrarClientesBuscador();
		echo json_encode($respuesta);


	}

	/*=============================================
			Clientes con Más Ventas Reporte Gráfico
	=============================================*/	

	public function ajaxRankingClientesReportes(){

		
		$respuesta = ControladorClientes::ctrRankingClientesReportes();
		echo json_encode($respuesta);


	}

		/*=============================================
			BUSQUEDA DNI RUC
	=============================================*/
	public $dniRuc;
	public $id_local;

public function busquedaDNIRUC(){
		
 
    	$valor =$this->dniRuc; 
    	$id_local =$this->id_local; 

  		$propietario = ControladorClientes::ctrbusquedaDNIRUC($valor , $id_local);	
		echo $propietario;

}

	/*=============================================
					IMPORTAR CLIENTES
	=============================================*/	


	public $archivo_adjuntar;	

	public function ajaxImportarClientes(){
			
		return ControladorClientes::ctrImportarClientes($this);

	}

	public $id_usuario;	

	public $descripcionNota;	

	public function anotacionesCliente()
	{
		$respuesta = ControladorClientes::ctrIngresarAnotacionCliente($this);
		$return = [];
		if( $respuesta == "ok" ) 
		{
			$return["data"] = 1;
			$return["mensaje"] = "Se ha ingresado la anotación correctamente";
		}
		else
		{
			$return["data"] = 0;
			$return["mensaje"] = $respuesta;
		}
		echo json_encode($return);exit;
	}

	public function listarAnotacionesPorCliente(){

        $anotaciones = ControladorClientes::ctrListadoClientesAnotaciones( $this );
		
		if(count($anotaciones)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

 		}

 		$datosJson = ' {

	 	"data":[ ';

	 	foreach ($anotaciones as $key => $value) {

			/*=============================================
			ACCIONES
			=============================================*/

			$datosJson.= '[
							
						"'.$value["fecha"].'",
						"'.$value["descripcion"].'"
						
						
				],';

		}

		$datosJson = substr($datosJson, 0, -1);

		$datosJson.=  ']

		}';

		echo $datosJson;exit;

	}
}

if(isset($_POST["anotacionesCliente"]) AND $_POST["anotacionesCliente"]="yes"){
	$anotaciones = new AjaxClientes();
	$anotaciones -> idCliente = $_POST["idCliente"];
	$anotaciones -> id_usuario = $_POST["id_usuario"];
	$anotaciones -> descripcionNota = $_POST["descripcionNota"];
	$anotaciones -> anotacionesCliente();exit;
}

if(isset($_POST["listarAnotacionesPorCliente"]) AND $_POST["listarAnotacionesPorCliente"]="yes"){
	$anotaciones = new AjaxClientes();
	$anotaciones -> idCliente = $_POST["idCliente"];
	$anotaciones -> listarAnotacionesPorCliente();exit;
}  

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["idCliente"])){

	$cliente = new AjaxClientes();
	$cliente -> idCliente = $_POST["idCliente"];
	$cliente -> ajaxEditarCliente();

}

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["idMostrarTodosLosClientes"])){

	$cliente = new AjaxClientes();
	$cliente -> ajaxTraerTodosClientesBuscador();

}


/*=============================================
	LISTADO DATOS CLIENTES
=============================================*/	

if(isset($_POST["ListadoClientes"])){

	$clientes = ControladorClientes::ctrListadoClientes();	
	echo json_encode($clientes);

}
	/*=============================================
			Clientes con Más Ventas Reporte Gráfico
	=============================================*/	
if(isset($_POST["clienteRanking"])){
	$valCliente = new AjaxClientes();
	$valCliente -> ajaxRankingClientesReportes();
};


/*=============================================
	TRAER INFORMACION RUC.
=============================================*/	

if(isset($_POST["idRucDniBusqueda"])){
	$busqueda = new AjaxClientes();
	$busqueda -> dniRuc = $_POST["idRucDniBusqueda"];
	$busqueda -> id_local = $_POST["id_local"];
	$busqueda -> busquedaDNIRUC();
}  

/*=============================================
GUARDAR CLIENTE DESDE LA CREACION DE LAS VENTAS
=============================================*/	


if(isset($_POST["nuevoCliente"])){
	
	

 ControladorClientes::ctrCrearCliente(false);
	
}  

/*=============================================
				IMPORTAR CLIENTES
=============================================*/	

if(isset($_POST["action"]) AND $_POST["action"]=="importar"){
	$clientes = new AjaxClientes();
	$clientes -> archivo_adjuntar = $_FILES["documento"];
	echo json_encode($clientes -> ajaxImportarClientes());
	return;
}  

