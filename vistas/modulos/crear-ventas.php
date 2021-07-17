
<?php 

//$local = ControladorLocal::ctrMostrarLocalPorid($_SESSION["id_local"] );


//echo '<pre class="bg-white">'; print_r($local); echo '</pre>';
//echo '<pre class="bg-white">'; print_r($local[0]['igv']); echo '</pre>';
//echo '<pre class="bg-white">'; print_r($local[0]['impuesto_bolsas']); echo '</pre>';

/*
$flag = $local[0]['flag_lectura_rapida_productos'];
if( $flag == 0){
  $url_archivo_productos = "ajax/crear-ventas.ajax.php";
}else{
  $url_archivo_productos = "./vistas/modulos/productos/productos.json";
}*/
  $url_archivo_productos = "ajax/crear-ventas.ajax.php";

?>

<input type="hidden" id="url_archivo_productos" value=<?= $url_archivo_productos ?>>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>
            Punto de venta
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
            <li class="breadcrumb-item active">Punto de venta</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <section class="content">
    <div class="container-fluid">
    <div class="row">
    
      <div class="col-lg-12 col-xs-12">
        <div class="card card-primary card-outline">
          <form autocomplete="off" role="form" method="post" class = "formularioVenta">
            <div class="card-body">
                <div class="row">
                  <!--=====================================
                    COMPROBANTE BUSCAMOS
                    ======================================-->
                    <div class="col-lg-6 pt-2">
                    <!--<div id="accordion">-->
                        <div class="row col-lg-12">
                          <div class="container mt-1 py-1 bg-default" style="border: 1px solid #c4c4c4; border-radius: 5px">
                            <div class="card card-body">
                            <label> Datos del Cliente</label>
                              <div class="form-group">
                                <div class="input-group">
                                  <!--<input type="text" class="form-control" name="nuevaVentaSeleccionarCliente" placeholder="Buscar empresa">-->
                                  <select id="nuevaVentaSeleccionarCliente3" class="form-control select2" name="nuevaVentaSeleccionarCliente3" placeholder="Buscar cliente"></select>

                                  <div class="input-group-append">
                                    <button type="button" class="btn btn-info buscarDocumentoRucDniClienteCrearVentas" data-toggle="modal"><span><i class="fas fa-plus"></i></span> </button>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                  </div>
                                  <input  type="number" class="form-control crearVentaIdentificadorRUC" id="crearVentaClienteIdentificadorFactura" min="0" placeholder="RUC">
                                  <div class="input-group-append">
                                    <button type="button" class="btn btn-primary buscarApiClienteFactura"><span><i class="fas fa-search"></i></span> </button>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                  </div>
                                  <input  type="text" class="form-control crearVentaRazonSocial" id="crearVentaClienteNombreFactura" placeholder="Razon social">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                  </div>
                                  <input  type="text" class="form-control crearVentaDireccion" id="crearVentaClienteDireccionFactura" placeholder="Direccion">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                  </div>
                                  <input  type="email" class="form-control" id="crearVentaClienteEmailFactura" placeholder="Correo">
                                </div>
                              </div>
                            </div>
                          </div>
                        <!--  </div>-->
                        <!--  auxiliar para mandar la forma cobrada a la base de datos-->
                        <input type="hidden" name="nuevaVentaFormaCobro" placeholder="Formas de Cobro">
                        <input type="hidden" class="nuevaVentaListaProductos" name ="nuevaVentaListaProductos" placeholder="Lista de Productos">
                        <input type="hidden" class="nuevaVentaDescuentoTotal" name ="nuevaVentaDescuentoTotal" placeholder="Descuento Total">
                        <input type="hidden" class="nuevaVentaDescuentoMotivo" name ="nuevaVentaDescuentoMotivo" placeholder="Comentarios Descuento">
                        <input type="hidden" class="nuevaVentaCliente" name ="nuevaVentaComprobante" placeholder="Informacion Comprobante">
                        <input type="hidden" class="nuevaVentaCliente" name ="nuevaVentaInfoCliente" placeholder="Lista Cliente">
                        <input type="hidden" class="" name ="nuevaVentaVuelto" placeholder="Informacion Vuelto">
                        <input type="hidden" class="" id ="crearVentaCodCliente" name ="crearVentaCodCliente" placeholder="Codigo Cliente">
                    </div>
                    
                  </div>
                  <div class="col-lg-5 pt-2">
                    <div class="row col-lg-12 listaMetodoPago">
                      <div class="container mt-1 py-1 bg-default" style="border: 1px solid #c4c4c4; border-radius: 5px">
                        <label for="" >Datos del pago</label>
                        <div class = "form-group row">
                          <!-- Entrada DE FECHA-->
                          <div class  = "col-xs-12 col-md-6">
                            <label>Forma Pago</label>
                            <div class = "form-group" id="copiarEsto">
                              <select name="" class ="form-control select2 nuevaVentaFormaPago" id="nuevaVentaFormaPagoOriginal" style="width: 100%;">

                                <?php

                                  $tipo_cobro = ControladorFormaPago::ctrMostrarFormaPago();
                                  //print_r($tipo_cobro);
                                  foreach ($tipo_cobro as $key => $value) {

                                    //if( $value['efectivo'] == 1 )  $value['efectivo'] = 'E'; else  $value['efectivo'] = 'O';

                                    if($key == 0 )
                                      echo '<option selected="selected" value="'.$value['codformapago'].'">'.  strtoupper ($value['nomformapago']).'</option>';
                                      else
                                    echo '<option value="'.$value['codformapago'].'">'. strtoupper ($value['nomformapago']).'</option>';
                                  }

                                ?>
                              </select>
                            </div>
                          </div>
                          <!-- Entrada DE FECHA-->
                          <div class  = "col-xs-12 col-md-6">
                            <label>Tipo Pago</label>
                            <div class = "form-group" id="copiarEsto">
                              <select name="" class ="form-control select2 nuevaVentaTipoPago" id="nuevaVentaTipoPagoOriginal" style="width: 100%;">

                                <?php

                                  $tipo_cobro = ControladorTipoPago::ctrMostrarTipoPago();
                                  //print_r($tipo_cobro);
                                  foreach ($tipo_cobro as $key => $value) {

                                    //if( $value['efectivo'] == 1 )  $value['efectivo'] = 'E'; else  $value['efectivo'] = 'O';

                                    if($key == 0 )
                                      echo '<option selected="selected" value="'.$value['codtipopago'].'">'.  strtoupper ($value['nomtipopago']).'</option>';
                                      else
                                    echo '<option value="'.$value['codtipopago'].'">'. strtoupper ($value['nomtipopago']).'</option>';
                                  }

                                ?>
                              </select>
                            </div>
                          </div>
                          <!-- Entrada DE FECHA-->
                          <div class  = "col-xs-12 col-md-6">
                            <label>Tipo Comprobante</label>
                            <div class = "form-group" id="copiarEsto">
                              <select name="" class ="form-control select2 nuevaVentaTipoCpe" id="nuevaVentaTipoCpeOriginal" style="width: 100%;">

                                <?php

                                  $tipo_cobro = ControladorTipoCpe::ctrMostrarTipoCpe();
                                  //print_r($tipo_cobro);
                                  foreach ($tipo_cobro as $key => $value) {

                                    //if( $value['efectivo'] == 1 )  $value['efectivo'] = 'E'; else  $value['efectivo'] = 'O';

                                    if($key == 0 )
                                      echo '<option selected="selected" value="'.$value['codtipocpe'].'">'.  strtoupper ($value['nomtipocpe']).'</option>';
                                      else
                                    echo '<option value="'.$value['codtipocpe'].'">'. strtoupper ($value['nomtipocpe']).'</option>';
                                  }

                                ?>
                              </select>
                            </div>
                          </div>
                          <!-- -->
                          <div class  = "col-xs-12 col-sm-6 col-md-12">
                            <label for="">Importe</label>
                            <div class = "input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                              </div>
                              <input type="number" class="form-control input-lg nuevaVentaPagoMonto" idPago="0" step = "any" id="nuevaVentaPrimerCobro" min ="0" placeholder="Monto Pago">
                            </div>
                          </div>
                        </div>
                        <div class = "form-group row" style="margin-top:-15px">
                          <div class  = "col-lg-10">
                            <label for="">Nota de pago</label>
                            <input type="text" name="" value="" class="form-control nuevaVentaNotaPago">
                          </div>
                          <!-- Entrada DE FECHA-->
                          <div class  = "col-lg-2">
                            <label for=""></label>
                            <button type="button" class="btn btn-danger mt-4 ml-2"><i class="far fa-trash-alt"></i></button>
                          </div>
                        </div>
                      </div>

                    </div>
                    <!--Termina tipo de pago-->


                    <!--Agregar mas medio de pago-->
                    <div class="col-lg-12">
                      <!--Agregar mas medio de pago-->
                      <div class="form-group mt-3">
                        <button type="button" class="btn btn-primary agregarMetodoPago" style="width: 100%"><i class="fas fa-plus"></i> Agregar más pagos</button>
                      </div>
                      <!--Monto a pagar-->
                      <div class = "form-group py-2 bg-gray">
                        <div class="row ">
                          <div class="col-lg-4 row" style="margin-left:0px">
                            <div class="col-lg-8" style="text-align:left">Total productos</div>
                            <div class="col-lg-4 cantidadTotalProductos" style="text-align:right">0</div>
                          </div>
                          <div class="col-lg-4 row ml-2">
                            <div class="col-lg-6" style="text-align:left">Total IGV</div>
                            <div class="col-lg-6 nuevaVentaTotalIgv" style="text-align:right">00.00</div>
                          </div>
                          <div class="col-lg-4 row ml-2">
                            <div class="col-lg-6" style="text-align:left">Total a pagar</div>
                            <div class="col-lg-6 nuevaVentaTotalPagar" style="text-align:right">00.00</div>
                          </div>
                        </div>
                      </div>
                      <div class = "form-group py-2 bg-gray" style="margin-top:-15px">
                        <div class="row" >
                          <div class="col-lg-6 row" style="margin-left:0px">
                            <div class="col-lg-8" style="text-align:left">Total Cobrado</div>
                            <div class="col-lg-4 nuevaVentaTotalCobrado" style="text-align:right">00.00</div>
                          </div>
                          <div class="col-lg-6 row ml-2">
                            <div class="col-lg-6" style="text-align:left">Diferencia</div>
                            <div class="col-lg-6 nuevaVentaDiferencia" style="text-align:right">0.00</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="row">

      <!--=====================================
      EL FORMULARIO
      ======================================-->

      <div class="col-lg-6 col-xs-12">
        <div class="card card-primary card-outline">
          <form autocomplete="off" role="form" method="post" class = "formularioVenta">
            <div class="card-body">

              <div class="form-group">
                <table class="table table-sm">
                  <thead class="bg-info">
                    <tr>
                      <th>Producto</th>
                      <th style="width:20px">Precio</th>
                      <th style="width:20px">Cantidad</th>
                      <th style="width:20px">Subtotal</th>
                      <th style="width:10px"><i class = "far fa-trash-alt" style="margin-left:5px"> </i></th>
                    </tr>
                  </thead>
                  <tbody class= "tablaProductosPedido">


                  </tbody>
                </table>

              <input type="hidden" class="nuevaVentaListaProductos"  value="[]">


              </div>
              <br>
              <div class = "form-group  py-2">
                <div class="row" >
                        <div class="col-lg-6 row" style="margin-left:0px">
                    <div class="col-lg-8" style="text-align:left">
                      <button type="button" class="btn" data-toggle = "modal" data-target ="#modalAgregarDescuentoTotalPedido" style="padding: 0px 0px; margin-top:-4px">
                      <i class="far fa-edit"></i> Descuento
                      </button>
                    </div>
                    <div class="col-lg-4 nuevaVentaDescuentoTotal"   style="text-align:right">0.00</div>
                    <input type="hidden" id="nuevaVentaDescuentoTotalId" class="form-control input-lg"  step = "any"  min ="0">
                    <input type="hidden" id="nuevaVentaDescuentoTotalMotivo" class="form-control input-lg"  step = "any"  min ="0">
                  </div>



                  <div class="col-lg-6 row ml-2">
                    <div class="col-lg-6" style="text-align:left">Total Venta Productos</div>
                    <div class="col-lg-6 nuevaVentaTotalPedido"   style="text-align:right">S/ 00.00</div>
                  </div>
                </div>
              </div>



              <div class = "form-group py-2" style="margin-top:-15px">
                <div class="row" >
                  <div class="col-lg-6 row" style="margin-left:0px">
                    
                    
                  </div>
                  <div class="col-lg-6 row ml-2">
                    <div class="col-lg-8" style="text-align:left">Impuesto BOLSAS</div>
                    <div class="col-lg-4 nuevaVentaImpuestoBolsasTotal" style="text-align:right">S/ 0.00</div>
                  </div>
                </div>
              </div>

              <div class = "form-group py-2 bg-secondary" style="margin-top:-15px">
                  <div class="col-lg-12 row " style="margin-right:0px; padding-right:0px">
                    <div class="" style="text-align:left; width:50%; margin: 0px -7px 0px 7px">
                      <button type="button" class="btn" data-toggle = "modal" data-target ="#modalAddEnvio" style="padding: 0px 0px; margin-top:-4px; color:white">
                      <i class="far fa-edit"></i> Total a pagar
                      </button>
                      </div>
                    <div class="nuevaVentaTotalPagar" style="text-align:right;width:50%">00.00</div>
                  </div>
              </div>
              <div class = "form-group row py-2">
                <div class  = "col-lg-12">
                  

                  <label for="comentario_venta">Comentario</label>
                  <textarea name="comentario_venta" value="" class="form-control"></textarea>

                </div>
              </div>
              <div class="form-group">
                <div class="row form-group">
                  <div class="btn-group-vertical col-lg-6" style="width:90%">
                  </div>
                  <div class="btn-group-vertical col-lg-6" style="width:90%">
                    <button type="button" class="btn btn-success" id="generarVenta"><i class="far fa-money-bill-alt"></i> GenerarVenta</button>
                  </div>
                </div>
              </div>
          </div>

        </form>



        </div>

      </div>

      <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->

      <div class="col-lg-6 col-xs-12">
        <div class="card card-success card-outline">

          <div class="card-body">



            </table>

          <table class="table table-bordered table-striped dt-responsive" id = "tablaVentas" width="100%">
            <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="width: 30px">Imagen</th>
                  <th>Código</th>
                  <th>Descripcion</th>
                  <th style="width: 30px">PrecioVenta</th>
                  <th style="width: 30px">Acciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                  <th style="width: 10px">#</th>
                   <th style="width: 60px">Imagen</th>
                  <th>Código</th>
                  <th>Descripcion</th>
                  <th style="width: 60px">PrecioVenta</th>
                  <th style="width: 60px">Acciones</th>
                </tr>
            </tfoot>
          </table>



          </div>

        </div>


      </div>

    </div>
    </div>
  </section>

