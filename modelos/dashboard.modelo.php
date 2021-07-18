<?php

require_once "conexion.php";
/**
* 
*/
class ModeloDashboard{
	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasDashboard($id_sesion_caja)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		(SELECT COALESCE(count(ventas.id),0) FROM ventas WHERE ventas.anulado = '0' AND ventas.id_sesion_caja = :id_sesion_caja) cantidad_ventas,
		(SELECT COALESCE(SUM(ventas.total),0) FROM ventas WHERE ventas.anulado = '0' AND ventas.id_sesion_caja = :id_sesion_caja2) total_ventas,
		(SELECT COALESCE(count(ventas.id),0) FROM ventas WHERE ventas.anulado = '1' AND ventas.id_sesion_caja = :id_sesion_caja3) cantidad_anulados,
		(SELECT COALESCE(SUM(ventas.total),0) FROM ventas WHERE ventas.anulado = '1' AND ventas.id_sesion_caja = :id_sesion_caja4) total_anulados,
		(SELECT COALESCE( count( DISTINCT(cliente.id_documento) ), 0 ) FROM ventas INNER JOIN cliente ON ventas.id_documento_cliente = cliente.id_documento WHERE	ventas.id_sesion_caja = :id_sesion_caja9) cantidad_clientes,
		(SELECT COALESCE(count(compras.id),0) FROM compras  WHERE  compras.estado = 'Compra Procesada' AND compras.id_sesion_caja = :id_sesion_caja5) cantidad_compras,
		(SELECT COALESCE(SUM(compras.monto_total),0) FROM compras WHERE compras.estado = 'Compra Procesada' AND compras.id_sesion_caja = :id_sesion_caja6) total_compras,
		(SELECT COALESCE (SUM(monto), 0)  FROM otros_ingresos_egresos WHERE id_sesion_caja = :id_sesion_caja7 AND estado <> 'Anulado' AND tipo = 'Ingreso') total_ingresos,
		(SELECT COALESCE (SUM(monto), 0)  FROM otros_ingresos_egresos WHERE id_sesion_caja = :id_sesion_caja8 AND estado <> 'Anulado' AND tipo = 'Egreso') total_egresos
		");

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja2", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja3", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja4", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja5", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja6", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja7", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja8", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja9", $id_sesion_caja , PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt ->fetch(PDO::FETCH_ASSOC);

	}
	/*=============================================
			MOSTRAR HISTORICO INDICADORES
	=============================================*/
	static public function mdlConsultasIndicadoresHistorico()
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		(SELECT COALESCE(count(ventas.id),0) FROM ventas WHERE ventas.anulado = '0' ) cantidad_ventas,
		(SELECT COALESCE(SUM(ventas.total),0) FROM ventas WHERE ventas.anulado = '0' ) total_ventas,
		(SELECT COALESCE(count(ventas.id),0) FROM ventas WHERE ventas.anulado = '1' ) cantidad_anulados,
		(SELECT COALESCE(SUM(ventas.total),0) FROM ventas WHERE ventas.anulado = '1' ) total_anulados,
		(SELECT COALESCE(count(cliente.id_documento),0) FROM cliente ) cantidad_clientes,
		(SELECT COALESCE(count(compras.id),0) FROM compras  WHERE  compras.estado = 'Compra Procesada') cantidad_compras,
		(SELECT COALESCE(SUM(compras.monto_total),0) FROM compras WHERE compras.estado = 'Compra Procesada') total_compras,
		(SELECT COALESCE (SUM(monto), 0)  FROM otros_ingresos_egresos WHERE estado <> 'Anulado' AND tipo = 'Ingreso') total_ingresos,
		(SELECT COALESCE (SUM(monto), 0)  FROM otros_ingresos_egresos WHERE estado <> 'Anulado' AND tipo = 'Egreso') total_egresos
		");
		$stmt -> execute();

		return $stmt ->fetch(PDO::FETCH_ASSOC);

	}
	/*=============================================
					MOSTRAR HISTORICO AMORTIZACIONES
	=============================================*/
	static public function mdlHistoricoAmortizaciones()
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		(SELECT IFNULL(COUNT(vtc.id),0) monto_pendiente_abono
		FROM
			ventas v
			INNER JOIN ventas_x_tipo_cobro vtc ON v.id = vtc.id_ventas
			LEFT JOIN tipo_cobro tc ON vtc.id_tipo_cobro = tc.id
			LEFT JOIN deudas_amortizacion dv ON ( vtc.id = dv.id_venta_x_tipo_cobro ) 
		WHERE
			 tc.efectivo = '2' 
			AND v.anulado <> '1' 
			AND vtc.estado <> '2' 
			AND (dv.monto_pendiente_abono > 0 OR dv.monto_pendiente_abono is null)) deudas_pendientes,
		(SELECT
		SUM(IF(dv.monto_pendiente_abono is null,vtc.monto_cobro,dv.monto_pendiente_abono))
		FROM
			ventas v
			INNER JOIN ventas_x_tipo_cobro vtc ON v.id = vtc.id_ventas
			LEFT JOIN tipo_cobro tc ON vtc.id_tipo_cobro = tc.id
			LEFT JOIN deudas_amortizacion dv ON ( vtc.id = dv.id_venta_x_tipo_cobro ) 
		WHERE
			 tc.efectivo = '2' 
			AND v.anulado <> '1' 
			AND vtc.estado <> '2' 
			AND (dv.monto_pendiente_abono > 0 OR dv.monto_pendiente_abono is null)) monto_deudas_pendientes,
		(SELECT IFNULL(COUNT(dv.monto_pendiente_abono),0) deudas_amort from deudas_amortizacion dv WHERE dv.monto_pendiente_abono = 0) deudas_amort,
		(SELECT IFNULL( sum( dv.monto_abono ), 0 )   monto_abono_amort from deudas_amortizacion dv WHERE dv.monto_pendiente_abono = 0) monto_abono_amort
		
		");


		$stmt -> execute();

		return $stmt ->fetch(PDO::FETCH_ASSOC);

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasDashboardAmortizaciones($id_sesion_caja)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		(SELECT IFNULL(COUNT(vtc.id),0) monto_pendiente_abono
		FROM
			ventas v
			INNER JOIN ventas_x_tipo_cobro vtc ON v.id = vtc.id_ventas
			LEFT JOIN tipo_cobro tc ON vtc.id_tipo_cobro = tc.id
			LEFT JOIN deudas_amortizacion dv ON ( vtc.id = dv.id_venta_x_tipo_cobro ) 
		WHERE
			v.id_sesion_caja = :id_sesion_caja 
			AND tc.efectivo = '2' 
			AND v.anulado <> '1' 
			AND vtc.estado <> '2' 
			AND (dv.monto_pendiente_abono > 0 OR dv.monto_pendiente_abono is null)) deudas_pendientes,
		(SELECT
		SUM(IF(dv.monto_pendiente_abono is null,vtc.monto_cobro,dv.monto_pendiente_abono))
		FROM
			ventas v
			INNER JOIN ventas_x_tipo_cobro vtc ON v.id = vtc.id_ventas
			LEFT JOIN tipo_cobro tc ON vtc.id_tipo_cobro = tc.id
			LEFT JOIN deudas_amortizacion dv ON ( vtc.id = dv.id_venta_x_tipo_cobro ) 
		WHERE v.id_sesion_caja = :id_sesion_caja2 
			AND tc.efectivo = '2' 
			AND v.anulado <> '1' 
			AND vtc.estado <> '2' 
			AND (dv.monto_pendiente_abono > 0 OR dv.monto_pendiente_abono is null)) monto_deudas_pendientes,
		(SELECT IFNULL(COUNT(dv.monto_pendiente_abono),0) deudas_amort from deudas_amortizacion dv WHERE dv.id_sesion_caja = :id_sesion_caja3 AND dv.monto_pendiente_abono = 0) deudas_amort,
		(SELECT IFNULL( sum( dv.monto_abono ), 0 )   monto_abono_amort from deudas_amortizacion dv WHERE dv.id_sesion_caja = :id_sesion_caja4 AND dv.monto_pendiente_abono = 0) monto_abono_amort
		
		");

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja2", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja3", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja4", $id_sesion_caja , PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt ->fetch(PDO::FETCH_ASSOC);

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasDashboardFlujo($id_sesion_caja)
	{

		$Conn = Conexion::conectar();

		$stmt = $Conn->prepare("SELECT
			DATE_FORMAT(ventas.fecha_venta,'%Y-%m-%d') AS fecha
		FROM
			sesion_caja
			INNER JOIN ventas ON sesion_caja.id = ventas.id_sesion_caja
			
		UNION
			SELECT
			DATE_FORMAT(otros_ingresos_egresos.fecha,'%Y-%m-%d') AS fecha
		
		FROM
			sesion_caja
			INNER JOIN otros_ingresos_egresos ON sesion_caja.id = otros_ingresos_egresos.id_sesion_caja
			
		UNION
			SELECT
			DATE_FORMAT(compras.fecha,'%Y-%m-%d') AS fecha
		FROM
			sesion_caja
			INNER JOIN compras ON sesion_caja.id = compras.id_sesion_caja
			WHERE compras.estado = '1'
		ORDER BY fecha			
		");

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja2", $id_sesion_caja , PDO::PARAM_INT);
		$stmt->bindParam(":id_sesion_caja3", $id_sesion_caja , PDO::PARAM_INT);

		$stmt -> execute();

		$fechas =  $stmt ->fetchAll(PDO::FETCH_ASSOC);

		$totales = [];
		
		foreach ($fechas as $fecha){
			$sql_totales = "SELECT
			(SELECT COALESCE(SUM(ventas.total),0) from ventas WHERE ventas.anulado = '0' AND CAST(ventas.fecha_venta as date)  = :fecha1 ) total_venta_dia,
			(SELECT COALESCE(SUM(compras.monto_total),0) from compras WHERE compras.estado = '1' AND CAST(compras.fecha as date)  = :fecha2 ) total_compra_dia,
			(SELECT COALESCE (SUM(monto), 0)  from otros_ingresos_egresos WHERE CAST(otros_ingresos_egresos.fecha as date)  = :fecha3 AND estado <> 'Anulado' AND tipo = 'Ingreso') total_ingresos,
			(SELECT COALESCE (SUM(monto), 0)  from otros_ingresos_egresos WHERE CAST(otros_ingresos_egresos.fecha as date)  = :fecha4 AND estado <> 'Anulado' AND tipo = 'Egreso') total_egresos";
			
			$stmt = $Conn->prepare(" $sql_totales ");

			$stmt->bindParam(":fecha1", $fecha["fecha"] , PDO::PARAM_STR);
			$stmt->bindParam(":fecha2", $fecha["fecha"] , PDO::PARAM_STR);
			$stmt->bindParam(":fecha3", $fecha["fecha"] , PDO::PARAM_STR);
			$stmt->bindParam(":fecha4", $fecha["fecha"] , PDO::PARAM_STR);

			$stmt -> execute();
			$ret_fecha = $stmt ->fetch(PDO::FETCH_ASSOC);

			$data_fecha = array(
				"date"    => $fecha["fecha"],
				"ventas" => round($ret_fecha["total_venta_dia"],2),
				"compras" => round($ret_fecha["total_compra_dia"],2),
				"ingresos"  => round($ret_fecha["total_ingresos"],2),
				"gastos"  => round($ret_fecha["total_egresos"],2)
			);	
			array_push($totales, $data_fecha);
		}
		return $totales;
	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasRankingProductos($id_sesion_caja)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		VD.id_producto,
		VD.nombre_producto descripcion,
		VD.precio_venta_original precio_venta,
		SUM( VD.cantidad_producto ) cantidad,
		SUM( VD.comprobante_sub_total ) monto 
	FROM
		ventas V
		INNER JOIN ventas_detalle VD ON (V.id = VD.id_ventas)
	WHERE 
		V.id_sesion_caja = :id_sesion_caja AND V.anulado <> '1'
	GROUP by 
		VD.id_producto
	ORDER BY 
		monto DESC
	LIMIT 5
		");

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja , PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasRankingCategorias($id_sesion_caja)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		CAT.descripcion,
		SUM( VD.comprobante_sub_total ) monto 
	FROM
		ventas V
		INNER JOIN ventas_detalle VD ON ( V.id = VD.id_ventas )
		INNER JOIN producto P ON P.id = VD.id_producto
		INNER JOIN categoria CAT ON CAT.id = P.id_categoria
	WHERE
		V.id_sesion_caja = :id_sesion_caja AND V.anulado <> '1'
	GROUP by 
		P.id_categoria 
	ORDER BY 
		monto DESC
	LIMIT 5
		");

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja , PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasRankingClientes($id_sesion_caja)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		IFNULL(V.id_documento_cliente,'----') id_cliente,
		IFNULL(C.nombre_comercial,'----') nombre_comercial,
		SUM( V.total ) monto 
	FROM
		ventas V
		LEFT JOIN cliente C ON C.id_documento = V.id_documento_cliente 
	WHERE
		V.id_sesion_caja = :id_sesion_caja AND V.anulado <> '1'
	GROUP BY
		V.id_documento_cliente
	ORDER BY
		monto DESC 
	LIMIT 5
		");

		$stmt->bindParam(":id_sesion_caja", $id_sesion_caja , PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
				VENTAS ULTIMOS 12 MESES
	=============================================*/
	static public function mdlVentasDoceMeses($mes, $year)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
			COALESCE(sum(total),0) total
		FROM
			ventas 
		WHERE
			anulado = '0' 
			AND YEAR ( fecha_venta ) = :anio
			AND MONTH ( fecha_venta ) = :mes
		");

	$stmt->bindParam(":mes", $mes , PDO::PARAM_INT);
	$stmt->bindParam(":anio", $year , PDO::PARAM_INT);

		$stmt -> execute();

		$return = $stmt ->fetchAll(PDO::FETCH_ASSOC);
		return $return[0]["total"];

	}

	/*=============================================
				VENTAS ULTIMOS 12 MESES
	=============================================*/
	static public function mdlCobrosDoceMeses($mes, $year)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		sum( monto_cobro ) total 
	FROM
		ventas v
		INNER JOIN ventas_x_tipo_cobro vtc ON v.id = vtc.id_ventas
		INNER JOIN tipo_cobro tc ON tc.id = vtc.id_tipo_cobro 
	WHERE
		tc.efectivo = '2' 
		AND v.anulado <> '1' 
		AND vtc.estado <> '2' 
		AND YEAR ( v.fecha_venta ) = :anio AND MONTH(v.fecha_venta) = :mes
		");

	$stmt->bindParam(":mes", $mes , PDO::PARAM_INT);
	$stmt->bindParam(":anio", $year , PDO::PARAM_INT);

		$stmt -> execute();

		$return = $stmt ->fetchAll(PDO::FETCH_ASSOC);
		return $return[0]["total"];

	}

	/*=============================================
				VENTAS ULTIMOS 12 MESES
	=============================================*/
	static public function mdlDeudasDoceMeses($mes, $year)
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		SUM(  vtc.monto_cobro ) total
	FROM
		ventas v
		INNER JOIN ventas_x_tipo_cobro vtc ON v.id = vtc.id_ventas
		LEFT JOIN tipo_cobro tc ON vtc.id_tipo_cobro = tc.id
		LEFT JOIN deudas_amortizacion dv ON ( vtc.id = dv.id_venta_x_tipo_cobro ) 
	WHERE
		tc.efectivo = '2' 
		AND v.anulado <> '1' 
		AND vtc.estado <> '2' 
		AND YEAR ( v.fecha_venta ) = :anio AND MONTH(v.fecha_venta) = :mes
		");

	$stmt->bindParam(":mes", $mes , PDO::PARAM_INT);
	$stmt->bindParam(":anio", $year , PDO::PARAM_INT);

		$stmt -> execute();

		$return = $stmt ->fetchAll(PDO::FETCH_ASSOC);
		return $return[0]["total"];

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasRankingProductosHistorico()
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		VD.id_producto,
		VD.nombre_producto descripcion,
		VD.precio_venta_original precio_venta,
		SUM( VD.cantidad_producto ) cantidad,
		SUM( VD.comprobante_sub_total ) monto 
	FROM
		ventas V
		INNER JOIN ventas_detalle VD ON (V.id = VD.id_ventas)
	WHERE 
		V.anulado <> '1'
	GROUP by 
		VD.id_producto
	ORDER BY 
		monto DESC
	LIMIT 10
		");


		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasRankingCategoriasHistorico()
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		CAT.descripcion,
		SUM( VD.comprobante_sub_total ) monto 
	FROM
		ventas V
		INNER JOIN ventas_detalle VD ON ( V.id = VD.id_ventas )
		INNER JOIN producto P ON P.id = VD.id_producto
		INNER JOIN categoria CAT ON CAT.id = P.id_categoria
	WHERE
		V.anulado <> '1'
	GROUP by 
		P.id_categoria 
	ORDER BY 
		monto DESC
	LIMIT 10
		");


		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlConsultasRankingClientesHistorico()
	{

		$stmt = Conexion::conectar()->prepare("SELECT
		IFNULL(V.id_documento_cliente,'----') id_cliente,
		IFNULL(C.nombre_comercial,'----') nombre_comercial,
		SUM( V.total ) monto 
	FROM
		ventas V
		LEFT JOIN cliente C ON C.id_documento = V.id_documento_cliente 
	WHERE
		V.anulado <> '1'
	GROUP BY
		V.id_documento_cliente
	ORDER BY
		monto DESC 
	LIMIT 10
		");


		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
					MOSTRAR DASHBOARD
	=============================================*/
	static public function mdlListarLocalesEtl()
	{

		$stmt = Conexion::conectar()->prepare("SELECT
			* 
		FROM
			etl_local;
		");


		$stmt -> execute();

		return $stmt ->fetchAll(PDO::FETCH_ASSOC);

	}


}