

<?php



//$um = ControladorInventario::ctrMostrarUnidadMedidaxInventario ( );
//$local = ControladorLocal::ctrMostrarLocalPorid($_SESSION["id_local"] );
// echo '<pre class="bg-white">'; print_r($um); echo '</pre>';
if(isset($_POST['crear_producto']))
{
  $crearProducto = new ControladorProductos();
  $crearProducto->ctrCrearProducto();
}
?>
<input type="hidden" name= "crear_productos" id= "crear_productos" value=1>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>
              Administrar Productos
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
              <li class="breadcrumb-item active">Administrar Productos</li>
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
                <div class="col-md-2">
                  <button type="button" class="btn btn-primary float-left" data-toggle = "modal" data-target ="#modalAgregarProducto" id="btnAgregarProducto">
                    <i class="fas fa-plus-circle"></i> Agregar Producto
                  </button>
                </div>
                <div class="col-5">&nbsp;</button></div>
              </div>
            </div>
            <div class="card-body">
            <table class="table table-bordered table-striped dt-responsive" id = "tablaProductos" width="100%">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Unidad Sunat</th>
                <th>Precio Venta</th>
                <th>Afectacion</th>
                <th >Acciones</th>
              </tr>
            </thead>
            <tfoot>
                <tr>
                <th style="width: 10px">#</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Unidad Sunat</th>
                <th>Precio Venta</th>
                <th>Afectacion</th>
                <th>Acciones</th>
                </tr>
            </tfoot>
          </table>
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

<!--=====================================
=          Agregar Producto Modal        =
======================================-->
<!-- Modal -->
<div id="modalAgregarProducto" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <form  autocomplete="off" method="post" enctype="multipart/form-data">
    
      <input type="hidden" name="crear_producto" value="1"/>
      <div class="modal-header bg-info">
        <h4 class="modal-title">Agregar Producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


        <!-- Entrada para seleccionar la categoría del producto-->

          <!-- Entrada para seleccionar la cod-->
          <div class = "form-group">
            <label for="">Código del producto</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fab fa-product-hunt"> </i></span>
              </div>
                <input type="text" class="form-control input-lg"  name= "nuevaCodProducto" placeholder="Código del producto" required>
            </div>
          </div>

          <!-- Entrada para seleccionar la descripción-->
          <div class = "form-group">
            <label for="">Nombre del producto</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fab fa-product-hunt"> </i></span>
              </div>
                <input type="text" class="form-control input-lg"  name= "nuevaDescripcion" placeholder="Nombre del producto" required>
            </div>
          </div>

          <!-- Entrada para ingresar el stock-->
          <div class = "form-group row" style="margin-bottom: 0px">


          </div>

          <!-- Entrada para ingresar el stock-->
          <div class = "form-group row">
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Unidad de medida</label>
              <select name="nuevoProductoUnidadSunat" class ="form-control select2" style="width: 100%; height:300px" required>
                <?php
                  $um = ControladorUnidadMedida::ctrMostrarUnidadMedida();
                  //print_r($um);
                  foreach ($um as $key => $value) {

                    //if( $value['efectivo'] == 1 )  $value['efectivo'] = 'E'; else  $value['efectivo'] = 'O';

                    if($key == 0 )
                      echo '<option selected="selected" value="'.$value['codunidad'].'">'.$value['codunidad'].'-'.  strtoupper ($value['nomunidad']).'</option>';
                    else
                      echo '<option value="'.$value['codunidad'].'">'.$value['codunidad'].'-'.  strtoupper ($value['nomunidad']).'</option>';
                  }
                ?>
              </select>
            </div>



            <!-- Entrada para ingresar el stock minimo-->
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Tipo de afectación</label>
              <select name="nuevoProductoAfectacion" class ="form-control select2" style="width: 100%; height:300px" required>
                <?php
                  $tipo_afectacion = ControladorTipoAfectacion::ctrMostrarTipoAfectacion();
                  //print_r($tipo_afectacion);
                  foreach ($tipo_afectacion as $key => $value) {

                    //if( $value['efectivo'] == 1 )  $value['efectivo'] = 'E'; else  $value['efectivo'] = 'O';

                    if($key == 0 )
                      echo '<option selected="selected" value="'.$value['codtipoafectacion'].'">'.  strtoupper ($value['nomtipoafectacion']).'</option>';
                    else
                      echo '<option value="'.$value['codtipoafectacion'].'">'.  strtoupper ($value['nomtipoafectacion']).'</option>';
                  }
                ?>
              </select>
            </div>




          </div>

          <!-- Entrada para el precio compra-->
          <div class = "form-group row">


          <!-- Entrada para el precio de venta-->
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Precio de venta</label>
              <div class = "input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-arrow-circle-up"> </i></span>
                </div>
                <input type="number" class="form-control input-lg"  name= "precioVenta" step = "any" min ="0" placeholder="Precio de venta" required>
              </div>
          </div>

          </div>


         <!--<div class = "form-group row col-lg-12" style="width:100%">
           <div id="accordion">
             <div class="card col-lg-12 p-0" style="width:100%">
               <div class="card-header" style="width:100%">
                 <h4 class="card-title">
                   <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                     <input class="form-check-input"  name="crearParejaInventario"  type="checkbox" data-toggle="toggle"  <?php if(1 == 1 ) echo  'checked'; else echo 'unchecked'; ?>>
                   </a>
                   <label for="" class="ml-2">Crear su par del producto en inventario</label>
                 </h4>
               </div>
               <div id="collapseOne" class="panel-collapse collapse show">
                 <div class="card-body pb-0">
                   <div class = "form-group row">

                     <div class  = "col-xs-12 col-sm-6">
                       <label for="">Costo Referencial</label>
                       <div class = "input-group mb-3">
                         <div class="input-group-prepend">
                           <span class="input-group-text"><i class = "fas fa-arrow-circle-up"> </i></span>
                         </div>
                         <input type="number" class="form-control input-lg"  name= "costoIdeal" step = "any" min ="0" placeholder="Costo Ideal" >
                       </div>
                     </div>

                     <div class  = "col-xs-12 col-sm-6">
                       <label for="">Cantidad de Alerta</label>
                         <div class = "input-group mb-3">
                         <div class="input-group-prepend">
                           <span class="input-group-text"><i class = "fas fa-arrow-circle-up"> </i></span>
                         </div>
                         <input type="number" class="form-control input-lg"  name= "crearStockMinimo" step = "any" min ="0" placeholder="Cantidad de Alerta" >
                       </div>
                     </div>

                   </div>
                 </div>
               </div>
             </div>
           </div>
          </div>-->




                    <!-- Entrada para ingresar el stock-->

          <!-- Entrada para la imagen del producto-->
          <!--<div class="form-group">
            <div class="form-group my-2">
              <div class="btn btn-default btn-file">
                  <i class="fas fa-paperclip"></i> Adjuntar Foto del Producto
                  <input type="file" name="cargarImagenProductoCreacion"  id="cargarImagenProductoCreacion" >
              </div>
              <div>
                <img class="img-fluid py-2" style='border-radius: 5%;width: 120px; height: 120px;' id="previsualizarImagenProductoCreacion"  src="vistas/img/plantilla/product-anonymous.png" class='img-fluid'>
                </div>

               <p class="help-block small">Dimensiones: 300px * 300px | Peso Max. 2MB | Formato: JPG o PNG</p>
            </div>
          </div>-->
          <!-- Finaliza - Entrada para la imagen del producto-->

          <input type="hidden" id="id_local" value="1"/>
          <input type="hidden" name="crear_producto" value="1"/>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary" >Guardar</button>
        </div>
        </form>
      </div>




    </div>

  </div>


