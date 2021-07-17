<?php
class ControladorTipoCpe {



static public function ctrMostrarTipoCpe(){

		$tabla = "tipocpe";

		$respuesta = ModeloTipoCpe::mdlMostrarTipoCpe($tabla );

		return   $respuesta;

}
static public function ctrListaTipoCpe(){

		$tabla = "tipocpe";

		$respuesta = ModeloTipoCpe::mdlListaTipoCpe($tabla );

		return   $respuesta;

}
static public function ctrConvertirDataYMD($fecha){

	date_default_timezone_set('America/Bogota');
	return date("Y-m-d",strtotime($fecha));

}
static public function ctrValidarEfectivo(){
	$tabla = "tipocpe";
	$response = ModeloTipoCpe::mdlValidarEfectivo($tabla);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrTipoCpeById($tipocpe){
	$tabla = "tipocpe";
	$response = ModeloTipoCpe::mdlTipoCpeById($tipocpe);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrMostrarTipoCpeTabla(){

	$tabla = "tipocpe";

	$respuesta = ModeloTipoCpe::mdlMostrarTipoCpeTabla($tabla );

	return   $respuesta;

}

static public function ctrMostrarTipoCpeEfectivo(){

		$tabla = "tipocpe";

		$respuesta = ModeloTipoCpe::mdlMostrarTipoCpeEfectivo($tabla );

	 
		return $respuesta;


}
static public function ctrAgregarTipoCpe($tipocpe)
{
	$tabla = "tipocpe";
	if($tipocpe->tipo_pago == 1){
		$validar =  ControladorTipoCpe::ctrValidarEfectivo();
		if($validar["efectivo"]<1)
		{
			return ModeloTipoCpe::mdlAgregarTipoCpe( $tabla , $tipocpe );
		}
		else
		{
			$respo = "SOLAMENTE SE PERMITE UN TIPO DE COBRO MARCADO COMO EFECTIVO";
			return $respo;
		}
	}
	else{		
		return ModeloTipoCpe::mdlAgregarTipoCpe( $tabla , $tipocpe );
	}

}

static public function ctrEditarTipoCpe($tipocpe)
{
	$tabla = "tipocpe";
	if($tipocpe->tipo_pago == 1){
		$id = "";
		$id = $tipocpe->idTipoCpe;
		$validar =  ControladorTipoCpe::ctrTipoCpeById( $tipocpe );
		if($validar["efectivo"]==1 AND $tipocpe->tipo_pago == 1)
		{
			if( ($validar["nombre"] != $tipocpe->nombre) || ($validar["simbolo"] != $tipocpe->simbolo) || ($validar["orden"] != $tipocpe->orden) )
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
			return ModeloTipoCpe::mdlEditarTipoCpe( $tabla , $tipocpe );
		}
		else
		{
			$respo = "Solamente se permite un tipo de cobro marcado como efectivo";
			return $respo;
		}
	}
	else{		
		return ModeloTipoCpe::mdlEditarTipoCpe( $tabla , $tipocpe );
	}	

}
static public function ctrEliminarTipoCpe($tipocpe)
{
	$tabla = "tipocpe";		
	return ModeloTipoCpe::mdlEliminarTipoCpe( $tabla , $tipocpe );

}

	static public function ctrEditarNotaInicial( $obj )
	{
		return ModeloTipoCpe::mdlEditarNotaInicial( $obj );

	}
}