</div>



<!--=====================================
MODAL Editar producto
======================================-->
<div  class="modal fade modalVariarProducto">

  <div class="modal-dialog">

    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title modalVariarProductoDescripcion">AQUÍ VA EL NOMBRE DEL PRODUCTO A MODIFICAR</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

             <!-- ENTRADA PARA EL DOCUMENTO ID

          <div class = "form-group">
            <div class="row" >
              <div class="col-lg-6 row ml-2">
                <div class="col-lg-6" style="text-align:center">Precio Original</div>
                <div class="col-lg-6 " style="text-align:center">15.00</div>
              </div>
              <div class="col-lg-6 row">
                <div class="col-lg-6" style="text-align:center">Impuesto</div>
                <div class="col-lg-6" style="text-align:center">gravado</div>
              </div>
            </div>


          </div> -->

          <!-- Entrada para el precio compra-->
          <div class = "form-group row mt-4">
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Precio Original</label>
              <div class = "input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                <input type="number" class="form-control input-lg modalVariarProductoPrecioOriginal" disabled>
                  <input type="hidden" id="modalVariarProductoID" >
            </div>

            </div>


          <!-- Entrada para el precio de venta-->
            <div class  = "col-xs-12 col-sm-6">
              <label for="">Tipo Afectación Sunat</label>
              <div class = "input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-cubes"> </i></span>
                </div>
                  <input type="text" class="form-control input-lg modalVariarProductoAfectacionSunat" disabled>
              </div>
          </div>

          </div>

          <!-- Entrada para el Descuento-->
          <div class = "form-group row">
            <div class  = "col-xs-12 col-sm-12">
              <label for="">Precio Modificado</label>
              <div class = "input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                <input type="text" class="form-control input-lg" id="modalVariarProductoModificacionPrecio" step = "any"  min ="0">
            </div>

            </div>


   <div class  = "col-xs-12 col-sm-12">
              <label for="">Motivo Modificación del Precio</label>
              <input type="text" class="form-control" id="modalVariarProductoModificacionComentario"  value="">
            </div>




          </div>
          <div class = "form-group row">
            <div class  = "col-xs-12 col-sm-12">
              <label for="">Comentario Venta Producto</label>
              <input type="text" class="form-control" id="modalVariarComentarioVentaProducto"  value="" autocomplete="off">
            </div>

          </div>

          

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          <button type="button" class="btn btn-primary" id="btnActualizarPrecio" >Actualizar</button>
        </div>

      </form>



    </div>

  </div>

