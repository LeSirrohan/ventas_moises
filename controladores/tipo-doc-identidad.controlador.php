<?php
class ControladorTipoDocIdentidad {



static public function ctrMostrarTipDocIdentidad(){

		$tabla = "tipodocumentoidentidad";

		$respuesta = ModeloTipoDocIdentidad::mdlMostrarTipoDocIdentidad($tabla );

		return   $respuesta;

}
static public function ctrListaTipoDocIdentidad(){

		$tabla = "tipodocumentoidentidad";

		$respuesta = ModeloTipoDocIdentidad::mdlListaTipoDocIdentidad($tabla );

		return   $respuesta;

}
static public function ctrConvertirDataYMD($fecha){

	date_default_timezone_set('America/Bogota');
	return date("Y-m-d",strtotime($fecha));

}
static public function ctrValidarEfectivo(){
	$tabla = "tipodocumentoidentidad";
	$response = ModeloTipoDocIdentidad::mdlValidarEfectivo($tabla);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrTipoDocIdentidadById($tipodocumentoidentidad){
	$tabla = "tipodocumentoidentidad";
	$response = ModeloTipoDocIdentidad::mdlTipoDocIdentidadById($tipodocumentoidentidad);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrMostrarTipoDocIdentidadTabla(){

	$tabla = "tipodocumentoidentidad";

	$respuesta = ModeloTipoDocIdentidad::mdlMostrarTipoDocIdentidadTabla($tabla );

	return   $respuesta;

}

static public function ctrMostrarTipoDocIdentidadEfectivo(){

		$tabla = "tipodocumentoidentidad";

		$respuesta = ModeloTipoDocIdentidad::mdlMostrarTipoDocIdentidadEfectivo($tabla );

	 
		return $respuesta;


}
static public function ctrAgregarTipoDocIdentidad($tipodocumentoidentidad)
{
	$tabla = "tipodocumentoidentidad";
	if($tipodocumentoidentidad->tipo_pago == 1){
		$validar =  ControladorTipoDocIdentidad::ctrValidarEfectivo();
		if($validar["efectivo"]<1)
		{
			return ModeloTipoDocIdentidad::mdlAgregarTipoDocIdentidad( $tabla , $tipodocumentoidentidad );
		}
		else
		{
			$respo = "SOLAMENTE SE PERMITE UN TIPO DE COBRO MARCADO COMO EFECTIVO";
			return $respo;
		}
	}
	else{		
		return ModeloTipoDocIdentidad::mdlAgregarTipoDocIdentidad( $tabla , $tipodocumentoidentidad );
	}

}

static public function ctrEditarTipoDocIdentidad($tipodocumentoidentidad)
{
	$tabla = "tipodocumentoidentidad";
	if($tipodocumentoidentidad->tipo_pago == 1){
		$id = "";
		$id = $tipodocumentoidentidad->idTipoDocIdentidad;
		$validar =  ControladorTipoDocIdentidad::ctrTipoDocIdentidadById( $tipodocumentoidentidad );
		if($validar["efectivo"]==1 AND $tipodocumentoidentidad->tipo_pago == 1)
		{
			if( ($validar["nombre"] != $tipodocumentoidentidad->nombre) || ($validar["simbolo"] != $tipodocumentoidentidad->simbolo) || ($validar["orden"] != $tipodocumentoidentidad->orden) )
			{
				$valido = 1;
			}
			else{
				$valido = 0;
			}
		}
		else{
			$valido = 0;
		}
		if($valido)
		{
			return ModeloTipoDocIdentidad::mdlEditarTipoDocIdentidad( $tabla , $tipodocumentoidentidad );
		}
		else
		{
			$respo = "Solamente se permite un tipo de cobro marcado como efectivo";
			return $respo;
		}
	}
	else{		
		return ModeloTipoDocIdentidad::mdlEditarTipoDocIdentidad( $tabla , $tipodocumentoidentidad );
	}	

}
static public function ctrEliminarTipoDocIdentidad($tipodocumentoidentidad)
{
	$tabla = "tipodocumentoidentidad";		
	return ModeloTipoDocIdentidad::mdlEliminarTipoDocIdentidad( $tabla , $tipodocumentoidentidad );

}

	static public function ctrEditarNotaInicial( $obj )
	{
		return ModeloTipoDocIdentidad::mdlEditarNotaInicial( $obj );

	}
}
