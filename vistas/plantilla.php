<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>FACTURAHORA PE</title>

<link rel="icon" href="vistas/img/plantilla/icono-negro.png">
  <!--=====================================
      PLUGIN CSS
======================================-->

<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="vistas/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="vistas/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons 
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
<!-- Theme style -->
<link rel="stylesheet" href="vistas/dist/css/adminlte.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load.
<link rel="stylesheet" href="vistas/dist/css/skins/_all-skins.min.css">-->
<!-- Google Font 
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<link rel="stylesheet" href="library/fonts/?family=Poppins:300,400,500,600,700|Roboto:300,400,500,700" media="all" />-->

<!-- DataTables -->
<link rel="stylesheet" href="vistas/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="vistas/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- iCheck -->
<link rel="stylesheet" href="vistas/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Autocompletar text box venta clientes 
<link rel="stylesheet" href="vistas/css/AutoCompletarClientesVentaNueva.css">-->
<!-- daterange picker -->
<link rel="stylesheet" href="vistas/plugins/daterangepicker/daterangepicker.css">
<!-- Morris chart 
<link rel="stylesheet" href="vistas/bower_components/morris.js/morris.css">-->
    <!-- Pantalla no encontrada : error 404 
<link rel="stylesheet" href="vistas/css/Pantalla404.css"> -->
<!-- JQVMap -->
<link rel="stylesheet" href="vistas/plugins/jqvmap/jqvmap.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="vistas/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<!-- summernote -->
<link rel="stylesheet" href="vistas/plugins/summernote/summernote-bs4.css">
<!-- Select2 -->
<link rel="stylesheet" href="vistas/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="vistas/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<!-- Bootstrap Switch -->
<link rel="stylesheet" href="vistas/plugins/bootstrap-switch/bootstrap4-switch.css">




<!--=====================================
      PLUGIN JAVASCRIPT
======================================-->
    <!-- jQuery  -->
<script src="vistas/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="vistas/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="vistas/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="vistas/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="vistas/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="vistas/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="vistas/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="vistas/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- date-range-picker -->
<script src="vistas/plugins/moment/moment.min.js"></script>
<script src="vistas/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="vistas/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Summernote -->
<script src="vistas/plugins/summernote/summernote-bs4.min.js"></script>
<!-- Select2 -->
<script src="vistas/plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap Switch
<script src="vistas/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>-->
<script src="vistas/plugins/bootstrap-switch/bootstrap4-switch.js"></script>


<!-- DataTables -->
<script src="vistas/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="vistas/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="vistas/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="vistas/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Sweet Alert2 -->
<script src="vistas/plugins/sweetalert2/sweetalert2.all.js"></script>
<!-- iCheck -->
<script src="vistas/plugins/iCheck/icheck.min.js"></script>
<!-- bs-custom-file-input -->
<script src="vistas/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- FastClick -->
<!-- Morris.js charts -->
<script src="vistas/plugins/raphael/raphael.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="vistas/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- overlayScrollbars -->
<script src="vistas/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="vistas/dist/js/adminlte.js"></script>

<script src="vistas/plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="vistas/plugins/flot-old/jquery.flot.resize.min.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="vistas/plugins/flot-old/jquery.flot.pie.min.js"></script>

  <style>
  #chartdiv, #chartdiv1, #chartdiv2, #chartdiv4{
    width: 100%;
    height: 500px;
  }

  </style>
  <style>
  /* source-sans-pro-regular - latin */
  @font-face {
    font-family: 'Source Sans Pro';
    font-style: normal;
    font-weight: 400;
    src: url('vistas/css/source_sans_pro/source-sans-pro-v14-latin-regular.eot'); /* IE9 Compat Modes */
    src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'),
        url('vistas/css/source_sans_pro/source-sans-pro-v14-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
        url('vistas/css/source_sans_pro/source-sans-pro-v14-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
        url('vistas/css/source_sans_pro/source-sans-pro-v14-latin-regular.woff') format('woff'), /* Modern Browsers */
        url('vistas/css/source_sans_pro/source-sans-pro-v14-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
        url('vistas/css/source_sans_pro/source-sans-pro-v14-latin-regular.svg#SourceSansPro') format('svg'); /* Legacy iOS */
  }
  </style>

  </head>
  <body class="hold-transition sidebar-mini layout-fixed sidebar-collapse" style="overflow-y: auto;">
<!-- Site wrapper -->


<?php
  file_put_contents("jose.txt", "123");
    if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"]=="ok")
    {

        if(isset($_GET["ruta"]) AND $_GET["ruta"]=="login")
        {
            session_destroy();
            include "modulos/login.php";      
        }
        echo '<div class="wrapper">';
        //MENU CABECERA
        include "modulos/cabezote.php";
        //MENU LATERAL
        include "modulos/menu.php";


        
// Write the contents back to the file




        if(isset($_GET["ruta"])) // Si esta correctamente seteada la URL.
        {

            if( $_GET["ruta"]=="inicio" 
            || $_GET["ruta"]=="usuarios"
            || $_GET["ruta"]=="clientes"
            || $_GET["ruta"]=="productos"
            || $_GET["ruta"]=="report-ventas-cliente"
            || $_GET["ruta"]=="report-venta"
            || $_GET["ruta"]=="report-venta-detalle"
            || $_GET["ruta"]=="report-prod"
            || $_GET["ruta"]=="crear-ventas"
            || $_GET["ruta"]=="ventas"
            || $_GET["ruta"]=="salir" ){

            include "modulos/".$_GET["ruta"].".php";


            }
        }
    } else
    include "modulos/login.php";
?>



  <!-- =============================================== -->

  <!-- =============================================== -->


<!-- ./wrapper -->

<?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/js/plantilla.js"></script>

<script src="vistas/js/usuarios.js"></script>
<script type="text/javascript">


//ESTO ES PARA SINCRONIZAR REPORTES ONLINE Y TAMBIÉN PARA SACAR BACKUPS !!

$(document).ready(function () {
});
</script>



<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    $('.js-example-basic-single').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    // Summernote
    $('.textarea').summernote()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    $('.example1').DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
    //Date range picker
    $('#reservation').daterangepicker()

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

  })
</script>



</body>
</html>

