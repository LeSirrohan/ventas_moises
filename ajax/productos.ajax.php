<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";


class AjaxProductos {

	/*=============================================
			MOSTRAR LA TABLA DE PRODUCTOS
	=============================================*/
public function nuevaVentaTraerProductos(){
		
		$item = null;
    	$valor = null;

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor);	
		echo json_encode($productos);
}


	/*=============================================
			MOSTRAR PRODUCTOS GRAFICO
	=============================================*/
public function  traerProductosGraficoProductos(){
		
  		$productos = ControladorProductos::ctrTraerProductosGraficoProductos();	
		

		echo $productos;
}


	/*=============================================
			TRAER PRODUCTO
	=============================================*/
	public $idProductoConsulta;

public function nuevaVentaTraerProductoAuxiliar(){
		
		$item = "id";
    	$valor =$this->idProductoConsulta; 

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor);	
		echo json_encode($productos);

}


/*=============================================
	ELIMINAR PRODUCTO
=============================================*/	

	public $idEliminarProducto;
	public $imagenProducto;

	 public function ajaxEliminarProducto(){

		 
		$id = $this->idEliminarProducto;
		$imagen = $this->imagenProducto;
		  
		$respuesta = ControladorProductos::ctrEliminarProducto($id, $imagen);
		echo json_encode($respuesta);

	}

/*=============================================
	ELIMINAR PRODUCTO
=============================================*/	

	public $idUnidadMedidaProducto;
 

	 public function ajaxEliminarUnidadMedidaProducto(){

		 
		$idUnidadMedidaProducto = $this->idUnidadMedidaProducto;
 
		  
		$respuesta = ControladorProductos::ctrEliminarUnidadMedidaProducto($idUnidadMedidaProducto);
		echo json_encode($respuesta);

	}

	/*=============================================
		IMPORTAR PRODUCTO
	=============================================*/	
	

	public $archivo_adjuntar;	
	public $id_local;	
	public $nombre_local;	
	public $ruta;	
	
	public function ajaxImportarProductos(){
	
			
		$respuesta = ControladorProductos::ctrImportarProductos($this);
		echo json_encode($respuesta);

	}

	/*=============================================
	CHEQUEAR CODIGO BARRAS
	=============================================*/
	
	public function chequearCodigo()
	{
		$respuesta = ControladorProductos::ctrChequearCodigo($this);
		echo json_encode($respuesta);
	}
	
	public function getProductos()
	{
		return  ControladorProductos::ctrGetProductos($this-> id_local);
	}
	public function getProductosApi(  )
	{
		return  ControladorProductos::ctrGetProductosApi( $this );
	}
}


/*=============================================
	ELIMINAR UNIDAD DE MEDIDA
=============================================*/	

if(isset($_POST["idUnidadMedidaProducto"])){
	$producto = new AjaxProductos();
	$producto -> idUnidadMedidaProducto = $_POST["idUnidadMedidaProducto"];
	$producto -> ajaxEliminarUnidadMedidaProducto();
}  

/*=============================================
	ELIMINAR PRODUCTO
=============================================*/	

if(isset($_POST["idEliminarProducto"])){
	$producto = new AjaxProductos();
	$producto -> idEliminarProducto = $_POST["idEliminarProducto"];
	$producto -> imagenProducto = $_POST["imagen"];
	$producto -> ajaxEliminarProducto();
}  


/*=============================================
	TRAER PRODUCTOS PARA LOS DISPOSITIVOS TABLETS Y CELULARES
=============================================*/	

if(isset($_POST["traerProductos"])){
	$producto = new AjaxProductos();
	$producto -> nuevaVentaTraerProductos();
}  


/*=============================================
	TRAER PRODUCTOS PARA LOS GRAFICOS DE VENTAS DE PRODUCTOS
=============================================*/	

if(isset($_POST["traerProductosGraficoProductos"])){
	$producto = new AjaxProductos();
	$producto -> traerProductosGraficoProductos();
}  



/*=============================================
	AUX TRAER PRODUCTOS PARA LOS DISPOSITIVOS TABLETS Y CELULARES
=============================================*/	

if(isset($_POST["idProductoConsulta"])){
	$producto = new AjaxProductos();
	$producto -> idProductoConsulta = $_POST["idProductoConsulta"];
	$producto -> nuevaVentaTraerProductoAuxiliar();
}  



/*=============================================
	IMPORTAR PRODUCTOS
=============================================*/	

if(isset($_POST["action"]) AND $_POST["action"]=="importar"){
	$producto = new AjaxProductos();
	$producto -> archivo_adjuntar = $_FILES["documento"];
	$producto -> id_local = $_POST["id_local"];
	return json_encode($producto -> ajaxImportarProductos());
}  



/*=============================================
	CHEQUEAR CODIGO BARRAS
=============================================*/	

if(isset($_POST["action"]) AND $_POST["action"]=="chequearEAN"){
	$producto = new AjaxProductos();
	$respuesta = $producto -> chequearCodigo();
	return json_encode( $respuesta[0] );
}  

/*=============================================
	IMPORTAR PRODUCTOS
=============================================*/	

if(isset($_POST["getProductos"]) AND $_POST["getProductos"]=="getProductos"){
	$producto = new AjaxProductos();
	$producto -> id_local = $_POST["id_local"];
	echo json_encode($producto -> getProductos(),JSON_FORCE_OBJECT);
	exit;
}  
/*=============================================
	IMPORTAR PRODUCTOS API
=============================================*/	
if(isset($_POST["getProductosApi"]) AND $_POST["getProductosApi"]=="getProductosApi"){
	$producto = new AjaxProductos();
	$producto -> id_local     = $_POST["id_local"];
	$producto -> nombre_local = $_POST["nombre_local"];
	$producto -> ruta         = $_POST["ruta"]."/ajax/sincronizacion.api.php";
	echo json_encode($producto -> getProductosApi( ));
	exit;
}  