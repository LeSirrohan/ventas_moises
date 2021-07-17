<?php

require_once "../controladores/usuario.controlador.php";
require_once "../modelos/usuario.modelo.php";


class AjaxUsuarios{
	/*=============================================
	=            EDITAR USUARIO            =
	=============================================*/

	public $idUsuario;
	public $estadoUsuarioActivar;
	public $idUsuarioActivar;

 	public function ajaxEditarUsuario(){
		$item = "codusuario";
		$valor = $this->idUsuario; 
		$respuesta = ControladorUsuarios::ctrMostrarUsuario($item , $valor );

		echo json_encode($respuesta);
	}
	
	
		/*=============================================
	=            ACTUALIZAR ESTADO USUARIO            =
	=============================================*/


	 public function ajaxActivarUsuario(){

		$tabla  = "usuario" ; 
		$item1 = "estado";
		$valor1 = -1 ; 
		$valor2 = -1 ;

		if($this->estadoUsuarioActivar == 0    )
			$valor1 = 1;
		else
			$valor1 = 0;

		$item2 = "id";
		$valor2 =$this->idUsuarioActivar  ;

		$respuesta = ModeloUsuarios::mdlActualizarUsuario($tabla , $item1 , $valor1, $item2, $valor2 );


		echo json_encode($respuesta);
	}

	/*=============================================
	VALIDAR NO REPETIR USUARIO
	=============================================*/	

	public $validarUsuario;

	 public function ajaxValidarUsuario(){

		$item = "usuario";
		$valor = $this->validarUsuario;

		$respuesta = ControladorUsuarios::ctrMostrarUsuario($item, $valor );

		echo json_encode($respuesta);

	}

	/*=============================================
			RANKING DE VENDEDORES
	=============================================*/	

	 public function ajaxRankingVendedoresReportes(){

		$respuesta = ControladorUsuarios::ctrRankingVendedoresReportes();

		echo $respuesta;

	}


	/*=============================================
					LISTAR USUARIOS
	=============================================*/	
	public function ajaxListarUsuario(){
		$respuesta = ControladorUsuarios::ctrListarUsuario();
		echo json_encode($respuesta);
		exit;
	}
}


	/*=============================================
	=            EDITAR USUARIO            =
	=============================================*/

if ( isset($_POST["idUsuario"]) ) {
	$editar = new AjaxUsuarios();
	$editar -> idUsuario = $_POST["idUsuario"];
	$editar -> ajaxEditarUsuario();
};

	/*=============================================
	=            ACTUALIZAR ESTADO USUARIO        =
	=============================================*/	

if(isset($_POST["idUsuarioActivar"])){
	$activarUsuario = new AjaxUsuarios();
	$activarUsuario -> idUsuarioActivar =  $_POST["idUsuarioActivar"];
	$activarUsuario -> estadoUsuarioActivar =  $_POST["estadoUsuarioActivar"];
	$activarUsuario ->ajaxActivarUsuario();
};


	/*=============================================
	VALIDAR NO REPETIR USUARIO
	=============================================*/	
if(isset($_POST["usuarioValidarRepetido"])){
	$valUsuario = new AjaxUsuarios();
	$valUsuario -> validarUsuario = $_POST["usuarioValidarRepetido"];
	$valUsuario -> ajaxValidarUsuario();
};

	/*=============================================
			Vendedor con Más Ventas
	=============================================*/	
if(isset($_POST["vendedorRanking"])){
	$valUsuario = new AjaxUsuarios();
	$valUsuario -> ajaxRankingVendedoresReportes();
};


/*=============================================
		LISTAR USUARIOS
=============================================*/	
if(isset($_POST["listarUsuarios"]) AND $_POST["listarUsuarios"]=="yes"){
	$usuario = new AjaxUsuarios();
	$usuario -> ajaxListarUsuario();
};
if(isset($_POST['accion']) AND $_POST["accion"]=="editar")
{
	print_r( $_POST );exit;
  $crearUsuario = new ControladorUsuarios();
  $crearUsuario -> ctrEditarUsuario();
}