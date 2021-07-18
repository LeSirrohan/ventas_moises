<?php 
$dashboard         = ControladorDashboard::ctrConsultasDashboard( $id_sesion_caja );
$amortizaciones    = ControladorDashboard::ctrConsultasDashboardAmortizaciones( $id_sesion_caja );
$flujo             = ControladorDashboard::ctrConsultasDashboardFlujo( $id_sesion_caja );
$rankingProductos  = ControladorDashboard::ctrConsultaRankingProductos( $id_sesion_caja );
$rankingCategorias = ControladorDashboard::ctrConsultaRankingCategorias( $id_sesion_caja );
$rankingClientes   = ControladorDashboard::ctrConsultaRankingClientes( $id_sesion_caja );
$ventas            = ControladorDashboard::ctrVentasDoceMeses( );

foreach ($dashboard as $key => $value)
{
  $dashboard[$key] = number_format($value, 2);
}
?>

<style>
@media (max-width: 575.98px) { 
    #chartdiv10,#chartdiv11
    {
        height: 700px !important;
    }
 }

/*Small devices (landscape phones, less than 768px)*/
@media (max-width: 767.98px) { 
    #chartdiv10,#chartdiv11
    {
        height: 600px !important;
    }
 }

/*// Medium devices (tablets, less than 992px)*/
@media (max-width: 991.98px) { 
    #chartdiv10,#chartdiv11
    {
        height: 500px !important;
    }
 }

/* Large devices (desktops, less than 1200px)*/
@media (max-width: 1199.98px) { 
    #chartdiv10,#chartdiv11
    {
        height: 500px !important;
    }
 }
@media (max-width: 1199.98px) { 
    #chartdiv10,#chartdiv11
    {
        height: 500px !important;
    }
 }
@media (min-width: 1199.98px) { 
    #chartdiv10,#chartdiv11
    {
        height: 400px !important;
    }
 }
</style>
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
    <div class="col-lg-3 col-sm-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3><span id="total_ventas"><?= $dashboard["cantidad_ventas"] ?></span></h3>

          <p>Total de Pedidos</p>
        </div>
        <div class="icon">
          <i class="ion ion-arrow-swap"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-sm-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>S/<?= $dashboard["total_ventas"] ?></h3>

          <p>Total de Pedidos (S/)</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-sm-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3><?= $dashboard["cantidad_anulados"] ?></h3>

          <p>Total Ventas Anuladas</p>
        </div>
        <div class="icon">
          <i class="ion ion-close-circled"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-sm-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>S/<?= $dashboard["total_anulados"] ?></h3>

          <p>Total Ventas Anuladas (S/)</p>
        </div>
        <div class="icon">
          <i class="ion ion-social-usd"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
  </div>
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-md-6 col-lg-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Clientes</span>
              <span class="info-box-number">
              <?= $dashboard["cantidad_clientes"] ?>

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-lg-3 col-md-6">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Compras</span>
              <span class="info-box-number">S/<?= $dashboard["total_compras"] ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-lg-3 col-md-6">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="far fa-money-bill-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Ingresos</span>
              <span class="info-box-number">S/<?= $dashboard["total_ingresos"] ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-lg-3 col-md-6">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-arrow-alt-circle-down"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Egresos</span>
              <span class="info-box-number">S/<?= $dashboard["total_egresos"] ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>

