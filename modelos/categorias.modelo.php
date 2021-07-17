<?php

require_once "conexion.php";
/**
  * 
  */
 class ModeloCategorias
{
	 	static public function mdlIngresarCategoria($tabla, $datos){
 		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(descripcion, id_local) VALUES (:descripcion,:id_local )");

 		$stmt -> bindParam(":descripcion" , $datos["descripcion"], PDO::PARAM_STR);
 		$stmt -> bindParam(":id_local" , $datos["id_local"], PDO::PARAM_STR);


 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}

 

/*=============================================
	MOSTRAR CATEGORIAS 
=============================================*/
 	static public function mdlMostrarCategorias($tabla, $item, $valor){

if ($item != null){
 		
 		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
 		$stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);

 		$stmt -> execute();

 		return $stmt ->fetch();

 		$stmt-> close();
 		$stmt= null;


} else {

 		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
 		 

 		$stmt -> execute();

 		return $stmt ->fetchAll();

 		$stmt-> close();
 		$stmt= null;



}



 	}

/*=============================================
	MOSTRAR CATEGORIAS 
=============================================*/
static public function mdlReporteCategorias($fecha_inicio, $fecha_fin){
	
	$stmt = Conexion::conectar()->prepare("SELECT
	categoria.id,
	categoria.descripcion,
	(
	SELECT
		SUM( ventas_detalle.cantidad_producto ) 
	FROM
	producto
	LEFT JOIN ventas_detalle ON ventas_detalle.id_producto = producto.id
	LEFT JOIN ventas ON ventas.id = ventas_detalle.id_ventas
	LEFT JOIN cliente ON cliente.id_documento = ventas.id_documento_cliente
	WHERE
		producto.id_categoria = categoria.id  AND ventas_detalle.estado <> '2'
	) cantidad_vendidos,
	(
	SELECT
		SUM( ventas_detalle.comprobante_sub_total ) 
	FROM
	producto
	LEFT JOIN ventas_detalle ON ventas_detalle.id_producto = producto.id
	LEFT JOIN ventas ON ventas.id = ventas_detalle.id_ventas
	LEFT JOIN cliente ON cliente.id_documento = ventas.id_documento_cliente
	WHERE
		producto.id_categoria = categoria.id  AND ventas_detalle.estado <> '2'
	) monto_vendidos 
FROM
	categoria");	

	$stmt -> execute();

	return $stmt ->fetchAll( PDO::FETCH_ASSOC);
}

/*=============================================
	MOSTRAR CATEGORIAS 
=============================================*/
static public function mdlProductoCategorias($obj){
	
	$stmt = Conexion::conectar()->prepare("SELECT
	ventas_detalle.id,
	ventas_detalle.id_ventas,
	ventas.fecha_venta,
	COALESCE(cliente.nombre_comercial,'-') nombre_comercial,
	producto.id_categoria,
	ventas_detalle.id_producto,
	producto.descripcion,
	producto.unidad_medida_sunat,
	ventas_detalle.precio_venta_producto,
	ventas_detalle.precio_venta_original,
	(ventas_detalle.cantidad_producto) cantidad_producto,
	(ventas_detalle.comprobante_sub_total)	monto_producto
FROM
	producto
	LEFT JOIN ventas_detalle ON ventas_detalle.id_producto = producto.id
	LEFT JOIN ventas ON ventas.id = ventas_detalle.id_ventas
	LEFT JOIN cliente ON cliente.id_documento = ventas.id_documento_cliente
WHERE
	producto.id_categoria = :id_categoria AND ventas_detalle.estado <> '2'
GROUP BY 
	ventas_detalle.id 
ORDER BY
	producto.descripcion");	
	
	$stmt -> bindParam(":id_categoria" , $obj->idCategoria, PDO::PARAM_STR);

	$stmt -> execute();

	return $stmt ->fetchAll( PDO::FETCH_ASSOC );
}

/*=============================================
			EDICION DE CATEGORIAS
=============================================*/


	 static public function mdlEditarCategoria($tabla, $datos){

 		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET descripcion = :categoria WHERE id =:id");

		$stmt -> bindParam(":categoria" , $datos["descripcion"], PDO::PARAM_STR);
 		$stmt -> bindParam(":id" , $datos["id"], PDO::PARAM_STR);


 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}




 
/*=============================================
			ELIMINACION DE CATEGORIAS
=============================================*/
 	static public function mdlEliminarCategorias( $item, $valor){

		$tabla = "categoria";

 		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla  WHERE id = :$valor");

		
 		$stmt -> bindParam(":".$valor , $valor, PDO::PARAM_STR);
 		
 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}



}