</div>


<!--Descuento modal-->
<div  class="modal fade" id="modalAgregarDescuentoTotalPedido">

  <div class="modal-dialog">

    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Descuento de pedido</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <div class="modal-body">
          <!-- Entrada para el Descuento
          <div class="form-group row" >
            <div class="row" style="width:100%" >
              <div class="col-lg-6 row ml-2">
                <div class="col-lg-6" style="text-align:center">Descuento</div>
                <div class="col-lg-6" style="text-align:center">0.00</div>
              </div>
              <div class="col-lg-6 row">
                <div class="col-lg-6" style="text-align:center">Total a pagar</div>
                <div class="col-lg-6" style="text-align:center">15.00</div>
              </div>
            </div>
          </div>
-->

          <div class = "form-group row mt-4" style="width:100%">
            <div class  = "col-xs-12 col-sm-8">
              <label for="">Descuento</label>
                <input type="number" class="form-control input-lg"  id="descuentoTotalCuenta" step = "any"  min ="0">
            </div>


          <div class  = "col-xs-12 col-sm-4">
            <label for="" class=""></label>
            <div class="form-check pl-0 ml-2 mt-2">
              <input class="form-check-input" id="checkBoxDescuentoEfectivo"  checked data-on="Efectivo" data-off="%" type="checkbox" data-toggle="toggle">
              </div>
        </div>

          </div>

          <div class="form-group">
            <label for="">Comentario</label>
            <input type="text" class="form-control" id="descuentoTotalComentario" name="" value="" required>
          </div>


        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          <button type="button" class="btn btn-primary" id="btnActualizarDescuentoTotal" >Guardar</button>
        </div>

      </form>


    </div>

  </div>

