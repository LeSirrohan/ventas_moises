<?php
class ControladorConfiguracion {

/*=============================================
=            MODAL CREAR CATEGORIA          =
=============================================*/

	static public function ctrServidorConsultas($id_local){
	
		$respuesta = ModeloConfiguracion::mdlServidorConsultas($id_local );
		return $respuesta;

	}


/*=============================================
=            MODAL CREAR CATEGORIA          =
=============================================*/

	static public function ctrParametrosGenerales($id_local){
	
		$respuesta = ModeloConfiguracion::mdlParametrosGenerales($id_local );
		return $respuesta;

	}


}