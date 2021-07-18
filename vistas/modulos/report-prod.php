
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
              Reporte de Productos
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
              <li class="breadcrumb-item active">Reporte de Productos</li>
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
            <div class="card-body">
              <div class="row mt-2 mb-2 p-2">
                <div class="col-md-12">
                  <div id="tablaDinamicaProductos"></div>

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
<script src="vistas/js/repor-prod.js?<?= date("smH") ?>"></script>