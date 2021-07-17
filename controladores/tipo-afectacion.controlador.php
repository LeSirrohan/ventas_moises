<?php
class ControladorTipoAfectacion {



static public function ctrMostrarTipoAfectacion(){

		$tabla = "tipoafectacion";

		$respuesta = ModeloTipoAfectacion::mdlMostrarTipoAfectacion($tabla );

		return   $respuesta;

}
static public function ctrListaTipoAfectacion(){

		$tabla = "tipoafectacion";

		$respuesta = ModeloTipoAfectacion::mdlListaTipoAfectacion($tabla );

		return   $respuesta;

}
static public function ctrConvertirDataYMD($fecha){

	date_default_timezone_set('America/Bogota');
	return date("Y-m-d",strtotime($fecha));

}
static public function ctrValidarEfectivo(){
	$tabla = "tipoafectacion";
	$response = ModeloTipoAfectacion::mdlValidarEfectivo($tabla);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrTipoAfectacionById($tipoafectacion){
	$tabla = "tipoafectacion";
	$response = ModeloTipoAfectacion::mdlTipoAfectacionById($tipoafectacion);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrMostrarTipoAfectacionTabla(){

	$tabla = "tipoafectacion";

	$respuesta = ModeloTipoAfectacion::mdlMostrarTipoAfectacionTabla($tabla );

	return   $respuesta;

}

static public function ctrMostrarTipoAfectacionEfectivo(){

		$tabla = "tipoafectacion";

		$respuesta = ModeloTipoAfectacion::mdlMostrarTipoAfectacionEfectivo($tabla );

	 
		return $respuesta;


}
static public function ctrAgregarTipoAfectacion($tipoafectacion)
{
	$tabla = "tipoafectacion";
	if($tipoafectacion->tipo_pago == 1){
		$validar =  ControladorTipoAfectacion::ctrValidarEfectivo();
		if($validar["efectivo"]<1)
		{
			return ModeloTipoAfectacion::mdlAgregarTipoAfectacion( $tabla , $tipoafectacion );
		}
		else
		{
			$respo = "SOLAMENTE SE PERMITE UN TIPO DE COBRO MARCADO COMO EFECTIVO";
			return $respo;
		}
	}
	else{		
		return ModeloTipoAfectacion::mdlAgregarTipoAfectacion( $tabla , $tipoafectacion );
	}

}

static public function ctrEditarTipoAfectacion($tipoafectacion)
{
	$tabla = "tipoafectacion";
	if($tipoafectacion->tipo_pago == 1){
		$id = "";
		$id = $tipoafectacion->idTipoAfectacion;
		$validar =  ControladorTipoAfectacion::ctrTipoAfectacionById( $tipoafectacion );
		if($validar["efectivo"]==1 AND $tipoafectacion->tipo_pago == 1)
		{
			if( ($validar["nombre"] != $tipoafectacion->nombre) || ($validar["simbolo"] != $tipoafectacion->simbolo) || ($validar["orden"] != $tipoafectacion->orden) )
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
			return ModeloTipoAfectacion::mdlEditarTipoAfectacion( $tabla , $tipoafectacion );
		}
		else
		{
			$respo = "Solamente se permite un tipo de cobro marcado como efectivo";
			return $respo;
		}
	}
	else{		
		return ModeloTipoAfectacion::mdlEditarTipoAfectacion( $tabla , $tipoafectacion );
	}	

}
static public function ctrEliminarTipoAfectacion($tipoafectacion)
{
	$tabla = "tipoafectacion";		
	return ModeloTipoAfectacion::mdlEliminarTipoAfectacion( $tabla , $tipoafectacion );

}

	static public function ctrEditarNotaInicial( $obj )
	{
		return ModeloTipoAfectacion::mdlEditarNotaInicial( $obj );

	}
}
