<?php
class ControladorTipoCobro {



static public function ctrMostrarTipoCobro(){

		$tabla = "tipo_cobro";

		$respuesta = ModeloTipoCobro::mdlMostrarTipoCobro($tabla );

		return   $respuesta;

}
static public function ctrListaTipoCobro(){

		$tabla = "tipo_cobro";

		$respuesta = ModeloTipoCobro::mdlListaTipoCobro($tabla );

		return   $respuesta;

}
static public function ctrConvertirDataYMD($fecha){

	date_default_timezone_set('America/Bogota');
	return date("Y-m-d",strtotime($fecha));

}
static public function ctrValidarEfectivo(){
	$tabla = "tipo_cobro";
	$response = ModeloTipoCobro::mdlValidarEfectivo($tabla);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrTipoCobroById($tipo_cobro){
	$tabla = "tipo_cobro";
	$response = ModeloTipoCobro::mdlTipoCobroById($tipo_cobro);
	$ixx = 0;
	return $response[$ixx];
}

static public function ctrMostrarTipoCobroTabla(){

	$tabla = "tipo_cobro";

	$respuesta = ModeloTipoCobro::mdlMostrarTipoCobroTabla($tabla );

	return   $respuesta;

}

static public function ctrMostrarTipoCobroEfectivo(){

		$tabla = "tipo_cobro";

		$respuesta = ModeloTipoCobro::mdlMostrarTipoCobroEfectivo($tabla );

	 
		return $respuesta;


}
static public function ctrAgregarTipoCobro($tipo_cobro)
{
	$tabla = "tipo_cobro";
	if($tipo_cobro->tipo_pago == 1){
		$validar =  ControladorTipoCobro::ctrValidarEfectivo();
		if($validar["efectivo"]<1)
		{
			return ModeloTipoCobro::mdlAgregarTipoCobro( $tabla , $tipo_cobro );
		}
		else
		{
			$respo = "SOLAMENTE SE PERMITE UN TIPO DE COBRO MARCADO COMO EFECTIVO";
			return $respo;
		}
	}
	else{		
		return ModeloTipoCobro::mdlAgregarTipoCobro( $tabla , $tipo_cobro );
	}

}

static public function ctrEditarTipoCobro($tipo_cobro)
{
	$tabla = "tipo_cobro";
	if($tipo_cobro->tipo_pago == 1){
		$id = "";
		$id = $tipo_cobro->idTipoCobro;
		$validar =  ControladorTipoCobro::ctrTipoCobroById( $tipo_cobro );
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
			return ModeloTipoCobro::mdlEditarTipoCobro( $tabla , $tipo_cobro );
		}
		else
		{
			$respo = "Solamente se permite un tipo de cobro marcado como efectivo";
			return $respo;
		}
	}
	else{		
		return ModeloTipoCobro::mdlEditarTipoCobro( $tabla , $tipo_cobro );
	}	

}
static public function ctrEliminarTipoCobro($tipo_cobro)
{
	$tabla = "tipo_cobro";		
	return ModeloTipoCobro::mdlEliminarTipoCobro( $tabla , $tipo_cobro );

}

	static public function ctrEditarNotaInicial( $obj )
	{
		return ModeloTipoCobro::mdlEditarNotaInicial( $obj );

	}
}