</div>


<!--Shipping modal-->
<div  class="modal fade" id="modalAddEnvio">

  <div class="modal-dialog modal-sm">

    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Adicionar envío</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">


          <!-- Entrada para el Descuento-->
          <div class = "form-group row">
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
              </div>
              <input type="number" class="form-control input-lg" step = "any"  min ="0">
          </div>

          </div>



        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary" >Actualizar</button>
        </div>

      </form>



    </div>

  </div>

</div>

<!--Suspender-->
<div class="modal fade" id="modalSuspender">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Suspender venta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Escriba la nota de referencia y envíela para suspender esta venta.</p>
        <label for="">Nota de referencia</label>
        <div class = "input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="far fa-file-alt"></i></span>
          </div>
            <input type="text" class="form-control input-lg" >
        </div>

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" >Enviar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- /.Cancelar -->
<div class="modal fade" id="modalCancelar">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-body">
        <p>¿Estás seguro?</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <a href="crear-ventas">
          <button type="button" class="btn btn-primary">Si. limpiar pantalla</button>
        </a>

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!--Imprimir Orden-->
<div class="modal fade" id="modalPrintOrden">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Default Modal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!--Imprimir Orden-->
<div class="modal fade" id="modalCotizacion">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cotizar</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
        <div class="form-group">
          <div class="input-group">
            <!--<input type="text" class="form-control" name="nuevaVentaSeleccionarCliente" placeholder="Buscar cliente">-->
            <div class="input-group-prepend">
              <span class="btn btn-primary"><i class="fas fa-search"></i></span>
            </div>
            <select id="cotizarCliente" class="form-control select2" name="cotizarCliente" placeholder="Buscar cliente"></select>

            <!--<div class="input-group-append">
              <button type="button" class="btn btn-info buscarClienteCrearCotizar"><span><i class="fas fa-plus"></i></span> </button>
            </div>-->
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
            </div>
              <input  type="text" class="form-control crearCotizacionIdentificadorDNI" id="crearCotizacionClienteIdentificador" placeholder="DNI">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
                <input  type="text" class="form-control crearCotizacionNombre" id="crearCotizacionClienteNombre" placeholder="Nombre">
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary btnGuardarCotizacion">Crear Cotización!</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

 <!--=====================================
