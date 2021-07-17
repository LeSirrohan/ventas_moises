<?php 
/*$dashboard         = ControladorDashboard::ctrConsultasDashboard( $id_sesion_caja );
$amortizaciones    = ControladorDashboard::ctrConsultasDashboardAmortizaciones( $id_sesion_caja );
$flujo             = ControladorDashboard::ctrConsultasDashboardFlujo( $id_sesion_caja );
$rankingProductos  = ControladorDashboard::ctrConsultaRankingProductos( $id_sesion_caja );
$rankingCategorias = ControladorDashboard::ctrConsultaRankingCategorias( $id_sesion_caja );
$rankingClientes   = ControladorDashboard::ctrConsultaRankingClientes( $id_sesion_caja );
$ventas            = ControladorDashboard::ctrVentasDoceMeses( );

foreach ($dashboard as $key => $value)
{
  $dashboard[$key] = number_format($value, 2);
}*/
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-lg-12 col-sm-6">
          <!-- small box -->
          <?php echo json_encode($_SESSION); ?>
        </div>
        <!-- ./col -->
      </div>

    </div><!--/. container-fluid -->
  </section>
</div>
<!-- Resources -->