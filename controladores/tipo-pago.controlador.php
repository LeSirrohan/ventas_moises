<?php
class ControladorTipoPago {



static public function ctrMostrarTipoPago(){

		$tabla = "tipo_cobro";

		$respuesta = ModeloTipoPago::mdlMostrarTipoPago($tabla );

		return   $respuesta;

}
static public function ctrListaTipoPago(){

		$tabla = "tipo_cobro";

		$respuesta = ModeloTipoPago::mdlListaTipoPago($tabla );

		return   $respuesta;

}
static public function ctrConvertirDataYMD($fecha){

	date_default_timezone_set('America/Bogota');
	return date("Y-m-d",strtotime($fecha));

}
static public function ctrValidarEfectivo(){
	$tabla = "tipo_cobro";
	$response = ModeloTipoPago::mdlValidarEfectivo($tabla);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrTipoPagoById($tipo_cobro){
	$tabla = "tipo_cobro";
	$response = ModeloTipoPago::mdlTipoPagoById($tipo_cobro);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrMostrarTipoPagoTabla(){

	$tabla = "tipo_cobro";

	$respuesta = ModeloTipoPago::mdlMostrarTipoPagoTabla($tabla );

	return   $respuesta;

}

static public function ctrMostrarTipoPagoEfectivo(){

		$tabla = "tipo_cobro";

		$respuesta = ModeloTipoPago::mdlMostrarTipoPagoEfectivo($tabla );

	 
		return $respuesta;


}
static public function ctrAgregarTipoPago($tipo_cobro)
{
	$tabla = "tipo_cobro";
	if($tipo_cobro->tipo_pago == 1){
		$validar =  ControladorTipoPago::ctrValidarEfectivo();
		if($validar["efectivo"]<1)
		{
			return ModeloTipoPago::mdlAgregarTipoPago( $tabla , $tipo_cobro );
		}
		else
		{
			$respo = "SOLAMENTE SE PERMITE UN TIPO DE COBRO MARCADO COMO EFECTIVO";
			return $respo;
		}
	}
	else{		
		return ModeloTipoPago::mdlAgregarTipoPago( $tabla , $tipo_cobro );
	}

}

static public function ctrEditarTipoPago($tipo_cobro)
{
	$tabla = "tipo_cobro";
	if($tipo_cobro->tipo_pago == 1){
		$id = "";
		$id = $tipo_cobro->idTipoPago;
		$validar =  ControladorTipoPago::ctrTipoPagoById( $tipo_cobro );
		if($validar["efectivo"]==1 AND $tipo_cobro->tipo_pago == 1)
		{
			if( ($validar["nombre"] != $tipo_cobro->nombre) || ($validar["simbolo"] != $tipo_cobro->simbolo) || ($validar["orden"] != $tipo_cobro->orden) )
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
			return ModeloTipoPago::mdlEditarTipoPago( $tabla , $tipo_cobro );
		}
		else
		{
			$respo = "Solamente se permite un tipo de cobro marcado como efectivo";
			return $respo;
		}
	}
	else{		
		return ModeloTipoPago::mdlEditarTipoPago( $tabla , $tipo_cobro );
	}	

}
static public function ctrEliminarTipoPago($tipo_cobro)
{
	$tabla = "tipo_cobro";		
	return ModeloTipoPago::mdlEliminarTipoPago( $tabla , $tipo_cobro );

}

	static public function ctrEditarNotaInicial( $obj )
	{
		return ModeloTipoPago::mdlEditarNotaInicial( $obj );

	}
}
