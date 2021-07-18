<link rel="stylesheet" type="text/css" href="vistas/css/report-ventas.css"/>
<link rel="stylesheet" type="text/css" href="vistas/plugins/datatables/Buttons/Buttons-1.6.2/css/buttons.bootstrap4.min.css"/>
 
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/JSZip-2.5.0/jszip.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/pdfmake-0.1.36/pdfmake.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/pdfmake-0.1.36/vfs_fonts.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/dataTables.buttons.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/buttons.bootstrap4.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/buttons.html5.min.js"></script>
 <script type="text/javascript" src="vistas/plugins/datatables/Buttons/Buttons-1.6.2/js/buttons.print.min.js"></script>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>
            Reporte de clientes
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
            <li class="breadcrumb-item active">Reporte de clientes</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  <div class="container-fluid">

    <div class="card ">
      <!--<div class="card-header border-transparent bg-info">
        <h3 class="card-title">Filtros de busqueda</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body pt-3">
        <div class="row form-group">
          <div class="col-lg-6">
            <label for="">Seleccionar cliente</label>
            <div class="select2-purple">
              <select class="select2" multiple="multiple" id="miSelectorEspecial" data-placeholder="Seleccionar Producto" data-dropdown-css-class="select2-purple" style="width: 100%;">

                     <?php
          $inventario = ControladorInventario::ctrMostrarInventario();
          foreach ($inventario as $key => $value) {
            echo '<option value="'.$value['id'].'">'. $value['codigo_barras'].' | '. $value['nombre'].'</option>';
          }

         ?>
              </select>
            </div>
          </div>
          <div class="col-lg-6">
            <label for="">Rango de fecha</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="far fa-calendar-alt"></i>
                </span>
              </div>
              <input type="text" class="form-control float-right" id="reservation">
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer clearfix">
        <a href="javascript:void(0)" class="btn btn-sm btn-info float-right">Filtrar</a>
        <a href="javascript:void(0)" class="btn btn-sm btn-light float-right">Limpiar filtros</a>
      </div>
    </div>-->

    <div class="row mt-2 mb-2 p-2">
      <div class="col-md-12">
        <div id="tablaDinamicaClientes"></div>

      </div>
    </div>


  </div><!-- /.container-fluid -->
</section>
</div>
</div>
  <?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/js/repor-client.js?<?= date("smH") ?>"></script>
