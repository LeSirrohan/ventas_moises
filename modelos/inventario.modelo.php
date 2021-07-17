<?php

require_once "conexion.php";
/**
  * 
  */
 class ModeloInventario{
/*=============================================
	Mostrar Otros Ingresos Egresos
=============================================*/
 static public function mdlMostrarInventario( ){

			$stmt = Conexion::conectar()->prepare("


 
SELECT inventario.id, codigo_barras, inventario.nombre, inventario.medida_ingreso,  COUNT( inventario_x_unidad_medida_salida.id_inventario)   as medida_salida , inventario.actual_cantidad, inventario.cantidad_alerta, inventario.actual_costo_valorizado, inventario.costo_ideal
FROM inventario 
LEFT JOIN inventario_x_unidad_medida_salida ON inventario_x_unidad_medida_salida.id_inventario = inventario.id
WHERE estado = 1
GROUP BY codigo_barras, inventario.nombre, inventario.medida_ingreso,   inventario.actual_cantidad, inventario.cantidad_alerta, inventario.actual_costo_valorizado, inventario.costo_ideal, inventario_x_unidad_medida_salida.id_inventario;




				");

			$stmt -> execute();

			return $stmt -> fetchAll();

 
		$stmt-> close();

		$stmt = null;

	}
    static public function mdlInventarioProducto( $id )
    {

        $stmt = Conexion::conectar()->prepare("SELECT
        umsp.id_producto 
    FROM
        inventario i
        INNER JOIN inventario_x_unidad_medida_salida ums ON ums.id_inventario = i.id
        INNER JOIN unidad_medida_salida_x_producto umsp ON umsp.id_inventario_unidad_medida_salida = ums.id 
    WHERE
        i.id = :id ;");

        $stmt -> bindParam(":id", $id , PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll( PDO::FETCH_ASSOC);
    }
	static public function mdlMostrarInventarioTrx( ){
   
			   $stmt = Conexion::conectar()->prepare("
   
   
	
   SELECT inventario.id,inventario_transaccion.id id_inventario_trx, codigo_barras, inventario.nombre, inventario.medida_ingreso,  COUNT( inventario_x_unidad_medida_salida.id_inventario)   as medida_salida , inventario.actual_cantidad, inventario.cantidad_alerta, inventario.actual_costo_valorizado, inventario.costo_ideal
   FROM inventario 
   LEFT JOIN inventario_x_unidad_medida_salida ON inventario_x_unidad_medida_salida.id_inventario = inventario.id
   LEFT JOIN inventario_transaccion ON inventario_transaccion.id_inventario = inventario.id
   WHERE inventario.estado = 1
   GROUP BY codigo_barras, inventario.nombre, inventario.medida_ingreso,   inventario.actual_cantidad, inventario.cantidad_alerta, inventario.actual_costo_valorizado, inventario.costo_ideal, inventario_x_unidad_medida_salida.id_inventario;
   
   
   
   
				   ");
   
			   $stmt -> execute();
   
			   return $stmt -> fetchAll();
   
	
		   $stmt-> close();
   
		   $stmt = null;
   
	   }
   


/*=============================================
	Mostrar Tabla Inventario Producto
=============================================*/
static public function mdlMostrarInventarioById( $obj ){

	$stmt = Conexion::conectar()
	->prepare("SELECT
				i.nombre,
				ifnull(it.fecha,'-') fecha,
				it.tipo_movimiento,
				it.cantidad_inicial,
				it.monto_inicial,
				it.cantidad_movimiento,
				it.monto_movimiento,
				it.observacion
			FROM
				inventario i
				INNER JOIN inventario_transaccion it ON it.id_inventario = i.id
			WHERE i.estado <> '2' AND i.id = :id
		");
		$stmt -> bindParam(":id" , $obj -> idInventario, PDO::PARAM_INT);

	$stmt -> execute();

	return $stmt -> fetchAll();


$stmt-> close();

$stmt = null;

}

/*=============================================
	Obtener Nombre en Inventario Producto
=============================================*/
static public function mdlMostrarNombreInventarioById( $obj ){

	$stmt = Conexion::conectar()
	->prepare("SELECT
				i.nombre,
				i.medida_ingreso
			FROM
				inventario i
				INNER JOIN inventario_transaccion it ON it.id_inventario = i.id
			WHERE i.id = :id
		");
		$stmt -> bindParam(":id" , $obj -> idInventario, PDO::PARAM_INT);

	$stmt -> execute();

	return $stmt -> fetchAll(PDO::FETCH_ASSOC);


$stmt-> close();

$stmt = null;

}
/*=============================================
			ELIMINACION DE INVENTARIO UNIDAD DE MEDIDA  
=============================================*/
 	static public function mdlEliminarUnidadMedidaInventario( $idUnidadMedidaInventario){

 		$stmt = Conexion::conectar()->prepare("DELETE FROM  inventario_x_unidad_medida_salida WHERE id = :id");
		
 		$stmt -> bindParam(":id" , $idUnidadMedidaInventario, PDO::PARAM_INT);
 		
 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}




	/*=================================================================
					INGRESAR UNIDAD DE MEDIDA
	=================================================================*/

 


 	static public function mdlCrearUnidadMedida( $datos  ){

 		$stmt = Conexion::conectar()->prepare("INSERT INTO inventario_x_unidad_medida_salida(unidad_medida_salida, id_inventario,  equivalencia   ) VALUES (  :unidad_medida_salida, :id_inventario,  :equivalencia )");
 
 
		$stmt->bindParam(":unidad_medida_salida", $datos["unidad_medida_salida"], PDO::PARAM_STR);
		$stmt->bindParam(":id_inventario", $datos["id_inventario"], PDO::PARAM_INT);
		$stmt->bindParam(":equivalencia", $datos["equivalencia"], PDO::PARAM_STR);
 



 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}






/*=============================================
	Mostrar UNIDADES DE MEDIDA
=============================================*/
 static public function mdlMostrarUnidadMedida($tabla , $id_inventario){

			$stmt = Conexion::conectar()->prepare("
													SELECT * 
													FROM $tabla 
													WHERE id_inventario = :id_inventario
													");


 		$stmt -> bindParam(":id_inventario" , $id_inventario, PDO::PARAM_INT);


			$stmt -> execute();

			return $stmt -> fetchAll();

 
		$stmt-> close();

		$stmt = null;

	}


/*=============================================
	Mostrar UNIDADES DE MEDIDA
=============================================*/
 static public function mdlMostrarUnidadMedidaxInventario(){

			$stmt = Conexion::conectar()->prepare("
													


			SELECT X.* FROM 
			(
			SELECT inventario.id  , 0  as unidad_medida ,  UPPER(inventario.nombre) as nombre FROM inventario 
			UNION ALL 
			SELECT inventario_x_unidad_medida_salida.id_inventario ,inventario_x_unidad_medida_salida.id, CONCAT( UPPER(inventario.nombre   ), '-' , LOWER(inventario_x_unidad_medida_salida.unidad_medida_salida )   ) FROM inventario 
			INNER JOIN inventario_x_unidad_medida_salida WHERE inventario.id = inventario_x_unidad_medida_salida.id_inventario ) AS X
			ORDER BY 1 DESC ,2 




													");


			$stmt -> execute();

			return $stmt -> fetchAll();

 
		$stmt-> close();

		$stmt = null;

	}

	/*=================================================================
					INGRESAR OTROS INGRESOS EGRESOS
	=================================================================*/

 


 	static public function mdlIngresarInventario($tabla, $datos){

 		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, medida_ingreso,  cantidad_alerta,  costo_ideal ,codigo_barras, fecha, sincronizado ) VALUES (:nombre, :medida_ingreso,  :cantidad_alerta,  :costo_ideal ,:codigo_barras, :fecha , 0 )");

 
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":medida_ingreso", $datos["medida_ingreso"], PDO::PARAM_STR);
		$stmt->bindParam(":cantidad_alerta", $datos["cantidad_alerta"], PDO::PARAM_STR);
		$stmt->bindParam(":costo_ideal", $datos["costo_ideal"], PDO::PARAM_STR);
		$stmt->bindParam(":codigo_barras", $datos["codigo_barras"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
		

 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "error";

 		$stmt-> close();
 		$stmt= null;
 	}

 	static public function mdlEditarInventario($tabla, $datos){

 	

	try {
 		$conn = Conexion::conectar();
		
		$conn->beginTransaction();
 

 		$stmt = $conn->prepare("UPDATE $tabla SET sincronizado = 0,codigo_barras =:codigo_barras ,nombre = :nombre ,cantidad_alerta = :cantidad_alerta ,costo_ideal = :costo_ideal , medida_ingreso = :medida_ingreso   WHERE id = :id");
 

 		$stmt -> bindParam(":id" , $datos["id"], PDO::PARAM_INT);
		$stmt -> bindParam(":codigo_barras" , $datos["codigo_barras"], PDO::PARAM_STR);
		$stmt -> bindParam(":nombre" , $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":cantidad_alerta" , $datos["cantidad_alerta"], PDO::PARAM_STR);
 		$stmt -> bindParam(":costo_ideal" , $datos["costo_ideal"], PDO::PARAM_STR);
 		$stmt -> bindParam(":medida_ingreso" , $datos["medida_ingreso"], PDO::PARAM_STR); 	
 		
 		
 		
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
			ELIMINACION DE PRODUCTOS
=============================================*/
 	static public function mdlEliminarProductoInventario( $tabla, $id){

try{

 		$conn = Conexion::conectar();
		
		$conn->beginTransaction();
 



 		$stmt = $conn->prepare("UPDATE $tabla  SET sincronizado = 0,estado = 0 WHERE id = :id");
 
$stmt -> bindParam(":id" , $id, PDO::PARAM_INT);
 		
 		
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

	static public function mdlAddProductoInventario( $ProductoInventario )
	{
		try
		{
			$conn = Conexion::conectar();

			$conn->beginTransaction();		

			//AHORA HACEMOS LA TRANSACCION DE DISMINUCION POR VENTA DEL INVENTARIO 

			$stmt = $conn->prepare("

			INSERT INTO  inventario_transaccion (id_inventario ,cantidad_inicial, monto_inicial, cantidad_movimiento , monto_movimiento , tipo_movimiento , nombre_inventario , fecha, estado, observacion )

			SELECT 	
			inventario.id, 
			inventario.actual_cantidad  , 
			inventario.actual_costo_valorizado ,  
			:cantidad_movimiento  ,    
			:monto_movimiento  ,      
			:tipo_movimiento  ,
			:nombre_inventario,
			:fecha,  
			'1',
			:nota
			FROM inventario
			WHERE id = :id_inventario 
			");

			$id_inventario     = $ProductoInventario['id_inventario'];
			$nombre_producto   = $ProductoInventario['nombre_producto'];
			$cantidad_producto = $ProductoInventario['cantidad_producto'];
			$precio_unitario_producto = $ProductoInventario['Precio'];
			$fecha = $ProductoInventario['Fecha'];
			$nota = $ProductoInventario['Nota'];
			$tipo_movimiento = $ProductoInventario['tipo_transaccion'];

			/*switch ($ProductoInventario['tipo_transaccion']) 
			{
				case 'COMPRAS':
					$tipo_movimiento = "compra";
					break;
				
				case 'COMPRAS SIN DOCUMENTOS':
					$tipo_movimiento = "inventario_ajuste";
					break;
				
				case 'OTROS':
					$tipo_movimiento = "inventario_ajuste";
					break;
				default:
					# code...
					break;
			}*/

			$stmt->bindValue(':id_inventario', $id_inventario, PDO::PARAM_STR);
			$stmt->bindValue(':nombre_inventario', $nombre_producto, PDO::PARAM_STR);  
			$stmt->bindValue(':cantidad_movimiento', $cantidad_producto, PDO::PARAM_STR);      
			$stmt->bindValue(':monto_movimiento',  $precio_unitario_producto, PDO::PARAM_STR);     
			$stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
			$stmt->bindParam(":tipo_movimiento", $tipo_movimiento, PDO::PARAM_STR);
            $stmt->bindParam(":nota", $nota, PDO::PARAM_STR);

			$stmt->execute();

			// HACEMOS EL RETIRO DE STOCK :)
			$stmt = $conn->prepare("

					UPDATE inventario 
					SET inventario.actual_cantidad = inventario.actual_cantidad +     :cantidad_producto1,
						inventario.actual_costo_valorizado =  inventario.actual_costo_valorizado+ :precio_unitario_producto
					WHERE inventario.id = :id_inventario

			");

			//file_put_contents("11mdlIngresarCompra.txt", $id_inventario );
			$stmt->bindParam(":id_inventario", $id_inventario , PDO::PARAM_INT);
			$stmt->bindParam(":cantidad_producto1",  $cantidad_producto   , PDO::PARAM_STR);
			//$stmt->bindParam(":cantidad_producto2",  $cantidad_producto   , PDO::PARAM_STR);
			$stmt->bindParam(":precio_unitario_producto",  $precio_unitario_producto  , PDO::PARAM_STR);
			$stmt->execute();

			//UNA VEZ QUE INSERTAMOS LA COMPRA ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES

			$conn->commit();
			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
			return "ok";

			$stmt->close();
			$stmt = null;

		}
		catch(Exception $e) {			

			$conn->rollBack();
			//file_put_contents("mdlIngresarCompra.txt", $e->getMessage() );
			return array( "error"=> 0,"mensaje" => $e->getMessage());
		}

	}
	static public function mdlDisminuirProductoInventario( $ProductoInventario )
	{
		try
        {
            $conn = Conexion::conectar();

            $conn->beginTransaction();

            $id_inventario     = $ProductoInventario['id_inventario'];
            $nombre_producto   = $ProductoInventario['nombre_producto'];
            $cantidad_producto = $ProductoInventario['cantidad_producto'];
            $nota = $ProductoInventario['Nota'];
            $precio_unitario_producto = $ProductoInventario['Precio'];

            $stmt = $conn->prepare("SELECT actual_costo_valorizado, actual_cantidad 
            FROM inventario
            WHERE id = :id_inventario;");
            $stmt->bindParam(":id_inventario", $id_inventario , PDO::PARAM_INT);
            $stmt->execute();
            $inventario_tmp = $stmt -> fetchAll( PDO::FETCH_ASSOC );

            $costo_movimiento_valorizado = $cantidad_producto * ( $inventario_tmp[0]['actual_costo_valorizado'] / $inventario_tmp[0]['actual_cantidad']);
			$costo_movimiento_valorizado = round($costo_movimiento_valorizado,2);
			$tipo_movimiento = $ProductoInventario['tipo_transaccion'];
			/*switch ($ProductoInventario['tipo_transaccion']) 
			{
				case 'MERMAS':
					$tipo_movimiento = "disminucion_inventario";
					break;
				
				case 'OTROS':
					$tipo_movimiento = "disminucion_inventario";
					break;
				default:
					# code...
					break;
			}*/

            $stmt = $conn->prepare("INSERT 
            INTO  inventario_transaccion (id_inventario ,cantidad_inicial, monto_inicial, cantidad_movimiento , monto_movimiento , tipo_movimiento , nombre_inventario , fecha, estado, observacion )
            SELECT
                inventario.id,
                inventario.actual_cantidad ,
                inventario.actual_costo_valorizado,
                :cantidad_movimiento,
                :costo_movimiento_valorizado,
                :tipo_movimiento,
                inventario.nombre,
                :fecha, 
                '1', 
                :nota
            FROM 
                inventario
            WHERE 
                id = :id_inventario 
            ");

            $stmt->bindValue(':id_inventario', $id_inventario, PDO::PARAM_STR);     
            $stmt->bindValue(':cantidad_movimiento', $cantidad_producto, PDO::PARAM_STR);      
            $stmt->bindValue(':costo_movimiento_valorizado', $costo_movimiento_valorizado, PDO::PARAM_STR);      
            $stmt->bindParam(":fecha", $ProductoInventario["Fecha"], PDO::PARAM_STR);
            $stmt->bindParam(":nota", $nota, PDO::PARAM_STR);
            $stmt->bindParam(":tipo_movimiento", $tipo_movimiento, PDO::PARAM_STR);
            $stmt->execute();

            //AHORA HACEMOS LA TRANSACCION DE DISMINUCION POR VENTA DEL INVENTARIO 

            // HACEMOS EL RETIRO DE STOCK :)
            $stmt = $conn->prepare("UPDATE inventario 
                SET inventario.actual_cantidad = inventario.actual_cantidad -     :cantidad_producto1,
                    inventario.actual_costo_valorizado =  ( inventario.actual_costo_valorizado-    :costo_movimiento_valorizado )
                WHERE inventario.id = :id_inventario

            ");

            //file_put_contents("11mdlIngresarCompra.txt", $id_inventario );
            $stmt->bindParam(":id_inventario", $id_inventario , PDO::PARAM_INT);
            $stmt->bindParam(":cantidad_producto1", $cantidad_producto , PDO::PARAM_STR);
            $stmt->bindParam(":costo_movimiento_valorizado", $costo_movimiento_valorizado , PDO::PARAM_STR);
            $stmt->execute();


            $conn->commit();
            return "ok";

        }
        catch(Exception $e) 
        {
            $conn->rollBack();
            return array( "error"=> 0,"mensaje" => $e->getMessage());
        }

	}

	static public function mdlGetListUndMedidaProducto( ){

		$stmt = Conexion::conectar()
		->prepare("	SELECT 
						unidad_medida_salida_x_producto.*
					FROM
						unidad_medida_salida_x_producto;
			");
			$stmt -> bindParam(":id" , $obj -> idInventario, PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);
	}

	static public function mdlGetListInvUndMedidaSalida( ){

		$stmt = Conexion::conectar()
		->prepare("	SELECT 
						inventario_x_unidad_medida_salida.*
					FROM
						inventario_x_unidad_medida_salida;
			");

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);
	}


	static public function mdlGetListInventario( ){

		$stmt = Conexion::conectar()
		->prepare("	SELECT 
						inventario.*
					FROM
						inventario;
			");

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);
	}



}