MODAL AGREGAR CLIENTE
======================================-->
<div id="modalAgregarCliente" class="modal fade show" aria-modal="true">

<div class="modal-dialog">

  <div class="modal-content">
    <div class="modal-header bg-info">
      <h4 class="modal-title">Agregar cliente</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>

    </div>

      <!--=====================================
      CUERPO DEL MODAL
      ======================================-->

      <div class="modal-body">

           <!-- ENTRADA PARA EL DOCUMENTO ID -->

        <div class="form-group">

          <div class="input-group">

              <input type="number" min="0" class="form-control input-lg" placeholder="DNI / RUC" id="valorBuscarDocumentoRucDniClienteCrearVentas" name="nuevoDocumentoId">
              <div class="input-group-append">
                <button type="button" class="btn btn-success" id="buscarDocumentoRucDniClienteCrearVentas"><span><i class="fas fa-search"></i></span> Buscar</button>
              </div>
          </div>

        </div>

          <!-- ENTRADA PARA EL NOMBRE -->
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"> </i></span>
              </div>
              <input type="text" class="form-control input-lg" id="crearVentaNuevoClienteNombre" name="nuevoCliente" placeholder="Ingresar nombre" required="">
            </div>
          </div>

          <!-- ENTRADA PARA EL EMAIL -->

          <div class="form-group">

            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"> </i></span>
              </div>
              <input type="email" class="form-control input-lg" id="nuevoEmail" name="nuevoEmail" placeholder="Ingresar email">
            </div>

          </div>

          <!-- ENTRADA PARA EL TELÉFONO -->

          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
              </div>
              <input type="text" class="form-control input-lg" id="nuevoTelefono" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask="" im-insert="true">
            </div>

          </div>

          <!-- ENTRADA PARA LA DIRECCIÓN -->

          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
              </div>
              <input type="text" class="form-control input-lg" id="crearVentaNuevoClienteDireccion" name="nuevaDireccion" placeholder="Ingresar dirección">
            </div>
          </div>

           <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->

          <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
              </div>
              <input type="text" class="form-control" name="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false">
            </div>
          </div>
      </div>

      <!--=====================================
      PIE DEL MODAL
      ======================================-->

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
        <button type="button" class="btn btn-primary guardarCliente">Agregar</button>
      </div>

    
  </div>

</div>

</div>






<!--Comprobante
<div class="modal fade" id="modalComprobante">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Default Modal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>

  </div>

</div>-->

<?php include "crear-ventas-cobro.php"; ?>


<!--<div class="modal fade" id="modalSelectComprobante">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Elegir comprobante</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="row">
            <div class="col-lg-4">
              <button type="button" class="form-control btn-info">Boleta
            </div>
            <div class="col-lg-4">
              <button type="button" class="form-control btn-info">Factura
            </div>
            <div class="col-lg-4">
              <button type="button" class="form-control btn-info">Precuenta
            </div>

          </div>

        </div>
        <div class="form-group">
          <div class="input-group">
                <input  id="inputClienteBuscador" type="text" class="form-control" idCliente name="nuevaVentaSeleccionarCliente" placeholder="Ingrese al cliente" required>
                  <input type="hidden" id="nuevaVentaClienteSeleccionado" name ="nuevaVentaClienteSeleccionado" class="form-control">
                <div class="input-group-append">
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal"><span><i class="fas fa-plus"></i></span> Nuevo</button>
                </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Imprimir</button>
      </div>
    </div>
    <!-- /.modal-content --
  </div>
  <!-- /.modal-dialog --
</div>-->



<script>
  
  var global_igv = 18;
  var global_impuesto_bolsas = 10;
 


</script>
<?php date_default_timezone_set('America/Bogota'); ?>
<input type="hidden" id="usuario" value="<?= $_SESSION['id'] ?>"></input>
<input type="hidden" id="id_local" value="<?= $_SESSION['id_local'] ?>"></input>
<script src="vistas/js/crear-ventas.js?<?= date("smH") ?>"></script>


