<?php
class ControladorTipoPago {



static public function ctrMostrarTipoPago(){

		$tabla = "tipopago";

		$respuesta = ModeloTipoPago::mdlMostrarTipoPago($tabla );

		return   $respuesta;

}
static public function ctrListaTipoPago(){

		$tabla = "tipopago";

		$respuesta = ModeloTipoPago::mdlListaTipoPago($tabla );

		return   $respuesta;

}
static public function ctrConvertirDataYMD($fecha){

	date_default_timezone_set('America/Bogota');
	return date("Y-m-d",strtotime($fecha));

}
static public function ctrValidarEfectivo(){
	$tabla = "tipopago";
	$response = ModeloTipoPago::mdlValidarEfectivo($tabla);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrTipoPagoById($tipopago){
	$tabla = "tipopago";
	$response = ModeloTipoPago::mdlTipoPagoById($tipopago);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrMostrarTipoPagoTabla(){

	$tabla = "tipopago";

	$respuesta = ModeloTipoPago::mdlMostrarTipoPagoTabla($tabla );

	return   $respuesta;

}

static public function ctrMostrarTipoPagoEfectivo(){

		$tabla = "tipopago";

		$respuesta = ModeloTipoPago::mdlMostrarTipoPagoEfectivo($tabla );

	 
		return $respuesta;


}
static public function ctrAgregarTipoPago($tipopago)
{
	$tabla = "tipopago";
	if($tipopago->tipo_pago == 1){
		$validar =  ControladorTipoPago::ctrValidarEfectivo();
		if($validar["efectivo"]<1)
		{
			return ModeloTipoPago::mdlAgregarTipoPago( $tabla , $tipopago );
		}
		else
		{
			$respo = "SOLAMENTE SE PERMITE UN TIPO DE COBRO MARCADO COMO EFECTIVO";
			return $respo;
		}
	}
	else{		
		return ModeloTipoPago::mdlAgregarTipoPago( $tabla , $tipopago );
	}

}

static public function ctrEditarTipoPago($tipopago)
{
	$tabla = "tipopago";
	if($tipopago->tipo_pago == 1){
		$id = "";
		$id = $tipopago->idTipoPago;
		$validar =  ControladorTipoPago::ctrTipoPagoById( $tipopago );
		if($validar["efectivo"]==1 AND $tipopago->tipo_pago == 1)
		{
			if( ($validar["nombre"] != $tipopago->nombre) || ($validar["simbolo"] != $tipopago->simbolo) || ($validar["orden"] != $tipopago->orden) )
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
			return ModeloTipoPago::mdlEditarTipoPago( $tabla , $tipopago );
		}
		else
		{
			$respo = "Solamente se permite un tipo de cobro marcado como efectivo";
			return $respo;
		}
	}
	else{		
		return ModeloTipoPago::mdlEditarTipoPago( $tabla , $tipopago );
	}	

}
static public function ctrEliminarTipoPago($tipopago)
{
	$tabla = "tipopago";		
	return ModeloTipoPago::mdlEliminarTipoPago( $tabla , $tipopago );

}

	static public function ctrEditarNotaInicial( $obj )
	{
		return ModeloTipoPago::mdlEditarNotaInicial( $obj );

	}
}