<!---->
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">
            <i class="far fa-chart-bar"></i>
            Flujo
          </h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div id="chartdiv4"></div>
        </div>
      </div>
         <!--/.card-body-->
      <!-- /.GRAFICO VENTA -->
      <!-- /.GRAFICO VENTAS MENSUALES -->
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">
            <i class="far fa-chart-bar"></i>
            VENTAS ÚLTIMOS 12 MESES
          </h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <canvas id="barChart" style="height:400px;"></canvas>
        </div>
        <!-- /.card-body-->
      </div>
      <!-- /.GRAFICO VENTAS MENSUALES -->
            <div class="row">
              <div class="col-12 col-lg-3 col-md-6">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i class="far fa-credit-card"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Deudas Pendientes</span>
                    <span class="info-box-number"> <?=  $amortizaciones["deudas_pendientes"] ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-lg-3 col-md-6">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="far fa-credit-card"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Deudas Pendientes (S/)</span>
                    <span class="info-box-number">S/ <?= number_format( $amortizaciones["monto_deudas_pendientes"],2,',','.') ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

              <!-- fix for small devices only -->
              <div class="clearfix hidden-md-up"></div>

              <div class="col-12 col-lg-3 col-md-6">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Deudas Amortizadas</span>
                    <span class="info-box-number"> <?= $amortizaciones["deudas_amort"] ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-lg-3 col-md-6">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Deudas Amortizadas (S/)</span>
                    <span class="info-box-number">S/ <?= number_format( $amortizaciones["monto_abono_amort"],2,',','.') ?></span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
            </div>


      <div class="row">
        <div class="col-lg-6">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="far fa-chart-bar"></i>
                Comprobantes
              </h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div id="chartdiv10"></div>
            </div>
            <!-- /.card-body-->
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="far fa-chart-bar"></i>
                Métodos de Pago
              </h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div id="chartdiv11"></div>
            </div>
            <!-- /.card-body-->
          </div>
        </div>


      </div>

      <div class="row">
        <div class="col-md-6">
        <div class="card">
          <div class="card-header border-0">
            <h3 class="card-title"><i class="fas fa-barcode"></i> Ranking de productos</h3>
            <div class="card-tools">
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle  dt-responsive display" id="ranking_productos">
              <thead>
              <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Monto</th>
              </tr>
              </thead>
              <tbody> 
                <?php 
                foreach ($rankingProductos as $product){
                  print '<tr>';
                  print '<td>'.$product['descripcion'].'</td>';
                  print '<td>'.$product['precio_venta'].'</td>';
                  print '<td>'.$product['cantidad'].'</td>';
                  print '<td>S/'.number_format($product['monto'],2).'</td>';
                  print '</tr>';
                }
                ?>
              <!--<tr>
                <td>Some Product</td>
                <td>S/13.00</td>
                <td>100</td>
                <td>S/1,300.00</td>
              </tr>
              <tr>
                <td>Another Product</td>
                <td>S/29.00</td>
                <td>
                    234</td>
                <td>S/2,000.00</td>
              </tr>
              <tr>
                <td>Amazing Product</td>
                <td>S/23.00</td>
                <td>198</td>
                <td>S/500.00</td>
              </tr>
              <tr>
                <td>Amazing Product</td>
                <td>S/23.00</td>
                <td>198</td>
                <td>S/500.00</td>
              </tr>
              <tr>
                <td>Another Product</td>
                <td>S/29.00</td>
                <td>234</td>
                <td>S/2,000.00</td>
              </tr>-->
              </tbody>
            </table>
          </div>
        </div>
        </div>
        <div class="col-md-3">
        <div class="card">
          <div class="card-header border-0">
            <h3 class="card-title"><i class="fas fa-th-large"></i> Ranking de categorias</h3>
            <div class="card-tools">
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle  dt-responsive display" id="ranking_categorias">
              <thead>
              <tr>
                <th>Nombre</th>
                <th>Monto</th>
              </tr>
              </thead>
              <tbody>
                <?php 
                foreach ($rankingCategorias as $category){
                  print '<tr>';
                  print '<td>'.$category['descripcion'].'</td>';
                  print '<td>S/'.number_format($category['monto'],2).'</td>';
                  print '</tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
        <div class="col-md-3">
        <div class="card">
          <div class="card-header border-0">
            <h3 class="card-title"><i class="fas fa-user-friends"></i> Ranking de clientes</h3>
            <div class="card-tools">
            </div>
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle  dt-responsive display" id="ranking_clientes">
              <thead>
              <tr>
                <th>Nombre</th>
                <th>Monto</th>
              </tr>
              </thead>
              <tbody>
                <?php 
                foreach ($rankingClientes as $client){
                  print '<tr>';
                  print '<td>'.$client['nombre_comercial'].'</td>';
                  print '<td>S/'.number_format($client['monto'],2).'</td>';
                  print '</tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
      </div>


    </div><!--/. container-fluid -->
  </section>
</div>
<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<!-- Chart code -->
<script>

//-------------
//- BAR CHART -
//-------------

var areaChartData = {
      labels  : <?= $ventas["fechas"] ?>,
      datasets: [
        {
          label               : 'Ventas',
          backgroundColor     : '#3b8bba',
          borderColor         : '#3b8bba',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : '#3b8bba',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: '#3b8bba',
          data                : <?= $ventas["ventas"] ?>
        },
        {
          label               : 'Cobros',
          backgroundColor     : '#00a65a',
          borderColor         : '#00a65a',
          pointRadius         : false,
          pointColor          : '#00a65a',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: '#00a65a',
          data                : <?= $ventas["cobros"] ?>
        },
        {
          label               : 'Deudas',
          backgroundColor     : '#00c0ef',
          borderColor         : '#00c0ef',
          pointRadius         : false,
          pointColor          : '#00c0ef',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: '#00c0ef',
          data                : <?= $ventas["deudas"] ?>
        },
      ]
    }
var barChartCanvas = $('#barChart').get(0).getContext('2d');
var barChartData = jQuery.extend(true, {}, areaChartData);
var temp0 = areaChartData.datasets[0];
var temp1 = areaChartData.datasets[1];
var temp2 = areaChartData.datasets[2];
barChartData.datasets[0] = temp0;
barChartData.datasets[1] = temp1;
barChartData.datasets[2] = temp2;

var barChartOptions = {
  responsive              : true,
  maintainAspectRatio     : false,
  datasetFill             : false
};

var barChart = new Chart(barChartCanvas, {
  type: 'bar', 
  data: barChartData,
  options: barChartOptions
});
var id_sesion_caja = <?= $id_sesion_caja ?>;
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv11", am4charts.PieChart);



var datos = new FormData();
	datos.append("accion", "PieVentasTipoCobro");
	datos.append("id_sesion_caja", id_sesion_caja);
$.ajax({
  method     : "POST",
  url        : "./ajax/ventas.ajax.php",
  data       : datos,
  cache      : false,
  contentType: false,
  processData: false,
  dataType   : "json",
  success    : function(data) { 
        chart.data =  data
  }
});
// Add data
/*chart.data = [ {
  "country": "Efectivo",
  "litres" : 281.99
}, {
  "country": "Visa",
  "litres" : 301.9
}, {
  "country": "Mastercard",
  "litres" : 50
} ];*/

// Set inner radius
chart.innerRadius = am4core.percent(50);
chart.legend = new am4charts.Legend();
// Responsive
chart.responsive.enabled = true;
chart.responsive.rules.push({
  relevant: function(target) {
    if (target.pixelWidth <= 600) {
      return true;
    }
    return false;
  },
  state: function(target, stateId) {
    if (target instanceof am4charts.PieSeries) {
      var state = target.states.create(stateId);

      var labelState = target.labels.template.states.create(stateId);
      labelState.properties.disabled = true;

      var tickState = target.ticks.template.states.create(stateId);
      tickState.properties.disabled = true;
      return state;
    }

    return null;
  }
});
// Add and configure Series
var pieSeries                               = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value              = "total";
    pieSeries.dataFields.category           = "tipo_cobro";
    pieSeries.slices.template.stroke        = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth   = 2;
    pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
pieSeries.hiddenState.properties.opacity    = 1;
pieSeries.hiddenState.properties.endAngle   = -90;
pieSeries.hiddenState.properties.startAngle = -90;

}); // end am4core.ready()
</script>
<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart          = am4core.create("chartdiv10", am4charts.PieChart);
var datos          = new FormData();

	datos.append("accion", "PieVentas");
	datos.append("id_sesion_caja", id_sesion_caja);
