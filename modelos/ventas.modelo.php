<?php

require_once "conexion.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ModeloVentas{
	public $idVentaDetalle;

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function mdlMostrarVentas($tabla, $item, $valor){

		$stmt = Conexion::conectar()->prepare("
			
			SELECT ventas.fecha_venta , coalesce ( cliente.nombre , 'No identificado') as cliente , coalesce(   CONCAT(comprobante.serie, '-' ,  comprobante.correlativo  )   ,'Ticket') as comprobante , ventas.total ,ventas.descuento, ventas.total as 'impuesto' , COALESCE(   ventas.anulado_motivo ,'VENTA PROCESADA' ) as estado
FROM ventas 
LEFT JOIN cliente ON ventas.id_documento_cliente = cliente.id_documento 
LEFT JOIN comprobante ON comprobante.id_venta = ventas.id 
ORDER BY ventas.id DESC


			");

		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR DETALLE VENTAS
	=============================================*/

	static public function mdlMostrarDetalleVentas($id_sesion_caja ){

		$SQL = "SELECT 
			ventas.id AS 'id_venta',
			ventas_x_tipo_cobro.fecha AS 'fecha_cobro',
			COALESCE(cliente.nombre_comercial, '-') 'cliente_nombre_comercial',
			ventas.nombre_vendedor,
			ventas.descuento,
			COALESCE(CONCAT(comprobante.serie,'-',comprobante.correlativo),'Ticket') AS 'comprobante',
			ventas.total AS 'total_venta',
			ventas_x_tipo_cobro.nombre_cobro,
			ventas_x_tipo_cobro.monto_cobro - ventas_x_tipo_cobro.monto_vuelto AS 'monto_cobro',
			ventas_x_tipo_cobro.nota,
			ventas_x_tipo_cobro.estado
		FROM
			ventas
		INNER JOIN
			ventas_x_tipo_cobro ON ventas.id = ventas_x_tipo_cobro.id_ventas
		LEFT JOIN
			cliente ON cliente.id_documento = ventas.id_documento_cliente
		LEFT JOIN
			comprobante ON comprobante.id_venta = ventas.id
		WHERE
			ventas.id_sesion_caja = :id_sesion_caja AND ventas_x_tipo_cobro.estado in ('1') 
		HAVING
		 monto_cobro <> 0";

		$stmt = Conexion::conectar()->prepare(" $SQL ");


		$stmt -> bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

	}



	/*=============================================
	MOSTRAR DETALLE VENTAS
	=============================================*/

	static public function mdlMostrarFormasDeCobro($id_venta ){

		$stmt = Conexion::conectar()->prepare("
			
SELECT * FROM  ventas_x_tipo_cobro WHERE id_ventas = :id_ventas  AND estado <> '2'
 
			");


			$stmt -> bindParam(":id_ventas", $id_venta, PDO::PARAM_INT);



		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

	}



	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function mdlMostrarVentasTablas($id_caja ){
			$stmt = Conexion::conectar()->prepare("

			
SELECT  ventas.id , coalesce ( ventas.id_documento_cliente,'-') id_documento_cliente,  ventas.fecha_venta , coalesce ( cliente.id_documento , '----') as id_cliente ,coalesce ( cliente.nombre_comercial , 'No identificado') as cliente , 
coalesce(   CONCAT(comprobante.serie, '-' ,  comprobante.correlativo  )   ,'Ticket') as comprobante , ventas.total ,ventas.descuento,ventas.descuento_motivo, ventas.total as 'impuesto' , 
IF(   ventas.anulado = '1', 'ANULADO','VENTA PROCESADA' ) as estado,
IFNULL(ventas.anulado,'-') anulado,
IFNULL(ventas.anulado_fecha,'-') anulado_fecha,
IFNULL(ventas.anulado_motivo,'-') anulado_motivo ,
IFNULL( ventas.comentario, '-' ) comentario,
IFNULL(cliente.direccion,'-') direccion ,
IFNULL(comprobante.id,'-') id_comprobante 
FROM ventas 
LEFT JOIN cliente ON ventas.id_documento_cliente = cliente.id_documento
LEFT JOIN comprobante ON comprobante.id_venta = ventas.id 
WHERE -- cast(ventas.fecha_venta as date) BETWEEN :fecha1 AND :fecha2 AND 
ventas.id_sesion_caja = :id_sesion_caja
ORDER BY ventas.id DESC



				");

		//	$stmt -> bindParam(":fecha1", $fechaInicio, PDO::PARAM_STR);
	//		$stmt -> bindParam(":fecha2", $fechaFin, PDO::PARAM_STR);
			$stmt -> bindParam(":id_sesion_caja", $id_caja, PDO::PARAM_INT);


			$stmt -> execute();

			return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

		
	}

	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function mdlListadoVentasTablas( $fechaInicio, $fechaFin  ){
		$SELECT = "";
		$FROM = "";
		$WHERE = "";
		$GROUP = "";
		$ORDER = "";
		$SELECT .= " SELECT
				ventas.id,
				ventas.fecha_venta,
				ventas.id_documento_cliente,
				IFNULL(cliente.id_documento,'----') id_cliente,
				COALESCE ( cliente.nombre_comercial, 'No identificado' ) AS cliente,
				COALESCE ( CONCAT( comprobante.serie, '-', comprobante.correlativo ), 'Ticket' ) AS comprobante,
				ventas.total,
				IFNULL( ventas.comentario, '-' ) comentario,
				ventas.descuento,
				ventas.descuento_motivo,
				ventas.total AS 'impuesto',
				IF ( ventas.anulado = '1', 'ANULADO' , 'VENTA PROCESADA' ) AS estado,
				sesion_caja.nombre_usuario AS vendedor,
				sesion_caja.fecha_inicio_caja,
				COALESCE (sesion_caja.fecha_cierre_caja , '-' ) AS fecha_cierre_caja
			FROM ventas 
				LEFT JOIN cliente ON ventas.id_documento_cliente = cliente.id_documento
				LEFT JOIN comprobante ON comprobante.id_venta = ventas.id 
				LEFT JOIN sesion_caja ON sesion_caja.id = ventas.id_sesion_caja  ";
		if($fechaInicio != "" AND $fechaFin != "")
		{
			$WHERE .=" WHERE cast(ventas.fecha_venta as date) BETWEEN :fecha1 AND :fecha2  ";
		}
		$GROUP .= "";
		$ORDER .= " ORDER BY ventas.id DESC ";
		$sql = "";
		$sql .= $SELECT;
		$sql .= $FROM ;
		$sql .= $WHERE;
		$sql .= $GROUP;
		$sql .= $ORDER;


		

		$stmt = Conexion::conectar()->prepare(" $sql ");
		if($fechaInicio != "" AND $fechaFin != "")
		{

			$stmt -> bindParam(":fecha1", $fechaInicio, PDO::PARAM_STR);
			$stmt -> bindParam(":fecha2", $fechaFin, PDO::PARAM_STR);
		}


		$stmt -> execute();

		return $stmt -> fetchAll();

	$stmt -> close();

	$stmt = null;

	
}

/*=============================================
MOSTRAR VENTAS
=============================================*/

static public function mdlListadoVentasDetalle($obj ){

	$SELECT = "";
	$FROM = "";
	$WHERE = "";
	$GROUP = "";
	$ORDER = "";

	$SELECT .= " SELECT
					ventas_detalle.*
				FROM
					ventas
					INNER JOIN ventas_detalle ON ventas.id = ventas_detalle.id_ventas";
	$WHERE .=" WHERE ventas.id=:id_venta  AND ventas_detalle.estado <> '2'";
	$GROUP .= "";
	$ORDER .= " ORDER BY ventas.id DESC ";
	$sql = "";
	$sql .= $SELECT;
	$sql .= $FROM ;
	$sql .= $WHERE;
	$sql .= $GROUP;
	$sql .= $ORDER;

	$stmt = Conexion::conectar()->prepare(" $sql ");
	$stmt -> bindParam(":id_venta", $obj->id_venta, PDO::PARAM_STR);
	

	$stmt -> execute();

	return $stmt -> fetchAll();

	$stmt -> close();

	$stmt = null;

}

/*=============================================
			MOSTRAR PRODUCTOS VENTAS
=============================================*/

static public function mdlMostrarVentaProductos( $obj ){

	$SELECT = "";
	$FROM = "";
	$WHERE = "";
	$GROUP = "";
	$ORDER = "";

	$SELECT .= " SELECT
					ventas_detalle.id_producto,
					ventas_detalle.nombre_producto,
					ventas_detalle.precio_venta_producto,
					ventas_detalle.precio_venta_original,
					ventas_detalle.cantidad_producto,
					ventas_detalle.comentario_producto,
					(ventas_detalle.precio_venta_producto *	ventas_detalle.cantidad_producto) subtotal,
					producto.unidad_medida_sunat
				FROM
					ventas
					INNER JOIN ventas_detalle ON ventas.id = ventas_detalle.id_ventas
					INNER JOIN producto ON producto.id = ventas_detalle.id_producto ";
	$WHERE .=" WHERE ventas.id=:id_venta  AND ventas_detalle.estado <> '2'";
	$GROUP .= "";
	$ORDER .= " ORDER BY ventas.id DESC ";
	$sql = "";
	$sql .= $SELECT;
	$sql .= $FROM ;
	$sql .= $WHERE;
	$sql .= $GROUP;
	$sql .= $ORDER;

	$stmt = Conexion::conectar()->prepare(" $sql ");
	$stmt -> bindParam(":id_venta", $obj->id_venta, PDO::PARAM_STR);
	

	$stmt -> execute();

	return $stmt -> fetchAll();

	$stmt -> close();

	$stmt = null;

}

/*=============================================
MOSTRAR VENTAS
=============================================*/

static public function mdlReporteVentasDetalle($obj ){

	$SELECT = "";
	$FROM = "";
	$WHERE = "";
	$GROUP = "";
	$ORDER = "";

	$SELECT .= " SELECT
					V.*,
					VD.*,
					IFNULL(SC.fecha_inicio_caja,'-') fecha_inicio_caja,
					IFNULL(SC.fecha_cierre_caja,'-') fecha_cierre_caja,
					IFNULL(C.id_documento,'----') id_documento,
					COALESCE ( C.nombre_comercial, 'No identificado' ) AS nombre

				FROM
					ventas V
					INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
					LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
					LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
				WHERE VD.estado <> '2' ";
					
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{
		$WHERE .=" AND cast(V.fecha_venta as date) BETWEEN :fecha1 AND :fecha2  ";
	}
	$GROUP .= "";
	$ORDER .= " ORDER BY V.id DESC ";
	$sql = "";
	$sql .= $SELECT;
	$sql .= $FROM ;
	$sql .= $WHERE;
	$sql .= $GROUP;
	$sql .= $ORDER;

	$stmt = Conexion::conectar()->prepare(" $sql ");
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{

		$stmt -> bindParam(":fecha1", $obj->fecha_inicio, PDO::PARAM_STR);
		$stmt -> bindParam(":fecha2", $obj->fecha_fin, PDO::PARAM_STR);
	}
	

	$stmt -> execute();

	return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	$stmt -> close();

	$stmt = null;

}

/*=============================================
MOSTRAR VENTAS
=============================================*/

static public function mdlReportePreciosMod($obj ){

	$SELECT = " ";
	$FROM = " ";
	$WHERE = " ";
	$GROUP = " ";
	$ORDER = " ";
	$HAVING = " ";

	$SELECT .= " SELECT
					V.*,
					VD.*,
					IFNULL(date_format(V.fecha_venta,'%d/%m/%Y %H:%i %p'),'-') fecha_venta2,
					IFNULL(date_format(SC.fecha_inicio_caja,'%d/%m/%Y %H:%i %p'),'-') fecha_inicio_caja2,
					IFNULL(date_format(SC.fecha_cierre_caja,'%d/%m/%Y %H:%i %p'),'-') fecha_cierre_caja2,
					IFNULL(C.id_documento,'----') id_documento,
					COALESCE ( C.nombre_comercial, 'No identificado' ) AS nombre,
					IF(VD.precio_venta_original > VD.precio_venta_producto,'reduccion','aumento') tipo

				FROM
					ventas V
					INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
					LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
					LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
				WHERE (VD.estado <> '2') ";
					
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{
		$WHERE .="  AND cast(V.fecha_venta as date) BETWEEN :fecha1 AND :fecha2  ";
	}
	$HAVING .= " HAVING
		VD.precio_venta_original <> VD.precio_venta_producto ";
	$sql = "";
	$sql .= $SELECT;
	$sql .= $FROM ;
	$sql .= $WHERE;
	$sql .= $GROUP;
	$sql .= $ORDER;
	$sql .= $HAVING;

	$stmt = Conexion::conectar()->prepare(" $sql ");
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{

		$stmt -> bindParam(":fecha1", $obj->fecha_inicio, PDO::PARAM_STR);
		$stmt -> bindParam(":fecha2", $obj->fecha_fin, PDO::PARAM_STR);
	}
	

	$stmt -> execute();

	return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	$stmt -> close();

	$stmt = null;

}

/*=============================================
MOSTRAR VENTAS
=============================================*/

static public function mdlReporteClienteVentas( $obj ){

	$sql = " SELECT
					V.*,
					VD.*,
					IFNULL(date_format(V.fecha_venta,'%d-%m-%Y %H:%i %p'),'-') fecha_venta2,
					IFNULL(date_format(SC.fecha_inicio_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_inicio_caja2,
					IFNULL(date_format(SC.fecha_cierre_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_cierre_caja2,
					IFNULL(C.id_documento,'----') id_documento,
					COALESCE ( C.nombre_comercial, 'No identificado' ) AS nombre

				FROM
					ventas V
					INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
					LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
					LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
				WHERE 
					V.id_documento_cliente = :id_documento AND VD.estado ='1'";
					
					
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{
		$sql .=" AND cast(V.fecha_venta as date) BETWEEN :fecha1 AND :fecha2  ";
	}

	$stmt = Conexion::conectar()->prepare(" $sql ");

	$stmt -> bindParam(":id_documento", $obj -> id_documento, PDO::PARAM_STR);	
	
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{

		$stmt -> bindParam(":fecha1", $obj->fecha_inicio, PDO::PARAM_STR);
		$stmt -> bindParam(":fecha2", $obj->fecha_fin, PDO::PARAM_STR);
	}

	$stmt -> execute();

	return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	$stmt -> close();

	$stmt = null;

}
/*=============================================
MOSTRAR VENTAS
=============================================*/

static public function mdlReporteProductoVentas( $obj ){

	$sql = " SELECT
					V.*,
					VD.*,
					IFNULL(date_format(V.fecha_venta,'%d-%m-%Y %H:%i %p'),'-') fecha_venta2,
					IFNULL(date_format(SC.fecha_inicio_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_inicio_caja2,
					IFNULL(date_format(SC.fecha_cierre_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_cierre_caja2,
					IFNULL(C.id_documento,'----') id_documento,
					COALESCE ( C.nombre_comercial, 'No identificado' ) AS nombre

				FROM
					ventas V
					INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
					LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
					LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
				WHERE 
					VD.id_producto = :id_producto AND VD.estado <> 2";
					
					
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{
		$sql .=" AND cast(V.fecha_venta as date) BETWEEN :fecha1 AND :fecha2  ";
	}

	$stmt = Conexion::conectar()->prepare(" $sql ");

	$stmt -> bindParam(":id_producto", $obj -> id_producto, PDO::PARAM_STR);	
	
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{

		$stmt -> bindParam(":fecha1", $obj->fecha_inicio, PDO::PARAM_STR);
		$stmt -> bindParam(":fecha2", $obj->fecha_fin, PDO::PARAM_STR);
	}

	$stmt -> execute();

	return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	$stmt -> close();

	$stmt = null;

}
/*=============================================
MOSTRAR VENTAS
=============================================*/

static public function mdlReporteProductoVentasCaja( $obj ){
	$sql = " SELECT
				VD.id_ventas,
				VD.nombre_producto,
				VD.comprobante_unidad_medida,
				VD.precio_venta_producto,
				VD.precio_venta_original,
				VD.cantidad_producto,
				VD.comprobante_codigo_interno_producto,
				VD.comprobante_valor_unitario,
				VD.comprobante_sub_total_neto,
				IFNULL(date_format(SC.fecha_inicio_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_inicio_caja2,
				IFNULL(date_format(SC.fecha_cierre_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_cierre_caja2,
				V.fecha_venta,
				V.anulado,
				VD.estado,
				IFNULL(C.id_documento,'----') id_documento,
				COALESCE ( C.nombre_comercial, 'No identificado' ) AS nombre
			FROM
				ventas V
				INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
				LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
				LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
			WHERE 
				V.id_sesion_caja = :id_sesion_caja and VD.estado <> '2'
			ORDER BY 
				VD.id_ventas,VD.id";
	$stmt = Conexion::conectar()->prepare(" $sql ");

	$stmt -> bindParam(":id_sesion_caja", $obj -> id_sesion_caja, PDO::PARAM_STR);

	$stmt -> execute();

	return $stmt -> fetchAll(PDO::FETCH_ASSOC);

}
/*=============================================
MOSTRAR VENTAS
=============================================*/

static public function mdlReporteVentasCaja( $obj ){

	$sql = " SELECT
					V.*,
					VD.*,
					IFNULL(date_format(V.fecha_venta,'%d-%m-%Y %H:%i %p'),'-') fecha_venta2,
					IFNULL(date_format(SC.fecha_inicio_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_inicio_caja2,
					IFNULL(date_format(SC.fecha_cierre_caja,'%d-%m-%Y %H:%i %p'),'-') fecha_cierre_caja2,
					IFNULL(C.id_documento,'----') id_documento,
					COALESCE ( C.nombre_comercial, 'No identificado' ) AS nombre

				FROM
					ventas V
					INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
					LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
					LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
				WHERE 
					V.id_sesion_caja = :id_sesion_caja";
					
					
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{
		$sql .=" AND cast(V.fecha_venta as date) BETWEEN :fecha1 AND :fecha2  ";
	}
	$stmt = Conexion::conectar()->prepare(" $sql ");

	$stmt -> bindParam(":id_sesion_caja", $obj -> id_sesion_caja, PDO::PARAM_STR);	
	
	if($obj->fecha_inicio != "" AND $obj->fecha_fin != "")
	{

		$stmt -> bindParam(":fecha1", $obj->fecha_inicio, PDO::PARAM_STR);
		$stmt -> bindParam(":fecha2", $obj->fecha_fin, PDO::PARAM_STR);
	}

	$stmt -> execute();

	return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	$stmt -> close();

	$stmt = null;

}

		/*=============================================
	MOSTRAR VENTAS GRAFICO VENTAS X DIA
	=============================================*/

	static public function mdlMostrarVentasGraficoVentasxDia($fechaInicio,$fechaFin ){

		

$stmt = Conexion::conectar()->prepare("SELECT  cast(fecha_venta as date) as fecha, SUM(total) as ventas , COUNT(1) AS cantidad FROM ventas WHERE cast(fecha_venta as date) BETWEEN :fecha1 AND :fecha2  GROUP BY cast(fecha_venta as date)" );

			$stmt -> bindParam(":fecha1", $fechaInicio, PDO::PARAM_STR);
			$stmt -> bindParam(":fecha2", $fechaFin, PDO::PARAM_STR);


			$stmt -> execute();

			return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

		
	}



	/*=============================================
	REGISTRO DE VENTA
	=============================================*/

	static public function mdlIngresarVenta(  $listaProductos, $listaCobros , $descuento , $descuentoMotivo,$fecha_venta , $id_sesion_caja , $vuelto, $id_vendedor, $nombre_vendedor , $id_local , $comprobante, $comentario_venta){

			//$file ="./jose4.txt";
   			//file_put_contents($file,  "entre4");

			$correo = "";
			$total= 0 ;
			$total_productos=0; // cantidad de productos
			$id_ventas_generado  = 0 ;
			$id_detalle_ventas_detalle = 0 ;
			$items_facturacion = array();

			$total_gravada = 0;
			$total_inafecta = 0;
			$total_exonerada = 0;
			$total_igv = 0 ;
			$total_impuesto_bolsas = 0;
			$total_impuesto_bolsas_aux= 0;

			  foreach ($listaProductos as $key => $producto) 
			    {
			    		 $total =  $total + $producto['subTotal'];
			    		 $total_productos = $total_productos + $producto['cantidad'];			     
			    		 $total_impuesto_bolsas_aux = $total_impuesto_bolsas_aux + $producto['impuesto_bolsas'];			     
				}


				if($total != 0 )
					$descuento_porcentaje =  $descuento / $total ;
				else 
					$descuento_porcentaje= 0 ;
				
				$total = $total + $total_impuesto_bolsas_aux - $descuento;

//=============================================================================================================
		$sanear_string = new bd();
		try {
 		$conn = Conexion::conectar();
		
		$conn->beginTransaction();

		$guardar_cliente = true;

 if(  $comprobante[0]['tipo_comprobante'] =='No Info'  ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
 		$guardar_cliente = false;
 

 if(  $comprobante[0]['tipo_comprobante'] =='Ticket' &&  $comprobante[0]['identificador'] ==''  && $comprobante[0]['nombre'] ==''    )
 		$guardar_cliente = false;





 if(  $comprobante[0]['tipo_comprobante'] =='Boleta' &&  $comprobante[0]['nombre'] ==''  &&  $comprobante[0]['identificador'] =='' )
 		 {
 		 	$comprobante[0]['nombre'] = "CLIENTE VARIOS" ; 
 		 }

//Se valida que no haya un nombre en vacío desde el formulario
if(  $comprobante[0]['tipo_comprobante'] != 'Ticket'  &&  $comprobante[0]['nombre'] ==''  )
 		 {
 		 	$comprobante[0]['nombre'] = "CLIENTE VARIOS" ; 
 		 }



 



 if(  $guardar_cliente  ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
{

	if($comprobante[0]['identificador'] =="" )
		$comprobante[0]['identificador'] = "VAR-" . date("YmdHis");  



	$stmt = $conn->prepare("
INSERT INTO  cliente (  nombre, id_documento , email , direccion , compras  , fecha_ingreso , fecha_ultima_compra, nombre_comercial )
VALUES(  :nombre, :id_documento , :email , :direccion , :compras  , :fecha_ingreso , :fecha_ultima_compra, :nombre_comercial )
ON DUPLICATE KEY UPDATE
nombre = :nombre_update,
compras = compras + :compras_update , 
fecha_ultima_compra = :fecha_ultima_compra_update  ;


");

		$stmt->bindParam(":nombre", $comprobante[0]['nombre'], PDO::PARAM_STR);	
		$stmt->bindParam(":nombre_comercial", $comprobante[0]['nombre'], PDO::PARAM_STR);	
		$stmt->bindParam(":id_documento", $comprobante[0]['identificador'], PDO::PARAM_STR);	
		$stmt->bindParam(":email", $comprobante[0]['email'], PDO::PARAM_STR);	
		$stmt->bindParam(":direccion", $comprobante[0]['direccion'], PDO::PARAM_STR);	
		
		$stmt->bindParam(":compras", $total  , PDO::PARAM_STR);
		$stmt->bindParam(":fecha_ingreso", $fecha_venta, PDO::PARAM_STR);	
		$stmt->bindParam(":fecha_ultima_compra", $fecha_venta, PDO::PARAM_STR);	

		$stmt->bindParam(":nombre_update", $comprobante[0]['nombre'] , PDO::PARAM_STR);
		$stmt->bindParam(":compras_update", $total , PDO::PARAM_STR);
		$stmt->bindParam(":fecha_ultima_compra_update", $fecha_venta, PDO::PARAM_STR);	

		$stmt->execute();

}



 		$stmt = $conn->prepare("INSERT INTO ventas(id_documento_cliente, id_vendedor, total , fecha_venta , total_productos , id_local, nombre_vendedor , descuento , descuento_motivo , id_sesion_caja , total_vuelto, anulado, comentario ) VALUES (:id_documento_cliente, :id_vendedor, :total , :fecha_venta , :total_productos , :id_local, :nombre_vendedor , :descuento , :descuento_motivo , :id_sesion_caja , :total_vuelto, :anulado, :comentario_venta )");


if( $guardar_cliente )
	 	$stmt->bindParam(':id_documento_cliente',  $comprobante[0]['identificador'], PDO::PARAM_STR); // clientes
	
else 
$stmt->bindValue(':id_documento_cliente', null, PDO::PARAM_INT); // clientes en nulos aun no los creamos


		$stmt->bindParam(":id_vendedor", $id_vendedor, PDO::PARAM_INT);
 		$stmt->bindParam(":total", $total, PDO::PARAM_STR);	
 		$stmt->bindParam(":fecha_venta", $fecha_venta, PDO::PARAM_STR);	
 		$stmt->bindParam(":total_productos", $total_productos, PDO::PARAM_STR);	

 		$stmt->bindParam(":id_local", $id_local, PDO::PARAM_INT);
		
		$stmt->bindParam(":nombre_vendedor", $nombre_vendedor, PDO::PARAM_STR);	
		$stmt->bindParam(":descuento", $descuento, PDO::PARAM_STR);	

		$descuentoMotivo=$sanear_string->sanear_strings_especiales( $descuentoMotivo );
		$stmt->bindParam(":descuento_motivo", $descuentoMotivo , PDO::PARAM_STR);	

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);	
		$stmt->bindParam(":total_vuelto", $vuelto, PDO::PARAM_STR);	
		$anulado = 0;
		$stmt->bindParam(":anulado", $anulado, PDO::PARAM_INT);

		$comentario_venta=$sanear_string->sanear_strings_especiales( $comentario_venta );
		$stmt->bindParam(":comentario_venta", $comentario_venta , PDO::PARAM_STR);	

		$stmt->execute();
		$id_ventas_generado = $conn->lastInsertId();

 	  	foreach ($listaProductos as $key => $producto) 
			    {
			 		$stmt = $conn->prepare("INSERT INTO ventas_detalle(id_ventas,precio_venta_original, comentario_modificacion_precio , comentario_producto , id_producto , nombre_producto , precio_venta_producto  , descuento_producto , comentario_descuento , cantidad_producto , comprobante_unidad_medida , comprobante_codigo_interno_producto , comprobante_valor_unitario , comprobante_sub_total_neto , comprobante_tipo_igv , comprobante_igv , comprobante_sub_total , comprobante_impuesto_bolsas, estado) VALUES 
					 (:id_ventas,:precio_venta_original, :comentario_modificacion_precio, :comentario_producto , :id_producto , :nombre_producto , :precio_venta_producto  , :descuento_producto , :comentario_descuento , :cantidad_producto , :comprobante_unidad_medida , :comprobante_codigo_interno_producto , :comprobante_valor_unitario ,:comprobante_sub_total_neto , :comprobante_tipo_igv , :comprobante_igv , :comprobante_sub_total , :comprobante_impuesto_bolsas, '1')");

			 		$stmt->bindParam(":id_ventas", $id_ventas_generado, PDO::PARAM_INT);
			 		$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
			 		$stmt->bindParam(":nombre_producto", $producto['descripcion'], PDO::PARAM_STR);

			 		
					$stmt->bindParam(":precio_venta_original", $producto['precio_original'], PDO::PARAM_STR);
					$comentario_modificacion_precio = $sanear_string->sanear_strings_especiales( $producto['modificacion_precio_motivo'] );
			 		$stmt->bindParam(":comentario_modificacion_precio", $comentario_modificacion_precio , PDO::PARAM_STR);
					$comentario_producto = $sanear_string->sanear_strings_especiales( $producto['comentario_producto'] );
			 		$stmt->bindParam(":comentario_producto", $comentario_producto, PDO::PARAM_STR);
			 		

			 		$stmt->bindParam(":precio_venta_producto", $producto['precio'], PDO::PARAM_STR);
			 		$stmt->bindParam(":descuento_producto", $producto['descuento'], PDO::PARAM_STR);
					$descuento_motivo = $sanear_string->sanear_strings_especiales( $producto['descuento_motivo'] );
			 		$stmt->bindParam(":comentario_descuento", $descuento_motivo , PDO::PARAM_STR );
			 		$stmt->bindParam(":cantidad_producto", $producto['cantidad'], PDO::PARAM_STR);


			 		$stmt->bindParam(":comprobante_unidad_medida", $producto['unidad_de_medida'], PDO::PARAM_STR);
			 		$stmt->bindParam(":comprobante_codigo_interno_producto", $producto['codigo_producto_interno'], PDO::PARAM_STR);
			 		$stmt->bindParam(":comprobante_valor_unitario", $producto['valor_unitario'], PDO::PARAM_STR);
			 		$stmt->bindParam(":comprobante_sub_total_neto", $producto['sub_total_facturacion'], PDO::PARAM_STR);
			 		$stmt->bindParam(":comprobante_tipo_igv", $producto['tipo_de_igv'], PDO::PARAM_INT);
			 		$stmt->bindParam(":comprobante_igv", $producto['igv'], PDO::PARAM_STR);

			 		$stmt->bindParam(":comprobante_sub_total", $producto['subTotal'], PDO::PARAM_STR);
			 		$stmt->bindParam(":comprobante_impuesto_bolsas", $producto['impuesto_bolsas'], PDO::PARAM_STR);


					$stmt->execute();
					$id_detalle_ventas_detalle = $conn->lastInsertId(); 


// HACEMOS EL RETIRO DE STOCK :)

			 		$stmt = $conn->prepare(" UPDATE producto  SET  total_ventas =  total_ventas +  :precio_venta_producto * :cantidad_producto  WHERE id  =  :id_producto ; ");
			 		$stmt->bindParam(":precio_venta_producto", $producto['precio'], PDO::PARAM_STR);
			 		$stmt->bindParam(":cantidad_producto", $producto['cantidad'], PDO::PARAM_STR);
			 		$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
					$stmt->execute();


//AHORA HACEMOS LA TRANSACCION DE DISMINUCION POR VENTA DEL INVENTARIO 

			 		$stmt = $conn->prepare("
INSERT INTO  inventario_transaccion (id_inventario, id_detalle_ventas_detalle,cantidad_inicial, monto_inicial, cantidad_movimiento , monto_movimiento , tipo_movimiento, fecha , nombre_inventario , estado )

  SELECT 	

  		inventario.id, 
		:id_detalle_ventas_detalle , 
		inventario.actual_cantidad  , 
		inventario.actual_costo_valorizado ,  
		:cantidad_prod1  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia  ,    
		 CASE inventario.actual_cantidad   
		 WHEN 0 THEN   inventario.actual_costo_valorizado   *:cantidad_prod2  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia 
		 ELSE  (inventario.actual_costo_valorizado/inventario.actual_cantidad)    *:cantidad_prod3  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia  
		 END,  
		'venta' , :fecha_venta , inventario.nombre , :estado
FROM inventario
INNER JOIN unidad_medida_salida_x_producto ON  unidad_medida_salida_x_producto.id_producto = :id_producto	

INNER JOIN inventario_x_unidad_medida_salida ON inventario_x_unidad_medida_salida.id = unidad_medida_salida_x_producto.id_inventario_unidad_medida_salida AND inventario.id = inventario_x_unidad_medida_salida.id_inventario
			 		");
		 
		 			$stmt->bindParam(":id_detalle_ventas_detalle", $id_detalle_ventas_detalle , PDO::PARAM_INT);
			 		$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
			 		$stmt->bindParam(":cantidad_prod1",   $producto['cantidad']   , PDO::PARAM_STR);
			 		$stmt->bindParam(":cantidad_prod2",   $producto['cantidad']   , PDO::PARAM_STR);
			 		$stmt->bindParam(":fecha_venta", $fecha_venta, PDO::PARAM_STR);	
			 		$stmt->bindParam(":cantidad_prod3",   $producto['cantidad']   , PDO::PARAM_STR);
			 		$stmt->bindValue(":estado", 1 , PDO::PARAM_INT);


					$stmt->execute();
					$id_inventario_transaccion = $conn->lastInsertId(); 


// HACEMOS EL RETIRO DE STOCK :)
			 		$stmt = $conn->prepare("
							UPDATE inventario 
							INNER JOIN inventario_transaccion ON  inventario_transaccion.id  = :id_inventario_transaccion AND inventario.id = inventario_transaccion.id_inventario
							SET   inventario.sincronizado = 0,	inventario.actual_cantidad  =   inventario.actual_cantidad   -  inventario_transaccion.cantidad_movimiento ,
									inventario.actual_costo_valorizado  =  inventario.actual_costo_valorizado    -  inventario_transaccion.monto_movimiento ;


							 	;");

			 		$stmt->bindParam(":id_inventario_transaccion", $id_inventario_transaccion, PDO::PARAM_INT);
			 		
					$stmt->execute();

			 		$stmt = $conn->prepare(" UPDATE inventario  SET inventario.sincronizado = 0, inventario.actual_cantidad = 0 WHERE  inventario.actual_cantidad < 0 ; ");
					$stmt->execute();

// HACEMOS EL RETIRO DE STOCK :) esto esta faltante ksm



					
					$arr_aux = explode("-", $producto['unidad_de_medida'], 2);
					$unidad_medida_aux = $arr_aux[0];
					if($unidad_medida_aux=='') $unidad_medida_aux = 'NIU'; 
					if($producto['comentario_producto'] == "" ) 
						$descripcion_producto = $producto['descripcion'];
					else
						$descripcion_producto = $producto['descripcion']." (". $producto['comentario_producto'].")";

		            $item =    array(
		                        "unidad_de_medida"          => $unidad_medida_aux,
		                        "codigo"                    => $producto['codigo_producto_interno'],
		                        "descripcion"               => $descripcion_producto,
		                        "cantidad"                  => $producto['cantidad'],
		                        "valor_unitario"            => $producto['valor_unitario'],
		                        "precio_unitario"           => $producto['precio'],
		                        "descuento"                 => $producto['descuento'],
		                        "subtotal"                  => $producto['sub_total_facturacion'],
		                        "tipo_de_igv"               => $producto['tipo_de_igv'],
		                        "igv"                       => $producto['igv'],
		                        "total"                     => $producto['subTotal'],
		                        "anticipo_regularizacion"   => "false",
		                        "anticipo_documento_serie"  => "",
		                        "anticipo_documento_numero" => "",
		                        "impuesto_bolsas"           => $producto['impuesto_bolsas'],
		                    );


		     if($producto['tipo_de_igv'] ==1 ) // caso gravada
		         {
		         	$total_gravada =  $total_gravada + $producto['sub_total_facturacion'] * (1 - $descuento_porcentaje ) ;
		         	$total_igv  = $total_igv +    $producto['igv']  * (1 - $descuento_porcentaje )   ;
		         }

			if($producto['tipo_de_igv'] == 8  ) // caso gravada
		         {
		         	$total_exonerada =  $total_exonerada + $producto['sub_total_facturacion'] * (1 - $descuento_porcentaje ) ; 
		   
		         }


		    if($producto['tipo_de_igv'] == 9 ) // caso gravada
		         {
		         	
		         	$total_inafecta =  $total_inafecta + $producto['sub_total_facturacion'] * (1 - $descuento_porcentaje ) ;
		         	
		         }


				$total_impuesto_bolsas  = $total_impuesto_bolsas +   $producto['impuesto_bolsas']     ;

					array_push($items_facturacion, $item );


			    }

//AHORA GUARDAMOS LOS COBROS 

			    $medio_pago = "";

 	  	foreach ($listaCobros as $key => $cobro) 
			    {

			 		$stmt = $conn->prepare("INSERT INTO ventas_x_tipo_cobro( id_ventas, id_tipo_cobro , monto_cobro , nombre_cobro , fecha , nota ,  monto_vuelto , id_sesion_caja ) VALUES (:id_ventas, :id_tipo_cobro , :monto_cobro , :nombre_cobro , :fecha , :nota  ,:monto_vuelto , :id_sesion_caja )  ");

			 		$stmt->bindParam(":id_ventas", $id_ventas_generado, PDO::PARAM_INT);
			 		$stmt->bindParam(":id_tipo_cobro", $cobro['tipoCobros_id'] , PDO::PARAM_INT);
			 		$stmt->bindParam(":monto_cobro",  $cobro['montoCobro']   , PDO::PARAM_STR);
			 		$stmt->bindParam(":nombre_cobro", $cobro['tipoCobros_nombre'], PDO::PARAM_STR);
			 		$stmt->bindParam(":nota", $cobro['notaCobro'], PDO::PARAM_STR);
			 		$stmt->bindParam(":fecha", $fecha_venta, PDO::PARAM_STR);
			 		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_STR);


			 		$aux_vuelto = 0;
					if( $cobro['tipoCobros_nombre'][0] === 'E'){
			 			$stmt->bindParam(":monto_vuelto", $vuelto, PDO::PARAM_STR);
			 			$aux_vuelto = $vuelto;
					}
			 		else{
						$stmt->bindValue(":monto_vuelto", 0 , PDO::PARAM_INT);
						$aux_vuelto = 0;
			 		}
					
		 
					$stmt->execute();

					$medio_pago =  $medio_pago. "|".$cobro['tipoCobros_nombre']." Monto Recibido:". number_format($cobro['montoCobro'], 2)." Vuelto:".number_format($aux_vuelto, 2) ;
			    }



//FACTURACION ELECTRONICA.
 
	 
		$id_comprobante_generado = "";

		$stmt1 = Conexion::conectar()->prepare("
		SELECT local.*, parametro_sistema.* FROM local , parametro_sistema 	WHERE local.id = :id_local ;
		");
		$stmt1->bindParam(":id_local", $id_local, PDO::PARAM_INT);
		$stmt1 -> execute();
		$local_info =  $stmt1 -> fetch();
		$stmt1 = null;

		// "eyJhbGciOiJIUzI1NiJ9.IjA3ZjQ1NmQ1NDlmYzQwMzZiMjc3NGMzMzFlNWRkOWYzMjM5MjllOGEyYWJiNDYxMTk0M2NlY2U3YmJiN2E4ZGYi.N3DSfyH9-K538k6eisclzjVDHG-AyfOSGTsvVQEXpNc";

		$tipo_comprobante = $comprobante[0]['tipo_comprobante'];
		$cliente_tipo_de_documento = 0 ;

		if($tipo_comprobante == 'Factura') {
			$tipo_comprobante = 1 ;
			$serie = $local_info['serie_factura'] ;
			$cliente_tipo_de_documento = 6 ;

		}

		if($tipo_comprobante == 'Boleta') {
			$tipo_comprobante = 2 ; 
			$serie = $local_info['serie_boleta'] ;
			$cliente_tipo_de_documento = 1 ;


			if (   substr( $comprobante[0]['identificador']  , 0, 3)    == 'VAR' ) {


				$stmt1 = Conexion::conectar()->prepare("SELECT (count(1)*1 ) as correlativo FROM cliente where left(id_documento ,3) = 'VAR'");
						$stmt1 -> execute();
						$correlativo_cliente =  $stmt1 -> fetch()['correlativo'];
						$correlativo_cliente = 'V-'. str_pad($correlativo_cliente, 8, '0', STR_PAD_LEFT);  ;
						$comprobante[0]['identificador'] = $correlativo_cliente;
							$cliente_tipo_de_documento = '-' ;

			}


			if ( $comprobante[0]['identificador'] == '-' ) {

				
			$cliente_tipo_de_documento = '-' ;
				 
			}


		}


		if ( $tipo_comprobante != 'No Info' &&  $tipo_comprobante != 'Ticket' ){

		// RUTA para enviar documentos
		$ruta = $local_info['ruta_url']; // "https://www.pse.pe/api/v1/d0b0bf9ac5ae4b7dbc63a3ef4ebfe6b3aa79a13ef7c8483e88b1ec2c79e53799";
		$token =$local_info['token']; 

		//obtenemos el correlativo
		$stmt1 = Conexion::conectar()->prepare("SELECT (count(1)*1 ) as correlativo FROM comprobante where serie = :serie order by 1 desc");
	 	$stmt1->bindParam(":serie", $serie, PDO::PARAM_STR);
		$stmt1 -> execute();
		$cantidad_serie =  $stmt1 -> fetch()['correlativo'];
		$stmt1 = null;

		$correlativo = $cantidad_serie + 1  ;

		$total_comprobante = $total_gravada + $total_inafecta + $total_exonerada + $total_igv + $total_impuesto_bolsas;

		$observaciones = "";

		//obtenemos el correlativo



 		$stmt = $conn->prepare("
			
INSERT INTO comprobante( id_venta, tipo_comprobante , serie , correlativo, fecha_emision , porcentaje_igv , descuento_global, total_descuento, total_gravada, total_inafecta , total_exonerada, total_igv, total_impuesto_bolsas, total, observaciones  , estado_comprobante  )
VALUES(:id_venta, :tipo_comprobante , :serie , :correlativo, :fecha_emision , :porcentaje_igv , :descuento_global, :total_descuento, :total_gravada, :total_inafecta , :total_exonerada, :total_igv, :total_impuesto_bolsas, :total, :observaciones  , :estado_comprobante );

			 		");



 		$stmt->bindParam(":id_venta", $id_ventas_generado , PDO::PARAM_INT);
 		$stmt->bindParam(":tipo_comprobante", $tipo_comprobante  , PDO::PARAM_INT);
 		$stmt->bindParam(":serie",   $serie  , PDO::PARAM_STR);
 		$stmt->bindParam(":correlativo",   $correlativo , PDO::PARAM_INT);
 		$stmt->bindValue(":fecha_emision",  $fecha_venta   , PDO::PARAM_STR);
 		$stmt->bindParam(":porcentaje_igv", $local_info['igv']   , PDO::PARAM_STR);
 		$stmt->bindParam(":descuento_global",  $descuento   , PDO::PARAM_STR);
 		$stmt->bindParam(":total_descuento",  $descuento   , PDO::PARAM_STR);
 		$stmt->bindParam(":total_gravada",   $total_gravada   , PDO::PARAM_STR);
 		$stmt->bindParam(":total_inafecta",  $total_inafecta   , PDO::PARAM_STR);
 		$stmt->bindParam(":total_exonerada",  $total_exonerada  , PDO::PARAM_STR);
 		$stmt->bindParam(":total_igv",  $total_igv  , PDO::PARAM_STR);
 		$stmt->bindParam(":total_impuesto_bolsas",  $total_impuesto_bolsas  , PDO::PARAM_STR);
 		$stmt->bindParam(":total", $total_comprobante    , PDO::PARAM_STR);
 		$stmt->bindValue(":observaciones", $observaciones  , PDO::PARAM_STR);
 		$stmt->bindValue(":estado_comprobante",  "GENERADA"  , PDO::PARAM_STR);
		$stmt->execute();

		$id_comprobante_generado = $conn->lastInsertId(); 

		$tipo_operacion = "generar_comprobante";
		$data = array(
			"operacion"							=> $tipo_operacion,
		    "tipo_de_comprobante"               => $tipo_comprobante,
		    "serie"                             => $serie,
		    "numero"							=> $correlativo,
		    "sunat_transaction"					=> "1",
		    "cliente_tipo_de_documento"			=> $cliente_tipo_de_documento,
		    "cliente_numero_de_documento"		=> $comprobante[0]['identificador'],
		    "cliente_denominacion"              => $comprobante[0]['nombre'],
		    "cliente_direccion"                 => $comprobante[0]['direccion'],
		    "cliente_email"                     => $comprobante[0]['email'],
		    "cliente_email_1"                   => "",
		    "cliente_email_2"                   => "",
		    "fecha_de_emision"                  => $fecha_venta,
		    "fecha_de_vencimiento"              => "",
		    "moneda"                            => "1", // 1 para soles
		    "tipo_de_cambio"                    => "",
		    "porcentaje_de_igv"                 => $local_info['igv'],
		    "descuento_global"                  =>  $descuento ,
		    "total_descuento"                   =>  $descuento  ,
		    "total_anticipo"                    => "",
		    "total_gravada"                     => $total_gravada,
		    "total_inafecta"                    => $total_inafecta,
		    "total_exonerada"                   => $total_exonerada,
		    "total_igv"                         => $total_igv,
		    "total_gratuita"                    => "",
		    "total_otros_cargos"                => "",
		    "total_impuestos_bolsas"            =>  $total_impuesto_bolsas,
		    "total"                             =>$total_comprobante ,
		    "percepcion_tipo"                   => "",
		    "percepcion_base_imponible"         => "",
		    "total_percepcion"                  => "",
		    "total_incluido_percepcion"         => "",
		    "detraccion"                        => "false",
		    "observaciones"                     => $observaciones,
		    "documento_que_se_modifica_tipo"    => "",
		    "documento_que_se_modifica_serie"   => "",
		    "documento_que_se_modifica_numero"  => "",
		    "tipo_de_nota_de_credito"           => "",
		    "tipo_de_nota_de_debito"            => "",
		    "enviar_automaticamente_a_la_sunat" => "true",
		    "enviar_automaticamente_al_cliente" => "false",
		    "codigo_unico"                      => "",
		    "condiciones_de_pago"               => "",
		    "medio_de_pago"                     => $medio_pago,
		    "placa_vehiculo"                    => "",
		    "orden_compra_servicio"             => "",
		    "tabla_personalizada_codigo"        => "",
		    "formato_de_pdf"                    => "",
		    "items" => $items_facturacion
		    
		);
			
		$data_json = json_encode($data);
		file_put_contents("1jsonComprobante.txt", $data_json);

				//Invocamos el servicio de NUBEFACT
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Token token="'.$token.'"',
			'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);



	$file ="./jose5.txt";
   		file_put_contents($file,  $respuesta);

	$file ="./jose6.txt";
   		file_put_contents($file,  $data_json);



		if (strpos($respuesta, 'errors') !== false) {
			$error = json_decode($respuesta, true);
			if($error["codigo"] == 23 OR $error["codigo"] == 21)
			{
				//file_put_contents("2rechazo.txt",$respuesta);
				$estado_comprobante = "RESPUESTA PROCESADA";
				$envio_comprobante = 'ok';
				$rechazado = True;
			}else{
				$estado_comprobante = "RESPUESTA PROCESADA";
				$envio_comprobante = 'ok';
				//$respuesta = $respuesta;
				$rechazado = False;
			}
		
		} else{
			$estado_comprobante = "RESPUESTA PROCESADA";
			$envio_comprobante = 'ok';
			$rechazado = False;
		}

		

			

 		$stmt = $conn->prepare(" UPDATE comprobante SET respuesta_nubefact = :respuesta_nubefact , estado_comprobante = :estado_comprobante, tipo_operacion = :tipo_operacion WHERE id = :id_comprobante; ");
 		$stmt->bindParam(":id_comprobante", $id_comprobante_generado , PDO::PARAM_INT);
 		$stmt->bindParam(":respuesta_nubefact",   $respuesta  , PDO::PARAM_STR);
 		$stmt->bindParam(":estado_comprobante",   $estado_comprobante  , PDO::PARAM_STR);
 		$stmt->bindParam(":tipo_operacion",   $tipo_operacion  , PDO::PARAM_STR);
		$stmt->execute();
 

		//FACTURACION ELECTRONICA.

			foreach ($listaProductos as $key => $producto) 
			{

				$arr_aux = explode("-", $producto['unidad_de_medida'], 2);
				$unidad_medida_aux = $arr_aux[0];
				if($unidad_medida_aux=='') $unidad_medida_aux = 'NIU'; 
				if($producto['comentario_producto'] == "" ) 
					$descripcion_producto = $producto['descripcion'];
				else
					$descripcion_producto = $producto['descripcion']." (". $producto['comentario_producto'].")";


				$stmt_comp_det = $conn->prepare("INSERT INTO comprobante_detalle (
					id_comprobante, id_producto, unidad_medida, codigo, descripcion, cantidad, valor_unitario, precio_unitario, 
					descuento, subtotal, tipo_de_igv, igv, total,impuesto_bolsas
				) 
				VALUES(
					:id_comprobante_comp, :id_producto_comp, :unidad_medida_comp, :codigo_comp, :descripcion_comp, :cantidad_comp, 
					:valor_unitario_comp, :precio_unitario_comp, :descuento_comp, :subtotal_comp, :tipo_de_igv_comp, 
					:igv_comp, :total_comp, :impuesto_bolsas_comp );");
				$stmt_comp_det->bindParam(":id_comprobante_comp", $id_comprobante_generado , PDO::PARAM_INT);
				$stmt_comp_det->bindParam(":id_producto_comp", $producto['codigo_producto_interno'] , PDO::PARAM_INT);
				$stmt_comp_det->bindParam(":unidad_medida_comp", $unidad_medida_aux , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":codigo_comp", $producto['codigo_producto_interno'] , pdo::PARAM_STR);
				$stmt_comp_det->bindParam(":descripcion_comp", $descripcion_producto , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":cantidad_comp", $producto['cantidad'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":valor_unitario_comp", $producto['valor_unitario'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":precio_unitario_comp", $producto['precio'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":descuento_comp", $producto['descuento'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":subtotal_comp", $producto['sub_total_facturacion'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":tipo_de_igv_comp", $producto['tipo_de_igv'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":igv_comp", $producto['igv'], PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":total_comp", $producto['subTotal'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":impuesto_bolsas_comp", $producto['impuesto_bolsas'] , PDO::PARAM_STR);
				$stmt_comp_det->execute();

			}


			if($rechazado)
			{
				try {
					$select1 = $conn -> prepare("SELECT * from comprobante where id = :idComprobante;");
					$select1 -> bindParam(":idComprobante",$id_comprobante_generado,PDO::PARAM_STR);
					$select1 -> execute();
					$comprobante = $select1->fetchAll(PDO::FETCH_ASSOC);
					$comprobante = $comprobante[0];
					$mail = new PHPMailer(true);
				
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
					//Server settings
					//mail->SMTPDebug = SMTP::DEBUG_SERVER;                  // Enable verbose debug output
					$mail->isSMTP();                                        // Send using SMTP
					$mail->Host       = 'abcmovil.pe';                      // Set the SMTP server to send through
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'notificacion@abcmovil.pe';         // SMTP username
					$mail->Password   = 'b+$44df}51&,';                     // SMTP password
					//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
					$mail->Port       = 587;                                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
					$titulo = $local_info["identificador_local"]." | ".$comprobante['serie']."-".$comprobante['correlativo'];

					$file1 ="./comprobantes_".$comprobante['serie']."-".$comprobante['correlativo'].".txt";
					file_put_contents($file1,  $data_json);
					$file2 ="./Mensaje_".$comprobante['serie']."-".$comprobante['correlativo'].".txt";
					file_put_contents($file2,  $respuesta);
					
					//Recipients
					$mail->setFrom('notificacion@abcmovil.pe', 'Notificaciones');
					//$mail->addAddress('jemarroquin@pucp.edu.pe');               // Name is optional
					//$mail->addAddress('jose.marroquin@abcmovil.pe');               // Name is optional
					$mail->ClearAddresses();
					$select_correos = $conn->prepare("SELECT * from correos where tipo = :tipo");
					$select_correos->bindValue(":tipo", 1 , PDO::PARAM_INT);
					$select_correos->execute();
					$correos = $select_correos->fetchAll(PDO::FETCH_ASSOC);
					if(!$correos){
						foreach ($correos as $correo)
						{
							$mail->addAddress($correo['correo']);
						}
					}else{
						$mail->addAddress('h.loreto@abcmovil.pe',"Hiram");
					}
					//$mail->addAddress('h.loreto@abcmovil.pe',"Hiram");

					// Attachments

					$mail->clearAttachments();
					$mail->addAttachment($file1);
					$mail->addAttachment($file2);
					/*$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

					// Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = $titulo;
					$body  = "ERROR COMPROBANTE ".$comprobante['serie']."-".$comprobante['correlativo']."<br>";
					$body .= "DETALLE DE ERROR: ".$error["errors"]."<br>";
					$body .= "URL: ".$ruta."<br>";
					$body .= "TOKEN: ".$token."<br>";
					$mail->Body    = $body;
					//$mail->AltBody = 'Prueba<br>';
					//file_put_contents("3rechazo.txt",json_encode($mail));

					$mail->send();
					$correo = 'Correo Enviado';
				} catch (Exception $e) {
					$correo = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				}
			}


		} 







		//UNA VEZ QUE INSERTAMOS LA COMPRA ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES

		$conn->commit();

			$retorno = array("respuesta"=>"ok", "venta"=>$id_ventas_generado, "comprobante"=>$id_comprobante_generado, "correo"=>$correo);
 			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
 			return $retorno;


		$stmt->close();
		$stmt = null;

 

		}
		catch(Exception $e) {
		    
		    $conn->rollBack();


	$file ="./errorGuardarNuevaVenta.txt";
   		file_put_contents($file,   $e->getMessage());




		    return ($e->getMessage());
		}

	}

/*


<h2>RESPUESTA DE SUNAT</h2>
    <table border="1" style="border-collapse: collapse">
        <tbody>
            <tr><th>tipo:</th><td><?php echo $leer_respuesta['tipo_de_comprobante']; ?></td></tr>
            <tr><th>serie:</th><td><?php echo $leer_respuesta['serie']; ?></td></tr>
            <tr><th>numero:</th><td><?php echo $leer_respuesta['numero']; ?></td></tr>
            <tr><th>enlace:</th><td><?php echo $leer_respuesta['enlace']; ?></td></tr>
            <tr><th>aceptada_por_sunat:</th><td><?php echo $leer_respuesta['aceptada_por_sunat']; ?></td></tr>
            <tr><th>sunat_description:</th><td><?php echo $leer_respuesta['sunat_description']; ?></td></tr>
            <tr><th>sunat_note:</th><td><?php echo $leer_respuesta['sunat_note']; ?></td></tr>
            <tr><th>sunat_responsecode:</th><td><?php echo $leer_respuesta['sunat_responsecode']; ?></td></tr>
            <tr><th>sunat_soap_error:</th><td><?php echo $leer_respuesta['sunat_soap_error']; ?></td></tr>
            <tr><th>pdf_zip_base64:</th><td><?php echo $leer_respuesta['pdf_zip_base64']; ?></td></tr>
            <tr><th>xml_zip_base64:</th><td><?php echo $leer_respuesta['xml_zip_base64']; ?></td></tr>
            <tr><th>cdr_zip_base64:</th><td><?php echo $leer_respuesta['cdr_zip_base64']; ?></td></tr>
            <tr><th>codigo_hash:</th><td><?php echo $leer_respuesta['cadena_para_codigo_qr']; ?></td></tr>
            <tr><th>codigo_hash:</th><td><?php echo $leer_respuesta['codigo_hash']; ?></td></tr>
        </tbody>
    </table>

*/

		/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function mdlEditarVenta($tabla, $datos){

/*
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET  id_cliente = :id_cliente, id_vendedor = :id_vendedor, productos = :productos,  total= :total, metodo_pago = :metodo_pago WHERE id = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
		$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

*/
	}
	/*=============================================
					CAMBIAR COMPROBANTE
	=============================================*/

	static public function mdlCambiarComprobante( $comprobante , $id_venta , $idLocal){

	
		$stmt1 = Conexion::conectar()->prepare("
			SELECT local.*, parametro_sistema.* FROM local , parametro_sistema 	WHERE local.id = :id_local ;
			");
		$stmt1->bindParam(":id_local", $idLocal, PDO::PARAM_INT);
		$stmt1 -> execute();
		$local_info =  $stmt1 -> fetch();
		$stmt1 = null;


		// RUTA para enviar documentos
		$ruta = $local_info['ruta_url']; // "https://www.pse.pe/api/v1/d0b0bf9ac5ae4b7dbc63a3ef4ebfe6b3aa79a13ef7c8483e88b1ec2c79e53799";
		$token =$local_info['token']; 

		//obtenemos el correlativo
		$stmt1 = Conexion::conectar()->prepare("SELECT count(1) as correlativo FROM comprobante where serie = :serie order by 1 desc");
		$stmt1->bindParam(":serie", $serie, PDO::PARAM_STR);
		$stmt1 -> execute();
		$cantidad_serie =  $stmt1 -> fetch()['correlativo'];
		$stmt1 = null;
	}
	/*
	=================================================
	|				ANULAR VENTA				|
	=================================================
	*/
	static public function mdlAnularVenta($tabla,$venta)
	{
		$file ="./errorAnularVenta.txt";
		try 
		{

			$conn = Conexion::conectar();
		
			$conn -> beginTransaction();

			//Se actualiza el estado de la venta

			$stmt = $conn -> prepare("UPDATE $tabla SET anulado='1', anulado_fecha = NOW() ,anulado_motivo=:anulado_motivo WHERE id = :idVenta;");

			$stmt -> bindParam(":anulado_motivo", $venta -> MotivoAnulacion, PDO::PARAM_STR);

			$stmt -> bindParam(":idVenta", $venta -> id_venta, PDO::PARAM_INT);

			
			$stmt -> execute();

			//Se actualiza el estado de la venta

			$stmt = $conn -> prepare("UPDATE ventas_x_tipo_cobro SET estado='0' WHERE id_ventas = :idVenta;");

			$stmt -> bindParam(":idVenta", $venta -> id_venta, PDO::PARAM_INT);

			
			$stmt -> execute();
			
			//Se busca el monto a descontar y el id del cliente
			
			$stmt = $conn -> prepare("SELECT total, id_documento_cliente FROM ventas WHERE  id = :idVenta;");
			
			$stmt -> bindParam(":idVenta", $venta -> id_venta, PDO::PARAM_INT);

			$stmt -> execute();

			$anulado = $stmt -> fetch(PDO::FETCH_ASSOC);

			//Se actualiza el campo compra en la tabla de clientes
			
			$stmt = $conn -> prepare("

			UPDATE cliente 
			SET cliente.compras = cliente.compras -     :monto_anulado
			WHERE cliente.id_documento = :id_clientes

						");

			$stmt -> bindParam(":monto_anulado",  $anulado["total"]   , PDO::PARAM_STR);
			$stmt -> bindParam(":id_clientes",  $anulado["id_documento_cliente"]   , PDO::PARAM_STR);
			$stmt -> execute();
			
			//Se busca el monto a descontar y el id del cliente
			
			$stmt = $conn -> prepare("SELECT
										IT.cantidad_movimiento,
										IT.monto_movimiento,
										IT.id_inventario,
										VD.id_producto,
										P.precio_venta,
										IT.cantidad_inicial
									FROM
										ventas V
										INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
										INNER JOIN inventario_transaccion IT ON VD.id = IT.id_detalle_ventas_detalle 
										INNER JOIN producto P ON P.id = VD.id_producto
									WHERE V.id = :idVenta;");
			
			$stmt -> bindParam(":idVenta", $venta -> id_venta, PDO::PARAM_INT);

			$stmt -> execute();

			$inventarios = $stmt -> fetchAll(PDO::FETCH_ASSOC);
			
			//Se actualiza el kardex  de cada producto
			foreach ($inventarios as $inventario ) {

				//Si es que en el inventario de la  transaccion a anular , la cantidad inicial estuvo en 0 no se repondra
				if($inventario["cantidad_inicial"] != 0 ){ 
 
					$stmt = $conn -> prepare("
		
					UPDATE inventario 
					SET inventario.sincronizado = 0,
						inventario.actual_cantidad = inventario.actual_cantidad -     :cantidad_producto1,
						inventario.actual_costo_valorizado =  inventario.actual_costo_valorizado-:cantidad_producto2 * :precio_unitario_producto
					WHERE inventario.id = :id_inventario
		
								 ");
		
					$stmt -> bindParam(":id_inventario", $inventario["id_inventario"] , PDO::PARAM_INT);
					$stmt -> bindParam(":cantidad_producto1",  $inventario["cantidad_movimiento"]  , PDO::PARAM_STR);
					$stmt -> bindParam(":cantidad_producto2",  $inventario["cantidad_movimiento"]   , PDO::PARAM_STR);
					$stmt -> bindParam(":precio_unitario_producto",  $inventario["precio_venta"]  , PDO::PARAM_STR);
					$stmt -> execute();

				}

			}

			$conn -> commit();
			return "ok";	
	
			$stmt -> close();
			$stmt = null;
		
		}
		catch(Exception $e) {
			
			$file ="./errorAnularVenta.txt";
			file_put_contents($file,   $e->getMessage());
			$conn->rollBack();
			return ($e->getMessage());
		}

	}

	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function mdlEliminarVenta($tabla, $datos){


		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
	/*=============================================
	OBTENER LA SUMA DE VENTAS DE PRODUCTOS Y TRANSACCIONES
	=============================================*/

	static public function mdlObtenerSumaTotalDeVentasCantYTransaccion(){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(total) as total_monto_ventas ,count(1) as total_pedidos FROM ventas");

 

		$stmt -> execute();

		return $stmt -> fetch();


		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	VENDEDOR CON MÁS VENTAS
	=============================================*/

	static public function mdlVendedorTop(){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(total)  , usuario.nombre as Vendedor FROM ventas INNER JOIN usuario ON ventas.id_vendedor = usuario.id GROUP BY id_vendedor ORDER BY 1 DESC LIMIT 1 ");
			
		$stmt -> execute();

		return $stmt -> fetch();


		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	VENDEDOR CON MÁS VENTAS
	=============================================*/
 
	static public function mdlActualizarPagos(  $listaCobros  ,$fecha   , $vuelto ,$id_venta,$id_sesion_caja){

			$file ="./id_venta.txt";
   			file_put_contents($file,  $id_venta);

   			$file ="./vuelto.txt";
   			file_put_contents($file,  $vuelto);

			$file ="./id_sesion_caja.txt";
   			file_put_contents($file,  $id_sesion_caja);

			$file ="./listaCobros.txt";
   			file_put_contents($file,  json_encode($listaCobros));


			$total= 0 ;

//=============================================================================================================
 
		try {
 		$conn = Conexion::conectar();
		
		$conn->beginTransaction();

		$guardar_cliente = true;




 	//	$stmt = $conn->prepare("INSERT INTO ventas(id_documento_cliente, id_vendedor, total , fecha_venta , total_inventar , id_local, nombre_vendedor , descuento , descuento_motivo , id_sesion_caja , total_vuelto ) VALUES (:id_documento_cliente, :id_vendedor, :total , :fecha_venta , :total_productos , :id_local, :nombre_vendedor , :descuento , :descuento_motivo , :id_sesion_caja , :total_vuelto )");

 		$stmt = $conn->prepare("UPDATE ventas SET    total_vuelto = :total_vuelto WHERE id = :id_venta");

 		$stmt->bindParam(":id_venta", $id_venta, PDO::PARAM_INT);
		$stmt->bindParam(":total_vuelto", $vuelto, PDO::PARAM_STR);	

		$stmt->execute();
		


//AHORA GUARDAMOS LOS COBROS 

			

 	  	foreach ($listaCobros as $key => $cobro) 
			    {


if($cobro['id_pago'] == 0 ) // SI LA VENTA NO TIENE UN ID DE PAGO ASOCIADO ENTONCES CREAMOS EL PAGO NUEVO
{

			 		$stmt = $conn->prepare("INSERT INTO ventas_x_tipo_cobro( id_ventas, id_tipo_cobro , monto_cobro , nombre_cobro , fecha , nota ,  monto_vuelto , id_sesion_caja ) VALUES (:id_ventas, :id_tipo_cobro , :monto_cobro , :nombre_cobro , :fecha , :nota  ,:monto_vuelto , :id_sesion_caja )  ");

			 		$stmt->bindParam(":id_ventas", $id_venta, PDO::PARAM_INT);
			 		$stmt->bindParam(":id_tipo_cobro", $cobro['tipoCobros_id'] , PDO::PARAM_INT);
			 		$stmt->bindParam(":monto_cobro",  $cobro['montoCobro']   , PDO::PARAM_STR);
			 		$stmt->bindParam(":nombre_cobro", $cobro['tipoCobros_nombre'], PDO::PARAM_STR);
			 		$stmt->bindParam(":nota", $cobro['notaCobro'], PDO::PARAM_STR);
			 		$stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
			 		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);


			 		$aux_vuelto = 0;
					if( $cobro['tipoCobros_nombre'][0] === 'E'){
			 			$stmt->bindParam(":monto_vuelto", $vuelto, PDO::PARAM_STR);
			 			$aux_vuelto = $vuelto;
					}
			 		else{
						$stmt->bindValue(":monto_vuelto", 0 , PDO::PARAM_INT);
						$aux_vuelto = 0;
			 		}
					
		 
					$stmt->execute();

				


} else { // SI LA VENTA YA TIENE UN ID DE PAGO ASOCIADO ENTONCES ACTUALIZAMOS EL PAGO

			 		$stmt = $conn->prepare("UPDATE ventas_x_tipo_cobro SET  id_tipo_cobro = :id_tipo_cobro  , monto_cobro = :monto_cobro , nombre_cobro = :nombre_cobro  ,  fecha = :fecha,  nota = :nota ,  monto_vuelto =:monto_vuelto , id_sesion_caja = :id_sesion_caja WHERE id = :id_venta_tipo_pago ");

			 		$stmt->bindParam(":id_venta_tipo_pago",  $cobro['id_pago'], PDO::PARAM_INT);
			 		$stmt->bindParam(":id_tipo_cobro", $cobro['tipoCobros_id'] , PDO::PARAM_INT);
			 		$stmt->bindParam(":monto_cobro",  $cobro['montoCobro']   , PDO::PARAM_STR);
			 		$stmt->bindParam(":nombre_cobro", $cobro['tipoCobros_nombre'], PDO::PARAM_STR);
			 		$stmt->bindParam(":nota", $cobro['notaCobro'], PDO::PARAM_STR);
			 		$stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
			 		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);

			 		$aux_vuelto = 0;
					if( $cobro['tipoCobros_nombre'][0] === 'E'){
			 			$stmt->bindParam(":monto_vuelto", $vuelto, PDO::PARAM_STR);
			 			$aux_vuelto = $vuelto;
					}
			 		else{
						$stmt->bindValue(":monto_vuelto", 0 , PDO::PARAM_INT);
						$aux_vuelto = 0;
			 		}
					
		 
					$stmt->execute();



}





			    }






		//UNA VEZ QUE INSERTAMOS LA COMPRA ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES

		$conn->commit();


 			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
 			return "ok";


		$stmt->close();
		$stmt = null;

 

		}
		catch(Exception $e) {
		    
		    $conn->rollBack();


	$file ="./errorActualizarPagoPedido.txt";
   		file_put_contents($file,   $e->getMessage());




		    return ($e->getMessage());
		}
	}

	/*=============================================
					PIE VENTA
	=============================================*/

	static public function mdlPieVentas( $id_sesion_caja ){

		$stmt = Conexion::conectar()->prepare("SELECT
			(SELECT
				COALESCE(SUM( ventas.total ) ,0)
			FROM
				ventas 
			WHERE
				ventas.id IN (
				SELECT ventas.id FROM ventas LEFT JOIN comprobante ON ventas.id = comprobante.id_venta 
				WHERE	comprobante.tipo_comprobante IN ( 1 ) 
				)
			AND ventas.id_sesion_caja = :id_sesion_caja	) total_factura,
			(SELECT
				COALESCE(SUM( ventas.total ) ,0) 
			FROM
				ventas	
			WHERE
				ventas.id IN (
				SELECT ventas.id FROM ventas LEFT JOIN comprobante ON ventas.id = comprobante.id_venta 
				WHERE	comprobante.tipo_comprobante IN (  2 ) 
			)
			AND ventas.id_sesion_caja =  :id_sesion_caja2 ) total_boleta,
			(SELECT
				COALESCE(SUM( ventas.total ) ,0)
			FROM
				ventas
			WHERE
				ventas.id_sesion_caja = :id_sesion_caja3 AND ventas.id NOT IN (
					SELECT ventas.id FROM ventas LEFT JOIN comprobante ON ventas.id = comprobante.id_venta 
					WHERE	comprobante.tipo_comprobante IN ( 1, 2 ) 
				)
			) total_ticket");

		$stmt -> bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_STR);
		$stmt -> bindParam(":id_sesion_caja2", $id_sesion_caja, PDO::PARAM_STR);
		$stmt -> bindParam(":id_sesion_caja3", $id_sesion_caja, PDO::PARAM_STR);

		if($stmt -> execute()){

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
		}else{

			return "error";	

		}
	}

	/*=============================================
					PIE TIPO COBRO
	=============================================*/

	static public function mdlPieVentasTipoCobro( $id_sesion_caja ){

		$stmt = Conexion::conectar()->prepare("SELECT
		tipo_cobro.nombre as tipo_cobro,
		sum(monto_cobro) total
	FROM
		ventas_x_tipo_cobro	
		INNER JOIN tipo_cobro ON ventas_x_tipo_cobro.id_tipo_cobro = tipo_cobro.id 
	
	WHERE
		ventas_x_tipo_cobro.id_sesion_caja = :id_sesion_caja
	GROUP BY ventas_x_tipo_cobro.id_tipo_cobro");

		$stmt -> bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);
		

		if($stmt -> execute()){

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
		}else{

			return "error";	

		}
	}
	/*=============================================
					PIE VENTA HISTORICO
	=============================================*/

	static public function mdlPieVentasHistorico(  ){

		$stmt = Conexion::conectar()->prepare("SELECT
			(SELECT
				COALESCE(SUM( ventas.total ) ,0)
			FROM
				ventas 
			WHERE
				ventas.id IN (
				SELECT ventas.id FROM ventas LEFT JOIN comprobante ON ventas.id = comprobante.id_venta 
				WHERE	comprobante.tipo_comprobante IN ( 1 ) 
				)
			) total_facturas,
			(SELECT
				COALESCE(SUM( ventas.total ) ,0) 
			FROM
				ventas	
			WHERE
				ventas.id IN (
				SELECT ventas.id FROM ventas LEFT JOIN comprobante ON ventas.id = comprobante.id_venta 
				WHERE	comprobante.tipo_comprobante IN (  2 ) 
			)
			 ) total_boleta,
			(SELECT
				COALESCE(SUM( ventas.total ) ,0)
			FROM
				ventas
			WHERE
				ventas.id NOT IN (
					SELECT ventas.id FROM ventas LEFT JOIN comprobante ON ventas.id = comprobante.id_venta 
					WHERE	comprobante.tipo_comprobante IN ( 1, 2 ) 
				)
			) total_ticket");

		if($stmt -> execute()){

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
		}else{

			return "error";	

		}
	}
	/*=============================================
					PIE TIPO COBRO HISTORICO
	=============================================*/
	static public function mdlPieVentasTipoCobroHistorico( ){

		$stmt = Conexion::conectar()->prepare("SELECT
		tipo_cobro.nombre as tipo_cobro,
		sum(monto_cobro) total
	FROM
		ventas_x_tipo_cobro	
		INNER JOIN tipo_cobro ON ventas_x_tipo_cobro.id_tipo_cobro = tipo_cobro.id 
	
	GROUP BY ventas_x_tipo_cobro.id_tipo_cobro
	LIMIT 5");		

		if($stmt -> execute()){
			return $stmt -> fetchAll(PDO::FETCH_ASSOC);		
		}else{
			return "error";	
		}
	}
	/*=============================================
					GET VENTA CLIENTE BY ID
	=============================================*/

	static public function mdlGetClienteVentaById( $id_venta ){

		$conn = Conexion::conectar();
		$stmt = $conn->prepare("  SELECT
		CONCAT(C.id_documento,'|',C.nombre,'|',C.direccion) id_documento,
		IFNULL( C.id_documento, '-' ) id_cliente ,
		IFNULL( C.nombre, '-' ) nombre_cliente ,
		IFNULL( C.direccion, '-' ) direccion_cliente ,
		IFNULL( C.email, '-' ) email_cliente 
	
	FROM
		ventas V
		INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
		LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento 
	WHERE
		VD.id_ventas = :id_venta
		AND ( VD.estado <> '2' )
	GROUP BY V.id_documento_cliente
		");

		$stmt -> bindParam(":id_venta", $id_venta, PDO::PARAM_INT);
		

		if($stmt -> execute()){

			return $stmt -> fetch(PDO::FETCH_ASSOC);
		
		}else{

			return "error";	

		}
	}
	

	/*=============================================
					GET VENTA BY ID
	=============================================*/

	static public function mdlGetVentaById( $id_venta ){

		$conn = Conexion::conectar();
		$stmt = $conn->prepare("  SELECT		P.id,
		VD.comprobante_codigo_interno_producto codigo_producto_interno,
		P.codigo_producto_sunat codigo_producto_sunat,
		P.unidad_medida_sunat unidad_de_medida,
		P.tipo_afectacion_sunat tipo_afectacion_sunat,
		V.descuento,
		V.descuento_motivo,
		P.descripcion,
		VD.cantidad_producto cantidad,
		VD.precio_venta_original,
		VD.precio_venta_producto,
		VD.comentario_modificacion_precio modificacion_precio_motivo,
		VD.comprobante_sub_total,
		VD.precio_venta_producto*VD.cantidad_producto valor_unitario,
		VD.comprobante_sub_total_neto,
		VD.comprobante_tipo_igv,
		VD.comprobante_igv,
		VD.comprobante_impuesto_bolsas impuesto_bolsas,
		IFNULL( VD.comentario_producto, '-' ) comentario_producto 
	FROM
		ventas V
		INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas
		INNER JOIN producto P ON P.id = VD.id_producto
		LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
		LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
	WHERE
		VD.id_ventas = :id_venta  AND (VD.estado <> '2')
		");

		$stmt -> bindParam(":id_venta", $id_venta, PDO::PARAM_INT);
		

		if($stmt -> execute()){

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
		}else{

			return "error";	

		}
	}
	

	/*=============================================
					GET VENTA BY ID
	=============================================*/

	static public function mdlCabeceraVentaById( $id_venta ){

		$conn = Conexion::conectar();
		$stmt = $conn->prepare(" SELECT
				V.*,C.*,CE.*, V.total as total_venta
			FROM
				ventas V
				LEFT JOIN sesion_caja SC ON SC.id = V.id_sesion_caja
				LEFT JOIN cliente C ON V.id_documento_cliente = C.id_documento
				LEFT JOIN comprobante CE on V.id = CE.id_venta
			WHERE
				V.id =:id_venta
		");

		$stmt -> bindParam(":id_venta", $id_venta, PDO::PARAM_INT);
		

		if($stmt -> execute()){

			return $stmt -> fetch(PDO::FETCH_ASSOC);
		
		}else{

			return "error";	

		}
	}
	

	/*=============================================
					GET VENTA BY ID
	=============================================*/

	static public function mdlVentaProductoDetalleById( $id_venta , $id_producto ){
		$conn = Conexion::conectar();
		$stmt = $conn->prepare(" SELECT
				*
			FROM
				ventas_detalle
			WHERE
				id_ventas = :id_venta AND
				id_producto = :id_producto AND
				(estado <> '2')
		");

		$stmt -> bindParam(":id_venta", $id_venta, PDO::PARAM_INT);
		$stmt -> bindParam(":id_producto", $id_producto, PDO::PARAM_STR);
		
		$stmt -> execute();
		return $stmt -> fetch(PDO::FETCH_ASSOC);
	}
	
	

	/*=============================================
					GET VENTA BY ID
	=============================================*/

	static public function mdlGetTotalVentaById( $id_venta ){

		$conn = Conexion::conectar();
		$stmt = $conn->prepare("  SELECT
					V.total,
					SUM(VD.comprobante_impuesto_bolsas) comprobante_impuesto_bolsas,
					V.descuento,
					V.descuento_motivo
				FROM
					ventas V INNER JOIN ventas_detalle VD ON V.id = VD.id_ventas 
			WHERE
				V.id = :id_venta  AND VD.estado in ('1','0')
		");

		$stmt -> bindParam(":id_venta", $id_venta, PDO::PARAM_INT);
		

		if($stmt -> execute()){

			return $stmt -> fetch(PDO::FETCH_ASSOC);
		
		}else{

			return "error";	

		}
	}
	

	/*=============================================
	MOSTRAR VENTAS POR ID VENTA
	=============================================*/

	static public function mdlMostrarVentasById( $idVenta ){
		$conn = Conexion::conectar();
		$sql  = "SELECT
			ventas.fecha_venta,
			COALESCE ( cliente.nombre, 'No identificado' ) AS cliente,
			ventas.total,
			ventas.descuento,
			ventas.total AS 'impuesto',
			COALESCE ( ventas.anulado_motivo, 'VENTA PROCESADA' ) AS estado,
			ventas_detalle.id_producto,
			ventas_detalle.comprobante_codigo_interno_producto,
			ventas_detalle.nombre_producto,
			ventas_detalle.precio_venta_producto,
			ventas_detalle.descuento_producto,
			ventas_detalle.comentario_descuento,
			ventas_detalle.comprobante_unidad_medida,
			ventas_detalle.comprobante_valor_unitario as valor_unitario,

			ventas_detalle.id id_venta_detalle
		FROM
			ventas
			LEFT JOIN ventas_detalle ON ventas_detalle.id_ventas = ventas.id 
			LEFT JOIN cliente ON ventas.id_documento_cliente = cliente.id_documento
		WHERE
			ventas.id = :id  AND ventas_detalle.estado in ('0','1')
		ORDER BY
			ventas.id DESC";
		$stmt = $conn->prepare(" $sql ");

		$stmt -> bindParam( ":id" , $idVenta , PDO::PARAM_INT );

		$stmt -> execute();

		return $stmt -> fetchAll( PDO::FETCH_ASSOC );

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR VENTAS POR ID VENTA
	=============================================*/

	static public function mdlGetComprobanteVentaById( $idVenta ){
		$conn = Conexion::conectar();
		$sql  = "SELECT
			ventas.fecha_venta,
			COALESCE ( cliente.nombre, 'No identificado' ) AS cliente,
			ventas.total,
			ventas.descuento,
			ventas.total AS 'impuesto',
			COALESCE ( ventas.anulado_motivo, 'VENTA PROCESADA' ) AS estado,
			ventas_detalle.id_producto,
			ventas_detalle.comprobante_codigo_interno_producto codigo_producto_interno,
			ventas_detalle.nombre_producto descripcion,
			ventas_detalle.precio_venta_producto,
			ventas_detalle.descuento_producto,
			ventas_detalle.cantidad_producto cantidad,
			ventas_detalle.comentario_descuento,
			ventas_detalle.comprobante_unidad_medida unidad_de_medida,
			ventas_detalle.comprobante_tipo_igv,
			ventas_detalle.comprobante_sub_total comprobante_sub_total,
			ventas_detalle.comprobante_sub_total_neto comprobante_sub_total_neto,
			ventas_detalle.comprobante_igv,
			ventas_detalle.comprobante_valor_unitario as valor_unitario,
			ventas_detalle.comprobante_impuesto_bolsas as impuesto_bolsas,
			ventas_detalle.comentario_producto,
			ventas_detalle.id id_venta_detalle
		FROM
			ventas
			LEFT JOIN ventas_detalle ON ventas_detalle.id_ventas = ventas.id 
			LEFT JOIN cliente ON ventas.id_documento_cliente = cliente.id_documento
			LEFT JOIN comprobante ON ventas.id = comprobante.id_venta
		WHERE
			ventas.id = :id  AND ventas_detalle.estado in ('0','1')
		ORDER BY
			ventas.id DESC";
		$stmt = $conn->prepare(" $sql ");

		$stmt -> bindParam( ":id" , $idVenta , PDO::PARAM_INT );

		$stmt -> execute();

		return $stmt -> fetchAll( PDO::FETCH_ASSOC );

		$stmt -> close();

		$stmt = null;

	}
	

	/*=============================================
	MOSTRAR VENTAS POR ID VENTA
	=============================================*/

	static public function mdlEliminarVentaById( $idVenta, $datos_venta ){
		$conn = Conexion::conectar();
		$conn ->beginTransaction();
		$sql  = "UPDATE ventas_detalle
		LEFT JOIN inventario_transaccion ON ventas_detalle.id = inventario_transaccion.id_detalle_ventas_detalle 
		SET inventario_transaccion.estado = 2,
		ventas_detalle.estado = 2 
		WHERE
			ventas_detalle.id_ventas = :id_venta; ";
		$stmt = $conn->prepare(" $sql ");

		$stmt -> bindParam( ":id_venta" , $idVenta , PDO::PARAM_INT );

		$stmt -> execute();

		$sql  = "UPDATE ventas_x_tipo_cobro
		SET estado = 2
		WHERE
			id_ventas = :id_ventas; ";
		$stmt = $conn->prepare(" $sql ");

		$stmt -> bindParam( ":id_ventas" , $idVenta , PDO::PARAM_INT );

		$stmt -> execute();

		$sql  = "UPDATE cliente
		SET compras = :compras_update 
		WHERE
			id_documento = :id_documento; ";
		$stmt = $conn->prepare(" $sql ");
		$compras = 0;
		$compras =  ( $datos_venta['compras'] - $datos_venta['total_venta'] ) ;

		$stmt -> bindParam( ":compras_update" , $compras , PDO::PARAM_STR );
		$stmt -> bindParam( ":id_documento"   , $datos_venta['id_documento_cliente'] , PDO::PARAM_STR );

		$stmt -> execute();

		$conn->commit();
 		return "ok";

		$stmt -> close();

		$stmt = null;

	}	

	static public function mdlEditarVentaById( $idVenta, $listaProductos, $listaCobros , $descuento , $descuentoMotivo,$fecha_venta , $id_sesion_caja , $vuelto, $id_vendedor, $nombre_vendedor , $id_local , $comprobante, $cambioCliente, $ventaCliente, $comentario_venta )
	{
		
		$id_comprobante_generado = "";
		$correo = "";

		$sanear_string = new bd();
		$stmt1 = Conexion::conectar()->prepare("
		SELECT local.*, parametro_sistema.* FROM local , parametro_sistema 	WHERE local.id = :id_local ;
		");
		$stmt1->bindParam(":id_local", $id_local, PDO::PARAM_INT);
		$stmt1 -> execute();
		$local_info =  $stmt1 -> fetch();
		unset( $stmt1 );
		
		$total                     = 0 ;
		$total_productos           = 0;        // cantidad de productos
		$id_ventas_generado        = 0 ;
		$id_detalle_ventas_detalle = 0 ;
		$items_facturacion         = array();
		
		$total_gravada         = 0;
		$total_inafecta        = 0;
		$total_exonerada       = 0;
		$total_igv             = 0 ;
		$total_impuesto_bolsas = 0;
		
		foreach ($listaProductos as $key => $producto) 
		{
			$total =  $total + $producto['subTotal'];
			$total_productos = $total_productos + $producto['cantidad'];	     
		}
		
		//=============================================================================================================
		
		try 
		{
			$conn = Conexion::conectar();
			
			$conn->beginTransaction();
			
			$guardar_cliente = true;
			
			if(  $comprobante[0]['tipo_comprobante'] =='No Info'  ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
			$guardar_cliente = false;
			
			if(  $comprobante[0]['tipo_comprobante'] =='Ticket'  ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
				$guardar_cliente = true;

			$total_cliente = 0;
			$total_cliente = $total - $ventaCliente["total"];
			$total_cliente = $ventaCliente["total"] + $total_cliente;
			$ventaCliente['total'] = $ventaCliente['compras'] - $ventaCliente['total_venta'];
			if($cambioCliente)
			{
				$cliente_ant = ModeloClientes::mdlMostrarClientes("cliente", "id_documento", $ventaCliente['id_documento']);
				#$file        = "./txt_Editar_venta.txt";
				#file_put_contents( $file, json_encode( $cliente_ant )); exit;

				if($ventaCliente['id_documento'] =="" )
					$ventaCliente['id_documento'] = "VAR-" . date("YmdHis");
				  
				$stmt = $conn->prepare(" INSERT INTO  cliente (  nombre, id_documento , email , direccion , compras  , fecha_ingreso , fecha_ultima_compra, nombre_comercial )
				VALUES(  :nombre_ant, :id_documento_ant , :email_ant , :direccion_ant , :compras_ant  , :fecha_ingreso_ant , :fecha_ultima_compra_ant, :nombre_comercial_ant )
				ON DUPLICATE KEY UPDATE
				compras = compras + :compras_update_ant ,
				fecha_ultima_compra = :fecha_ultima_compra_update_ant; ");

				$stmt->bindParam(":nombre_ant", $cliente_ant['nombre'], PDO::PARAM_STR);	
				$stmt->bindParam(":nombre_comercial_ant", $cliente_ant['nombre'], PDO::PARAM_STR);
				$stmt->bindParam(":id_documento_ant",  $ventaCliente['id_documento'] , PDO::PARAM_STR);
				$stmt->bindParam(":email_ant", $cliente_ant['email'], PDO::PARAM_STR);
				$stmt->bindParam(":direccion_ant", $cliente_ant['direccion'], PDO::PARAM_STR);

				$stmt->bindParam(":compras_ant", $total  , PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ingreso_ant", $fecha_venta, PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ultima_compra_ant", $fecha_venta, PDO::PARAM_STR);

				$stmt->bindParam(":compras_update_ant", $total_cliente  , PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ultima_compra_update_ant", $fecha_venta, PDO::PARAM_STR);

				$stmt->execute();
				
				if($comprobante[0]['identificador'] =="" )
				$comprobante[0]['identificador'] = "VAR-" . date("YmdHis");  
				
				$stmt = $conn->prepare("
				INSERT INTO  cliente (  nombre, id_documento , email , direccion , compras  , fecha_ingreso , fecha_ultima_compra, nombre_comercial )
				VALUES(  :nombre, :id_documento , :email , :direccion , :compras  , :fecha_ingreso , :fecha_ultima_compra, :nombre_comercial )
				ON DUPLICATE KEY UPDATE
				compras = compras +  :compras_update ,
				fecha_ultima_compra = :fecha_ultima_compra_update  ;
				");
				
				$stmt->bindParam(":nombre", $comprobante[0]['nombre'], PDO::PARAM_STR);	
				$stmt->bindParam(":nombre_comercial", $comprobante[0]['nombre'], PDO::PARAM_STR);	
				$stmt->bindParam(":id_documento", $comprobante[0]['identificador'], PDO::PARAM_STR);	
				$stmt->bindParam(":email", $comprobante[0]['email'], PDO::PARAM_STR);	
				$stmt->bindParam(":direccion", $comprobante[0]['direccion'], PDO::PARAM_STR);	
				
				$stmt->bindParam(":compras", $total  , PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ingreso", $fecha_venta, PDO::PARAM_STR);	
				$stmt->bindParam(":fecha_ultima_compra", $fecha_venta, PDO::PARAM_STR);	
				
				$stmt->bindParam(":compras_update", $total  , PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ultima_compra_update", $fecha_venta, PDO::PARAM_STR);	
				
				$stmt->execute();
				$guardar_cliente = false;
			}
			/*else if($ventaCliente["total"]>$total_cliente)
			{
				$sql_insert = " compras = crompas - :compras_update , ";
			}
			else if($ventaCliente["total"] = $total)
			{
				$sql_insert = " ";
			}
			if(  $comprobante[0]['tipo_comprobante'] =='Ticket' &&  $comprobante[0]['identificador'] ==''  && $comprobante[0]['nombre'] ==''    ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
			$guardar_cliente = false;*/
			if(  $comprobante[0]['tipo_comprobante'] =='Boleta' &&  $comprobante[0]['nombre'] ==''  &&  $comprobante[0]['identificador'] =='' )
			{
				$comprobante[0]['nombre'] = "CLIENTE VARIOS" ; 
			}
			
			
			if(  $comprobante[0]['tipo_comprobante'] =='Boleta' &&  ( $comprobante[0]['identificador'] =='' || strlen($comprobante[0]['identificador']) < 4 )  )
			{
				$comprobante[0]['identificador'] = "----" ; 
			}
			
			if(  $guardar_cliente  ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
			{
				
				if($comprobante[0]['identificador'] =="" )
				$comprobante[0]['identificador'] = "VAR-" . date("YmdHis");  
				
				$stmt = $conn->prepare("
				INSERT INTO  cliente (  nombre, id_documento , email , direccion , compras  , fecha_ingreso , fecha_ultima_compra, nombre_comercial )
				VALUES(  :nombre, :id_documento , :email , :direccion , :compras  , :fecha_ingreso , :fecha_ultima_compra, :nombre_comercial )
				ON DUPLICATE KEY UPDATE
				compras = compras + :compras_update ,
				fecha_ultima_compra = :fecha_ultima_compra_update  ;
				");
				
				$stmt->bindParam(":nombre", $comprobante[0]['nombre'], PDO::PARAM_STR);	
				$stmt->bindParam(":nombre_comercial", $comprobante[0]['nombre'], PDO::PARAM_STR);	
				$stmt->bindParam(":id_documento", $comprobante[0]['identificador'], PDO::PARAM_STR);	
				$stmt->bindParam(":email", $comprobante[0]['email'], PDO::PARAM_STR);	
				$stmt->bindParam(":direccion", $comprobante[0]['direccion'], PDO::PARAM_STR);	
				
				$stmt->bindParam(":compras", $total_cliente  , PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ingreso", $fecha_venta, PDO::PARAM_STR);	
				$stmt->bindParam(":fecha_ultima_compra", $fecha_venta, PDO::PARAM_STR);	
				
				$stmt->bindParam(":compras_update", $total_cliente  , PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ultima_compra_update", $fecha_venta, PDO::PARAM_STR);	
				
				$stmt->execute();
			}
			$SQL = "UPDATE ventas 
			SET id_documento_cliente = :_id_documento_cliente,
			    id_vendedor          = :_id_vendedor,
			    total                = :_total,
			    fecha_venta          = :_fecha_venta,
			    total_productos      = :_total_productos,
			    id_local             = :_id_local,
			    nombre_vendedor      = :_nombre_vendedor,
			    comentario           = :_comentario_venta,
			    descuento            = :_descuento,
			    descuento_motivo     = :_descuento_motivo,
			    id_sesion_caja       = :_id_sesion_caja,
			    total_vuelto         = :_total_vuelto
			WHERE
				id = :_idVenta";
			
			$stmt_edit = $conn->prepare(" $SQL ");
	
			if( $guardar_cliente )
					$stmt_edit->bindParam(':_id_documento_cliente',  $comprobante[0]['identificador'], PDO::PARAM_STR); // clientes
				
			else 
				$stmt_edit->bindValue(':_id_documento_cliente', null, PDO::PARAM_INT); // clientes en nulos aun no los creamos

			if( $cambioCliente )
				$stmt_edit->bindParam(':_id_documento_cliente',  $comprobante[0]['identificador'], PDO::PARAM_STR); // Si es un cambio de cliente se agrega el que viene en el comprobante

			
			$stmt_edit->bindParam(":_id_vendedor", $id_vendedor, PDO::PARAM_INT);
			$stmt_edit->bindParam(":_total", $total, PDO::PARAM_STR);	
			$stmt_edit->bindParam(":_fecha_venta", $fecha_venta, PDO::PARAM_STR);	
			$stmt_edit->bindParam(":_total_productos", $total_productos, PDO::PARAM_STR);	
	
			$stmt_edit->bindParam(":_id_local", $id_local, PDO::PARAM_INT);
	
			$stmt_edit->bindParam(":_nombre_vendedor", $nombre_vendedor, PDO::PARAM_STR);	
			$comentario_venta=$sanear_string->sanear_strings_especiales( $comentario_venta );
			$stmt_edit->bindParam(":_comentario_venta", $comentario_venta, PDO::PARAM_STR);	
			$stmt_edit->bindParam(":_descuento", $descuento, PDO::PARAM_STR);
			$descuentoMotivo=$sanear_string->sanear_strings_especiales( $descuentoMotivo );
			$stmt_edit->bindParam(":_descuento_motivo", $descuentoMotivo , PDO::PARAM_STR);	
	
			$stmt_edit->bindParam(":_id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);	
			$stmt_edit->bindParam(":_total_vuelto", $vuelto, PDO::PARAM_STR);	
			$stmt_edit->bindParam(":_idVenta", $idVenta, PDO::PARAM_STR);	
			$anulado = 0;
	
			$stmt_edit->execute();

			$file ="./txt_Editar_venta.txt";
			file_put_contents($file,  json_encode($listaProductos));
			foreach ($listaProductos as $key => $producto) 
			{
				$stmt = $conn->prepare("INSERT INTO ventas_detalle(id_ventas,precio_venta_original, comentario_modificacion_precio, comentario_producto , id_producto , nombre_producto , precio_venta_producto  , descuento_producto , comentario_descuento , cantidad_producto , comprobante_unidad_medida , comprobante_codigo_interno_producto , comprobante_valor_unitario , comprobante_sub_total_neto , comprobante_tipo_igv , comprobante_igv , comprobante_sub_total , comprobante_impuesto_bolsas , estado) 
				VALUES (:id_ventas,:precio_venta_original, :comentario_modificacion_precio, :comentario_producto , :id_producto , :nombre_producto , :precio_venta_producto  , :descuento_producto , :comentario_descuento , :cantidad_producto , :comprobante_unidad_medida , :comprobante_codigo_interno_producto , :comprobante_valor_unitario ,:comprobante_sub_total_neto , :comprobante_tipo_igv , :comprobante_igv , :comprobante_sub_total , :comprobante_impuesto_bolsas, '1')");
				
				$stmt->bindParam(":id_ventas", $idVenta, PDO::PARAM_INT);
				$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
				$stmt->bindParam(":nombre_producto", $producto['descripcion'], PDO::PARAM_STR);
				
				$stmt->bindParam(":precio_venta_original", $producto['precio_original'], PDO::PARAM_STR);

				$modificacion_precio_motivo = $producto['modificacion_precio_motivo'];
				$modificacion_precio_motivo=$sanear_string->sanear_strings_especiales( $modificacion_precio_motivo );
				$stmt->bindParam(":comentario_modificacion_precio", $modificacion_precio_motivo , PDO::PARAM_STR);

				$comentario_producto = $producto['comentario_producto'];
				$comentario_producto=$sanear_string->sanear_strings_especiales( $comentario_producto );
				$stmt->bindParam(":comentario_producto", $comentario_producto, PDO::PARAM_STR);
				
				$stmt->bindParam(":precio_venta_producto", $producto['precio'], PDO::PARAM_STR);
				$stmt->bindParam(":descuento_producto", $producto['descuento'], PDO::PARAM_STR);

				$descuento_motivo = $producto['descuento_motivo'];
				$descuento_motivo=$sanear_string->sanear_strings_especiales( $descuento_motivo );
				$stmt->bindParam(":comentario_descuento", $descuento_motivo , PDO::PARAM_STR);
				$stmt->bindParam(":cantidad_producto", $producto['cantidad'], PDO::PARAM_STR);
				
				$stmt->bindParam(":comprobante_unidad_medida", $producto['unidad_de_medida'], PDO::PARAM_STR);
				$stmt->bindParam(":comprobante_codigo_interno_producto", $producto['codigo_producto_interno'], PDO::PARAM_STR);
				$stmt->bindParam(":comprobante_valor_unitario", $producto['valor_unitario'], PDO::PARAM_STR);
				$stmt->bindParam(":comprobante_sub_total_neto", $producto['sub_total_facturacion'], PDO::PARAM_STR);
				$stmt->bindParam(":comprobante_tipo_igv", $producto['tipo_de_igv'], PDO::PARAM_INT);
				$stmt->bindParam(":comprobante_igv", $producto['igv'], PDO::PARAM_STR);
				
				$stmt->bindParam(":comprobante_sub_total", $producto['subTotal'], PDO::PARAM_STR);
				$stmt->bindParam(":comprobante_impuesto_bolsas", $producto['impuesto_bolsas'], PDO::PARAM_STR);
				
				$stmt->execute();
				$id_detalle_ventas_detalle = $conn->lastInsertId(); 
				if($producto['cambio'])
				{
				
					$stmt = $conn->prepare(" UPDATE producto  SET  total_ventas =  total_ventas - ( :precio_venta_producto * :cantidad_producto)  WHERE id  =  :id_producto ; ");
					$stmt->bindParam(":precio_venta_producto", $producto['precio_ant'], PDO::PARAM_STR);
					$stmt->bindParam(":cantidad_producto", $producto['cantidad_ant'], PDO::PARAM_STR);
					$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
					$stmt->execute();
				}
				
				$stmt = $conn->prepare(" UPDATE producto  SET  total_ventas =  total_ventas +  :precio_venta_producto * :cantidad_producto  WHERE id  =  :id_producto ; ");
				$stmt->bindParam(":precio_venta_producto", $producto['precio'], PDO::PARAM_STR);
				$stmt->bindParam(":cantidad_producto", $producto['cantidad'], PDO::PARAM_STR);
				$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
				$stmt->execute();
				
				// HACEMOS EL RETIRO DE STOCK :)
				$stmt = $conn->prepare("
				UPDATE inventario 
				
				INNER JOIN unidad_medida_salida_x_producto ON  unidad_medida_salida_x_producto.id_producto = :id_producto
				INNER JOIN inventario_x_unidad_medida_salida ON inventario_x_unidad_medida_salida.id = unidad_medida_salida_x_producto.id_inventario_unidad_medida_salida AND inventario.id = inventario_x_unidad_medida_salida.id_inventario 
				SET inventario.sincronizado = 0,inventario.actual_cantidad =  inventario.actual_cantidad -  ( :cantidad_producto  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia  )   ;			 		");
				
				$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
				$stmt->bindParam(":cantidad_producto",  $producto['cantidad']   , PDO::PARAM_STR);
				$stmt->execute();
				
				$stmt = $conn->prepare(" UPDATE inventario  SET  inventario.actual_cantidad = 0, inventario.sincronizado = 0 WHERE  inventario.actual_cantidad < 0 ; ");
				$stmt->execute();
				
				// HACEMOS EL RETIRO DE STOCK :) esto esta faltante ksm
				
				//AHORA HACEMOS LA TRANSACCION DE DISMINUCION POR VENTA DEL INVENTARIO 
				
				$stmt = $conn->prepare("
				INSERT INTO  inventario_transaccion (id_inventario, id_detalle_ventas_detalle,cantidad_inicial, monto_inicial, cantidad_movimiento , monto_movimiento , tipo_movimiento, estado)
				
				SELECT 	
				
				inventario.id, 
				:id_detalle_ventas_detalle , 
				inventario.actual_cantidad  , 
				inventario.actual_costo_valorizado ,  
				:cantidad_prod1  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia  ,    
				CASE inventario.actual_cantidad   
				WHEN 0 THEN   inventario.actual_costo_valorizado   *:cantidad_prod2  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia 
				ELSE  (inventario.actual_costo_valorizado/inventario.actual_cantidad)    *:cantidad_prod3  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia  
				END,  
				'venta','1'
				FROM inventario
				INNER JOIN unidad_medida_salida_x_producto ON  unidad_medida_salida_x_producto.id_producto = :id_producto	
				
				INNER JOIN inventario_x_unidad_medida_salida ON inventario_x_unidad_medida_salida.id = unidad_medida_salida_x_producto.id_inventario_unidad_medida_salida AND inventario.id = inventario_x_unidad_medida_salida.id_inventario
				");
				
				$stmt->bindParam(":id_detalle_ventas_detalle", $id_detalle_ventas_detalle , PDO::PARAM_INT);
				$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
				$stmt->bindParam(":cantidad_prod1",   $producto['cantidad']   , PDO::PARAM_STR);
				$stmt->bindParam(":cantidad_prod2",   $producto['cantidad']   , PDO::PARAM_STR);
				$stmt->bindParam(":cantidad_prod3",   $producto['cantidad']   , PDO::PARAM_STR);
				
				$stmt->execute();
				
				$arr_aux = explode("-", $producto['unidad_de_medida'], 2);
				$unidad_medida_aux = $arr_aux[0];
				if($unidad_medida_aux=='') $unidad_medida_aux = 'NIU'; 
				if($producto['comentario_producto'] == "" ) 
					$descripcion_producto = $producto['descripcion'];
				else
					$descripcion_producto = $producto['descripcion']." (". $producto['comentario_producto'].")";
				
				$item =    array(
					"unidad_de_medida"          => $unidad_medida_aux,
					"codigo"                    => $producto['codigo_producto_interno'],
					"descripcion"               => $descripcion_producto,
					"cantidad"                  => $producto['cantidad'],
					"valor_unitario"            => $producto['valor_unitario'],
					"precio_unitario"           => $producto['precio'],
					"descuento"                 => $producto['descuento'],
					"subtotal"                  => $producto['sub_total_facturacion'],
					"tipo_de_igv"               => $producto['tipo_de_igv'],
					"igv"                       => $producto['igv'],
					"total"                     => $producto['subTotal'],
					"anticipo_regularizacion"   => "false",
					"anticipo_documento_serie"  => "",
					"anticipo_documento_numero" => "",
					"impuesto_bolsas"           => $producto['impuesto_bolsas'],
				);
				
				if($producto['tipo_de_igv'] ==1 ) // caso gravada
				{
					$total_gravada =  $total_gravada + $producto['sub_total_facturacion'];
					$total_igv  = $total_igv +    $producto['igv']    ;
				}
				
				if($producto['tipo_de_igv'] == 8  ) // caso gravada
				{
					$total_exonerada =  $total_exonerada + $producto['sub_total_facturacion'];
					
				}
				
				if($producto['tipo_de_igv'] == 9 ) // caso gravada
				{
					
					$total_inafecta =  $total_inafecta + $producto['sub_total_facturacion'];
					
				}
				
				$total_impuesto_bolsas  = $total_impuesto_bolsas +   $producto['impuesto_bolsas']     ;
				
				array_push($items_facturacion, $item );
				
				
			}
			
			//AHORA GUARDAMOS LOS COBROS 
			
			$medio_pago = "";
			
			foreach ($listaCobros as $key => $cobro) 
			{
				
				$stmt = $conn->prepare("INSERT INTO ventas_x_tipo_cobro( id_ventas, id_tipo_cobro , monto_cobro , nombre_cobro , fecha , nota ,  monto_vuelto , id_sesion_caja ) VALUES (:id_ventas, :id_tipo_cobro , :monto_cobro , :nombre_cobro , :fecha , :nota  ,:monto_vuelto , :id_sesion_caja )  ");
				
				$stmt->bindParam(":id_ventas", $idVenta, PDO::PARAM_INT);
				$stmt->bindParam(":id_tipo_cobro", $cobro['tipoCobros_id'] , PDO::PARAM_INT);
				$stmt->bindParam(":monto_cobro",  $cobro['montoCobro']   , PDO::PARAM_STR);
				$stmt->bindParam(":nombre_cobro", $cobro['tipoCobros_nombre'], PDO::PARAM_STR);
				$stmt->bindParam(":nota", $cobro['notaCobro'], PDO::PARAM_STR);
				$stmt->bindParam(":fecha", $fecha_venta, PDO::PARAM_STR);
				$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_STR);
				
				$aux_vuelto = 0;
				if( $cobro['tipoCobros_nombre'][0] === 'E'){
					$stmt->bindParam(":monto_vuelto", $vuelto, PDO::PARAM_STR);
					$aux_vuelto = $vuelto;
				}
				else{
					$stmt->bindValue(":monto_vuelto", 0 , PDO::PARAM_INT);
					$aux_vuelto = 0;
				}
				
				$stmt->execute();
				
				$medio_pago =  $medio_pago. "|".$cobro['tipoCobros_nombre']." Monto Recibido:". number_format($cobro['montoCobro'], 2)." Vuelto:".number_format($aux_vuelto, 2) ;
			}
			
			//FACTURACION ELECTRONICA.
			
			// "eyJhbGciOiJIUzI1NiJ9.IjA3ZjQ1NmQ1NDlmYzQwMzZiMjc3NGMzMzFlNWRkOWYzMjM5MjllOGEyYWJiNDYxMTk0M2NlY2U3YmJiN2E4ZGYi.N3DSfyH9-K538k6eisclzjVDHG-AyfOSGTsvVQEXpNc";
			
			$tipo_comprobante = $comprobante[0]['tipo_comprobante'];
			$cliente_tipo_de_documento = 0 ;
			
			$id_cliente = $comprobante[0]['identificador'];
			if($tipo_comprobante == 'Factura') {
				$tipo_comprobante = 1 ;
				$serie = $local_info['serie_factura'] ;
				$cliente_tipo_de_documento = 6 ;
				
			}
			
			if($tipo_comprobante == 'Boleta') {
				$tipo_comprobante = 2 ; 
				$serie = $local_info['serie_boleta'] ;
				$cliente_tipo_de_documento = 1 ;
				
				
				if ( $comprobante[0]['identificador'] == '' ) 
				$cliente_tipo_de_documento = '-' ;
				
				if (   substr( $id_cliente  , 0, 3)    == 'VAR' OR strlen($id_cliente) != 8) {


					$stmt1 = Conexion::conectar()->prepare("SELECT (count(1)*1 ) as correlativo FROM cliente where left(id_documento ,3) = 'VAR'");
					$stmt1 -> execute();
					$correlativo_cliente       = $stmt1 -> fetch()['correlativo'];
					$correlativo_cliente       = 'V-'. str_pad($correlativo_cliente, 8, '0', STR_PAD_LEFT);  ;
					$id_cliente                = $correlativo_cliente;
					$cliente_tipo_de_documento = '-' ;
	
				}
				
			}
			
			if ( $tipo_comprobante != 'No Info' &&  $tipo_comprobante != 'Ticket' ){
				
				$stmt1 = Conexion::conectar()->prepare("
				SELECT local.*, parametro_sistema.* FROM local , parametro_sistema 	WHERE local.id = :id_local ;
				");
				$stmt1->bindParam(":id_local", $id_local, PDO::PARAM_INT);
				$stmt1 -> execute();
				$local_info =  $stmt1 -> fetch();
				$stmt1 = null;
				
				
				// RUTA para enviar documentos
				$ruta = $local_info['ruta_url']; // "https://www.pse.pe/api/v1/d0b0bf9ac5ae4b7dbc63a3ef4ebfe6b3aa79a13ef7c8483e88b1ec2c79e53799";
				$token =$local_info['token']; 
				
				//obtenemos el correlativo
				$stmt1 = Conexion::conectar()->prepare("SELECT count(1) as correlativo FROM comprobante where serie = :serie order by 1 desc");
				$stmt1->bindParam(":serie", $serie, PDO::PARAM_STR);
				$stmt1 -> execute();
				$cantidad_serie =  $stmt1 -> fetch()['correlativo'];
				$stmt1 = null;
				
				$correlativo = $cantidad_serie + 1  ;
				
				$total_comprobante = $total_gravada + $total_inafecta + $total_exonerada + $total_igv + $total_impuesto_bolsas - $descuento ;
				
				$observaciones = "";
				
				//obtenemos el correlativo
				
				$stmt = $conn->prepare("
				
				INSERT INTO comprobante( id_venta, tipo_comprobante , serie , correlativo, fecha_emision , porcentaje_igv , descuento_global, total_descuento, total_gravada, total_inafecta , total_exonerada, total_igv, total_impuesto_bolsas, total, observaciones  , estado_comprobante  )
				VALUES(:id_venta, :tipo_comprobante , :serie , :correlativo, :fecha_emision , :porcentaje_igv , :descuento_global, :total_descuento, :total_gravada, :total_inafecta , :total_exonerada, :total_igv, :total_impuesto_bolsas, :total, :observaciones  , :estado_comprobante );
				
				");
				
				
				
				$stmt->bindParam(":id_venta", $idVenta , PDO::PARAM_INT);
				$stmt->bindParam(":tipo_comprobante", $tipo_comprobante  , PDO::PARAM_INT);
				$stmt->bindParam(":serie",   $serie  , PDO::PARAM_STR);
				$stmt->bindParam(":correlativo",   $correlativo , PDO::PARAM_INT);
				$stmt->bindValue(":fecha_emision",  $fecha_venta   , PDO::PARAM_STR);
				$stmt->bindParam(":porcentaje_igv", $local_info['igv']   , PDO::PARAM_STR);
				$stmt->bindParam(":descuento_global",  $descuento   , PDO::PARAM_STR);
				$stmt->bindParam(":total_descuento",  $descuento   , PDO::PARAM_STR);
				$stmt->bindParam(":total_gravada",   $total_gravada   , PDO::PARAM_STR);
				$stmt->bindParam(":total_inafecta",  $total_inafecta   , PDO::PARAM_STR);
				$stmt->bindParam(":total_exonerada",  $total_exonerada  , PDO::PARAM_STR);
				$stmt->bindParam(":total_igv",  $total_igv  , PDO::PARAM_STR);
				$stmt->bindParam(":total_impuesto_bolsas",  $total_impuesto_bolsas  , PDO::PARAM_STR);
				$stmt->bindParam(":total", $total_comprobante    , PDO::PARAM_STR);
				$stmt->bindValue(":observaciones", $observaciones  , PDO::PARAM_STR);
				$stmt->bindValue(":estado_comprobante",  "GENERADA"  , PDO::PARAM_STR);
				$stmt->execute();
				
				$id_comprobante_generado = $conn->lastInsertId(); 
				
				
				$tipo_operacion = "generar_comprobante";
				$data = array(
					"operacion"							=> $tipo_operacion,
					"tipo_de_comprobante"               => $tipo_comprobante,
					"serie"                             => $serie,
					"numero"							=> $correlativo,
					"sunat_transaction"					=> "1",
					"cliente_tipo_de_documento"			=> $cliente_tipo_de_documento,
					"cliente_numero_de_documento"		=> $id_cliente,
					"cliente_denominacion"              => $comprobante[0]['nombre'],
					"cliente_direccion"                 => $comprobante[0]['direccion'],
					"cliente_email"                     => $comprobante[0]['email'],
					"cliente_email_1"                   => "",
					"cliente_email_2"                   => "",
					"fecha_de_emision"                  => $fecha_venta,
					"fecha_de_vencimiento"              => "",
					"moneda"                            => "1", // 1 para soles
					"tipo_de_cambio"                    => "",
					"porcentaje_de_igv"                 => $local_info['igv'],
					"descuento_global"                  =>  $descuento ,
					"total_descuento"                   =>  $descuento  ,
					"total_anticipo"                    => "",
					"total_gravada"                     => $total_gravada,
					"total_inafecta"                    => $total_inafecta,
					"total_exonerada"                   => $total_exonerada,
					"total_igv"                         => $total_igv,
					"total_gratuita"                    => "",
					"total_otros_cargos"                => "",
					"total_impuestos_bolsas"            =>  $total_impuesto_bolsas,
					"total"                             =>$total_comprobante ,
					"percepcion_tipo"                   => "",
					"percepcion_base_imponible"         => "",
					"total_percepcion"                  => "",
					"total_incluido_percepcion"         => "",
					"detraccion"                        => "false",
					"observaciones"                     => $observaciones,
					"documento_que_se_modifica_tipo"    => "",
					"documento_que_se_modifica_serie"   => "",
					"documento_que_se_modifica_numero"  => "",
					"tipo_de_nota_de_credito"           => "",
					"tipo_de_nota_de_debito"            => "",
					"enviar_automaticamente_a_la_sunat" => "true",
					"enviar_automaticamente_al_cliente" => "false",
					"codigo_unico"                      => "",
					"condiciones_de_pago"               => "",
					"medio_de_pago"                     => $medio_pago,
					"placa_vehiculo"                    => "",
					"orden_compra_servicio"             => "",
					"tabla_personalizada_codigo"        => "",
					"formato_de_pdf"                    => "",
					"items" => $items_facturacion
					
				);
				
				$data_json = json_encode($data);
				file_put_contents("1getcomprobanteEditarVenta.txt", $data_json);
				
				
				//Invocamos el servicio de NUBEFACT
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $ruta);
				curl_setopt(
					$ch, CURLOPT_HTTPHEADER, array(
						'Authorization: Token token="'.$token.'"',
						'Content-Type: application/json',
						)
				);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$respuesta  = curl_exec($ch);
				curl_close($ch);
				
				
				
				$file ="./jose5.txt";
				file_put_contents($file,  $respuesta);
				
				$file ="./jose6.txt";
				file_put_contents($file,  $data_json);
				
				
				if (strpos($respuesta, 'errors') !== false) {
					$error = json_decode($respuesta, true);
					if($error["codigo"] == 23 OR $error["codigo"] == 21)
					{
						//file_put_contents("2rechazo.txt",$respuesta);
						$estado_comprobante = "RESPUESTA PROCESADA";
						$envio_comprobante = 'ok';
						$rechazado = True;
					}else{
						$estado_comprobante = "RESPUESTA PROCESADA";
						$envio_comprobante = 'ok';
						//$respuesta = $respuesta;
						$rechazado = False;
					}
				
				} else{
					$estado_comprobante = "RESPUESTA PROCESADA";
					$envio_comprobante = 'ok';
					$rechazado = False;
				}
				
				$stmt = $conn->prepare(" UPDATE comprobante SET respuesta_nubefact = :respuesta_nubefact , estado_comprobante = :estado_comprobante, tipo_operacion = :tipo_operacion WHERE id = :id_comprobante; ");
				$stmt->bindParam(":id_comprobante", $id_comprobante_generado , PDO::PARAM_INT);
				$stmt->bindParam(":respuesta_nubefact",   $respuesta  , PDO::PARAM_STR);
				$stmt->bindParam(":estado_comprobante",   $estado_comprobante  , PDO::PARAM_STR);
 				$stmt->bindParam(":tipo_operacion",   $tipo_operacion  , PDO::PARAM_STR);
				$stmt->execute();
				
				

				foreach ($listaProductos as $key => $producto) 
				{

					$arr_aux = explode("-", $producto['unidad_de_medida'], 2);
					$unidad_medida_aux = $arr_aux[0];
					if($unidad_medida_aux=='') $unidad_medida_aux = 'NIU'; 
					if($producto['comentario_producto'] == "" ) 
						$descripcion_producto = $producto['descripcion'];
					else
						$descripcion_producto = $producto['descripcion']." (". $producto['comentario_producto'].")";


					$stmt_comp_det = $conn->prepare("INSERT INTO comprobante_detalle (
						id_comprobante, id_producto, unidad_medida, codigo, descripcion, cantidad, valor_unitario, precio_unitario, 
						descuento, subtotal, tipo_de_igv, igv, total,impuesto_bolsas
					) 
					VALUES(
						:id_comprobante_comp, :id_producto_comp, :unidad_medida_comp, :codigo_comp, :descripcion_comp, :cantidad_comp, 
						:valor_unitario_comp, :precio_unitario_comp, :descuento_comp, :subtotal_comp, :tipo_de_igv_comp, 
						:igv_comp, :total_comp, :impuesto_bolsas_comp );");
					$stmt_comp_det->bindParam(":id_comprobante_comp", $id_comprobante_generado , PDO::PARAM_INT);
					$stmt_comp_det->bindParam(":id_producto_comp", $producto['codigo_producto_interno'] , PDO::PARAM_INT);
					$stmt_comp_det->bindParam(":unidad_medida_comp", $unidad_medida_aux , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":codigo_comp", $producto['codigo_producto_interno'] , pdo::PARAM_STR);
					$stmt_comp_det->bindParam(":descripcion_comp", $descripcion_producto , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":cantidad_comp", $producto['cantidad'] , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":valor_unitario_comp", $producto['valor_unitario'] , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":precio_unitario_comp", $producto['precio'] , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":descuento_comp", $producto['descuento'] , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":subtotal_comp", $producto['sub_total_facturacion'] , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":tipo_de_igv_comp", $producto['tipo_de_igv'] , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":igv_comp", $producto['igv'], PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":total_comp", $producto['subTotal'] , PDO::PARAM_STR);
					$stmt_comp_det->bindParam(":impuesto_bolsas_comp", $producto['impuesto_bolsas'] , PDO::PARAM_STR);
					$stmt_comp_det->execute();

				}
				
				//FACTURACION ELECTRONICA.
				if($rechazado)
				{
					try {

						$select1 = $conn -> prepare("SELECT * from comprobante where id = :idComprobante;");
						$select1 -> bindParam(":idComprobante",$id_comprobante_generado,PDO::PARAM_STR);
						$select1 -> execute();
						$comprobante = $select1->fetchAll(PDO::FETCH_ASSOC);
						$comprobante = $comprobante[0];
						$mail = new PHPMailer(true);
					
						$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
						//Server settings
						//mail->SMTPDebug = SMTP::DEBUG_SERVER;                  // Enable verbose debug output
						$mail->isSMTP();                                        // Send using SMTP
						$mail->Host       = 'abcmovil.pe';                      // Set the SMTP server to send through
						$mail->SMTPAuth   = true;                               // Enable SMTP authentication
						$mail->Username   = 'notificacion@abcmovil.pe';         // SMTP username
						$mail->Password   = 'b+$44df}51&,';                     // SMTP password
						//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
						$mail->Port       = 587;                                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
						$titulo = $local_info["identificador_local"]." | ".$comprobante['serie']."-".$comprobante['correlativo'];
						//Recipients
						$mail->setFrom('notificacion@abcmovil.pe', 'Notificaciones');
						//$mail->addAddress('jemarroquin@pucp.edu.pe');               // Name is optional
						//$mail->addAddress('jose.marroquin@abcmovil.pe');               // Name is optional
						$mail->ClearAddresses();
						$select_correos = $conn->prepare("SELECT * from correos where tipo = :tipo");
						$select_correos->bindValue(":tipo", 1 , PDO::PARAM_INT);
						$select_correos->execute();
						$correos = $select_correos->fetchAll(PDO::FETCH_ASSOC);
						if(!$correos){
							foreach ($correos as $correo)
							{
								$mail->addAddress($correo['correo']);
							}
						}else{
							$mail->addAddress('h.loreto@abcmovil.pe',"Hiram");

						}
						
						$file1 ="./comprobantes_".$comprobante['serie']."-".$comprobante['correlativo'].".txt";
						file_put_contents($file1,  $data_json);
						$file2 ="./Mensaje_".$comprobante['serie']."-".$comprobante['correlativo'].".txt";
						file_put_contents($file2,  $respuesta);

						// Attachments

						$mail->clearAttachments();
						$mail->addAttachment($file1);
						$mail->addAttachment($file2);
						/*$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

						// Content
						$mail->isHTML(true);                                  // Set email format to HTML
						$mail->Subject = $titulo;
						$body  = "ERROR COMPROBANTE ".$comprobante['serie']."-".$comprobante['correlativo']."<br>";
						$body .= "DETALLE DE ERROR: ".$error["errors"]."<br>";
						$body .= "URL: ".$ruta."<br>";
						$body .= "TOKEN: ".$token."<br>";
						$mail->Body    = $body;
						//$mail->AltBody = 'Prueba<br>';
						//file_put_contents("3rechazo.txt",json_encode($mail));

						$mail->send();
						$correo = 'Correo Enviado';
					} catch (Exception $e) {
						$correo = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
					}
				}
				
				
			} 
			//UNA VEZ QUE INSERTAMOS LA COMPRA ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES
			
			$conn->commit();
			$retorno = array("respuesta"=>"ok", "venta"=>$idVenta, "comprobante"=>$id_comprobante_generado, "correo" => $correo );

			
			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
			return $retorno;
			
			
			$stmt->close();
				$stmt = null;
				
		}
		catch(Exception $e) {
			
			$conn->rollBack();
			
			
			$file ="./errorEditarVenta.txt";
			file_put_contents($file,   $e->getMessage());
			return ($e->getMessage());
		}		
	}

	/*=============================================
				CAMBIAR COMPROBANTE
	=============================================*/

	static public function mdlCambiarCliente( $venta, $comprobante ){
		$cambio = $venta->id_cliente == $comprobante['id_documento_cliente'] ? 0 : 1;
		$direccion = ($venta->direccion == "" OR $venta->direccion == null) ? '-' : $venta->direccion;

		if($cambio)
		{
			try
			{
				date_default_timezone_set('America/Bogota');
				$fecha = date('Y-m-d');
				$hora  = date('H:i:s');
				$fecha = $fecha." ".$hora;
				$conn = Conexion::conectar();

				$conn->beginTransaction();

				$SQL = "UPDATE ventas 
				SET id_documento_cliente = :id_documento_client
				WHERE
					id = :id_vent;";
				
				$stmt_edit = $conn->prepare(" $SQL ");
				$stmt_edit->bindParam(':id_documento_client', $venta->id_cliente , PDO::PARAM_STR); // clientes
				$stmt_edit->bindParam(':id_vent', $venta->id_venta , PDO::PARAM_STR); // clientes
				$stmt_edit->execute();

				$SQL   = "UPDATE cliente 
				SET 
					compras = compras - :compras 
				WHERE
					id_documento = :id_documento_cliente;";

				$stmt_ = $conn->prepare(" $SQL ");

				$stmt_->bindParam(":compras", $comprobante['total_venta']  , PDO::PARAM_STR);//
				$stmt_->bindParam(':id_documento_cliente', $comprobante['id_documento_cliente'] , PDO::PARAM_STR); //
				$stmt_->execute();

				$stmt = $conn->prepare(" INSERT INTO  cliente (  nombre , id_documento , direccion , compras  , fecha_ingreso , fecha_ultima_compra , nombre_comercial )
				VALUES( :nombre_new , :id_documento_new , :direccion_new , :compras_new , :fecha_ingreso_new , :fecha_ultima_compra_new , :nombre_comercial_new )
				ON DUPLICATE KEY UPDATE
				compras = compras + :compras_update_new ,
				fecha_ultima_compra = :fecha_ultima_compra_update_new; ");

				$stmt->bindParam(":nombre_new", $venta->nombre_cliente, PDO::PARAM_STR);	
				$stmt->bindParam(":nombre_comercial_new", $venta->nombre_cliente, PDO::PARAM_STR);
				$stmt->bindParam(":id_documento_new",  $venta->id_cliente , PDO::PARAM_STR);
				//$stmt->bindParam(":email_new", $venta->email, PDO::PARAM_STR);
				$stmt->bindParam(":direccion_new", $direccion, PDO::PARAM_STR);

				$stmt->bindParam(":compras_new", $comprobante['total_venta']  , PDO::PARAM_STR);
				$stmt->bindParam(":compras_update_new", $comprobante['total_venta']  , PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ingreso_new", $fecha, PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ultima_compra_new", $fecha, PDO::PARAM_STR);
				$stmt->bindParam(":fecha_ultima_compra_update_new", $fecha, PDO::PARAM_STR);
				$txt = $fecha."\n";
				$txt .= json_encode($venta)."\n";
				$txt .= json_encode($comprobante)."\n";
				$file ="./errorCambiarCliente.txt";
				file_put_contents($file, $txt );

				$stmt->execute();
		
				$conn->commit();
				return "ok";
				
			}
			catch(Exception $e) {
				$conn->rollBack();
				return ($e->getMessage());
			}
		}
		else
		{
			return "EL CLIENTE NO HA CAMBIADO";
		}
	}
	/*=============================================
				OBTENER VENTA POR ID
	=============================================*/

	static public function mdlGetVentas( $obj )
	{
		
		 $idVenta = $obj->id_venta ;

		$stmt = Conexion::conectar()->prepare("SELECT
			*
		FROM ventas
		LEFT JOIN cliente ON (cliente.id_documento = ventas.id_documento_cliente)
		WHERE
			id = :idVenta");
		$stmt->bindParam(":idVenta", $idVenta, PDO::PARAM_INT);	
		$stmt -> execute();
		return $stmt -> fetchAll( PDO::FETCH_ASSOC);

	}
	/*=============================================
				OBTENER VENTA POR ID
	=============================================*/

	static public function mdlGetVentaDetalles( $obj )
	{
		
		 $idVenta = $obj->id_venta ;

		$stmt = Conexion::conectar()->prepare("SELECT
			* 
		FROM
			ventas_detalle
		WHERE
			id_ventas = :idVenta AND ventas_detalle.estado = '1'");
		$stmt->bindParam(":idVenta", $idVenta, PDO::PARAM_INT);	
		$stmt -> execute();
		return $stmt -> fetchAll( PDO::FETCH_ASSOC);

	}
	/*=============================================
				OBTENER COMPROBANTE POR ID
	=============================================*/

	static public function mdlGetComprobante( $obj )
	{

		$idVenta       = $obj->id_venta;
		$idComprobante = $obj->id_comprobante;

		$stmt = Conexion::conectar()->prepare("SELECT
			*
		FROM
			comprobante
		WHERE
			id_venta =:idVenta AND id = :idComprobante");
		$stmt->bindParam(":idVenta", $idVenta, PDO::PARAM_STR);	
		$stmt->bindParam(":idComprobante", $idComprobante, PDO::PARAM_STR);	
		$stmt -> execute();

		return $stmt -> fetchAll( PDO::FETCH_ASSOC);

	}
	/*=============================================
				OBTENER COMPROBANTE POR ID
	=============================================*/

	static public function mdlGetTipoCobro( $obj )
	{

		$idVenta       = $obj->id_venta;

		$stmt = Conexion::conectar()->prepare("SELECT
			ventas_x_tipo_cobro.*
		FROM
			ventas
		INNER JOIN 
			ventas_x_tipo_cobro ON ventas.id = ventas_x_tipo_cobro.id_ventas
		WHERE
			ventas.id =:idVenta AND ventas_x_tipo_cobro.estado = '1'");
		$stmt->bindParam(":idVenta", $idVenta, PDO::PARAM_STR);	
		$stmt -> execute();

		return $stmt -> fetchAll( PDO::FETCH_ASSOC);

	}
	/*=============================================
			OBTENER PRODUCTOS VENTAS CAJA
	=============================================*/

	static public function mdlProductoVentaCaja( $id_sesion_caja )
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		CONCAT(ventas_detalle.id_producto,'-',ventas_detalle.nombre_producto) producto,
		ventas_detalle.precio_venta_producto precio_unitario,
		SUM( ventas_detalle.cantidad_producto ) cantidad_producto,
		(ventas_detalle.precio_venta_producto * SUM( ventas_detalle.cantidad_producto ) ) monto_producto 
	FROM
		 ventas_detalle 
		LEFT JOIN ventas ON ventas.id = ventas_detalle.id_ventas 
	WHERE
		ventas_detalle.estado = '1' 
		AND ventas.id_sesion_caja = :id_sesion_caja 
	GROUP BY
		producto,
		ventas_detalle.precio_venta_producto 
	ORDER BY
		monto_producto desc");
		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);	
		$stmt -> execute();

		return $stmt -> fetchAll( PDO::FETCH_ASSOC);

	}
	/*=============================================
	REGISTRO DE VENTA
	=============================================*/

	static public function mdlPagarCotizacion(  $listaProductos, $listaCobros , $descuento , $descuentoMotivo,$fecha_venta , $id_sesion_caja , $vuelto, $id_vendedor, $nombre_vendedor , $id_local , $comprobante,$id_cotizacion, $comentario_venta){
			//$file ="./jose4.txt";
   			//file_put_contents($file,  "entre4");

			   $correo = "";
			   $sanear_string = new bd();
			$total= 0 ;
			$total_productos=0; // cantidad de productos
			$id_ventas_generado  = 0 ;
			$id_detalle_ventas_detalle = 0 ;
			$items_facturacion = array();

			$total_gravada = 0;
			$total_inafecta = 0;
			$total_exonerada = 0;
			$total_igv = 0 ;
			$total_impuesto_bolsas = 0;
			$total_impuesto_bolsas_aux= 0;

			  foreach ($listaProductos as $key => $producto) 
			    {
			    		 $total =  $total + $producto['subTotal'];
			    		 $total_productos = $total_productos + $producto['cantidad'];			     
			    		 $total_impuesto_bolsas_aux = $total_impuesto_bolsas_aux + $producto['impuesto_bolsas'];			     
				}


				if($total != 0 )
					$descuento_porcentaje =  $descuento / $total ;
				else 
					$descuento_porcentaje= 0 ;
				
				$total = $total + $total_impuesto_bolsas_aux - $descuento;

//=============================================================================================================
 
		try {
 		$conn = Conexion::conectar();
		
		$conn->beginTransaction();

		$guardar_cliente = true;

 if(  $comprobante[0]['tipo_comprobante'] =='No Info'  ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
 		$guardar_cliente = false;
 

 if(  $comprobante[0]['tipo_comprobante'] =='Ticket' &&  $comprobante[0]['identificador'] ==''  && $comprobante[0]['nombre'] ==''    )
 		$guardar_cliente = false;





 if(  $comprobante[0]['tipo_comprobante'] =='Boleta' &&  $comprobante[0]['nombre'] ==''  &&  $comprobante[0]['identificador'] =='' )
 		 {
 		 	$comprobante[0]['nombre'] = "CLIENTE VARIOS" ; 
 		 }


//if(  $comprobante[0]['tipo_comprobante'] =='Boleta' &&  $comprobante[0]['identificador'] ==''  )
 //		 {
 	//	 	$comprobante[0]['identificador'] = "-" ; 
 	//	 }



 



 if(  $guardar_cliente  ) // Si esta seleccionado NO INFO, NO GUARDAS EL CLIENTE.
{

	if($comprobante[0]['identificador'] =="" )
		$comprobante[0]['identificador'] = "VAR-" . date("YmdHis");  



	$stmt = $conn->prepare("
INSERT INTO  cliente (  nombre, id_documento , email , direccion , compras  , fecha_ingreso , fecha_ultima_compra, nombre_comercial )
VALUES(  :nombre, :id_documento , :email , :direccion , :compras  , :fecha_ingreso , :fecha_ultima_compra, :nombre_comercial )
ON DUPLICATE KEY UPDATE
compras = compras + :compras_update , 
fecha_ultima_compra = :fecha_ultima_compra_update  ;


");

		$stmt->bindParam(":nombre", $comprobante[0]['nombre'], PDO::PARAM_STR);	
		$stmt->bindParam(":nombre_comercial", $comprobante[0]['nombre'], PDO::PARAM_STR);	
		$stmt->bindParam(":id_documento", $comprobante[0]['identificador'], PDO::PARAM_STR);	
		$stmt->bindParam(":email", $comprobante[0]['email'], PDO::PARAM_STR);	
		$stmt->bindParam(":direccion", $comprobante[0]['direccion'], PDO::PARAM_STR);	
		
		$stmt->bindParam(":compras", $total  , PDO::PARAM_STR);
		$stmt->bindParam(":fecha_ingreso", $fecha_venta, PDO::PARAM_STR);	
		$stmt->bindParam(":fecha_ultima_compra", $fecha_venta, PDO::PARAM_STR);	

		$stmt->bindParam(":compras_update",   $total  , PDO::PARAM_STR);
		$stmt->bindParam(":fecha_ultima_compra_update", $fecha_venta, PDO::PARAM_STR);	

		$stmt->execute();

}



 		$stmt = $conn->prepare("INSERT INTO ventas(id_documento_cliente, id_vendedor, total , fecha_venta , total_productos , id_local, nombre_vendedor , descuento , descuento_motivo , id_sesion_caja , total_vuelto, anulado, comentario ) VALUES (:id_documento_cliente, :id_vendedor, :total , :fecha_venta , :total_productos , :id_local, :nombre_vendedor , :descuento , :descuento_motivo , :id_sesion_caja , :total_vuelto, :anulado, :comentario_venta )");


if( $guardar_cliente )
	 	$stmt->bindParam(':id_documento_cliente',  $comprobante[0]['identificador'], PDO::PARAM_STR); // clientes
	
else 
$stmt->bindValue(':id_documento_cliente', null, PDO::PARAM_INT); // clientes en nulos aun no los creamos


		$stmt->bindParam(":id_vendedor", $id_vendedor, PDO::PARAM_INT);
 		$stmt->bindParam(":total", $total, PDO::PARAM_STR);	
 		$stmt->bindParam(":fecha_venta", $fecha_venta, PDO::PARAM_STR);	
 		$stmt->bindParam(":total_productos", $total_productos, PDO::PARAM_STR);	

 		$stmt->bindParam(":id_local", $id_local, PDO::PARAM_INT);
		
		$stmt->bindParam(":nombre_vendedor", $nombre_vendedor, PDO::PARAM_STR);	

		$comentario_venta  =$sanear_string->sanear_strings_especiales( $comentario_venta );
		$stmt->bindParam(":comentario_venta", $comentario_venta , PDO::PARAM_STR);	

		$stmt->bindParam(":descuento", $descuento, PDO::PARAM_STR);	

		$descuentoMotivo  =$sanear_string->sanear_strings_especiales( $descuentoMotivo );
		$stmt->bindParam(":descuento_motivo", $descuentoMotivo , PDO::PARAM_STR);	

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_INT);	
		$stmt->bindParam(":total_vuelto", $vuelto, PDO::PARAM_STR);	
		$anulado = 0;
		$stmt->bindParam(":anulado", $anulado, PDO::PARAM_INT);

		$stmt->execute();
		$id_ventas_generado = $conn->lastInsertId();

 	  	foreach ($listaProductos as $key => $producto) 
		{
			$stmt = $conn->prepare("INSERT INTO ventas_detalle(id_ventas,precio_venta_original,  comentario_producto ,comentario_modificacion_precio , id_producto , nombre_producto , precio_venta_producto  , descuento_producto , comentario_descuento , cantidad_producto , comprobante_unidad_medida , comprobante_codigo_interno_producto , comprobante_valor_unitario , comprobante_sub_total_neto , comprobante_tipo_igv , comprobante_igv , comprobante_sub_total , comprobante_impuesto_bolsas, estado) VALUES (:id_ventas,:precio_venta_original, :comentario_producto ,:comentario_modificacion_precio , :id_producto , :nombre_producto , :precio_venta_producto  , :descuento_producto , :comentario_descuento , :cantidad_producto , :comprobante_unidad_medida , :comprobante_codigo_interno_producto , :comprobante_valor_unitario ,:comprobante_sub_total_neto , :comprobante_tipo_igv , :comprobante_igv , :comprobante_sub_total , :comprobante_impuesto_bolsas, '1')");

			$stmt->bindParam(":id_ventas", $id_ventas_generado, PDO::PARAM_INT);
			$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
			$stmt->bindParam(":nombre_producto", $producto['descripcion'], PDO::PARAM_STR);

			
			$stmt->bindParam(":precio_venta_original", $producto['precio_original'], PDO::PARAM_STR);

			$modificacion_precio_motivo = $producto['modificacion_precio_motivo'];
			$modificacion_precio_motivo = $sanear_string->sanear_strings_especiales( $modificacion_precio_motivo );
			$stmt->bindParam(":comentario_modificacion_precio", $modificacion_precio_motivo , PDO::PARAM_STR);

			$comentario_producto = $producto['comentario_producto'];
			$comentario_producto = $sanear_string->sanear_strings_especiales( $comentario_producto );
			$stmt->bindParam(":comentario_producto", $comentario_producto , PDO::PARAM_STR);

			$stmt->bindParam(":precio_venta_producto", $producto['precio'], PDO::PARAM_STR);
			$stmt->bindParam(":descuento_producto", $producto['descuento'], PDO::PARAM_STR);
			$descuento_motivo = $producto['descuento_motivo'];
			$descuento_motivo = $sanear_string->sanear_strings_especiales( $descuento_motivo );
			$stmt->bindParam(":comentario_descuento", $descuento_motivo , PDO::PARAM_STR);
			$stmt->bindParam(":cantidad_producto", $producto['cantidad'], PDO::PARAM_STR);


			$stmt->bindParam(":comprobante_unidad_medida", $producto['unidad_de_medida'], PDO::PARAM_STR);
			$stmt->bindParam(":comprobante_codigo_interno_producto", $producto['codigo_producto_interno'], PDO::PARAM_STR);
			$stmt->bindParam(":comprobante_valor_unitario", $producto['valor_unitario'], PDO::PARAM_STR);
			$stmt->bindParam(":comprobante_sub_total_neto", $producto['sub_total_facturacion'], PDO::PARAM_STR);
			$stmt->bindParam(":comprobante_tipo_igv", $producto['tipo_de_igv'], PDO::PARAM_INT);
			$stmt->bindParam(":comprobante_igv", $producto['igv'], PDO::PARAM_STR);

			$stmt->bindParam(":comprobante_sub_total", $producto['subTotal'], PDO::PARAM_STR);
			$stmt->bindParam(":comprobante_impuesto_bolsas", $producto['impuesto_bolsas'], PDO::PARAM_STR);


			$stmt->execute();
			$id_detalle_ventas_detalle = $conn->lastInsertId(); 


			// HACEMOS EL RETIRO DE STOCK :)

			$stmt = $conn->prepare(" UPDATE producto  SET  total_ventas =  total_ventas +  :precio_venta_producto * :cantidad_producto  WHERE id  =  :id_producto ; ");
			$stmt->bindParam(":precio_venta_producto", $producto['precio'], PDO::PARAM_STR);
			$stmt->bindParam(":cantidad_producto", $producto['cantidad'], PDO::PARAM_STR);
			$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
			$stmt->execute();


			//AHORA HACEMOS LA TRANSACCION DE DISMINUCION POR VENTA DEL INVENTARIO 

			$stmt = $conn->prepare("
			INSERT INTO  inventario_transaccion (id_inventario, id_detalle_ventas_detalle,cantidad_inicial, monto_inicial, cantidad_movimiento , monto_movimiento , tipo_movimiento, fecha , nombre_inventario , estado )

			SELECT 	

					inventario.id, 
					:id_detalle_ventas_detalle , 
					inventario.actual_cantidad  , 
					inventario.actual_costo_valorizado ,  
					:cantidad_prod1  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia  ,    
					CASE inventario.actual_cantidad   
					WHEN 0 THEN   inventario.actual_costo_valorizado   *:cantidad_prod2  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia 
					ELSE  (inventario.actual_costo_valorizado/inventario.actual_cantidad)    *:cantidad_prod3  * unidad_medida_salida_x_producto.cantidad_inventario / inventario_x_unidad_medida_salida.equivalencia  
					END,  
					'venta' , :fecha_venta , inventario.nombre , :estado
			FROM inventario
			INNER JOIN unidad_medida_salida_x_producto ON  unidad_medida_salida_x_producto.id_producto = :id_producto	

			INNER JOIN inventario_x_unidad_medida_salida ON inventario_x_unidad_medida_salida.id = unidad_medida_salida_x_producto.id_inventario_unidad_medida_salida AND inventario.id = inventario_x_unidad_medida_salida.id_inventario
			");
	
			$stmt->bindParam(":id_detalle_ventas_detalle", $id_detalle_ventas_detalle , PDO::PARAM_INT);
			$stmt->bindParam(":id_producto", $producto['id'] , PDO::PARAM_INT);
			$stmt->bindParam(":cantidad_prod1",   $producto['cantidad']   , PDO::PARAM_STR);
			$stmt->bindParam(":cantidad_prod2",   $producto['cantidad']   , PDO::PARAM_STR);
			$stmt->bindParam(":fecha_venta", $fecha_venta, PDO::PARAM_STR);	
			$stmt->bindParam(":cantidad_prod3",   $producto['cantidad']   , PDO::PARAM_STR);
			$stmt->bindValue(":estado", 1 , PDO::PARAM_INT);


			$stmt->execute();
			$id_inventario_transaccion = $conn->lastInsertId(); 


			// HACEMOS EL RETIRO DE STOCK :)
			$stmt = $conn->prepare("
					UPDATE inventario 
					INNER JOIN inventario_transaccion ON  inventario_transaccion.id  = :id_inventario_transaccion AND inventario.id = inventario_transaccion.id_inventario
					SET   inventario.sincronizado = 0,	inventario.actual_cantidad  =   inventario.actual_cantidad   -  inventario_transaccion.cantidad_movimiento ,
							inventario.actual_costo_valorizado  =  inventario.actual_costo_valorizado    -  inventario_transaccion.monto_movimiento ;


						;");

			$stmt->bindParam(":id_inventario_transaccion", $id_inventario_transaccion, PDO::PARAM_INT);
			
			$stmt->execute();

			$stmt = $conn->prepare(" UPDATE inventario  SET  inventario.sincronizado = 0,inventario.actual_cantidad = 0 WHERE  inventario.actual_cantidad < 0 ; ");
			$stmt->execute();

			// HACEMOS EL RETIRO DE STOCK :) esto esta faltante ksm



					
			$arr_aux = explode("-", $producto['unidad_de_medida'], 2);
			$unidad_medida_aux = $arr_aux[0];
			if($unidad_medida_aux=='') $unidad_medida_aux = 'NIU'; 

			$item =    array(
						"unidad_de_medida"          => $unidad_medida_aux,
						"codigo"                    => $producto['codigo_producto_interno'],
						"descripcion"               => $producto['descripcion'],
						"cantidad"                  => $producto['cantidad'],
						"valor_unitario"            => $producto['valor_unitario'],
						"precio_unitario"           => $producto['precio'],
						"descuento"                 => $producto['descuento'],
						"subtotal"                  => $producto['sub_total_facturacion'],
						"tipo_de_igv"               => $producto['tipo_de_igv'],
						"igv"                       => $producto['igv'],
						"total"                     => $producto['subTotal'],
						"anticipo_regularizacion"   => "false",
						"anticipo_documento_serie"  => "",
						"anticipo_documento_numero" => "",
						"impuesto_bolsas"           => $producto['impuesto_bolsas'],
						"comentario_producto"       => $producto['comentario_producto'],
					);


		     if($producto['tipo_de_igv'] ==1 ) // caso gravada
			{
				$total_gravada =  $total_gravada + $producto['sub_total_facturacion'] * (1 - $descuento_porcentaje ) ;
				$total_igv  = $total_igv +    $producto['igv']  * (1 - $descuento_porcentaje )   ;
			}

			if($producto['tipo_de_igv'] == 8  ) // caso gravada
			{
				$total_exonerada =  $total_exonerada + $producto['sub_total_facturacion'] * (1 - $descuento_porcentaje ) ; 
	
			}


		    if($producto['tipo_de_igv'] == 9 ) // caso gravada
			{
			
				$total_inafecta =  $total_inafecta + $producto['sub_total_facturacion'] * (1 - $descuento_porcentaje ) ;
			
			}


			$total_impuesto_bolsas  = $total_impuesto_bolsas +   $producto['impuesto_bolsas']     ;

				array_push($items_facturacion, $item );


			}

			//AHORA GUARDAMOS LOS COBROS 

			    $medio_pago = "";

 	  		foreach ($listaCobros as $key => $cobro) 
			{

				$stmt = $conn->prepare("INSERT INTO ventas_x_tipo_cobro( id_ventas, id_tipo_cobro , monto_cobro , nombre_cobro , fecha , nota ,  monto_vuelto , id_sesion_caja ) VALUES (:id_ventas, :id_tipo_cobro , :monto_cobro , :nombre_cobro , :fecha , :nota  ,:monto_vuelto , :id_sesion_caja )  ");

				$stmt->bindParam(":id_ventas", $id_ventas_generado, PDO::PARAM_INT);
				$stmt->bindParam(":id_tipo_cobro", $cobro['tipoCobros_id'] , PDO::PARAM_INT);
				$stmt->bindParam(":monto_cobro",  $cobro['montoCobro']   , PDO::PARAM_STR);
				$stmt->bindParam(":nombre_cobro", $cobro['tipoCobros_nombre'], PDO::PARAM_STR);
				$stmt->bindParam(":nota", $cobro['notaCobro'], PDO::PARAM_STR);
				$stmt->bindParam(":fecha", $fecha_venta, PDO::PARAM_STR);
				$stmt->bindParam(":id_sesion_caja", $id_sesion_caja, PDO::PARAM_STR);


				$aux_vuelto = 0;
				if( $cobro['tipoCobros_nombre'][0] === 'E'){
					$stmt->bindParam(":monto_vuelto", $vuelto, PDO::PARAM_STR);
					$aux_vuelto = $vuelto;
				}
				else{
					$stmt->bindValue(":monto_vuelto", 0 , PDO::PARAM_INT);
					$aux_vuelto = 0;
				}
				
		
				$stmt->execute();

				$medio_pago =  $medio_pago. "|".$cobro['tipoCobros_nombre']." Monto Recibido:". number_format($cobro['montoCobro'], 2)." Vuelto:".number_format($aux_vuelto, 2) ;
			}


			$update = $conn->prepare("UPDATE cotizacion SET anulado = '1' , id_ventas = :id_venta WHERE id = :id_cotizacion;");
			$update->bindParam(':id_cotizacion',$id_cotizacion, PDO::PARAM_INT);
			$update->bindParam(':id_venta',$id_ventas_generado, PDO::PARAM_INT);
			$update->execute();
			$update = $conn->prepare("UPDATE cotizacion_detalle SET estado = '1' WHERE id_cotizacion = :param_cotizacion;");
			$update->bindParam(':param_cotizacion',$id_cotizacion,PDO::PARAM_INT);
			$update->execute();

			//FACTURACION ELECTRONICA.
 
	 
			$id_comprobante_generado = "";

			$stmt1 = Conexion::conectar()->prepare("
			SELECT local.*, parametro_sistema.* FROM local , parametro_sistema 	WHERE local.id = :id_local ;
			");
			$stmt1->bindParam(":id_local", $id_local, PDO::PARAM_INT);
			$stmt1 -> execute();
			$local_info =  $stmt1 -> fetch();
			$stmt1 = null;

			// "eyJhbGciOiJIUzI1NiJ9.IjA3ZjQ1NmQ1NDlmYzQwMzZiMjc3NGMzMzFlNWRkOWYzMjM5MjllOGEyYWJiNDYxMTk0M2NlY2U3YmJiN2E4ZGYi.N3DSfyH9-K538k6eisclzjVDHG-AyfOSGTsvVQEXpNc";

			$tipo_comprobante = $comprobante[0]['tipo_comprobante'];
			$cliente_tipo_de_documento = 0 ;

			if($tipo_comprobante == 'Factura') {
				$tipo_comprobante = 1 ;
				$serie = $local_info['serie_factura'] ;
				$cliente_tipo_de_documento = 6 ;

			}

			if($tipo_comprobante == 'Boleta') {
				$tipo_comprobante = 2 ; 
				$serie = $local_info['serie_boleta'] ;
				$cliente_tipo_de_documento = 1 ;


				if (   substr( $comprobante[0]['identificador']  , 0, 3)    == 'VAR' ) {


					$stmt1 = Conexion::conectar()->prepare("SELECT (count(1)*1 ) as correlativo FROM cliente where left(id_documento ,3) = 'VAR'");
							$stmt1 -> execute();
							$correlativo_cliente =  $stmt1 -> fetch()['correlativo'];
							$correlativo_cliente = 'V-'. str_pad($correlativo_cliente, 8, '0', STR_PAD_LEFT);  ;
							$comprobante[0]['identificador'] = $correlativo_cliente;
								$cliente_tipo_de_documento = '-' ;

				}


				if ( $comprobante[0]['identificador'] == '-' ) {

					
				$cliente_tipo_de_documento = '-' ;
					
				}


			}


			if ( $tipo_comprobante != 'No Info' &&  $tipo_comprobante != 'Ticket' ){




			// RUTA para enviar documentos
			$ruta = $local_info['ruta_url']; // "https://www.pse.pe/api/v1/d0b0bf9ac5ae4b7dbc63a3ef4ebfe6b3aa79a13ef7c8483e88b1ec2c79e53799";
			$token =$local_info['token']; 

			//obtenemos el correlativo
			$stmt1 = Conexion::conectar()->prepare("SELECT (count(1)*1 ) as correlativo FROM comprobante where serie = :serie order by 1 desc");
			$stmt1->bindParam(":serie", $serie, PDO::PARAM_STR);
			$stmt1 -> execute();
			$cantidad_serie =  $stmt1 -> fetch()['correlativo'];
			$stmt1 = null;

			$correlativo = $cantidad_serie + 1  ;

			$total_comprobante = $total_gravada + $total_inafecta + $total_exonerada + $total_igv + $total_impuesto_bolsas;

			$observaciones = "";

			//obtenemos el correlativo



			$stmt = $conn->prepare("
				
	INSERT INTO comprobante( id_venta, tipo_comprobante , serie , correlativo, fecha_emision , porcentaje_igv , descuento_global, total_descuento, total_gravada, total_inafecta , total_exonerada, total_igv, total_impuesto_bolsas, total, observaciones  , estado_comprobante  )
	VALUES(:id_venta, :tipo_comprobante , :serie , :correlativo, :fecha_emision , :porcentaje_igv , :descuento_global, :total_descuento, :total_gravada, :total_inafecta , :total_exonerada, :total_igv, :total_impuesto_bolsas, :total, :observaciones  , :estado_comprobante );

						");



			$stmt->bindParam(":id_venta", $id_ventas_generado , PDO::PARAM_INT);
			$stmt->bindParam(":tipo_comprobante", $tipo_comprobante  , PDO::PARAM_INT);
			$stmt->bindParam(":serie",   $serie  , PDO::PARAM_STR);
			$stmt->bindParam(":correlativo",   $correlativo , PDO::PARAM_INT);
			$stmt->bindValue(":fecha_emision",  $fecha_venta   , PDO::PARAM_STR);
			$stmt->bindParam(":porcentaje_igv", $local_info['igv']   , PDO::PARAM_STR);
			$stmt->bindParam(":descuento_global",  $descuento   , PDO::PARAM_STR);
			$stmt->bindParam(":total_descuento",  $descuento   , PDO::PARAM_STR);
			$stmt->bindParam(":total_gravada",   $total_gravada   , PDO::PARAM_STR);
			$stmt->bindParam(":total_inafecta",  $total_inafecta   , PDO::PARAM_STR);
			$stmt->bindParam(":total_exonerada",  $total_exonerada  , PDO::PARAM_STR);
			$stmt->bindParam(":total_igv",  $total_igv  , PDO::PARAM_STR);
			$stmt->bindParam(":total_impuesto_bolsas",  $total_impuesto_bolsas  , PDO::PARAM_STR);
			$stmt->bindParam(":total", $total_comprobante    , PDO::PARAM_STR);
			$stmt->bindValue(":observaciones", $observaciones  , PDO::PARAM_STR);
			$stmt->bindValue(":estado_comprobante",  "GENERADA"  , PDO::PARAM_STR);
			$stmt->execute();

			$id_comprobante_generado = $conn->lastInsertId(); 


			foreach ($listaProductos as $key => $producto) 
			{

				$arr_aux = explode("-", $producto['unidad_de_medida'], 2);
				$unidad_medida_aux = $arr_aux[0];
				if($unidad_medida_aux=='') $unidad_medida_aux = 'NIU'; 
				if($producto['comentario_producto'] == "" ) 
					$descripcion_producto = $producto['descripcion'];
				else
					$descripcion_producto = $producto['descripcion']." (". $producto['comentario_producto'].")";


				$stmt_comp_det = $conn->prepare("INSERT INTO comprobante_detalle (
					id_comprobante, id_producto, unidad_medida, codigo, descripcion, cantidad, valor_unitario, precio_unitario, 
					descuento, subtotal, tipo_de_igv, igv, total,impuesto_bolsas
				) 
				VALUES(
					:id_comprobante_comp, :id_producto_comp, :unidad_medida_comp, :codigo_comp, :descripcion_comp, :cantidad_comp, 
					:valor_unitario_comp, :precio_unitario_comp, :descuento_comp, :subtotal_comp, :tipo_de_igv_comp, 
					:igv_comp, :total_comp, :impuesto_bolsas_comp );");
				$stmt_comp_det->bindParam(":id_comprobante_comp", $id_comprobante_generado , PDO::PARAM_INT);
				$stmt_comp_det->bindParam(":id_producto_comp", $producto['codigo_producto_interno'] , PDO::PARAM_INT);
				$stmt_comp_det->bindParam(":unidad_medida_comp", $unidad_medida_aux , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":codigo_comp", $producto['codigo_producto_interno'] , pdo::PARAM_STR);
				$stmt_comp_det->bindParam(":descripcion_comp", $descripcion_producto , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":cantidad_comp", $producto['cantidad'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":valor_unitario_comp", $producto['valor_unitario'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":precio_unitario_comp", $producto['precio'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":descuento_comp", $producto['descuento'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":subtotal_comp", $producto['sub_total_facturacion'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":tipo_de_igv_comp", $producto['tipo_de_igv'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":igv_comp", $producto['igv'], PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":total_comp", $producto['subTotal'] , PDO::PARAM_STR);
				$stmt_comp_det->bindParam(":impuesto_bolsas_comp", $producto['impuesto_bolsas'] , PDO::PARAM_STR);
				$stmt_comp_det->execute();

			}

			$tipo_operacion = "generar_comprobante";
			$data = array(
				"operacion"							=> $tipo_operacion,
				"tipo_de_comprobante"               => $tipo_comprobante,
				"serie"                             => $serie,
				"numero"							=> $correlativo,
				"sunat_transaction"					=> "1",
				"cliente_tipo_de_documento"			=> $cliente_tipo_de_documento,
				"cliente_numero_de_documento"		=> $comprobante[0]['identificador'],
				"cliente_denominacion"              => $comprobante[0]['nombre'],
				"cliente_direccion"                 => $comprobante[0]['direccion'],
				"cliente_email"                     => $comprobante[0]['email'],
				"cliente_email_1"                   => "",
				"cliente_email_2"                   => "",
				"fecha_de_emision"                  => $fecha_venta,
				"fecha_de_vencimiento"              => "",
				"moneda"                            => "1", // 1 para soles
				"tipo_de_cambio"                    => "",
				"porcentaje_de_igv"                 => $local_info['igv'],
				"descuento_global"                  =>  $descuento ,
				"total_descuento"                   =>  $descuento  ,
				"total_anticipo"                    => "",
				"total_gravada"                     => $total_gravada,
				"total_inafecta"                    => $total_inafecta,
				"total_exonerada"                   => $total_exonerada,
				"total_igv"                         => $total_igv,
				"total_gratuita"                    => "",
				"total_otros_cargos"                => "",
				"total_impuestos_bolsas"            =>  $total_impuesto_bolsas,
				"total"                             =>$total_comprobante ,
				"percepcion_tipo"                   => "",
				"percepcion_base_imponible"         => "",
				"total_percepcion"                  => "",
				"total_incluido_percepcion"         => "",
				"detraccion"                        => "false",
				"observaciones"                     => $observaciones,
				"documento_que_se_modifica_tipo"    => "",
				"documento_que_se_modifica_serie"   => "",
				"documento_que_se_modifica_numero"  => "",
				"tipo_de_nota_de_credito"           => "",
				"tipo_de_nota_de_debito"            => "",
				"enviar_automaticamente_a_la_sunat" => "true",
				"enviar_automaticamente_al_cliente" => "false",
				"codigo_unico"                      => "",
				"condiciones_de_pago"               => "",
				"medio_de_pago"                     => $medio_pago,
				"placa_vehiculo"                    => "",
				"orden_compra_servicio"             => "",
				"tabla_personalizada_codigo"        => "",
				"formato_de_pdf"                    => "",
				"items" => $items_facturacion
				
			);
				
			$data_json = json_encode($data);


					//Invocamos el servicio de NUBEFACT
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ruta);
			curl_setopt(
				$ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Token token="'.$token.'"',
				'Content-Type: application/json',
				)
			);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta  = curl_exec($ch);
			curl_close($ch);



		$file ="./jose5.txt";
			file_put_contents($file,  $respuesta);

		$file ="./jose6.txt";
			file_put_contents($file,  $data_json);


			if (strpos($respuesta, 'errors') !== false) {
				$error = json_decode($respuesta, true);
				if($error["codigo"] == 23 OR $error["codigo"] == 21)
				{
					//file_put_contents("2rechazo.txt",$respuesta);
					$estado_comprobante = "RESPUESTA PROCESADA";
					$envio_comprobante = 'ok';
					$rechazado = True;
				}else{
					$estado_comprobante = "RESPUESTA PROCESADA";
					$envio_comprobante = 'ok';
					//$respuesta = $respuesta;
					$rechazado = False;
				}
			
			} else{
				$estado_comprobante = "RESPUESTA PROCESADA";
				$envio_comprobante = 'ok';
				$rechazado = False;
			}

			$stmt = $conn->prepare(" UPDATE comprobante SET respuesta_nubefact = :respuesta_nubefact , estado_comprobante = :estado_comprobante, tipo_operacion = :tipo_operacion WHERE id = :id_comprobante; ");
			$stmt->bindParam(":id_comprobante", $id_comprobante_generado , PDO::PARAM_INT);
			$stmt->bindParam(":respuesta_nubefact",   $respuesta  , PDO::PARAM_STR);
			$stmt->bindParam(":estado_comprobante",   $estado_comprobante  , PDO::PARAM_STR);
 			$stmt->bindParam(":tipo_operacion",   $tipo_operacion  , PDO::PARAM_STR);
			$stmt->execute();
 

		//FACTURACION ELECTRONICA.

		} 







		//UNA VEZ QUE INSERTAMOS LA COMPRA ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES

		$conn->commit();
			if($rechazado)
			{
				try {

					$select1 = $conn -> prepare("SELECT * from comprobante where id = :idComprobante;");
					$select1 -> bindParam(":idComprobante",$id_comprobante_generado,PDO::PARAM_STR);
					$select1 -> execute();
					$comprobante = $select1->fetchAll(PDO::FETCH_ASSOC);
					$comprobante = $comprobante[0];
					$mail = new PHPMailer(true);
				
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
					//Server settings
					//mail->SMTPDebug = SMTP::DEBUG_SERVER;                  // Enable verbose debug output
					$mail->isSMTP();                                        // Send using SMTP
					$mail->Host       = 'abcmovil.pe';                      // Set the SMTP server to send through
					$mail->SMTPAuth   = true;                               // Enable SMTP authentication
					$mail->Username   = 'notificacion@abcmovil.pe';         // SMTP username
					$mail->Password   = 'b+$44df}51&,';                     // SMTP password
					//$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
					$mail->Port       = 587;                                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
					$titulo = $local_info["identificador_local"]." | ".$comprobante['serie']."-".$comprobante['correlativo'];
					//Recipients
					$mail->setFrom('notificacion@abcmovil.pe', 'Notificaciones');
					//$mail->addAddress('jemarroquin@pucp.edu.pe');               // Name is optional
					//$mail->addAddress('jose.marroquin@abcmovil.pe');               // Name is optional
					$mail->ClearAddresses();
					$select_correos = $conn->prepare("SELECT * from correos where tipo = :tipo");
					$select_correos->bindValue(":tipo", 1 , PDO::PARAM_INT);
					$select_correos->execute();
					$correos = $select_correos->fetchAll(PDO::FETCH_ASSOC);
					if(!$correos){
						foreach ($correos as $correo)
						{
							$mail->addAddress($correo['correo']);
						}
					}else{
						$mail->addAddress('h.loreto@abcmovil.pe',"Hiram");

					}

					$file1 ="./comprobantes_".$comprobante['serie']."-".$comprobante['correlativo'].".txt";
					file_put_contents($file1,  $data_json);
					$file2 ="./Mensaje_".$comprobante['serie']."-".$comprobante['correlativo'].".txt";
					file_put_contents($file2,  $respuesta);

					// Attachments

					$mail->clearAttachments();
					$mail->addAttachment($file1);
					$mail->addAttachment($file2);
					/*$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

					// Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = $titulo;
					$body  = "ERROR COMPROBANTE ".$comprobante['serie']."-".$comprobante['correlativo']."<br>";
					$body .= "DETALLE DE ERROR: ".$error["errors"]."<br>";
					$body .= "URL: ".$ruta."<br>";
					$body .= "TOKEN: ".$token."<br>";
					$mail->Body    = $body;
					//$mail->AltBody = 'Prueba<br>';
					//file_put_contents("3rechazo.txt",json_encode($mail));

					$mail->send();
					$correo = 'Correo Enviado';
				} catch (Exception $e) {
					$correo = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				}
			}

			$retorno = array("respuesta"=>"ok", "venta"=>$id_ventas_generado, "comprobante"=>$id_comprobante_generado, "cotizacion"=>$id_cotizacion , "correo" => $correo );
 			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
 			return $retorno;


		$stmt->close();
		$stmt = null;

 

		}
		catch(Exception $e) {
		    
		    $conn->rollBack();


		$file ="./errorGuardarNuevaVenta.txt";
   		file_put_contents($file,   $e->getMessage());




		    return ($e->getMessage());
		}

	}



	/*=============================================
	MOSTRAR DETALLE VENTAS
	=============================================*/

	static public function mdlVentasRangoFecha($datos ){
		$stmt = Conexion::conectar()->prepare("SELECT
				* 
			FROM
				ventas 
			WHERE fecha_venta BETWEEN CAST(:fecha_inicio AS DATE)
				AND CAST(:fecha_fin AS DATE)

		");

		$stmt -> bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	/*=============================================
	MOSTRAR DETALLE VENTAS
	=============================================*/

	static public function mdlVentasDetalleRangoFecha($datos ){
		$stmt = Conexion::conectar()->prepare("SELECT
				ventas_detalle.* 
			FROM
				ventas 
				INNER JOIN ventas_detalle ON ventas.id = ventas_detalle.id_ventas
			WHERE ventas.fecha_venta BETWEEN CAST(:fecha_inicio AS DATE)
				AND CAST(:fecha_fin AS DATE)

		");

		$stmt -> bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	}

	/*=============================================
	MOSTRAR DETALLE VENTAS
	=============================================*/

	static public function mdlProductosDetalleRangoFecha($datos ){
		$stmt = Conexion::conectar()->prepare("SELECT
		categoria.descripcion AS 'Nombre_Categoria',
		producto.descripcion AS 'Nombre_Producto',
		UPPER( SUBSTRING_INDEX( producto.unidad_medida_sunat, '-',- 1 ) ) AS 'Unidad_Medida',
		COALESCE ( ventas_detalle.precio_venta_producto, 0 ) AS 'Precio_Venta',
		COALESCE ( SUM( ventas_detalle.cantidad_producto ), 0 ) AS 'Total_Venta' 
	FROM
		producto
		INNER JOIN categoria ON producto.id_categoria = categoria.id
		LEFT JOIN ventas_detalle ON producto.id = ventas_detalle.id_producto 
		AND ventas_detalle.estado = 1 
		INNER JOIN ventas ON ventas.id = ventas_detalle.id_ventas
	WHERE
		ventas.fecha_venta BETWEEN :fecha_inicio AND :fecha_fin
	GROUP BY
		categoria.descripcion,
		producto.descripcion,
		ventas_detalle.precio_venta_producto,
		ventas_detalle.cantidad_producto 
	ORDER BY 5 DESC,
		1,2,3,4
		");

		$stmt -> bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
	MOSTRAR DETALLE VENTAS
	=============================================*/

	static public function mdlGraficoVentasRangoFecha($datos ){
		$stmt = Conexion::conectar()->prepare("SELECT * from (
			SELECT 'ÚLTIMOS 31 DÍAS' AS 'FILTRO', 'ABCVentas' AS 'PERIODO' , cast( ventas.fecha_venta as date) AS 'FECHA' , count(1) AS  'CANTIDAD' , sum(total ) AS  'VOLUMEN'
			FROM ventas 
			WHERE ventas.fecha_venta BETWEEN :fecha_inicio1 AND :fecha_fin1
			GROUP BY  'ÚLTIMOS 31 DÍAS' , YEAR(ventas.fecha_venta) , cast( ventas.fecha_venta as date)  
			ORDER BY 3 DESC
			LIMIT 31 ) as X
		/*	UNION
			SELECT * FROM (
			SELECT 'ÚLTIMAS 2 SEMANAS' , CONCAT('SEMANA ',  WEEK(ventas.fecha_venta) ), cast( ventas.fecha_venta as date) , count(1), sum(total )
			FROM ventas 
			WHERE ventas.fecha_venta BETWEEN :fecha_inicio2 AND :fecha_fin2
			GROUP BY  'ÚLTIMOS 30 DÍAS' ,  CONCAT('SEMANA ',  WEEK(ventas.fecha_venta) ), cast( ventas.fecha_venta as date) 
			ORDER BY 3 DESC
			LIMIT 14) AS Y 
			UNION
			SELECT * FROM ( 
			SELECT 'MENSUALIZADO' ,   YEAR(ventas.fecha_venta)  , DATE_FORMAT(ventas.fecha_venta,'%Y-%b')     , count(1), sum(total )
			FROM ventas 
			WHERE ventas.fecha_venta BETWEEN :fecha_inicio3 AND :fecha_fin3
			GROUP BY  'ÚLTIMOS 30 DÍAS' ,  YEAR(ventas.fecha_venta)  , DATE_FORMAT(ventas.fecha_venta,'%Y-%b') 
			ORDER BY 3 DESC
			LIMIT 13 ) AS Z*/
			
		");

		$stmt -> bindParam(":fecha_inicio1", $datos['fecha_inicio'], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_fin1", $datos['fecha_fin'], PDO::PARAM_STR);
	/*	$stmt -> bindParam(":fecha_inicio2", $datos['fecha_inicio'], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_fin2", $datos['fecha_fin'], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_inicio3", $datos['fecha_inicio'], PDO::PARAM_STR);
		$stmt -> bindParam(":fecha_fin3", $datos['fecha_fin'], PDO::PARAM_STR);
*/
		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	}
}

