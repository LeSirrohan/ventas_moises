<?php

	require_once "../controladores/ventas.controlador.php";
	require_once "../modelos/ventas.modelo.php";
	require_once "../controladores/productos.controlador.php";
	require_once "../modelos/productos.modelo.php";

	require_once "../controladores/clientes.controlador.php";
	require_once "../modelos/clientes.modelo.php";
	require_once "../modelos/bd.modelo.php";


	require_once "../extensiones/phpmailer/src/Exception.php";
	require_once "../extensiones/phpmailer/src/PHPMailer.php";
	require_once "../extensiones/phpmailer/src/SMTP.php";	

	class AjaxFinalizarVentas {




	public $id_sesion_caja;
	public $listaProductos;
	public $listaCobros;
	public $descuento;
	public $descuentoMotivo;
	public $vuelto;
	public $id_vendedor;
	public $nombre_vendedor;
 	public $id_local;
 	public $comprobante;
 	public $comentario_venta;
 	public $id_venta;
 	public $id_cotizacion;
 	public $fecha_venta;

 	
	
	public function finalizarVentas(){

	//	$file ="./jose2.txt";
   	//	file_put_contents($file,  "entre1");


		$id_sesion_caja = $this->id_sesion_caja;
		$listaProductos = $this->listaProductos;
		$listaCobros = $this->listaCobros;
		$descuento = $this->descuento;
		$descuentoMotivo = $this->descuentoMotivo;
		$vuelto = $this->vuelto;
		$id_vendedor = $this->id_vendedor;
		$nombre_vendedor = $this->nombre_vendedor;
		$id_local = $this->id_local;
		$comprobante = $this->comprobante;
		$comentario_venta = $this->comentario_venta;

		  
		$respuesta = ControladorVentas::ctrCrearVenta($id_sesion_caja, $listaProductos , $listaCobros, $descuento , $descuentoMotivo , $vuelto, $id_vendedor, $nombre_vendedor , $id_local , $comprobante,$comentario_venta);
		echo json_encode($respuesta);

	}
		public function pagarCotizacion(){
			$id_sesion_caja = $this->id_sesion_caja;
			$listaProductos = $this->listaProductos;
			$listaCobros = $this->listaCobros;
			$descuento = $this->descuento;
			$descuentoMotivo = $this->descuentoMotivo;
			$vuelto = $this->vuelto;
			$id_vendedor = $this->id_vendedor;
			$nombre_vendedor = $this->nombre_vendedor;
			$id_local = $this->id_local;
			$comprobante = $this->comprobante;
			$id_cotizacion = $this->id_cotizacion;
			$comentario_venta = $this->comentario_venta;
			
			return ControladorVentas::ctrPagarCotizacion($id_sesion_caja, $listaProductos , $listaCobros, $descuento , $descuentoMotivo , $vuelto, $id_vendedor, $nombre_vendedor , $id_local , $comprobante,$id_cotizacion,$comentario_venta);
		}


	}
	if(isset($_POST["editarVenta"]) AND $_POST["editarVenta"] == "editarVenta"){
		$idVenta = "";
		$idVenta = $_POST["idVenta"];
		$venta             		  = new AjaxFinalizarVentas();
		$venta -> id_sesion_caja  = $_POST["id_sesion_caja"];
		$venta -> listaProductos  = $_POST["listaProductos"];
		$venta -> listaCobros     = $_POST["listaCobros"];
		$venta -> descuento       = $_POST["descuento"];
		$venta -> descuentoMotivo = $_POST["descuentoMotivo"];
		$venta -> vuelto          = $_POST["vuelto"];
		$venta -> id_vendedor     = $_POST["id_vendedor"];
		$venta -> nombre_vendedor = $_POST["nombre_vendedor"];
		$venta -> id_local        = $_POST["id_local"];
		$venta -> comprobante     = $_POST["comprobante"];
		$venta -> fecha_venta     = $_POST["fecha_venta"];
		$venta -> comentario_venta = $_POST["comentario_venta"];
		$resp_venta               = ControladorVentas::ctrEditarVentas($idVenta,$venta);
        if( $resp_venta['respuesta'] == "ok" ) {
			$resp_venta["data"]    = 1;
			$resp_venta["mensaje"] = "Se ha editado venta exitosamente";

        } else {
			$resp_venta["data"]    = 0;
			$resp_venta["mensaje"] = "Hubo un error al editar la venta";

        }
        echo json_encode($resp_venta);
		exit;
	}
	if(isset($_POST["pagarCotizacion"]) AND $_POST["pagarCotizacion"] == "pagarCotizacion"){
		$venta             		  = new AjaxFinalizarVentas();
		$venta -> id_sesion_caja  = $_POST["id_sesion_caja"];
		$venta -> listaProductos  = $_POST["listaProductos"];
		$venta -> listaCobros     = $_POST["listaCobros"];
		$venta -> descuento       = $_POST["descuento"];
		$venta -> descuentoMotivo = $_POST["descuentoMotivo"];
		$venta -> vuelto          = $_POST["vuelto"];
		$venta -> id_vendedor     = $_POST["id_vendedor"];
		$venta -> nombre_vendedor = $_POST["nombre_vendedor"];
		$venta -> id_local        = $_POST["id_local"];
		$venta -> comprobante     = $_POST["comprobante"];
		$venta -> id_cotizacion   = $_POST["idCotizacion"];	
		$venta -> comentario_venta = $_POST["comentario_venta"];

		$resp_venta =$venta -> pagarCotizacion();

        if( $resp_venta['respuesta'] == "ok" ) {
			$resp_venta["data"]    = 1;
			$resp_venta["mensaje"] = "Se ha editado venta exitosamente";

        } else {
			$resp_venta["data"]    = 0;
			$resp_venta["mensaje"] = "Hubo un error al editar la venta";

        }
        echo json_encode($resp_venta);
		exit;
	}

 
	$venta = new AjaxFinalizarVentas();
	$venta -> id_sesion_caja = $_POST["id_sesion_caja"];
	$venta -> listaProductos = $_POST["listaProductos"];
	$venta -> listaCobros = $_POST["listaCobros"];
	$venta -> descuento = $_POST["descuento"];
	$venta -> descuentoMotivo = $_POST["descuentoMotivo"];
	$venta -> vuelto = $_POST["vuelto"];
	$venta -> id_vendedor = $_POST["id_vendedor"];
	$venta -> nombre_vendedor = $_POST["nombre_vendedor"];
	$venta -> id_local = $_POST["id_local"];
	$venta -> comprobante = $_POST["comprobante"];
	$venta -> comentario_venta = $_POST["comentario_venta"];
	$venta -> finalizarVentas();
 
 
