<?php
class ControladorFormaPago {



static public function ctrMostrarFormaPago(){

		$tabla = "formapago";

		$respuesta = ModeloFormaPago::mdlMostrarFormaPago($tabla );

		return   $respuesta;

}
static public function ctrListaFormaPago(){

		$tabla = "formapago";

		$respuesta = ModeloFormaPago::mdlListaFormaPago($tabla );

		return   $respuesta;

}
static public function ctrConvertirDataYMD($fecha){

	date_default_timezone_set('America/Bogota');
	return date("Y-m-d",strtotime($fecha));

}
static public function ctrValidarEfectivo(){
	$tabla = "formapago";
	$response = ModeloFormaPago::mdlValidarEfectivo($tabla);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrFormaPagoById($formapago){
	$tabla = "formapago";
	$response = ModeloFormaPago::mdlFormaPagoById($formapago);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrMostrarFormaPagoTabla(){

	$tabla = "formapago";

	$respuesta = ModeloFormaPago::mdlMostrarFormaPagoTabla($tabla );

	return   $respuesta;

}

static public function ctrMostrarFormaPagoEfectivo(){

		$tabla = "formapago";

		$respuesta = ModeloFormaPago::mdlMostrarFormaPagoEfectivo($tabla );

	 
		return $respuesta;


}
static public function ctrAgregarFormaPago($formapago)
{
	$tabla = "formapago";
	if($formapago->tipo_pago == 1){
		$validar =  ControladorFormaPago::ctrValidarEfectivo();
		if($validar["efectivo"]<1)
		{
			return ModeloFormaPago::mdlAgregarFormaPago( $tabla , $formapago );
		}
		else
		{
			$respo = "SOLAMENTE SE PERMITE UN TIPO DE COBRO MARCADO COMO EFECTIVO";
			return $respo;
		}
	}
	else{		
		return ModeloFormaPago::mdlAgregarFormaPago( $tabla , $formapago );
	}

}

static public function ctrEditarFormaPago($formapago)
{
	$tabla = "formapago";
	if($formapago->tipo_pago == 1){
		$id = "";
		$id = $formapago->idFormaPago;
		$validar =  ControladorFormaPago::ctrFormaPagoById( $formapago );
		if($validar["efectivo"]==1 AND $formapago->tipo_pago == 1)
		{
			if( ($validar["nombre"] != $formapago->nombre) || ($validar["simbolo"] != $formapago->simbolo) || ($validar["orden"] != $formapago->orden) )
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
			return ModeloFormaPago::mdlEditarFormaPago( $tabla , $formapago );
		}
		else
		{
			$respo = "Solamente se permite un tipo de cobro marcado como efectivo";
			return $respo;
		}
	}
	else{		
		return ModeloFormaPago::mdlEditarFormaPago( $tabla , $formapago );
	}	

}
static public function ctrEliminarFormaPago($formapago)
{
	$tabla = "formapago";		
	return ModeloFormaPago::mdlEliminarFormaPago( $tabla , $formapago );

}

	static public function ctrEditarNotaInicial( $obj )
	{
		return ModeloFormaPago::mdlEditarNotaInicial( $obj );

	}
}
