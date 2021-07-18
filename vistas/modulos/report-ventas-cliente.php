<?php 

require_once "./controladores/clientes.controlador.php";
require_once "./modelos/clientes.modelo.php";

date_default_timezone_set('America/Bogota');
$fecha_actual = new Datetime(); 

$fecha_modificada = $fecha_actual->modify("-13 months");
//$fecha_modificada_temp = $fecha_modificada->modify("last day of self month");

$mes_actual       = $fecha_modificada->format("Y-m-d");

//echo $mes_actual;echo "\n";
$doce_meses = [];
$mes=0;
while($mes < 13)
{
  $fecha_modificada = $fecha_actual->modify("last day of next month");
  $mes_actual       = $fecha_modificada->format("m");
  $mes_letras       = $fecha_modificada->format("M");
  $year             = $fecha_modificada->format("y");

  $array = array(
    "mes" => $mes,
    "titulo" => $mes_letras."-".$year
  );
  array_push( $doce_meses, $array );
  $mes++;     
}
$tabla = '<table class="table table-bordered table-striped dt-responsive .ClienteVentas" id="tablaClienteVentasxMes" width="100%">';
$tabla .= '<thead>';
$tabla .= '  <tr>';
$tabla .= '	<th>NOMBRE COMERCIAL</th>';
$tabla .= '	<th id="mes12_h">'.$doce_meses[0]["titulo"].'</th>';
$tabla .= '	<th id="mes11_h">'.$doce_meses[1]["titulo"].'</th>';
$tabla .= '	<th id="mes10_h">'.$doce_meses[2]["titulo"].'</th>';
$tabla .= '	<th id="mes9_h">'.$doce_meses[3]["titulo"].'</th>';
$tabla .= '	<th id="mes8_h">'.$doce_meses[4]["titulo"].'</th>';
$tabla .= '	<th id="mes7_h">'.$doce_meses[5]["titulo"].'</th>';
$tabla .= '	<th id="mes6_h">'.$doce_meses[6]["titulo"].'</th>';
$tabla .= '	<th id="mes5_h">'.$doce_meses[7]["titulo"].'</th>';
$tabla .= '	<th id="mes4_h">'.$doce_meses[8]["titulo"].'</th>';
$tabla .= '	<th id="mes3_h">'.$doce_meses[9]["titulo"].'</th>';
$tabla .= '	<th id="mes2_h">'.$doce_meses[10]["titulo"].'</th>';
$tabla .= '	<th id="mes1_h">'.$doce_meses[11]["titulo"].'</th>';
$tabla .= '	<th id="mes0_h">'.$doce_meses[12]["titulo"].'</th>';
$tabla .= ' </tr>';
$tabla .= '</thead>';
$tabla .= '<tfoot>';
$tabla .= '	<tr>';
$tabla .= '	<th>NOMBRE COMERCIAL</th>';
$tabla .= '	<th id="mes12_f">'.$doce_meses[0]["titulo"].'</th>';
$tabla .= '	<th id="mes11_f">'.$doce_meses[1]["titulo"].'</th>';
$tabla .= '	<th id="mes10_f">'.$doce_meses[2]["titulo"].'</th>';
$tabla .= '	<th id="mes9_f">'.$doce_meses[3]["titulo"].'</th>';
$tabla .= '	<th id="mes8_f">'.$doce_meses[4]["titulo"].'</th>';
$tabla .= '	<th id="mes7_f">'.$doce_meses[5]["titulo"].'</th>';
$tabla .= '	<th id="mes6_f">'.$doce_meses[6]["titulo"].'</th>';
$tabla .= '	<th id="mes5_f">'.$doce_meses[7]["titulo"].'</th>';
$tabla .= '	<th id="mes4_f">'.$doce_meses[8]["titulo"].'</th>';
$tabla .= '	<th id="mes3_f">'.$doce_meses[9]["titulo"].'</th>';
$tabla .= '	<th id="mes2_f">'.$doce_meses[10]["titulo"].'</th>';
$tabla .= '	<th id="mes1_f">'.$doce_meses[11]["titulo"].'</th>';
$tabla .= '	<th id="mes0_f">'.$doce_meses[12]["titulo"].' </th>';
$tabla .= '	</tr>';
$tabla .= '</tfoot>';
$tabla .= '</table>';
?>
<link rel="stylesheet" type="text/css" href="vistas/css/report-ventas.css"/>
<link rel="stylesheet" type="text/css" href="vistas/plugins/datatables/Buttons/Buttons-1.6.2/css/buttons.bootstrap4.min.css"/>
 
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/JSZip-2.5.0/jszip.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/pdfmake-0.1.36/pdfmake.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/pdfmake-0.1.36/vfs_fonts.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/dataTables.buttons.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/buttons.bootstrap4.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/buttons.html5.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/buttons.print.min.js"></script>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>
              Reporte de Ventas al Mes
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
            <li class="breadcrumb-item"><a href="repor-client">Reporte de Clientes</a></li>
              <li class="breadcrumb-item active">Reporte de Ventas al Mes</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6 text-left">
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-primary" href="repor-client">
                      <i class="fas fa-reply-all"></i> Regresar
                    </a>
                </div>
              </div>
            
              <div class="row mt-1 mb-1">
                <div class="col-md-6 text-left">              
                <!-- <button class="btn btn-default pull-right" id="daterange-btn">
                  <span> <i class="far fa-calendar-alt"> </i> Rango de fecha </span>
                  <span> <i class="fa fa-caret-down"> </i> </span>
                </button> -->
                </div>
              </div>
            </div>
            <div class="card-body">

              <div class="row mt-2 mb-2 p-2">
                <div class="col-md-12">
                  <?php echo $tabla; ?>

                </div>
              </div>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
      </div>
      </div>
      </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->

<?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/js/report-ventas-cliente.js?<?= date("smH") ?>"></script>