<script>
  //ESTO ES PARA HACER FOCUS AL TEXT BOX QUE TIENE LA BUSQUEDA DE PRODUCTOS CUÁNDO SE ABRA LA PÁGINA.

    $(document).ready(function(){
     $('.form-control-sm').focus();
});




</script>


 
<script>
  


$(document).ready(function() {
      /*

      var post = <?php echo json_encode($_POST) ?>;
  
 
 

      if (     isEmpty(post)   ) {


              //console.log('entre');




      let id_sesion_caja = 1 ;



      if(id_sesion_caja == 0 ) // significa que aún no se abrio caja y no se puede omitir este este modal dandole click fuuera de el
          $('#modalAbriCaja').modal({backdrop: 'static', keyboard: false})  


      }





*/


});

</script>


<script>
  $(document).on("click","#btnCotizar" , function(){
    
   let totalPagar  = Number($(".nuevaVentaTotalPagar").html()) ;
   //console.log("totalPagar", totalPagar);
   if(totalPagar < 0 )
   {
        alert("No se puede proceder con la cotización debido a que el monto es negativo");
        return;

   }else
   {
     $("#modalCotizacion").modal('show');
    $("#cotizarCliente").css("width","90%");
    var datos = new FormData();
    datos.append("ListadoClientes","yes");

    $.ajax({
      url: "ajax/clientes.ajax.php",
      method : "POST",
      data: datos,
      cache: false,
      contentType : false,
      processData : false,
      dataType : "json" ,
      success: function(resp){
        var select = $("#cotizarCliente");
        var option = '<option value="">Seleccione...</option>';
        select.html('');
        resp.forEach(function (item, index, array) {
          //console.log(item[0]);
          option += '<option value="' +item.id_documento + '|' +item.nombre + '|' +item.direccion+ '">' + item.id_documento+"-"+ item.nombre_comercial + '</option>';
          
        });          
        select.append(option);
        select.select2({
          placeholder: "SELECCIONE"
        });
        
        var id_documento = $("#id_documento_cliente").val();
        if(id_documento != "")
        {         
          select.val(id_documento).trigger("change.select2");
          var id = $("#id_cliente").val();
          var nombre = $("#nombre_cliente").val();

          $("#crearCotizacionClienteIdentificador").val(id);
          $("#crearCotizacionClienteNombre").val(nombre);
        }

      }
    });
    $("#cotizarCliente").on("change" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.
      var cliente1 = $(this).val();
      var cliente = [];
      cliente = cliente1.split("|");

      $("#crearCotizacionClienteIdentificador").val("");
      $("#crearCotizacionClienteNombre").val("");

      $("#crearCotizacionClienteIdentificador").val(cliente[0]);
      $("#crearCotizacionClienteNombre").val(cliente[1]);  

    });
    $(".btnGuardarCotizacion").off( "click" ).on("click" , function(){ // cuando este lista la clase formulario venta agregamos lo que sigue.
      event.preventDefault();

      let id_vendedor     = <?php echo $_SESSION["id"]   ?> ;
      let nombre_vendedor = "<?php echo $_SESSION['nombre']   ?>" ;
      let id_local        = <?php echo 1   ?> ;
      let id_sesion_caja  = <?php echo 1 ?> ;
      let comentario_venta = $('[name=comentario_venta]').val();
      let lista_productos = $(".nuevaVentaListaProductos").val();
      var datos = new FormData();

      var identificador = $("#crearCotizacionClienteIdentificador").val();
      var nombre = $("#crearCotizacionClienteNombre").val();

      if(identificador==""){
        alert("EL RUC NO PUEDE SER VACIO");
        return;
      }
      if(identificador.length == 11){
        var doschar = identificador.substr(0,2);
        if(doschar != "10" && doschar != "15" && doschar != "17" && doschar != "20")
        {
          alert("INGRESE UN RUC VÁLIDO");
          return;
        }
      }

      if(nombre==""){
        alert("LA RAZON SOCIAL NO PUEDE ESTAR EN VACIO");
        return;
      }
      if(nombre.length<3){
        alert("LA RAZON SOCIAL NO PUEDE SER MENOR A 3 CARACTERES");
        return;
      }

      datos.append("guardarCotizacion","yes");
      datos.append("id_cliente",identificador);
      datos.append("nombre_cliente",nombre);
      
      datos.append("id_vendedor",id_vendedor);
      datos.append("nombre_vendedor",nombre_vendedor);
      datos.append("id_local",id_local);
      datos.append("listaProductos",lista_productos);
      datos.append("id_sesion_caja",id_sesion_caja);
      datos.append("descuento",$('#nuevaVentaDescuentoTotalId').val());
      datos.append("motivo_descuento",$('#nuevaVentaDescuentoTotalMotivo').val());
      datos.append("comentario_venta",comentario_venta);
    
      $.ajax({
        url: "ajax/cotizacion.ajax.php",
        method : "POST",
        data: datos,
        cache: false,
        contentType : false,
        processData : false,
        dataType : "json" ,
        success: function(resp){  
          if(resp != "ok")
          {
            swal({
              type            : "error",
              title           : resp,
              showCancelButton: false
              }).then(function(result){
              // window.location = "crear-ventas";

            });
          }
          else{
            swal({
              type            : "success",
              title           : "Cotización creada al cliente "+$("#crearCotizacionClienteNombre").val(),
              showCancelButton: false
              }).then(function(result){
                window.location = "crear-ventas";

            });

          }
        }
      });
    });
  }
    
});

  /*=============================================
        BUSQUEDA DE CLIENTES PARA LOS COMPROBANTES.
=============================================*/
$(document).on("click",".buscarDocumentoRucDniClienteCrearVentas" , function(){
   
//CERRAMOS EL MODAL

  $('#modalPago').css('display','none');
  $('#modalAgregarCliente').css('z-index','9999');
  $('#modalAgregarCliente').modal('toggle');
  $("#buscarDocumentoRucDniClienteCrearVentas").on("click", function (){
    buscarDocumentoRucDniClienteCrearVentas();
  });
  
  $( '#modalAgregarCliente' ).on('hidden.bs.modal', function() {
    //al cerrar se limpian los valores del modal 
    $("#modalPago").css("display","block");
  });
  var buscarDocumentoRucDniClienteCrearVentas = function () {    
    
      //var idBusqueda = $(this).parent().parent().children().val();
      var idBusqueda =$("#valorBuscarDocumentoRucDniClienteCrearVentas").val();

      let id_local = 1 ;
      var datos = new FormData();

      datos.append("idRucDniBusqueda",idBusqueda);
      datos.append("id_local",id_local);

    $.ajax({

        url:"ajax/clientes.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){
          let listaCliente = [];
          let nombre = "";
          let tipdoc = "";
          let tipComprobante = "";

          if(respuesta.hasOwnProperty('dni'))
          {

            $(".crearVentaIdentificadorDNI").val(idBusqueda); 
            $(".crearVentaNombre").val(respuesta["nombres"]+  ' ' + respuesta["apellido_paterno"] + '  '   + respuesta["apellido_materno"] );
            nombre =  respuesta["nombres"]+  ' ' + respuesta["apellido_paterno"] + '  '   + respuesta["apellido_materno"];
            tipdoc = 'dni';
            $("#crearVentaNuevoClienteNombre").val(nombre);
                
          }
    
          if(respuesta.hasOwnProperty('ruc'))
          {
            tipdoc = 'ruc';

            $(".crearVentaIdentificadorRUC").val(idBusqueda);

            $("#crearVentaNuevoClienteNombre").val(respuesta["nombre_o_razon_social"]);

            $("#crearVentaNuevoClienteDireccion").val(respuesta["direccion_completa"]);
            nombre =  respuesta["nombre_o_razon_social"];

          }
            
            if ( $('#collapseTicket').hasClass('show') ) {
            
              tipComprobante = "ticket";            
  
              $("#crearVentaClienteIdentificadorTicket").val("");
              $("#crearVentaClienteNombreTicket").val("");
  
              $("#crearVentaClienteIdentificadorTicket").val(idBusqueda);
              $("#crearVentaClienteNombreTicket").val(nombre);
          
            } 
            
            if ( $('#collapseBoleta').hasClass('show') ) {
            
              tipComprobante = "boleta";            
  
              $("#crearVentaClienteIdentificadorBoleta").val("");
              $("#crearVentaClienteNombreBoleta").val(""); 
              $("#crearVentaClienteNombreBoleta").val(""); 
  
              $('#crearVentaClienteIdentificadorBoleta').val(idBusqueda);    
              $('#crearVentaClienteNombreBoleta').val(nombre);
          
            } 
            
            if ( $('#collapseFactura').hasClass('show') ) {
            
              tipComprobante = "factura"; 
              
              let crearVentaNuevoClienteDireccion = $("#crearVentaNuevoClienteDireccion").val();
              let nuevoEmail = $("#nuevoEmail").val();
              let nuevoTelefono = $("#nuevoTelefono").val();
  
              $("#crearVentaClienteIdentificadorFactura").val("");
              $("#crearVentaClienteNombreFactura").val(""); 
  
              $('#crearVentaClienteIdentificadorFactura').val(idBusqueda);    
              $('#crearVentaClienteNombreFactura').val(nombre);
  
              $('#crearVentaClienteDireccionFactura').val(crearVentaNuevoClienteDireccion);    
              $('#crearVentaClienteEmailFactura').val(nuevoEmail);
          
            }
            alert(tipComprobante);
        }

      });
  }
 });