$.ajax({
  method     : "POST",
  url        : "./ajax/ventas.ajax.php",
  data       : datos,
  cache      : false,
  contentType: false,
  processData: false,
  dataType   : "json",
  success    : function(data) { 
    let total = data[0];
    chart.data = [ {
      "country": "Ticket",
      "litres" : total.total_ticket
    }, {
      "country": "Boleta",
      "litres" : total.total_boleta
    }, {
      "country": "Factura",
      "litres" : total.total_factura
    } ];


  }
});


// Add data
// Set inner radius
chart.innerRadius = am4core.percent(50);
chart.legend = new am4charts.Legend();
// Responsive
chart.responsive.enabled = true;
chart.responsive.rules.push({
  relevant: function(target) {
    if (target.pixelWidth <= 600) {
      return true;
    }
    return false;
  },
  state: function(target, stateId) {
    if (target instanceof am4charts.PieSeries) {
      var state = target.states.create(stateId);

      var labelState = target.labels.template.states.create(stateId);
      labelState.properties.disabled = true;

      var tickState = target.ticks.template.states.create(stateId);
      tickState.properties.disabled = true;
      return state;
    }

    return null;
  }
});
// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "litres";
pieSeries.dataFields.category = "country";
pieSeries.slices.template.stroke = am4core.color("#fff");
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

}); // end am4core.ready()
</script>
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv4", am4charts.XYChart);
chart.data = <?= json_encode($flujo); ?>;


// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
//dateAxis.renderer.grid.template.location = 0;
//dateAxis.renderer.minGridDistance = 30;

var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis1.title.text = "Ingreso y Gasto";

var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis2.title.text = "Venta y Compra";
valueAxis2.renderer.opposite = true;
valueAxis2.renderer.grid.template.disabled = true;

// Create series
var series1 = chart.series.push(new am4charts.ColumnSeries());
series1.dataFields.valueY = "ingresos";
series1.dataFields.dateX = "date";
series1.yAxis = valueAxis1;
series1.name = "Ingresos";
series1.tooltipText = "{name}\n[bold font-size: 20]S./{valueY}[/]";
series1.fill = chart.colors.getIndex(0);
series1.strokeWidth = 0;
series1.clustered = false;
series1.columns.template.width = am4core.percent(40);

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "ventas";
series2.dataFields.dateX = "date";
series2.yAxis = valueAxis1;
series2.name = "Ventas";
series2.tooltipText = "{name}\n[bold font-size: 20]S./{valueY}[/]";
series2.fill = chart.colors.getIndex(0).lighten(0.5);
series2.strokeWidth = 0;
series2.clustered = false;
series2.toBack();

var series3 = chart.series.push(new am4charts.LineSeries());
series3.dataFields.valueY = "compras";
series3.dataFields.dateX = "date";
series3.name = "Compras";
series3.strokeWidth = 2;
series3.tensionX = 0.7;
series3.yAxis = valueAxis2;
series3.tooltipText = "{name}\n[bold font-size: 20]S./{valueY}[/]";

var bullet3 = series3.bullets.push(new am4charts.CircleBullet());
bullet3.circle.radius = 3;
bullet3.circle.strokeWidth = 2;
bullet3.circle.fill = am4core.color("#fff");

var series4 = chart.series.push(new am4charts.LineSeries());
series4.dataFields.valueY = "gastos";
series4.dataFields.dateX = "date";
series4.name = "Gastos";
series4.strokeWidth = 2;
series4.tensionX = 0.7;
series4.yAxis = valueAxis2;
series4.tooltipText = "{name}\n[bold font-size: 20]S./{valueY}[/]";
series4.stroke = chart.colors.getIndex(0).lighten(0.5);
series4.strokeDasharray = "3,3";

var bullet4 = series4.bullets.push(new am4charts.CircleBullet());
bullet4.circle.radius = 3;
bullet4.circle.strokeWidth = 2;
bullet4.circle.fill = am4core.color("#fff");

// Add cursor
chart.cursor = new am4charts.XYCursor();

// Add legend
chart.legend = new am4charts.Legend();
chart.legend.position = "top";

// Add scrollbar
chart.scrollbarX = new am4charts.XYChartScrollbar();
chart.scrollbarX.series.push(series1);
chart.scrollbarX.series.push(series3);
chart.scrollbarX.parent = chart.bottomAxesContainer;

}); // end am4core.ready()
</script>

<script>
$(document).ready( function () {
    $('#ranking_productos').DataTable( {
        "paging"   : false,
        "ordering" : false,
        "info"     : false,
        "searching": false,
        "language" : 
        {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing":"Procesando...",
        }
	} );
    $('#ranking_categorias').DataTable( {
        "paging"   : false,
        "ordering" : false,
        "info"     : false,
        "searching": false,
        "language" : 
        {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing":"Procesando...",
        }
	} );
    $('#ranking_clientes').DataTable( {
        "paging"   : false,
        "ordering" : false,
        "info"     : false,
        "searching": false,
        "language" : 
        {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing":"Procesando...",
        }
	} );
   
});
</script>