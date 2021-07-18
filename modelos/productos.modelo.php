<?php

require_once "conexion.php";
/**
  * 
  */
 class ModeloProductos
 {
 	/*=============================================
	MOSTRAR USUARIOS -> Usado en el login.
=============================================*/
 	static public function MdlMostrarProductos($tabla, $item, $valor){



		if ($item != null){
 		
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND estado=1");
			$stmt -> bindParam(":".$item , $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt ->fetch();

			$stmt-> close();
			$stmt= null;


		} else {

 		$stmt = Conexion::conectar()->prepare("SELECT  DISTINCT ON (p.codproducto) p.codproducto  as id_prod, ap.valorunitario as stock
		 , ta.nomtipoafectacion as tipo_afectacion_sunat,p.*
				  FROM productos p 
				  LEFT JOIN almacenproducto ap ON p.codproducto = ap.codproducto
				  INNER JOIN tipoafectacion ta ON p.codtipoafectacion = ta.codtipoafectacion;");
			/* $stmt = Conexion::conectar()->prepare("SELECT
			 producto.*,
			 '-' AS categoriaNombre,
			 '1' idCategoria,
			 'NO RELACION'  AS stock,
			 'NO RELACION'  AS cantidad_alerta 
		 FROM
			 producto
		 WHERE
			 producto.estado = 1");*/
 		 

 		$stmt -> execute();

 		return $stmt ->fetchAll();

 		$stmt-> close();
 		$stmt= null;



}



 	}
 	

 
	/*=================================================================
					PRODUCTOS UNIDAD DE MEDIDA SALIDA
	=================================================================*/

 


	static public function mdlCrearUnidadMedidaEnlace( $datos  ){

		$stmt = Conexion::conectar()->prepare("INSERT INTO unidad_medida_salida_x_producto(id_inventario_unidad_medida_salida, id_producto,  cantidad_inventario   ) VALUES (  :id_inventario_unidad_medida_salida, :id_producto,  :cantidad_inventario )");


	   $stmt->bindParam(":id_inventario_unidad_medida_salida", $datos["id_inventario_unidad_medida_salida"], PDO::PARAM_INT);
	   $stmt->bindParam(":cantidad_inventario", $datos["cantidad_inventario"], PDO::PARAM_INT);
	   $stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);




		if( $stmt -> execute()) 
			return "ok";
		else 
			return "error";

		$stmt-> close();
		$stmt= null;
	}
 

	/*=============================================
				CHEQUEAR CODIGO BARRAS
	=============================================*/	

 	static public function mdlChequearCodigo( $datos ){

		$stmt = Conexion::conectar()->prepare("SELECT CONCAT('77',FLOOR(RAND() * 99999999999)) AS random_num
		FROM producto 
		WHERE 'random_num' NOT IN (SELECT codigo_barras FROM producto )
		LIMIT 1
		");

		$stmt -> execute();

		return $stmt -> fetchAll( PDO::FETCH_ASSOC );

 	}



/*=============================================
	Mostrar UNIDADES DE MEDIDA
=============================================*/
 static public function mdlMostrarUnidadSalidaProducto(  $id_producto){

			$stmt = Conexion::conectar()->prepare("



	SELECT inventario_x_unidad_medida_salida.id , inventario_x_unidad_medida_salida.unidad_medida_salida  ,unidad_medida_salida_x_producto.cantidad_inventario
	FROM unidad_medida_salida_x_producto
	INNER JOIN producto ON producto.id = unidad_medida_salida_x_producto.id_producto 
	INNER JOIN inventario_x_unidad_medida_salida ON unidad_medida_salida_x_producto.id_inventario_unidad_medida_salida  = inventario_x_unidad_medida_salida.id
	WHERE producto.id = :id_producto


													");


 		$stmt -> bindParam(":id_producto" , $id_producto, PDO::PARAM_INT);


			$stmt -> execute();

			return $stmt -> fetchAll();

 
		$stmt-> close();

		$stmt = null;

	}





 	/*=============================================
	MOSTRAR PRODUCTOS GRAFICO DE VENTAS
=============================================*/
 	static public function MdlMostrarProductosGraficoVentas (){

 		$stmt = Conexion::conectar()->prepare("SELECT id , descripcion  as descrip , SUM(ventas) AS ventaTotal , imagen , ROUND(SUM(ventas)*100/ x.total_ventas, 2)    as porcentaje FROM producto , (SELECT SUM(ventas) as total_ventas FROM producto ) as x GROUP BY id , descripcion limit 10");

 		$stmt -> execute();

 		return $stmt -> fetchAll();

 		$stmt-> close();

 		$stmt= null;

 	}
 	



 	static public function mdlIngresarProductos( $datos ,  $crearParejaInventario ,$costoIdeal , 	$cantidad_alerta , $fecha  ){

		try 
		{
			$conn = Conexion::conectar();
			
			$conn->beginTransaction();

			$stmt = $conn->prepare("INSERT INTO 
			productos( codproducto, nomproducto, descripcion, fecingreso, lote, nroserie, precio ,codunidad ,codmarca, codmoneda, codtipoafectacion) 
			VALUES ( :codproducto, :nomproducto, :descripcion, NOW() , '', '', :precio_venta , :unidad_medida_sunat , '01', :moneda, :tipo_afectacion_sunat);");

			$ultimo_id_producto =0;
			$moneda = "PEN";
			$stmt -> bindParam(":codproducto" , $datos["codproducto"], PDO::PARAM_STR);
			$stmt -> bindParam(":nomproducto" , $datos["descripcion"], PDO::PARAM_STR);
			$stmt -> bindParam(":descripcion" , $datos["descripcion"], PDO::PARAM_STR);
			$stmt -> bindParam(":precio_venta" , $datos["precio_venta"], PDO::PARAM_STR);
			$stmt -> bindParam(":tipo_afectacion_sunat" , $datos["tipo_afectacion_sunat"], PDO::PARAM_STR);
			$stmt -> bindParam(":unidad_medida_sunat" , $datos["unidad_medida_sunat"], PDO::PARAM_STR);
			$stmt -> bindParam(":moneda" , $moneda, PDO::PARAM_STR);	

			$stmt->execute();
			
			$insert = $conn->prepare("INSERT INTO 
			almacenproducto ( valorunitario, codalmacen,codproducto, codmoneda) 
			VALUES ( '1', '1', :codproducto, :codmoneda);");

			$moneda = "PEN";
			$insert -> bindParam(":codproducto" , $datos["codproducto"], PDO::PARAM_STR);
			$insert -> bindParam(":codmoneda" , $moneda, PDO::PARAM_STR);

			$insert->execute();
			//UNA VEZ QUE INSERTAMOS EL PRODUCTO ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES

			$conn->commit();
			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
			return array("error"=> 0, "data" =>$datos["codproducto"]);


		}
		catch(Exception $e)
		{
			$conn->rollBack();
			return array("error"=> 1, "data" =>$e->getMessage());
		}


 
 	}
 

 	static public function mdlEditarProductos($tabla, $datos){


	try {
 		$conn = Conexion::conectar();
		
		$conn->beginTransaction();
 

 		$stmt = $conn->prepare("UPDATE productos SET nomproducto = :nomproducto ,descripcion = :descripcion  , codunidad = :unidad_medida_sunat , precio = :precio_venta , codtipoafectacion=:tipo_afectacion_sunat  WHERE codproducto = :id;");
 

 		$stmt -> bindParam(":id" , $datos["codproducto"], PDO::PARAM_STR);
		$stmt -> bindParam(":nomproducto" , $datos["descripcion"], PDO::PARAM_STR);
		$stmt -> bindParam(":descripcion" , $datos["descripcion"], PDO::PARAM_STR);
 		$stmt -> bindParam(":unidad_medida_sunat" , $datos["unidad_medida_sunat"], PDO::PARAM_STR);
 		$stmt -> bindParam(":tipo_afectacion_sunat" , $datos["tipo_afectacion_sunat"], PDO::PARAM_STR);


 		$stmt -> bindParam(":precio_venta" , $datos["precio_venta"], PDO::PARAM_STR);
 		
 		
		$stmt->execute();
	


 

		//UNA VEZ QUE INSERTAMOS EL PRODUCTO ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES

		$conn->commit();
 			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
 			return "ok";


		$stmt->close();
		$stmt = null;

 

}
catch(Exception $e) {
    
    $conn->rollBack();
    return ($e->getMessage());
}




 	}



 	/*=============================================
			EDICION DE CATEGORIAS
=============================================*/


	 static public function mdlActualizarFotoProducto($tabla, $datos){

 		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET imagen = :imagen WHERE id =:id_producto");

		$stmt -> bindParam(":imagen" , $datos["imagen"], PDO::PARAM_STR);
 		$stmt -> bindParam(":id_producto" , $datos["id_producto"], PDO::PARAM_STR);


 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}



/*=============================================
			ELIMINACION DE PRODUCTOS
=============================================*/
 	static public function mdlEliminarProducto( $tabla, $id){



 		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE codproducto = :$id");

		
 		$stmt -> bindParam(":".$id , $id, PDO::PARAM_STR);
 		
 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}


/*=============================================
			ELIMINACION DE PRODUCTOS UNIDAD DE MEDIDA PRODUCTO
=============================================*/
 	static public function mdlEliminarUnidadMedidaProducto( $idUnidadMedidaProducto){

 		$stmt = Conexion::conectar()->prepare("DELETE FROM  unidad_medida_salida_x_producto WHERE id = :id");
		
 		$stmt -> bindParam(":id" , $idUnidadMedidaProducto, PDO::PARAM_INT);
 		
 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}



	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
	/*=============================================
		REPORTE PRODUCTOS
	=============================================*/


	static public function mdlReporteProductos(){

		$stmt = Conexion::conectar()->prepare("SELECT CAST(v.codventa as INT) codventa, v.fecemision, ta.nomtipoafectacion, p.*
		FROM ventas as v 
		INNER JOIN detalle AS d ON d.codventa = v.codventa
		LEFT JOIN productos as p ON d.codproducto = p.codproducto
		LEFT JOIN tipoafectacion as ta ON ta.codtipoafectacion = d.codtipoafectacion;");
	
		$stmt -> execute();

		return 	$stmt -> fetchAll( PDO::FETCH_ASSOC );
		$stmt -> close();

		$stmt = null;

	}

 
	static public function mdlImportarProductos( $productos , $fecha )
	{
		try 
		{
			$conn = Conexion::conectar();
			
			$conn->beginTransaction();
			$TXT = "";
			foreach ($productos as $producto)
			{
				$stmt_cat = $conn->prepare(" SELECT id FROM categoria WHERE descripcion = :descripcion;");
				$stmt_cat -> bindParam(":descripcion" , $producto["CATEGORIA_NOMBRE"], PDO::PARAM_STR);
				
				$stmt_cat -> execute();
				$categoria = $stmt_cat -> fetch( PDO::FETCH_ASSOC );
				
				/*$file ="./producto.txt";
				file_put_contents($file,  json_encode($categoria) );*/
				
				/*
				$stmt_qprod = $conn->prepare(" SELECT id FROM producto WHERE codigo_barras = :codigo_barras;");
				$stmt_qprod -> bindParam(":codigo_barras" , $producto["CODIGO_BARRAS"], PDO::PARAM_STR);
				
				$stmt_qprod -> execute();
				$existe_producto = $stmt_qprod -> fetch( PDO::FETCH_ASSOC );*/
				
				$SQL ="";
				
					
				$SQL = "INSERT INTO producto 
				( id_categoria, codigo_barras, descripcion, imagen, precio_venta, codigo_producto_sunat, estado, tipo_afectacion_sunat, unidad_medida_sunat ) 
				VALUES 
				( :id_categoria, :codigo_barras2, :descripcion, :imagen, :precio_venta, :codigo_producto_sunat, '1', :tipo_afectacion_sunat,	:unidad_medida_sunat );";
				/*if(!$existe_producto)
				{
					//$TXT.="prd \n";
					
					$SQL = "INSERT INTO producto 
					( id_categoria, codigo_barras, descripcion, imagen, precio_venta, codigo_producto_sunat, estado, tipo_afectacion_sunat, unidad_medida_sunat ) 
					VALUES 
					( :id_categoria, :codigo_barras2, :descripcion, :imagen, :precio_venta, :codigo_producto_sunat, '1', :tipo_afectacion_sunat,	:unidad_medida_sunat );";
				}
				else
				{
					//$TXT.="prd ".$existe_producto["id"]."\n";
					$SQL = "UPDATE producto 
					SET id_categoria= :id_categoria, 
					descripcion=:descripcion, 
					precio_venta=	:precio_venta, 					
					codigo_producto_sunat=:codigo_producto_sunat, 
					tipo_afectacion_sunat=:tipo_afectacion_sunat, 
					unidad_medida_sunat=:unidad_medida_sunat
					where codigo_barras =:codigo_barras2";
				}*/
				//$TXT.=$SQL."\n";
				
				$stmt_prd = $conn->prepare(" $SQL ");
				
				if(!$categoria)
				{
					
					$stmt_cat2 = $conn->prepare("INSERT INTO categoria ( descripcion, id_local ) VALUES ( :descripcion , :id_local );");
					
					
					$stmt_cat2 -> bindParam(":descripcion" ,  $producto["CATEGORIA_NOMBRE"], PDO::PARAM_STR);
					$stmt_cat2 -> bindParam(":id_local" ,  $producto["LOCAL"], PDO::PARAM_STR);
					
					//$TXT.= $producto["CATEGORIA_NOMBRE"]."\n";
					//$TXT.= $producto["LOCAL"]."\n";
					$stmt_cat2->execute();
					$id_categoria = $conn->lastInsertId();
					$stmt_prd -> bindParam(":id_categoria" , $id_categoria, PDO::PARAM_INT);
				}
				else
				{
					//$TXT.="cat ". $categoria["id"]." \n";
					$stmt_prd -> bindParam(":id_categoria" , $categoria["id"], PDO::PARAM_INT);
				}

				$stmt_prd -> bindParam(":codigo_barras2" , $producto["CODIGO_BARRAS"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":descripcion" , $producto["NOMBRE_PRODUCTO"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":codigo_producto_sunat" , $producto["CODIGO_SUNAT"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":unidad_medida_sunat" , $producto["UNIDAD_DE_MEDIDA"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":tipo_afectacion_sunat" , $producto["TIPO_AFECTACION"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":precio_venta" , $producto["PRECIO_VENTA"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":imagen" , $producto["IMAGEN"], PDO::PARAM_STR); 	
				/*
				if(!$existe_producto)
				{
					$stmt_prd -> bindParam(":imagen" , $producto["IMAGEN"], PDO::PARAM_STR); 	
				}*/

				$stmt_prd -> execute();
				
				if( $producto["CREAR_INVENTARIO"] == "SI")
				{
					//PRIMERO CREAMOS EL PRODUCTO A INVENTARIAR
					
					$ultimo_id_producto = $conn->lastInsertId();
					$stmt_inv = $conn->prepare("INSERT INTO inventario(nombre,medida_ingreso ,cantidad_alerta, costo_ideal, codigo_barras,fecha, id_almacen) 
					VALUES (:descripcion_inv , :unidad_medida_sunat_inv   , :cantidad_alerta_inv ,:costo_ideal_inv,:codigo_barras_inv, :fecha_inv, :id_almacen )");

					$ultimo_id_inventario =0;

					$medida_reporte_defecto = 'Medida de Entrada';
					//$TXT.="inventario ". $medida_reporte_defecto." \n";

					$stmt_inv -> bindParam(":descripcion_inv" , $producto["NOMBRE_PRODUCTO"], PDO::PARAM_STR);
					$stmt_inv -> bindParam(":unidad_medida_sunat_inv" , $producto["UNIDAD_DE_MEDIDA"], PDO::PARAM_STR);

					$stmt_inv -> bindParam(":cantidad_alerta_inv" , $producto["CANTIDAD_ALERTA"] ,  PDO::PARAM_STR); 	
					$stmt_inv -> bindParam(":costo_ideal_inv" , $producto["COSTO_REFERENCIAL"] ,  PDO::PARAM_STR); 	
					$stmt_inv -> bindParam(":codigo_barras_inv" , $producto["CODIGO_BARRAS"], PDO::PARAM_STR);
					$stmt_inv -> bindParam(":fecha_inv" , $fecha, PDO::PARAM_STR);
					$stmt_inv -> bindParam(":id_almacen" , $producto["ALMACCEN"], PDO::PARAM_STR);						

					$stmt_inv->execute();
					$ultimo_id_inventario = $conn->lastInsertId();
					
					//$TXT.="inventario ".$ultimo_id_inventario."\n";

					//AHORA CREAMOS LA UNIDAD DE MEDIDA DE SALIDA
					$stmt_um = $conn->prepare("INSERT INTO inventario_x_unidad_medida_salida(unidad_medida_salida,id_inventario,equivalencia ) 
					VALUES (:unidad_medida_salida,:id_inventario, :equivalencia )");

					$equivalencia = 1;
					$ultimo_id_unidad_medida_salida =0;

					$stmt_um -> bindParam(":unidad_medida_salida" ,  $producto["UNIDAD_DE_MEDIDA"], PDO::PARAM_STR);
					$stmt_um -> bindParam(":id_inventario" ,  $ultimo_id_inventario, PDO::PARAM_STR);
					$stmt_um -> bindParam(":equivalencia" ,  $equivalencia, PDO::PARAM_STR);

					$stmt_um->execute();
					$ultimo_id_unidad_medida_salida = $conn->lastInsertId();

					//$TXT.="inventario_x_unidad_medida_salida ".$ultimo_id_unidad_medida_salida."\n";
					//AHORA CREAMOS LA RELACION CON EL PRODUCTO
					$stmt_um_prd = $conn->prepare("INSERT INTO unidad_medida_salida_x_producto(cantidad_inventario,id_inventario_unidad_medida_salida,id_producto ) 
					VALUES (:cantidad_inventario,:id_inventario_unidad_medida_salida, :id_producto )");

					$stmt_um_prd -> bindParam(":cantidad_inventario" ,  $equivalencia  , PDO::PARAM_STR);
					$stmt_um_prd -> bindParam(":id_inventario_unidad_medida_salida" ,  $ultimo_id_unidad_medida_salida, PDO::PARAM_STR);
					$stmt_um_prd -> bindParam(":id_producto" ,  $ultimo_id_producto, PDO::PARAM_STR);

					$TXT.="inventario_x_unidad_medida_salida ".$ultimo_id_producto."\n";

					$stmt_um_prd->execute();
					/*$file ="./producto.txt";
					file_put_contents($file, $TXT );*/

				}

				
			}
			$stmt_cod_bar = $conn->prepare("UPDATE producto SET codigo_barras = '' where codigo_barras is null;");

			$stmt_cod_bar->execute();

			//UNA VEZ QUE INSERTAMOS EL PRODUCTO ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES
			
			$conn->commit();
			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
			return array("data"=>1, "mensaje"=>"Productos importados exitosamente");
			
			$stmt->close();
			$stmt = null;
			
		}
		catch(Exception $e)
		{			
			$conn->rollBack();
			return ($e->getMessage());
		}
	}
	/*=============================================
					GET PRODUCTOS
	=============================================*/


	static public function mdlGetProductos( $id_local ){

		$stmt = Conexion::conectar()->prepare(" SELECT
				P.id,
				P.descripcion,
				P.precio_venta,
				P.orden,
				C.descripcion categoria
			FROM
				producto P
			INNER JOIN 
				categoria C ON C.id = P.id_categoria
			WHERE 
				C.id_local = :id_local
			GROUP BY
				P.id 
			ORDER BY
				P.id ASC");
	
		$stmt -> bindParam(":id_local" ,  $id_local  , PDO::PARAM_STR);
		$stmt -> execute();

		return 	$stmt -> fetchAll( PDO::FETCH_ASSOC );

	}

	static public function mdlGetListProductosApi( $datos ){

		$stmt = Conexion::conectar()->prepare(" SELECT
				P.id,
				P.descripcion,
				P.codigo_barras,
				P.codigo_producto_sunat,
				P.unidad_medida_sunat,
				P.tipo_afectacion_sunat,
				P.precio_venta,
				P.orden,
				C.descripcion categoria
			FROM
				producto P
			INNER JOIN 
				categoria C ON C.id = P.id_categoria
			WHERE 
				C.id_local = :id_local
			GROUP BY
				P.id 
			ORDER BY
				P.id ASC");
	
		$stmt -> bindParam(":id_local" ,  $datos->id_local  , PDO::PARAM_STR);
		$stmt -> execute();

		return 	$stmt -> fetchAll( PDO::FETCH_ASSOC );
		
	}

	static public function mdlImportarProductosApi( $list_productos, $fecha, $unidad_medida, $um_salida, $inventarios )
	{
		try 
		{
			$conn = Conexion::conectar();
			
			$conn->beginTransaction();
			$TXT = "";
			foreach ( $list_productos as $producto )
			{
				$stmt_cat = $conn->prepare(" SELECT id FROM categoria WHERE descripcion = :descripcion;");
				$stmt_cat -> bindParam(":descripcion" , $producto["CATEGORIA_NOMBRE"], PDO::PARAM_STR);
				
				$stmt_cat -> execute();
				$categoria = $stmt_cat -> fetch( PDO::FETCH_ASSOC );
				
				$SQL ="";				
					
				$SQL = "INSERT INTO producto 
				( id, id_categoria, codigo_barras, descripcion, imagen, precio_venta, codigo_producto_sunat, estado, tipo_afectacion_sunat, unidad_medida_sunat ) 
				VALUES 
				( :id, :id_categoria, :codigo_barras2, :descripcion, :imagen, :precio_venta, :codigo_producto_sunat, '1', :tipo_afectacion_sunat,	:unidad_medida_sunat )
				ON DUPLICATE KEY UPDATE
				precio_venta = :precio_venta_update,
				descripcion = :descripcion_update,
				codigo_barras = :codigo_barras_update,
				id_categoria = :id_categoria_update,
				codigo_producto_sunat = :codigo_producto_sunat_update,
				tipo_afectacion_sunat = :tipo_afectacion_sunat_update,
				unidad_medida_sunat = :unidad_medida_sunat_update
				
				;";
				
				$stmt_prd = $conn->prepare(" $SQL ");
				
				if(!$categoria)
				{
					
					$stmt_cat2 = $conn->prepare("INSERT INTO categoria ( descripcion, id_local ) VALUES ( :descripcion , :id_local );");
					
					
					$stmt_cat2 -> bindParam(":descripcion" ,  $producto["CATEGORIA_NOMBRE"], PDO::PARAM_STR);
					$stmt_cat2 -> bindParam(":id_local" ,  $producto["LOCAL"], PDO::PARAM_STR);
					
					$stmt_cat2->execute();
					$id_categoria = $conn->lastInsertId();
					$stmt_prd -> bindParam(":id_categoria" , $id_categoria, PDO::PARAM_INT);
					$stmt_prd -> bindParam(":id_categoria_update" , $id_categoria, PDO::PARAM_INT);
				}
				else
				{
					//$TXT.="cat ". $categoria["id"]." \n";
					$stmt_prd -> bindParam(":id_categoria" , $categoria["id"], PDO::PARAM_INT);
					$stmt_prd -> bindParam(":id_categoria_update" , $categoria["id"], PDO::PARAM_INT);
				}

				$stmt_prd -> bindParam(":id" , $producto["ID"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":codigo_barras2" , $producto["CODIGO_BARRAS"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":descripcion" , $producto["NOMBRE_PRODUCTO"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":codigo_producto_sunat" , $producto["CODIGO_SUNAT"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":unidad_medida_sunat" , $producto["UNIDAD_DE_MEDIDA"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":tipo_afectacion_sunat" , $producto["TIPO_AFECTACION"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":precio_venta" , $producto["PRECIO_VENTA"], PDO::PARAM_STR);

				$stmt_prd -> bindParam(":precio_venta_update" , $producto["PRECIO_VENTA"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":descripcion_update" , $producto["NOMBRE_PRODUCTO"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":codigo_producto_sunat_update" , $producto["CODIGO_SUNAT"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":codigo_barras_update" , $producto["CODIGO_BARRAS"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":tipo_afectacion_sunat_update" , $producto["TIPO_AFECTACION"], PDO::PARAM_STR);
				$stmt_prd -> bindParam(":unidad_medida_sunat_update" , $producto["UNIDAD_DE_MEDIDA"], PDO::PARAM_STR);
				
				$stmt_prd -> bindParam(":imagen" , $producto["IMAGEN"], PDO::PARAM_STR); 	

				$stmt_prd -> execute();
			}
			foreach ( $inventarios as $inventario )
			{
				//PRIMERO CREAMOS EL PRODUCTO A INVENTARIAR
					
				$ultimo_id_producto = $conn->lastInsertId();
				$stmt_inv = $conn->prepare("INSERT INTO inventario(id,nombre,medida_ingreso ,cantidad_alerta, costo_ideal, codigo_barras,fecha, id_almacen) 
				VALUES (:id, :descripcion_inv , :unidad_medida_sunat_inv , :cantidad_alerta_inv ,:costo_ideal_inv, :codigo_barras_inv, :fecha_inv, :id_almacen)
				ON DUPLICATE KEY UPDATE
				nombre = :descripcion_inv_up,
				medida_ingreso = :unidad_medida_sunat_inv_up,
				cantidad_alerta = :cantidad_alerta_inv_up,
				costo_ideal = :costo_ideal_inv_up,
				codigo_barras = :codigo_barras_inv_up,
				fecha = :fecha_inv_up,
				id_almacen = :id_almacen_up
				; ");

				$ultimo_id_inventario =0;

				$medida_reporte_defecto = 'Medida de Entrada';
				$stmt_inv -> bindParam(":id" , $inventario["id"], PDO::PARAM_STR);
				$stmt_inv -> bindParam(":descripcion_inv" , $inventario["nombre"], PDO::PARAM_STR);
				$stmt_inv -> bindParam(":unidad_medida_sunat_inv" , $inventario["medida_ingreso"], PDO::PARAM_STR);

				$stmt_inv -> bindParam(":cantidad_alerta_inv" , $inventario["cantidad_alerta"] ,  PDO::PARAM_STR); 	
				$stmt_inv -> bindParam(":costo_ideal_inv" , $inventario["costo_ideal"] ,  PDO::PARAM_STR); 	
				$stmt_inv -> bindParam(":codigo_barras_inv" , $inventario["codigo_barras"], PDO::PARAM_STR);
				$stmt_inv -> bindParam(":fecha_inv" , $inventario["fecha"], PDO::PARAM_STR);
				$stmt_inv -> bindParam(":id_almacen" , $inventario["id_almacen"], PDO::PARAM_STR);					

				$stmt_inv -> bindParam(":descripcion_inv_up" , $inventario["nombre"], PDO::PARAM_STR);
				$stmt_inv -> bindParam(":unidad_medida_sunat_inv_up" , $inventario["medida_ingreso"], PDO::PARAM_STR);

				$stmt_inv -> bindParam(":cantidad_alerta_inv_up" , $inventario["cantidad_alerta"] ,  PDO::PARAM_STR); 	
				$stmt_inv -> bindParam(":costo_ideal_inv_up" , $inventario["costo_ideal"] ,  PDO::PARAM_STR); 	
				$stmt_inv -> bindParam(":codigo_barras_inv_up" , $inventario["codigo_barras"], PDO::PARAM_STR);
				$stmt_inv -> bindParam(":fecha_inv_up" , $inventario["fecha"], PDO::PARAM_STR);					
				$stmt_inv -> bindParam(":id_almacen_up" , $inventario["id_almacen"], PDO::PARAM_STR);
				$stmt_inv->execute();
			}
			foreach ( $um_salida as $salida )
			{

				//AHORA CREAMOS LA UNIDAD DE MEDIDA DE SALIDA
				$stmt_um = $conn->prepare("INSERT INTO inventario_x_unidad_medida_salida(id,unidad_medida_salida,id_inventario,equivalencia ) 
				VALUES (:id,:unidad_medida_salida,:id_inventario, :equivalencia )
				ON DUPLICATE KEY UPDATE
				unidad_medida_salida = :unidad_medida_salida_up,
				id_inventario = :id_inventario_up,
				equivalencia = :equivalencia_up ;");

				$equivalencia = 1;
				$ultimo_id_unidad_medida_salida =0;

				$stmt_um -> bindParam(":id" ,  $salida["id"], PDO::PARAM_STR);
				$stmt_um -> bindParam(":unidad_medida_salida" ,  $salida["unidad_medida_salida"], PDO::PARAM_STR);
				$stmt_um -> bindParam(":id_inventario" ,  $salida["id_inventario"], PDO::PARAM_STR);
				$stmt_um -> bindParam(":equivalencia" ,  $salida["equivalencia"], PDO::PARAM_STR);

				$stmt_um -> bindParam(":unidad_medida_salida_up" ,  $salida["unidad_medida_salida"], PDO::PARAM_STR);
				$stmt_um -> bindParam(":id_inventario_up" ,  $salida["id_inventario"], PDO::PARAM_STR);
				$stmt_um -> bindParam(":equivalencia_up" ,  $salida["equivalencia"], PDO::PARAM_STR);

				$stmt_um->execute();
			}
			
			foreach ( $unidad_medida as $um )
			{
				//AHORA CREAMOS LA RELACION CON EL PRODUCTO
				$stmt_um_prd = $conn->prepare("INSERT INTO unidad_medida_salida_x_producto(id , cantidad_inventario , id_inventario_unidad_medida_salida , id_producto ) 
				VALUES (:id,:cantidad_inventario,:id_inventario_unidad_medida_salida, :id_producto )
				ON DUPLICATE KEY UPDATE
				cantidad_inventario = :cantidad_inventario_up,
				id_inventario_unidad_medida_salida = :id_inventario_unidad_medida_salida_up,
				id_producto = :id_producto_up ;");

				$stmt_um_prd -> bindParam(":id" ,  $um['id']  , PDO::PARAM_STR);
				$stmt_um_prd -> bindParam(":cantidad_inventario" ,  $um['cantidad_inventario']  , PDO::PARAM_STR);
				$stmt_um_prd -> bindParam(":id_inventario_unidad_medida_salida" ,  $um['id_inventario_unidad_medida_salida'], PDO::PARAM_STR);
				$stmt_um_prd -> bindParam(":id_producto" ,  $um['id_producto'], PDO::PARAM_STR);


				$stmt_um_prd -> bindParam(":cantidad_inventario_up" , $um['cantidad_inventario']  , PDO::PARAM_STR);
				$stmt_um_prd -> bindParam(":id_inventario_unidad_medida_salida_up" , $um['id_inventario_unidad_medida_salida'], PDO::PARAM_STR);
				$stmt_um_prd -> bindParam(":id_producto_up" , $um['id_producto'], PDO::PARAM_STR);

				$stmt_um_prd->execute();

			}

				
			
			$stmt_cod_bar = $conn->prepare("UPDATE producto SET codigo_barras = '' where codigo_barras is null;");

			$stmt_cod_bar->execute();

			//UNA VEZ QUE INSERTAMOS EL PRODUCTO ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES
			
			$conn->commit();
			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
			return array("data"=>1, "mensaje"=>"Productos importados exitosamente");
			
			$stmt->close();
			$stmt = null;
			
		}
		catch(Exception $e)
		{			
			$conn->rollBack();
			file_put_contents("1mdlImportarProductosApi.txt", $e->getMessage());
			return array("data"=>0, "mensaje"=>"Productos importados exitosamente");
		}
	}

}