$(document).on("click",".buscarApiClienteCrearVentas", function (){
  let nro_doc = $("#crearVentaClienteIdentificadorTicket").val();
    buscarDocumentoRucDniClienteEditarVentas(nro_doc);
  });
$(document).on("click",".buscarApiClienteBoleta", function (){
  let nro_doc = $("#crearVentaClienteIdentificadorBoleta").val();
    buscarDocumentoRucDniClienteEditarVentas(nro_doc);
  });
$(document).on("click",".buscarApiClienteFactura", function (){
  let nro_doc = $("#crearVentaClienteIdentificadorFactura").val();
    buscarDocumentoRucDniClienteEditarVentas(nro_doc);
  });
  

  var buscarDocumentoRucDniClienteEditarVentas = function (nro_doc) {
    
    //var idBusqueda = $(this).parent().parent().children().val();
    var idBusqueda = nro_doc != "" ? nro_doc : $("#valorBuscarDocumentoRucDniClienteCrearVentas").val()  ;
    let id_local = <?php echo $_SESSION["id_local"]   ?> ;
    var datos = new FormData();

    datos.append("idRucDniBusqueda",idBusqueda);
    datos.append("id_local",id_local);

    $.ajax({

      url:"ajax/clientes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
        let listaCliente = [];
        let nombre = "";
        let tipdoc = "";
        let tipComprobante = "";

        if(respuesta.hasOwnProperty('dni'))
        {

          $(".crearVentaIdentificadorDNI").val(idBusqueda); 
          //$(".crearVentaNombre").val(respuesta["nombres"]+  ' ' + respuesta["apellido_paterno"] + '  '   + respuesta["apellido_materno"] );
          nombre =  respuesta["nombres"]+  ' ' + respuesta["apellido_paterno"] + '  '   + respuesta["apellido_materno"];
          tipdoc = 'dni';
          if( $('#collapseTicket').hasClass('show') ){
            $("#crearVentaClienteNombreTicket").val(nombre);
          }
          if( $('#collapseBoleta').hasClass('show') ){
            $("#crearVentaClienteNombreBoleta").val(nombre);
          }
          if( $('#collapseFactura').hasClass('show') ){
            $("#crearVentaClienteNombreFactura").val(nombre);
          }
        }
  
        if(respuesta.hasOwnProperty('ruc'))
        {
          tipdoc = 'ruc';
          $(".crearVentaIdentificadorRUC").val(idBusqueda);
          
          $("#crearVentaNuevoClienteNombre").val(respuesta["nombre_o_razon_social"]);
          $("#crearVentaNuevoClienteDireccion").val(respuesta["direccion_completa"]);
          nombre =  respuesta["nombre_o_razon_social"];
          if( $('#collapseTicket').hasClass('show') ){
            $("#crearVentaClienteNombreTicket").val(nombre);
          }
          if( $('#collapseBoleta').hasClass('show') ){
            $("#crearVentaClienteNombreBoleta").val(nombre);
          }
          if( $('#collapseFactura').hasClass('show') ){
            $("#crearVentaClienteNombreFactura").val(nombre);
            $("#crearVentaClienteDireccionFactura").val(respuesta["direccion_completa"]);
          }


        }
        $(".guardarCliente").on("click" , function(){
          
        if ( $('#collapseTicket').hasClass('show') ) {
        
          tipComprobante = "ticket";     

          $("#crearVentaClienteIdentificadorTicket").val("");
          $("#crearVentaClienteNombreTicket").val("");

          $("#crearVentaClienteIdentificadorTicket").val(idBusqueda);
          $("#crearVentaClienteNombreTicket").val(nombre);  
      
        } 
        
        if ( $('#collapseBoleta').hasClass('show') ) {
        
          tipComprobante = "boleta";            

          $("#crearVentaClienteIdentificadorBoleta").val("");
          $("#crearVentaClienteNombreBoleta").val(""); 
          $("#crearVentaClienteNombreBoleta").val(""); 

          $('#crearVentaClienteIdentificadorBoleta').val(idBusqueda);    
          $('#crearVentaClienteNombreBoleta').val(nombre);
      
        } 
        
        if ( $('#collapseTicket').hasClass('show') ) {
        
          tipComprobante = "ticket";

          $("#crearVentaClienteIdentificadorTicket").val("");
          $("#crearVentaClienteNombreTicket").val("");

          $('#crearVentaClienteIdentificadorTicket').val(idBusqueda);
          $('#crearVentaClienteNombreTicket').val(nombre);
      
        } 
        
        if ( $('#collapseFactura').hasClass('show') ) {
        
          tipComprobante = "factura"; 
          
          let crearVentaNuevoClienteDireccion = $("#crearVentaNuevoClienteDireccion").val();
          let nuevoEmail = $("#nuevoEmail").val();
          let nuevoTelefono = $("#nuevoTelefono").val();

          $("#crearVentaClienteIdentificadorFactura").val("");
          $("#crearVentaClienteNombreFactura").val(""); 

          $('#crearVentaClienteIdentificadorFactura').val(idBusqueda);    
          $('#crearVentaClienteNombreFactura').val(nombre);
          
          $('#crearVentaClienteDireccionFactura').val(crearVentaNuevoClienteDireccion);    
          $('#crearVentaClienteEmailFactura').val(nuevoEmail);
      
        } 
        listaCliente.push({ 
          "id" : idBusqueda,
          "tipo_doc" : tipdoc,
          "tipComprobante" : tipComprobante,
          "nombre" : nombre,
          "telefono": $("#nuevoTelefono").val(),
          "direccion": $("#crearVentaNuevoClienteDireccion").val()
        });
        $('[name=nuevaVentaComprobante]').val( JSON.stringify(listaCliente));
        $('#modalAgregarCliente').modal('toggle');
        $("#modalPago").css("display","block");
      });
    }
  });
}
</script>