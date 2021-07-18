<?php

require_once "conexion.php";
/**
  * 
  */
class ModeloUnidadMedida
{
    public $codunidad;
    public $nomuidad;

    /*=============================================
                Mostrar Unidad Medida
    =============================================*/
    static public function mdlMostrarUnidadMedida($tabla){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla" );

        $stmt -> execute();

        return $stmt -> fetchAll(PDO::FETCH_ASSOC);

    }


}