<!--=====================================
=          Editar Producto Modal        =
======================================-->

<!-- Modal -->
<div id="modalEditarProducto" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <form  autocomplete="off" method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Editar Producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <!-- Entrada para seleccionar la cod-->
          <div class = "form-group">
            <label for="">Código del producto</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fab fa-product-hunt"> </i></span>
              </div>
                <input type="text" class="form-control input-lg"  name= "editarCodProducto" id="editarCodProducto" placeholder="Código del producto" required>
            </div>
          </div>


          <!-- Entrada para seleccionar la descripción-->
          <div class = "form-group">
            <label for="">Nombre del producto</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fab fa-product-hunt"> </i></span>
              </div>
                <input type="text" class="form-control input-lg"  name= "editarDescripcion" id="editarDescripcion" placeholder="Nombre del producto" required>
                 <input type="hidden"  name= "editarIdProducto">
            </div>
          </div>



          <!-- Entrada para ingresar el stock-->
          <div class = "form-group row">
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Unidad de medida</label>
              <select name="editarProductoUnidadSunat" class ="form-control select2" style="width: 100%; height:300px" required>
                <?php
                  $um = ControladorUnidadMedida::ctrMostrarUnidadMedida();
                  //print_r($um);
                  foreach ($um as $key => $value) {

                    //if( $value['efectivo'] == 1 )  $value['efectivo'] = 'E'; else  $value['efectivo'] = 'O';

                    if($key == 0 )
                      echo '<option selected="selected" value="'.$value['codunidad'].'">'.$value['codunidad'].'-'.  strtoupper ($value['nomunidad']).'</option>';
                    else
                      echo '<option value="'.$value['codunidad'].'">'.$value['codunidad'].'-'.  strtoupper ($value['nomunidad']).'</option>';
                  }
                ?>
              </select>
            </div>



            <!-- Entrada para ingresar el stock minimo-->
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Tipo de afectación</label>
              <select name="editarProductoAfectacion" class ="form-control select2" style="width: 100%; height:300px" required>
                <?php
                  $tipo_afectacion = ControladorTipoAfectacion::ctrMostrarTipoAfectacion();
                  //print_r($tipo_afectacion);
                  foreach ($tipo_afectacion as $key => $value) {

                    //if( $value['efectivo'] == 1 )  $value['efectivo'] = 'E'; else  $value['efectivo'] = 'O';

                    if($key == 0 )
                      echo '<option selected="selected" value="'.$value['codtipoafectacion'].'">'.  strtoupper ($value['nomtipoafectacion']).'</option>';
                    else
                      echo '<option value="'.$value['codtipoafectacion'].'">'.  strtoupper ($value['nomtipoafectacion']).'</option>';
                  }
                ?>
              </select>
            </div>




          </div>




          <!-- Entrada para el precio compra-->
          <div class = "form-group row">


            <!-- Entrada para el precio de venta-->
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Precio de venta</label>
              <div class = "input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-arrow-circle-up"> </i></span>
                </div>
                <input type="number" class="form-control input-lg"  name= "editarPrecioVenta" step = "any"  min ="0" placeholder="Precio de venta" required>
              </div>
            </div>

          </div>








          <!-- Entrada para la imagen del producto-->
          <!--<div class="form-group">
            <div class="form-group my-2">
              <div class="btn btn-default btn-file">
                  <i class="fas fa-paperclip"></i> Adjuntar Foto del Producto
                  <input type="file" name="cargarImagenProductoEdicion" id="cargarImagenProductoEdicion">
                  <input type="text" name="previsualizarCargarImagenProductoEdicion">
              </div>
              <div>
                <img class="img-fluid py-2" style='border-radius: 5%;width: 120px; height: 120px;' id="previsualizarImagenProductoEdicion"   class='img-fluid'>
                </div>

               <p class="help-block small">Dimensiones: 300px * 300px | Peso Max. 2MB | Formato: JPG o PNG</p>
            </div>
          </div>-->
          <!-- Finaliza - Entrada para la imagen del producto-->

        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary" >Guardar</button>
      </div>
      </form>
      </div>
      </div>



