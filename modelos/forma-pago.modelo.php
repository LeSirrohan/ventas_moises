<?php

require_once "conexion.php";
/**
  * 
  */
 class ModeloFormaPago
 {
	 public $idFormaPago;
	 public $nombre;
	 public $fecha;
	 public $simbolo;
	 public $tipo_pago;
	 public $orden;
	 public $estado;
	 public $id_local;

/*=============================================
	Mostrar Otros Ingresos Egresos
=============================================*/
 static public function mdlMostrarFormaPago($tabla){


 

			$stmt = Conexion::conectar()->prepare("SELECT * FROM formapago" );

			$stmt -> execute();

			return $stmt -> fetchAll();

 
		$stmt-> close();

		$stmt = null;

	}
	/*=============================================
		Mostrar Otros Ingresos Egresos
	=============================================*/
	 static public function mdlListaFormaPago($tabla){	 
	
		$stmt = Conexion::conectar()->prepare("SELECT * FROM formapago WHERE estado = 1 AND efectivo <> 2 ORDER BY orden" );

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);
	}

	
	/*
	=================================================
	|	MOSTRAR REGISTROS TIPO COBRO				|
	=================================================
	*/
	 static public function mdlMostrarFormaPagoTabla($tabla){
		 $sql= "SELECT
					$tabla.codformapago,
					$tabla.nomformapago
				FROM
					$tabla ;";

		$stmt = Conexion::conectar()->prepare( $sql );

		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}
	/*
	=================================================
	|	AGREGAR TIPO COBRO							|
	=================================================
	*/
	 static public function mdlAgregarFormaPago($tabla,$formapago){
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (nombre,fecha,simbolo,efectivo,orden,estado,id_local) values (:nombre,NOW(),:simbolo,:efectivo,:orden,'1',:id_local);");
 
		$stmt->bindParam(":nombre", $formapago->nombre, PDO::PARAM_STR);
		$stmt->bindParam(":simbolo", $formapago->simbolo, PDO::PARAM_STR);
		$stmt->bindParam(":efectivo", $formapago->tipo_pago, PDO::PARAM_STR);
		$stmt->bindParam(":orden", $formapago->orden, PDO::PARAM_STR);
		$stmt->bindParam(":id_local", $formapago->id_local, PDO::PARAM_STR);
		

 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "Error al crear el tipo de cobro ";

	}
	/*
	=================================================
	|				EDITAR TIPO COBRO				|
	=================================================
	*/
	 static public function mdlEditarFormaPago($tabla,$formapago){		
		
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre=:nombre,simbolo=:simbolo,efectivo=:efectivo,orden=:orden WHERE id = :idFormaPago;");
		
		$stmt->bindParam(":nombre", $formapago->nombre, PDO::PARAM_STR);
		$stmt->bindParam(":simbolo", $formapago->simbolo, PDO::PARAM_STR);
		$stmt->bindParam(":efectivo", $formapago->tipo_pago, PDO::PARAM_STR);
		$stmt->bindParam(":orden", $formapago->orden, PDO::PARAM_STR);
		$stmt->bindParam(":idFormaPago", $formapago->idFormaPago, PDO::PARAM_INT);
 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "Error al editar el tipo de cobro ";

 		$stmt-> close();
 		$stmt= null;

	}
	/*
	=================================================
	|				EDITAR NOTA INCIAL				|
	=================================================
	*/
	 static public function mdlEditarNotaInicial($formapago){	
		$stmt = Conexion::conectar()->prepare("UPDATE ventas_x_formapago SET nota=:editNotaInicial WHERE id = :id_venta_tip_cobro;");
		
		$stmt->bindParam(":editNotaInicial", $formapago->editNotaInicial, PDO::PARAM_STR);
		$stmt->bindParam(":id_venta_tip_cobro", $formapago->id_venta_tip_cobro, PDO::PARAM_STR);
 		if( $stmt -> execute()) 
 			return "ok";
 		else 
 			return "Error al editar la nota inicial ";

 		$stmt-> close();
 		$stmt= null;

	}
	/*
	=================================================
	|				ELIMINAR TIPO COBRO				|
	=================================================
	*/
	 static public function mdlEliminarFormaPago($tabla,$formapago){	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=0 WHERE id = :id");
		
		$stmt -> bindParam(":id" , $formapago->idFormaPago, PDO::PARAM_INT);
		
		if( $stmt -> execute()) 
			return "ok";
		else 
			return "Error al eliminar el tipo de cobro ";

		$stmt-> close();
		$stmt= null;

	}
	/*
	=================================================
	|				EDITAR TIPO COBRO				|
	=================================================
	*/
	 static public function mdlEditarVentaFormaPago($tabla,$idFormaPago){	
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado=2 WHERE id = :id");
		
		$stmt -> bindParam(":id" , $idFormaPago, PDO::PARAM_INT);
		
		if( $stmt -> execute()) 
			return "ok";
		else 
			return "Error al eliminar el tipo de cobro ";

		$stmt-> close();
		$stmt= null;

	}
	/*
	=================================================
	|	VALIDAR EFECTIVO							|
	=================================================
	*/
	 static public function mdlValidarEfectivo($tabla){
		 $sql= "SELECT
					count(*) efectivo
				FROM
					$tabla
				
				WHERE efectivo = '1'";

		$stmt = Conexion::conectar()->prepare( $sql );

		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

		$stmt-> close();

		$stmt = null;

	}



	/*
	=================================================
	|	VALIDAR EFECTIVO							|
	=================================================
	*/
	static public function mdlFormaPagoById($formapago){
		$sql= "SELECT
				   efectivo,
				   nombre,
				   simbolo,
				   orden
			   FROM
				   formapago
			   
			   WHERE  id = :id";

	   $stmt = Conexion::conectar()->prepare( $sql );
	   $stmt -> bindParam(":id" , $formapago->idFormaPago, PDO::PARAM_STR);

	   $stmt -> execute();

	   return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	   $stmt-> close();

	   $stmt = null;

   }


/*=============================================
	Mostrar Otros Ingresos Egresos
=============================================*/
 static public function mdlMostrarFormaPagoEfectivo(){


 			$stmt = Conexion::conectar()->prepare("SELECT * FROM formapago WHERE estado = 1 AND efectivo = 1 ORDER BY orden" );

			$stmt -> execute();

			return $stmt -> fetchAll();

 
		$stmt-> close();

		$stmt = null;

	}






}