<?php
class ControladorDashboard
{

	/*=============================================
	=           INDICADORES DASHBOARD             =
	===============================================*/

	static public function ctrConsultasDashboard( $id_sesion_caja )
	{
		return ModeloDashboard::mdlConsultasDashboard( $id_sesion_caja );
	}

	/*=============================================
	=           INDICADORES DASHBOARD             =
	===============================================*/

	static public function ctrConsultasDashboardFecha( )
	{
		return ModeloDashboard::mdlConsultasDashboardFecha( );
	}

	/*=============================================
	=           INDICADORES AMORTIZACIONES        =
	===============================================*/

	static public function ctrConsultasDashboardAmortizaciones( $id_sesion_caja )
	{
		return ModeloDashboard::mdlConsultasDashboardAmortizaciones( $id_sesion_caja );

	}

	/*=============================================
	=           INDICADORES FLUJO        =
	===============================================*/

	static public function ctrConsultasDashboardFlujo( $id_sesion_caja )
	{
		return ModeloDashboard::mdlConsultasDashboardFlujo( $id_sesion_caja );

	}

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrConsultaRankingProductos( $id_sesion_caja )
	{
		return ModeloDashboard::mdlConsultasRankingProductos( $id_sesion_caja );

	}

	/*=============================================
	=           INDICADORES RANKING CATEGORIAS     =
	===============================================*/
	static public function ctrConsultaRankingCategorias( $id_sesion_caja )
	{
		return ModeloDashboard::mdlConsultasRankingCategorias( $id_sesion_caja );

	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrConsultaRankingClientes( $id_sesion_caja )
	{
		return ModeloDashboard::mdlConsultasRankingClientes( $id_sesion_caja );

	}

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrConsultaRankingProductosHistorico( )
	{
		return ModeloDashboard::mdlConsultasRankingProductosHistorico( );

	}

	/*=============================================
	=           INDICADORES RANKING CATEGORIAS     =
	===============================================*/
	static public function ctrConsultaRankingCategoriasHistorico( )
	{
		return ModeloDashboard::mdlConsultasRankingCategoriasHistorico( );

	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrConsultaRankingClientesHistorico( )
	{
		return ModeloDashboard::mdlConsultasRankingClientesHistorico( );

	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrVentasDoceMeses( )
	{
		$fecha_actual = new Datetime(); 
		$mes = 0;
		$fecha_modificada = $fecha_actual->modify("-12 months");
		$fecha_modificada = $fecha_modificada->modify("last day of this month");
		$mes_actual =$fecha_modificada->format("Y-m-d");
		$ventas = "[";
		$fechas = "[";
		$cobros = "[";
		$deudas = "[";
	 	//echo $mes_actual;echo "\n";
		while($mes < 12){
			//Se obtiene el mes de la primera fecha
			$fecha_modificada = $fecha_actual->modify("last day of next month");
			//$fecha_modificada = $fecha_actual->modify("- ".$mes." MONTHS");
			$mes_actual =$fecha_modificada->format("m");
			$mes_letras =$fecha_modificada->format("M");
			$year =$fecha_modificada->format("Y");
			$ventas.="'".ModeloDashboard::mdlVentasDoceMeses( $mes_actual, $year )."',";
			$cobros.="'".ModeloDashboard::mdlCobrosDoceMeses( $mes_actual, $year )."',";
			$deudas.="'".ModeloDashboard::mdlDeudasDoceMeses( $mes_actual, $year )."',";
			$fechas.="'".(string)$mes_letras."-".(string)$year."',";	
			$mes++;		
		}
		$ventas = substr($ventas, 0, -1);
		$ventas.= "]";
		$cobros = substr($cobros, 0, -1);
		$cobros.= "]";
		$deudas = substr($deudas, 0, -1);
		$deudas.= "]";
		$fechas = substr($fechas, 0, -1);
		$fechas.= "]";
		$return = ["ventas"=> $ventas, "fechas" =>$fechas, "cobros" => $cobros, "deudas" => $deudas];
		return $return;

	}
	/*=============================================
	=           INDICADORES DASHBOARD             =
	===============================================*/

	static public function ctrConsultasIndicadoresHistorico( )
	{
		return ModeloDashboard::mdlConsultasIndicadoresHistorico( );
	}

	/*=============================================
	=           INDICADORES AMORTIZACIONES        =
	===============================================*/

	static public function ctrHistoricoAmortizaciones( )
	{
		return ModeloDashboard::mdlHistoricoAmortizaciones( );

	}

	/*=============================================
	=           INDICADORES COMPROBANTES        =
	===============================================*/

	static public function ctrEtlComprobantes( $dashboard )
	{
		$comprobantes =  ModeloSincronizacion::mdlEtlComprobantes( $dashboard );
		if(!$comprobantes){
			return array(array(
				"total_factura" => '0.00',
				"total_boleta"  => '0.00',
				"total_ticket"  => '0.00')
			);
		}
		else
		{
			return $comprobantes;
		}

	}

	/*=============================================
	=	           	VENTAS DOCE MESES     		=
	===============================================*/
	static public function ctrEtlVentasDoceMeses( )
	{
		$doce_meses = ModeloSincronizacion::mdlEtlVentasDoceMeses( );
		if(!$doce_meses){
			return array(
			"fechas" => '0000-00-00',	
			"ventas" => '0.00',
			"cobros" => '0.00',
			"deudas" => '0.00'
			);
		}
		else
		{
			
		$ventas = "[";
		$fechas = "[";
		$cobros = "[";
		$deudas = "[";
		foreach($doce_meses as $mes)
		{
			$fechas .= "'".$mes['fechas']."',";
			$ventas .= "'".$mes['ventas']."',";
			$cobros .= "'".$mes['cobros']."',";
			$deudas .= "'".$mes['deudas']."',";
		}

		$ventas = substr($ventas, 0, -1);
		$ventas.= "]";
		$cobros = substr($cobros, 0, -1);
		$cobros.= "]";
		$deudas = substr($deudas, 0, -1);
		$deudas.= "]";
		$fechas = substr($fechas, 0, -1);
		$fechas.= "]";
		$return = ["ventas"=> $ventas, "fechas" =>$fechas, "cobros" => $cobros, "deudas" => $deudas];

		return $return;
		}

	}

	/*=============================================
	=           INDICADORES INDICADORES		     =
	===============================================*/
	static public function ctrEtlIndicadores( )
	{
		$indicadores = ModeloSincronizacion::mdlEtlIndicadores( );
		if(!$indicadores){
			return array(
			"fechas" => '0',	
			"cantidad_ventas" => '0.00',
			"total_ventas" => '0.00',
			"cantidad_anulados" => '0.00',
			"total_anulados" => '0.00',
			"cantidad_clientes" => '0.00',
			"total_compras" => '0.00',
			"total_ingresos" => '0.00',
			"total_egresos" => '0.00'
			);
		}
		else
		{
			return $indicadores[0];
		}

	}
	static public function ctrEtlIndicadoresFecha( )
	{
		$indicadores = ModeloSincronizacion::mdlEtlIndicadoresFecha( );
		if(!$indicadores){
			return array(
			"fechas" => '0',	
			"cantidad_ventas" => '0.00',
			"total_ventas" => '0.00',
			"cantidad_anulados" => '0.00',
			"total_anulados" => '0.00',
			"cantidad_clientes" => '0.00',
			"total_compras" => '0.00',
			"total_ingresos" => '0.00',
			"total_egresos" => '0.00'
			);
		}
		else
		{
			return $indicadores[0];
		}
	}
	static public function ctrEtlIndicadoresLocalFecha( $idLocal )
	{
		$indicadores = ModeloSincronizacion::mdlEtlIndicadoresLocalFecha( $idLocal );
		if(!$indicadores){
			return array(
			"fechas" => '0000-00-00',	
			"cantidad_ventas" => '0.00',
			"total_ventas" => '0.00',
			"cantidad_anulados" => '0.00',
			"total_anulados" => '0.00',
			"cantidad_clientes" => '0.00',
			"total_compras" => '0.00',
			"total_ingresos" => '0.00',
			"total_egresos" => '0.00'
			);
		}
		else
		{
			return $indicadores[0];
		}
	}
	/*=============================================
	=           INDICADORES METODOS PAGO	     =
	===============================================*/
	static public function ctrEtlMetodosPago( $dashboard )
	{
		$metodos_pago = ModeloSincronizacion::mdlEtlMetodosPago( $dashboard );
		if(!$metodos_pago){
			return array(array(
				"tipo_cobro" => '-',
				"total"  => '0.00')
			);
		}
		else
		{
			return $metodos_pago;
		}

	}

	/*=============================================
	=           INDICADORES RANKING CATEGORIAS     =
	===============================================*/
	static public function ctrEtlRankingCategorias( )
	{
		$categorias = ModeloSincronizacion::mdlEtlRankingCategorias( );
		return $categorias;
	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlRankingClientes( $id_local , $nombre_local )
	{
		return ModeloSincronizacion::mdlEtlRankingClientes( $id_local , $nombre_local );

	}

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrEtlRankingProductos( )
	{
		return ModeloSincronizacion::mdlEtlRankingProductos( );

	}

	/*=============================================
	=           INDICADORES RANKING CATEGORIAS     =
	===============================================*/
	static public function ctrEtlRankingCategoriasFecha( )
	{
		$categorias = ModeloSincronizacion::mdlEtlRankingCategoriasFecha( );
		return $categorias;
	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlRankingClientesFecha( )
	{
		return ModeloSincronizacion::mdlEtlRankingClientesFecha( );

	}

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrEtlRankingProductosFecha( )
	{
		return ModeloSincronizacion::mdlEtlRankingProductosFecha( );

	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlComprobanteFecha( )
	{
		return ModeloSincronizacion::mdlPieVentasComprobantesFecha( );

	}
	

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrEtlMetodosPagoFecha( )
	{
		return ModeloSincronizacion::mdlPieVentasTipoCobroFecha( );

	}

	/*=============================================
	=           INDICADORES RANKING CATEGORIAS     =
	===============================================*/
	static public function ctrEtlRankingCategoriasLocalFecha($idLocal )
	{
		$categorias = ModeloSincronizacion::mdlEtlRankingCategoriasLocalFecha( $idLocal);
		return $categorias;
	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlRankingClientesLocalFecha($idLocal )
	{
		return ModeloSincronizacion::mdlEtlRankingClientesLocalFecha( $idLocal);

	}

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrEtlRankingProductosLocalFecha($idLocal )
	{
		return ModeloSincronizacion::mdlEtlRankingProductosLocalFecha( $idLocal);

	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlComprobanteLocalFecha($idLocal )
	{
		return ModeloSincronizacion::mdlPieVentasComprobantesLocalFecha( $idLocal);

	}
	

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrEtlMetodosPagoLocalFecha($idLocal )
	{
		$return = ModeloSincronizacion::mdlPieVentasTipoCobroLocalFecha( $idLocal);
		return $return;

	}

	/*=============================================
	=         SINCRONIZAR DASHBOARD     =
	===============================================*/
	static public function ctrSincronizarDashboard( $data )
	{
		//file_put_contents("111.txt", json_encode( $data ));
		try{
			
			$dashboard =  $data['listadoDashboard'];
			
			$indicadores       = $dashboard['indicadores'];
			$comprobantes      = $dashboard['comprobantes'];
			$metodoPago        = $dashboard['metodoPago'];
			$rankingProductos  = $dashboard['rankingProductos'];
			$rankingCategorias = $dashboard['rankingCategorias'];
			$rankingClientes   = $dashboard['rankingClientes'];
			$doce_meses        = $dashboard['doce_meses'];
			$datos_local       = $dashboard['datos_local'];
			$inventarios       = $dashboard['inventarios'];
			//Se inicializa en el modelo con valor 1 en caso de que no se pase la variable por parámetro de la función
			ModeloSincronizacion::mdlLimpiarEtl( $datos_local[0]['id'], $datos_local[0]['nombre_comercial'] );
			$ids = [];
			if(count($inventarios))
			{
				//$ids = ModeloSincronizacion::mdlEtlBulkInventario( $datos_local[0]['id'], $inventarios, $datos_local[0]['nombre_comercial'] );
				foreach($inventarios as $inventario){
					ModeloSincronizacion::mdlEtlInventario( $datos_local[0]['id'], $inventario, $datos_local[0]['nombre_comercial'] );
					$id = array("id_inventario" => $inventario['inventario_id']);
					array_push($ids, $id);
				}
			}
			else{
				$ids = [];
			}
			ModeloSincronizacion::mdlIndicadores( $indicadores, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'] );
			ModeloSincronizacion::mdlComprobantes( $comprobantes, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'] );
			ModeloSincronizacion::mdlDatosLocal( $datos_local[0]['id'], $datos_local[0]['nombre_comercial'] );

			foreach ($metodoPago as $key => $value) {
				ModeloSincronizacion::mdlMetodoPago( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $key );
			}
			foreach ($rankingProductos as $key => $value) {
				//file_put_contents( "111mdlRnkPrdt.txt", json_encode($value) );

				ModeloSincronizacion::mdlRankingProductos( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $key );
			}
			foreach ($rankingCategorias as $key => $value) {
				ModeloSincronizacion::mdlRankingCategorias( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $key );
			}
			foreach ($rankingClientes as $key => $value) {
				ModeloSincronizacion::mdlRankingClientes( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $key );
			}
			file_put_contents("1mdlEtlSincVentasDoceMeses.txt", json_encode($doce_meses) );
			foreach ($doce_meses as $key => $value) {
				ModeloSincronizacion::mdlEtlSincVentasDoceMeses(  $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $value, $value['mes'] );
			}
			//foreach ($inventarios as $key => $value) {
			//}
			$local = array("error"=> 0,"local" => $datos_local[0]['id'] , "inventarios" => $ids);
			//file_put_contents("111.txt", json_encode( $ids ));
			return json_encode($local);
		}catch (Exception $th){

			file_put_contents("./Error Sincronizacion.txt", $th->getMessage());
			return array("error"=> 1,"mensaje"=>$th->getMessage());
		}
	}

	/*=============================================
	=         SINCRONIZAR DASHBOARD     =
	===============================================*/
	static public function ctrSincronizarDashboardFecha( $data )
	{
		try{
			//file_put_contents("dashboard.txt",json_encode( $data ));
			
			$dashboard =  $data['listadoDashboard'];
			
			$indicadores       = $dashboard['indicadores'];
			$comprobantes      = $dashboard['comprobantes'];
			$metodoPago        = $dashboard['metodoPago'];
			$rankingProductos  = $dashboard['rankingProductos'];
			$rankingCategorias = $dashboard['rankingCategorias'];
			$rankingClientes   = $dashboard['rankingClientes'];
			$datos_local       = $dashboard['datos_local'];
			$fecha             = $indicadores['fecha'];
			//Se inicializa en el modelo con valor 2 en caso de que no se pase la variable por parámetro de la función
			ModeloSincronizacion::mdlLimpiarEtlFecha( $datos_local[0]['id'], $datos_local[0]['nombre_comercial'] );
			ModeloSincronizacion::mdlIndicadoresFecha( $indicadores, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'] , $fecha);
			ModeloSincronizacion::mdlComprobantesFecha( $comprobantes, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $fecha );
			//ModeloSincronizacion::mdlDatosLocalFecha( $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $fecha );

			foreach ($metodoPago as $key => $value) {
				ModeloSincronizacion::mdlMetodoPagoFecha( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $fecha );
			}
			foreach ($rankingProductos as $key => $value) {
				ModeloSincronizacion::mdlRankingProductosFecha( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $fecha );
			}
			foreach ($rankingCategorias as $key => $value) {
				ModeloSincronizacion::mdlRankingCategoriasFecha( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $fecha );
			}
			foreach ($rankingClientes as $key => $value) {
				ModeloSincronizacion::mdlRankingClientesFecha( $value, $datos_local[0]['id'], $datos_local[0]['nombre_comercial'], $fecha );
			}
			//foreach ($inventarios as $key => $value) {
			//}
			//$local = array("error"=> 0,"local" => $datos_local[0]['id'] , "inventarios" => $ids);
			
			return array("error"=> 0,"mensaje"=>"ok");
		}catch (Exception $th){

			//file_put_contents("./Error Sincronizacion.txt", $th->getMessage());
			return array("error"=> 1,"mensaje"=>$th->getMessage());
		}
	}
	/*=============================================
	=           INDICADORES RANKING CATEGORIAS     =
	===============================================*/
	static public function ctrListarLocalesEtl( )
	{
		$categorias = ModeloDashboard::mdlListarLocalesEtl( );
		return $categorias;
	}

	/*=============================================
	=           INDICADORES INDICADORES		     =
	===============================================*/
	static public function ctrEtlIndicadoresLocal( $local,$nombre_local )
	{
		$indicadores = ModeloSincronizacion::mdlEtlIndicadoresLocal( $local,$nombre_local );
		if(!$indicadores){
			return array(
			"fechas" => '0',	
			"cantidad_ventas" => '0.00',
			"total_ventas" => '0.00',
			"cantidad_anulados" => '0.00',
			"total_anulados" => '0.00',
			"cantidad_clientes" => '0.00',
			"total_compras" => '0.00',
			"total_ingresos" => '0.00',
			"total_egresos" => '0.00'
			);
		}
		else
		{
			return $indicadores[0];
		}
	}

	/*=============================================
	=           INDICADORES RANKING PRODUCTOS     =
	===============================================*/
	static public function ctrEtlRankingProductosLocal( $local,$nombre_local )
	{
		return ModeloSincronizacion::mdlEtlRankingProductosLocal( $local,$nombre_local );
	}
	/*=============================================
	=           INDICADORES RANKING CATEGORIAS     =
	===============================================*/
	static public function ctrEtlRankingCategoriasLocal( $local,$nombre_local )
	{
		return ModeloSincronizacion::mdlEtlRankingCategoriasLocal( $local,$nombre_local );
	}
	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlRankingClientesLocal( $local,$nombre_local )
	{
		return ModeloSincronizacion::mdlEtlRankingClientesLocal( $local,$nombre_local );
	}
	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlVentasDoceMesesLocal( $local,$nombre_local )
	{
		return ModeloSincronizacion::mdlEtlVentasDoceMesesLocal( $local,$nombre_local );
	}
	static public function ctrEtlVentasDoceMesesLocalFecha( $local,$nombre_local )
	{
		$doce_meses= ModeloSincronizacion::mdlEtlVentasDoceMesesLocalFecha( $local,$nombre_local );
		if(!$doce_meses){
			return array(
			"fechas" => '0000-00-00',	
			"ventas" => '0.00',
			"cobros" => '0.00',
			"deudas" => '0.00'
			);
		}
		else
		{
			
			$ventas = "[";
			$fechas = "[";
			$cobros = "[";
			$deudas = "[";
			foreach($doce_meses as $mes)
			{
				$fechas .= "'".$mes['fechas']."',";
				$ventas .= "'".$mes['ventas']."',";
				$cobros .= "'".$mes['cobros']."',";
				$deudas .= "'".$mes['deudas']."',";
			}

			$ventas = substr($ventas, 0, -1);
			$ventas.= "]";
			$cobros = substr($cobros, 0, -1);
			$cobros.= "]";
			$deudas = substr($deudas, 0, -1);
			$deudas.= "]";
			$fechas = substr($fechas, 0, -1);
			$fechas.= "]";
			$return = ["ventas"=> $ventas, "fechas" =>$fechas, "cobros" => $cobros, "deudas" => $deudas];

			return $return;
		}
	}

	/*=============================================
	=           INDICADORES COMPROBANTES        =
	===============================================*/

	static public function ctrEtlComprobantesLocal( $local,$nombre_local )
	{
		$comprobantes =  ModeloSincronizacion::mdlEtlComprobantesLocal( $local,$nombre_local );
		if(!$comprobantes){
			return array(array(
				"total_factura" => '0.00',
				"total_boleta"  => '0.00',
				"total_ticket"  => '0.00')
			);
		}
		else
		{
			return $comprobantes;
		}

	}

	/*=============================================
	=           INDICADORES METODOS PAGO	     =
	===============================================*/
	static public function ctrEtlMetodosPagoLocal( $local,$nombre_local )
	{
		$metodos_pago = ModeloSincronizacion::mdlEtlMetodosPagoLocal( $local,$nombre_local );
		if(!$metodos_pago){
			return array(array(
				"tipo_cobro" => '-',
				"total"  => '0.00')
			);
		}
		else
		{
			return $metodos_pago;
		}
	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlMostrarInventario( $id_local, $id_almacen, $nombre_almacen )
	{
		return ModeloSincronizacion::mdlEtlMostrarInventario( $id_local, $id_almacen, $nombre_almacen );
	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrEtlMostrarInventarioxAlmacen( $id_local, $id_almacen )
	{
		return ModeloSincronizacion::mdlEtlMostrarInventarioxAlmacen( $id_local, $id_almacen );
	}

	/*=============================================
	=           INDICADORES INVENTARIO ALMACÉN    =
	===============================================*/
	static public function ctrIndicadoresInventarioAlmacen( $indicadores )
	{
		$return= ModeloSincronizacion::mdlIndicadoresInventarioAlmacen( $indicadores );
		return  $return;
	}

	/*=============================================
	=           INDICADORES INVENTARIO ALMACÉN    =
	===============================================*/
	static public function ctrAgregarProductoInventario( $inventario )
	{
		$datos_json = array(
		"id_local"         => $inventario->id_local,
		"nombre_local"     => $inventario->nombre_local,
		"id_almacen"       => $inventario->id_almacen,
		"id_inventario"    => $inventario->id_inventario,
		"nuevaDescripcion" => $inventario->nuevaDescripcion,
		"Precio"           => $inventario->Precio,
		"id_inventario"    => $inventario->id_inventario,
		"Nota"             => $inventario->Nota,
		"Cantidad"         => $inventario->Cantidad);
		$return= ModeloSincronizacion::mdlAgregarProductoInventario( $datos_json, $inventario->id_local );
		return  $return;
	}

	static public function ctrDisminuirProductoInventario( $inventario )
	{
		$datos_json = array(
		"id_local"          => $inventario->id_local,
		"nombre_local"      => $inventario->nombre_local,
		"id_almacen"        => $inventario->id_almacen,
		"id_almacen_origen" => $inventario->id_almacen_origen,
		"id_inventario"     => $inventario->id_inventario,
		"nuevaDescripcion"  => $inventario->nuevaDescripcion,
		"Precio"            => 0,
		"id_inventario"     => $inventario->id_inventario,
		"Nota"              => $inventario->Nota,
		"Cantidad"          => $inventario->Cantidad);
		$return= ModeloSincronizacion::mdlDisminuirProductoInventario( $datos_json, $inventario->id_local );
		return  $return;
	}
	static public function ctrTransferirProductoInventario( $inventario )
	{
		$datos_json = array(
		"id_local"         => $inventario->id_local,
		"nombre_local"     => $inventario->nombre_local,
		"id_almacen"       => $inventario->id_almacen,
		"almacenDestino"   => $inventario->almacenDestino,
		"id_inventario"    => $inventario->id_inventario,
		"nuevaDescripcion" => $inventario->nombreInventario,
		"Precio"           => ($inventario->Precio * $inventario->Cantidad),
		"Nota"             => $inventario->Nota,
		"Cantidad"         => $inventario->Cantidad);
		
		$return= ModeloSincronizacion::mdlTransferirProductoInventario( $datos_json, $inventario->id_local );
		
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $valor1b = $fecha.' '.$hora;
        $datos  = array( "fecha" => $valor1b );
		
        $listaProductos = array(
            'id_inventario'     => $inventario->id_inventario,
            'id_producto'       => $inventario->id_producto,
            'nombre_producto'   => $inventario->nombreInventario,
            'cantidad_producto' => $inventario->Cantidad,
            'Precio'            => ($inventario->Precio * $inventario->Cantidad)
        );

        $respuesta_ing_inv = ModeloProcesos::mdlDisminuirInventario( $datos, $listaProductos );
		return  $return;
	}

	static public function ctrTransferirProductoInventarioPrincipal( $inventario )
	{	
		
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $valor1b = $fecha.' '.$hora;
        $datos  = array( "fecha" => $valor1b );
		
        $listaProductos = array(
            'id_inventario'     => $inventario->id_inventario,
            'id_producto'       => $inventario->id_producto,
            'nombre_producto'   => $inventario->nombreInventario,
            'cantidad_producto' => $inventario->Cantidad,
            'Precio'            => ($inventario->Precio * $inventario->Cantidad)
		);
		$act_cantidad = ModeloProcesos::mdlBuscarCantInventario ($inventario->id_inventario);

		$tiene_inventario = ($act_cantidad[0]['actual_cantidad'] >= $inventario->Cantidad) ? True: False;
		
		if($tiene_inventario)
		{
			$respuesta_ing_inv = ModeloProcesos::mdlDisminuirInventario( $datos, $listaProductos );
			
			$ruta = $inventario->url_reportes_online."/ajax/sincronizacion.api.php";
			$data = array(
			"accion"           => "TransferirProductoInventarioPrincipal",
			"id_local"         => $inventario -> id_local,
			"nombre_local"     => $inventario -> nombre_local,
			"id_almacen"       => $inventario -> id_almacen,
			"id_producto"      => $inventario -> id_producto,
			"almacenOrigen"    => $inventario -> almacenOrigen,
			"almacenDestino"   => $inventario -> almacenDestino,
			"id_inventario"    => $inventario -> id_inventario,
			"nuevaDescripcion" => $inventario -> nombreInventario,
			"Precio"           => ($inventario -> Precio * $inventario -> Cantidad),
			"Nota"             => $inventario -> Nota,
			"Cantidad"         => $inventario -> Cantidad);
			$data_json = json_encode( $data );
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ruta);
			curl_setopt(
				$ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				)
			);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta  = curl_exec($ch);
			curl_close($ch);
			$rep = json_decode($respuesta,true);
			file_put_contents("111ctrTransferirProductoInventarioPrincipal.txt", $respuesta);	
			if(isset($rep["error"]) AND $rep["error"]== 0)
			{
				return "ok";
			}
			else
			{
				return "error";
			}
		}
		else{
			return "No existe disponibilidad solicitada en el inventario";

		}
		
		//$return= ModeloSincronizacion::mdlTransferirProductoInventarioPrincipal( $datos_json, $inventario->id_local );
	}
	static public function ctrTransferirProductoInventarioPrincipalSync( $inventario )
	{
		//$inventario = json_decode( $data , true);
		ModeloSincronizacion::mdlTransferirProductoInventarioPrincipalSync( $inventario, $inventario['id_local'] );
		return ;
	}
	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrMostrarProcesos( $id_local, $id_almacen )
	{
		return ModeloSincronizacion::mdlMostrarProcesos( $id_local, $id_almacen );
	}

	/*=============================================
	=           INDICADORES RANKING CLIENTES     =
	===============================================*/
	static public function ctrSincronizarInventario( $id_local, $nombre_local )
	{
		return ModeloSincronizacion::mdlMostrarSincronizacion( $id_local, $nombre_local );
	}
	static public function ctrSincronizaProcesos( $id_local, $nombre_local )
	{
		return ModeloSincronizacion::mdlGetListProcesos( $id_local, $nombre_local );
	}
	static public function ctrListarLocalesEtlById( )
	{
		return ModeloSincronizacion::mdlListarLocalesEtlById( );
	}

	/*=============================================
	=      ACTUALIZAR INVENTARIO SINCRONIZADO     =
	===============================================*/
	static public function ctrActualizarEstadoSincronizacion( $id_local, $data )
	{
		$inventario = json_decode( $data['data'] , true);
		foreach ($inventario as $key => $value) {
			ModeloSincronizacion::mdlActualizarEstadoSincronizacion( $value['id_local'], $value['nombre_local'], $value['id'] );
		}
		return ;
	}
	static public function enviarBackup( $datos_local,$datos_ftp ){
		//print_r("111ex.txt",json_encode($datos_ftp));
		//print_r("111datos_local.txt",json_encode($datos_local));
		//shell_exec("python ".$ruta);
		//buscar la carpeta
		$ruta = "../content-download/backup_bd/".$datos_local['id']."_".$datos_local['identificador_local'];
        //$folder = scandir($ruta);
		//modificar sus permisos para lectura y escritura
        //chmod($ruta.$file,0755);
		//Se busca la carpeta, sino existe se crea de acuerdo al id del local y el identificador del local

		//if(!is_dir($ruta))
		//	mkdir($ruta, 0755);
		//se almacenan los parámetros de conexión y de la tienda para hacer el backup de la bd
		$parametros_local = array(
			"ruta"                => $ruta,
			"id_local"            => $datos_local['id'],
			"identificador_local" => $datos_local['identificador_local'],
			"datos_local"         => $datos_local,
			"datos_ftp"           => $datos_ftp
		);
		file_put_contents("../content-download/backup_bd/datos_local.json", json_encode($parametros_local));

		shell_exec("python ../content-download/backup_bd/main.py");
	}

}