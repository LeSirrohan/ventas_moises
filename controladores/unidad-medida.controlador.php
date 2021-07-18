<?php
class ControladorUnidadMedida {

    static public function ctrMostrarUnidadMedida(){

            $tabla = "unidadmedida";

            $respuesta = ModeloUnidadMedida::mdlMostrarUnidadMedida( $tabla );

            return   $respuesta;

    }
}
