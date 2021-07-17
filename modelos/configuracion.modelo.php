<?php

require_once "conexion.php";
/**
  * 
  */
 class ModeloConfiguracion{
/*=============================================
	Mostrar Otros Ingresos Egresos
=============================================*/
 static public function mdlServidorConsultas($id_local){

 

		$stmt = Conexion::conectar()->prepare("
					

					SELECT servidor FROM parametro_sistema 
					INNER JOIN local ON local.id_parametro_sistema = parametro_sistema.id
					WHERE local.id = :id_local;


			");
 		
 		$stmt->bindParam(":id_local", $id_local , PDO::PARAM_INT);

 		$stmt -> execute();

 		return $stmt ->fetch()['servidor'];

 		$stmt-> close();

 		$stmt= null;

	}


/*=============================================
	Mostrar Otros Ingresos Egresos
=============================================*/
 static public function mdlParametrosGenerales($id_local){

 

		$stmt = Conexion::conectar()->prepare("
					

					SELECT * FROM parametro_sistema 
					INNER JOIN local ON local.id_parametro_sistema = parametro_sistema.id
					WHERE local.id = :id_local;


			");
 		
 		$stmt->bindParam(":id_local", $id_local , PDO::PARAM_INT);

 		$stmt -> execute();

 		return $stmt ->fetchAll();

 		$stmt-> close();

 		$stmt= null;

	}



}