<?php
  $crearProducto = new ControladorProductos();
  $crearProducto->ctrEditarProducto();
?>
<input type="hidden" id="id_local" value="1"/>
<input type="hidden" id="ruta" value=""/>
<input type="hidden" id="nombre_local" value="pepito sac"/>
    </div>

  </div>

 




<!--=====================================
=          Agregar Unidad de Medida        =
======================================-->
<!-- Modal -->
<div id="modalEnlaceInventario" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
    <form  autocomplete="off" method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Unidades De Medida</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <div class="modal-body">

            <div class="card-body">
            <table class="table table-bordered table-striped dt-responsive tablaUnidadMedidaProducto"   width="100%">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th style="width: 100px">Cantidad</th>
                <th>Unidad Medida</th>
                <th >Acciones</th>
              </tr>
            </thead>


          </table>
        </div>

        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
             <button type="button" class="btn btn-primary" data-toggle = "modal" data-target ="#modalAgregarUnidadMedidaProducto">
                Agregar Nueva Medida
              </button>
        </div>
        </form>
      </div>



<?php
//  $crearProducto = new ControladorInventario();
//  $crearProducto->ctrAgregarProductoInventario();
?>

    </div>

  </div>
















 <!--=====================================
  =          UNIDAD DE MEDIDA ENLACE       =
  ======================================-->
  <!-- Modal -->
  <div id="modalAgregarUnidadMedidaProducto" class="modal fade">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Enlazar inventario</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <!-- Entrada para seleccionar la descripción-->
            <div class = "form-group">
              <div class="row form-group">
                <div class="col-lg-8">

          <input type="hidden" class="idProductoUnidadMedida" name= "idProductoUnidadMedida" > 


                  <label for="">Producto inventario</label>
                  <select class="js-example-basic-single" name="unidadMedidaProductoEnlaceUnidadMedida" >





              
                  </select>
                </div>
                <div class="col-lg-4">
                  <label for="">Cantidad</label>
                    <input type="text" min="0" class="form-control"  name= "unidadMedidaProductoEnlaceCantidad" required>
                </div>
              </div>
            </div>
 


          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
            <button type="submit" class="btn btn-primary" >Generar</button>
          </div>
          </form>
        </div>



    
      <?php
      $crearUnidadMedidaEnlace = new  ControladorProductos();
      $crearUnidadMedidaEnlace -> ctrCrearUnidadMedidaEnlace();
      ?>


      </div>

    </div>
<?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/js/productos.js?<?= date("smH") ?>"></script>
