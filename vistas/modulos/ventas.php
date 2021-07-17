<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>
              Administrar Ventas
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
              <li class="breadcrumb-item active">Administrar Ventas</li>
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
              
              <!--<button class="btn btn-default pull-right" id="daterange-btn">

                <span>

                  <i class="far fa-calendar-alt"></i> Rango de fecha

                </span>

                <span>

                  <i class="fa fa-caret-down"> </i>

                </span>

              </button>-->
            </div>
            <div class="card-body" id="tablaDinamicaProducto">
            <table class="table table-bordered table-striped dt-responsive" id="tablaVentas" width="100%">
            <thead>
              <tr>
                <th style="width:10px">#</th>
                <th>Fecha</th>
                <th>Identificación</th>
                <th>Nombre Comercial</th>
                <th>Comprobante</th>
                <th>Descuento</th>
                <th>Comentario</th>
                <th>Total Final</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tfoot>
                <tr>
                <th style="width: 10px">#</th>
                <th>Fecha</th>
                <th>Identificación</th>
                <th>Nombre Comercial</th>
                <th>Comprobante</th>
                <th>Descuento</th>
                <th>Comentario</th>
                <th>Total Final</th>
                <th>Estado</th>
                <th>Acciones</th>
                </tr>
            </tfoot>
          </table>
          <?php
            $eliminarVenta = new ControladorVentas();
            $eliminarVenta-> ctrEliminarVenta();
          ?>
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
  <!-- Modal VerPago-->
<div class="modal fade" id="modalVerPago">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Finalizar venta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="post" enctype="multipart/form-data">
      <div class="modal-body">
        <div class="row form-group col-lg-12">
        <div class="row col-lg-12 pt-2" style="border: 1px solid #d1d1d1">
          <div class="col-lg-9 listaMetodoPago"> 
          <!--SE LLENA CON JAVASCRIPT-->
          </div>
          <!--Termina tipo de pago-->

          <!--Seleccion dinero rapido-->
          <div class="col-lg-3">
            <label for="">Dinero rápido</label>
            <div class="btn-group-vertical" style="width:100%">
              <button type="button" class="btn btn-info nuevaVentaTotalPagar" id="cobroPagoTotal" style="border-bottom: 1px solid white">0.00</button>
              <button type="button" class="btn btn-default cobroPagoMonto" style="border-bottom: 1px solid white">1.00</button>
              <button type="button" class="btn btn-default cobroPagoMonto" style="border-bottom: 1px solid white">2.00</button>
              <button type="button" class="btn btn-default cobroPagoMonto" style="border-bottom: 1px solid white">5.00</button>
              <button type="button" class="btn btn-default cobroPagoMonto" style="border-bottom: 1px solid white">10.00</button>
              <button type="button" class="btn btn-default cobroPagoMonto" style="border-bottom: 1px solid white">50.00</button>
              <button type="button" class="btn btn-default cobroPagoMonto" style="border-bottom: 1px solid white">100.00</button>
              <button type="button" class="btn btn-default cobroPagoMonto" style="border-bottom: 1px solid white">200.00</button>
              <button type="button" class="btn btn-danger"  id="limpiarPago">Limpiar</button>
            </div>
          </div>

          <!--Agregar mas medio de pago-->
          <div class="col-lg-9">
            <!--Agregar mas medio de pago-->
            <div class="form-group mt-3">
              <button type="button" class="btn btn-primary agregarMetodoPago" style="width: 100%"><i class="fas fa-plus"></i> Agregar más pagos</button>
            </div>
            <!--Monto a pagar-->
            <div class = "form-group py-2 bg-gray">
              <div class="row ">
                <div class="col-lg-6 row" style="margin-left:0px">
                  <div class="col-lg-8" style="text-align:left"></div>
                  <div class="col-lg-4 " style="text-align:right"></div>
                </div>
                <div class="col-lg-6 row ml-2">
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

        <input type="hidden" class="" name ="actualizacionVentaID" placeholder="Identificador Ventas">
         <!--  auxiliar para mandar la forma cobrada a la base de datos-->
        <input type="hidden" name="nuevaVentaFormaCobro" placeholder="Formas de Cobro">
        <input type="hidden" class="" name ="nuevaVentaVuelto" placeholder="Informacion Vuelto">

       
        </div>

      </div>


    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary actualizarVenta" >Actualizar Pago</button>
      </div>


  </form>


  
    </div>



    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>



  <!-- Modal EditarPago-->
  <div class="modal fade" id="modalEditarPago">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Editar Pago</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Entrada para el precio compra-->
            <div class="row">
              <div class  ="col-xs-12 col-sm-6">
                <div class="form-group">
                  <label>Fecha</label>
                  <input type="datetime-local" class="form-control">

            </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class = "form-group">
                <label for="">Referencia</label>
                <input type="text" class="form-control"  name= "">
              </div>
            </div>
            </div>

            <!-- Entrada para el precio compra-->
              <div class="row">
                <div class  ="col-xs-12 col-sm-6">
                  <div class="form-group">
                    <label for="editarMontoPago">Monto</label>
                    <input type="number" class="form-control input-lg"  name= "editarMontoPago" id ="editarMontoPago" min ="0" value="0.00">
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6">
                <div class = "form-group">
                  <label for="">Pagado con</label>
                   <select name="nuevaSimbologia" class ="form-control select2" style="width: 100%; height:300px" required>
                     <option value="" selected>Efectivo</option>
                     <option value="" >Tarjeta débito</option>
                     <option value="" >Tarjeta crédito</option>
                     <option value="" >Déposito</option>
                     <option value="" >Crédito - por pagar</option>
                     <option value="" >Otros</option>
                   </select>
                </div>
              </div>
              </div>


            <!-- Entrada para el archivo-->
            <div class="form-group">
              <label for="exampleInputFile">Subir archivo</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="exampleInputFile">
                  <label class="custom-file-label" for="exampleInputFile"></label>
                </div>
              </div>
              <p class="help-block small mt-1">tamaño de archivo permitido de 200 KB</p>
            </div>

            <div class="form-group pad">
              <label for="exampleInputFile">Nota</label>
              <textarea class="textarea" placeholder="Place some text here"
                        style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary">Editar pago</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>



  <!-- Modal AddPago-->
  <div class="modal fade" id="modalAddPago">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Agregar Pago</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Entrada para el precio compra-->
            <div class="row">
              <div class  ="col-xs-12 col-sm-6">
                <div class="form-group">
                  <label>Fecha</label>
                  <input type="datetime-local" class="form-control">

            </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class = "form-group">
                <label for="">Referencia</label>
                <input type="text" class="form-control"  name= "">
              </div>
            </div>
            </div>

            <!-- Entrada para el precio compra-->
              <div class="row">
                <div class  ="col-xs-12 col-sm-6">
                  <div class="form-group">
                    <label for="editarMontoPago">Monto</label>
                    <input type="number" class="form-control input-lg"  name= "editarMontoPago" id ="editarMontoPago" min ="0" value="0.00">
                  </div>
              </div>
              <div class="col-xs-12 col-sm-6">
                <div class = "form-group">
                  <label for="">Pagando con</label>
                   <select name="nuevaSimbologia" class ="form-control select2" style="width: 100%; height:300px" required>
                     <option value="" selected>Efectivo</option>
                     <option value="" >Tarjeta débito</option>
                     <option value="" >Tarjeta crédito</option>
                     <option value="" >Déposito</option>
                     <option value="" >Crédito - por pagar</option>
                     <option value="" >Otros</option>
                   </select>
                </div>
              </div>
              </div>


            <!-- Entrada para el archivo-->
            <div class="form-group">
              <label for="exampleInputFile">Subir archivo</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="exampleInputFile">
                  <label class="custom-file-label" for="exampleInputFile"></label>
                </div>
              </div>
              <p class="help-block small mt-1">tamaño de archivo permitido de 200 KB</p>
            </div>

            <div class="form-group pad">
              <label for="exampleInputFile">Nota</label>
              <textarea class="textarea" placeholder="Place some text here"
                        style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary">Agregar pago</button>
        </div>

         


      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


<!--=====================================
=          Ver Descuento Ventas         =
======================================-->
<!-- Modal -->
<div id="modalVerDescuento" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Descuento Pedido</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-sm-2"></div>
          <div class  = "col-xs-12 col-sm-8">

            <!-- Monto del Descuento -->
            <label for="">Monto del Descuento</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><span class="ion-social-usd"></span></span>
              </div>
              <input type="text" class="form-control input-lg"  name= "MontoDescuento" id= "MontoDescuento" placeholder="Monto">
            </div>
          </div>
        </div>
        <!-- Fin Fila nueva-->
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-sm-2"></div>
          <div class  = "col-xs-12 col-sm-8">

            <!-- Motivo del Descuento -->
            <label for="">Motivo del Descuento</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-book"></i></span>
              </div>
              <textarea class="form-control" rows="3"   name= "MotivoDescuento" id= "MotivoDescuento"  placeholder="Enter ..." disabled=""></textarea>
            </div>
          </div>
        </div>
        <!-- Fin Fila nueva-->
        
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-xs-12 col-sm-12 text-center">
            <button class="btn btn-danger CerrarModalVerDescuento">Cerrar</button>
          </div>
        </div>
        <!-- Fin Fila nueva-->
      </div>
    </div>
  </div>
</div>

<!--=====================================
=          Ver Descuento Ventas         =
======================================-->
<!-- Modal -->
<div id="modalAnularVenta" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Anulación</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-sm-2"></div>
          <div class  = "col-xs-12 col-sm-8">

            <!-- Motivo del Anulacion -->
            <label for="">Motivo de la Anulación</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-book"></i></span>
              </div>
              <textarea class="form-control" rows="3"   name= "MotivoAnulacion" id= "MotivoAnulacion"  placeholder="Motivo de la Anulación"></textarea>
            </div>
          </div>
        </div>
        <!-- Fin Fila nueva-->
        
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-sm-2"></div>
          <div class  = "col-xs-12 col-sm-8">

            <!-- Monto del Descuento -->
            <label for="">Clave de Confirmación</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><span class="fas fa-fingerprint"></span></span>
              </div>
              <input type="password" class="form-control input-lg"  name= "ClaveConfirma" id= "ClaveConfirma" placeholder="Clave de Confirmacion">
            </div>
          </div>
        </div>
        <!-- Fin Fila nueva-->
        
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-xs-12 col-sm-12 text-center">
            <button class="btn btn-info btnAnularVenta">Anular!</button>
          </div>
        </div>
        <!-- Fin Fila nueva-->
      </div>
    </div>
  </div>
</div>

<!--=====================================
=          Modal Editar Ventas         =
======================================-->
<!-- Modal -->
<div id="modalConfirmarEditarVenta" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Confirmación de Edición</h4>
      </div>

      <div class="modal-body">        
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-sm-2"></div>
          <div class  = "col-xs-12 col-sm-8">

            <!-- Monto del Descuento -->
            <label for="">Clave de Confirmación</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><span class="fas fa-fingerprint"></span></span>
              </div>
              <input type="password" class="form-control input-lg"  name= "ClaveConfirmacion" id= "ClaveConfirmacion" placeholder="Clave de Confirmacion">
            </div>
          </div>
        </div>
        <!-- Fin Fila nueva-->
        
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-xs-12 col-sm-12 text-center">
            <button class="btn btn-info btnAccesoEditar">Editar!</button>            
            <button class="btn btn-danger CerrarModalEditarVenta">Cerrar</button>
          </div>
        </div>
        <!-- Fin Fila nueva-->
      </div>
    </div>
  </div>
</div>

<!--=====================================
=          Modal Editar Ventas         =
======================================-->
<!-- Modal -->
<div id="modalVerProductosVentas" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Listado de Productos</h4>
      </div>

      <div class="modal-body">        
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class="col-md-12">
          <span  id="listadoVentaProductos"></span>
          </div>
        </div>
        <!-- Fin Fila nueva-->
      </div>
    </div>
  </div>
</div>


<!--=====================================
=       Ver Comprobante Anulado         =
======================================-->
<!-- Modal -->
<div id="modalVentaAnulada" class="modal fade">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Comprobante Anulado</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-sm-2"></div>
          <div class  = "col-xs-12 col-sm-8">

            <!-- Monto del Descuento -->
            <label for="">Fecha de Anulación</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><span class="fas fa-calendar"></span></span>
              </div>
              <input type="text" class="form-control input-lg"  name= "FechaAnulado" id= "FechaAnulado" readonly>
            </div>
          </div>
        </div>
        <!-- Fin Fila nueva-->
        <!-- Fila nueva-->
        <div class = "form-group row" style="margin-bottom: 0px">
          <div class  = "col-sm-2"></div>
          <div class  = "col-xs-12 col-sm-8">

            <!-- Motivo del Anulacion -->
            <label for="">Motivo de la Anulación</label>
            <div class = "input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-book"></i></span>
              </div>
              <textarea class="form-control" rows="3"   name= "MotivoAnulado" id= "MotivoAnulado"  placeholder="Motivo de la Anulación" readonly></textarea>
            </div>
          </div>
        </div>
        <!-- Fin Fila nueva-->
      </div>
    </div>
  </div>
</div>
<!--Imprimir Orden-->
<div class="modal fade" id="modalCambiarComprobante">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cambiar Comprobante</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

<!--=====================================
  COMPROBANTE BUSCAMOS
  ======================================-->
 <div class="form-group col-lg-12" style="margin-left:15px">
    <!--<div id="accordion">-->

      <div class="card card-success" style="width:100%">
        <div class="card-header">
          <h4 class="card-title">
            <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" >
              Tipo de comprobante
            </a>-->
            Tipo de comprobante
          </h4>
        </div>
        <!--<div id="collapseOne" class="panel-collapse collapse in">-->
          <div class="card-body">
            <div class="row col-lg-12">

              <div class="accordion" id="accordionExample" style="width:100%">


                  <button class="btn btn-secondary tipoComprobanteBoton  btn-success" tipoComprobante="ticket" type="button" data-toggle="collapse" data-target="#collapseTicket" aria-expanded="true" style="width: 30%">
                    Ticket
                  </button>
                  <button class="btn btn-secondary tipoComprobanteBoton collapsed" tipoComprobante="boleta" type="button" data-toggle="collapse" data-target="#collapseBoleta" aria-expanded="false" style="width: 33%">
                    Boleta
                  </button>
                  <button class="btn btn-secondary tipoComprobanteBoton collapsed" tipoComprobante="factura" type="button" data-toggle="collapse" data-target="#collapseFactura" aria-expanded="false" style="width: 33%">
                    Factura
                  </button>
               

                <div class="collapse show" id="collapseTicket"  data-parent="#accordionExample" style="width:100%">
                  <div class="card card-body">
                    <div class="form-group">
                      <div class="input-group">
                            <input type="text" class="form-control" name="nuevaVentaSeleccionarCliente" placeholder="Buscar cliente">

                            <div class="input-group-append">
                              <button type="button" class="btn btn-success buscarDocumentoRucDniClienteCrearVentas"><span><i class="fas fa-search"></i></span> Buscar</button>
                            </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                        </div>
                            <input  type="text" class="form-control crearVentaIdentificadorDNI" id="crearVentaClienteIdentificadorTicket" placeholder="DNI">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                            <input  type="text" class="form-control crearVentaNombre" id="crearVentaClienteNombreTicket" placeholder="Nombre">
                      </div>
                    </div>

                  </div>
                </div>

                <div class="collapse" id="collapseBoleta"  data-parent="#accordionExample" style="width:100%">
                  <div class="card card-body">
                    <div class="form-group">
                      <div class="input-group">
                            <input type="text" class="form-control" name="nuevaVentaSeleccionarCliente" placeholder="Buscar cliente">

                            <div class="input-group-append">
                              <button type="button" class="btn btn-success buscarDocumentoRucDniClienteCrearVentas"><span><i class="fas fa-search"></i></span> Buscar</button>
                            </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                        </div>
                            <input  type="text" class="form-control crearVentaIdentificadorDNI" id="crearVentaClienteIdentificadorBoleta" placeholder="DNI">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                            <input  type="text" class="form-control crearVentaNombre" id="crearVentaClienteNombreBoleta" placeholder="Nombre">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-envelope"></i></span>
                        </div>
                            <input  type="text" class="form-control" id="crearVentaClienteEmailBoleta" placeholder="Correo">
                      </div>
                    </div>
                  </div>
                </div>






                <div class="collapse" id="collapseFactura"  data-parent="#accordionExample"style="width:100%">
                  <div class="card card-body">
                    <div class="form-group">
                      <div class="input-group">
                            <input type="text" class="form-control" name="nuevaVentaSeleccionarCliente" placeholder="Buscar empresa">

                            <div class="input-group-append">
                              <button type="button" class="btn btn-success buscarDocumentoRucDniClienteCrearVentas" data-toggle="modal"><span><i class="fas fa-search"></i></span> Buscar</button>
                            </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                        </div>
                            <input  type="number" class="form-control crearVentaIdentificadorRUC" id="crearVentaClienteIdentificadorFactura" min="0" placeholder="RUC">
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
                            <input  type="text" class="form-control" id="crearVentaClienteEmailFactura" placeholder="Correo">
                      </div>
                    </div>
                  </div>
                </div>


              </div><!-- FIN ACORDEON -->
            </div>
            <div class="row">
              <div class="col-lg-12">
                <input id="texto-json" type="hidden" class="nuevaVentaListaProductos" readonly="readonly">
              </div>
            </div>
          </div>
      <!--  </div>-->




      </div>
  <!--  </div>-->
  </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary btnGuardarCambioComprobante">Cambiar Comprobante!</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modalEditarCliente">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cambiar Cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <!--=====================================
        COMPROBANTE BUSCAMOS
        ======================================-->
      <div class="form-group col-lg-12" style="padding: 2px">
        <div class="card card-info" style="width:100%">
          <div class="card-header">
            <h4 class="card-title">
              Cambiar Cliente
            </h4>
          </div>
            <div class="card-body">
              <div class="row col-lg-12"></div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <div class="input-group">
                      <select id="cambiarClienteVenta" name="cambiarClienteVenta" class="form-control select2" placeholder="Buscar cliente" tabindex="-1" aria-hidden="true"></select>

                      <div class="input-group-append">
                        <button type="button" class="btn btn-info buscarDocumentoRucDniClienteCrearVentas"><span><i class="fas fa-plus"></i></span> </button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                      </div>
                      <input type="text" class="form-control editarVentaIdentificadorDNI" id="editarVentaClienteIdentificadorTicket" placeholder="DNI">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-primary buscarApiClienteEditarVentas"><span><i class="fas fa-search"></i></span> </button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                      </div>
                      <input type="text" class="form-control editarVentaNombre" id="editarVentaClienteNombreTicket" placeholder="Nombre">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary btnCambiarCliente">Cambiar Cliente!</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<input type="hidden" id="sesion_caja" value="<?= $id_sesion_caja ?>"></input>
<input type="hidden" id="id_local" value="<?= $_SESSION['id_local'] ?>"></input>
<input type="hidden" id="usuario" value="<?= $_SESSION['id'] ?>"></input>


  <?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/js/dinamica-cobro.js?<?= date("smH") ?>"></script>
<script src="vistas/js/ventas.js?<?= date("smH") ?>"></script>





<script>
  




/*=============================================
     BOTON PRINCIPAL DE GENERACION DE PAGOS.
=============================================*/
  $(document).on("click",".actualizarVenta" , function(){
 


 listarCobrosRealizado();

 // listarComprobante();
 


//VALIDACIONES: NO PERMITIR DOS TIPO DE COBROS IGUALES
 let tipoCobros = $(".nuevaVentaTipoPago"); //       $(".nuevaVentaTipoPago").select2('data')[0].text  
 let listaCobros = []; 
  
for(let i = 0 ; i < tipoCobros.length ; i++) listaCobros.push( $(tipoCobros[i]).select2('data')[0].text ); 

if( findDuplicates(listaCobros).length >0 ){
 alert("No se permite dos tipos de cobro iguales") ;
event.preventDefault(); 
return;

}


//VALIDACIONES: NO PERMITIR MONTOS DE COBRO EN VACIO Y TAMPOCO EN NEGATIVOS
  let monto_vacio = false;
  let monto_negativo = false;

  let totalCobros = $(".nuevaVentaPagoMonto"); //total de cobros realizados
 
  for(let i = 0 ; i < totalCobros.length ; i++){
    
      if( $(totalCobros[i]).val() ===''  ){
          monto_vacio= true;
          break;
      }    

      if( $(totalCobros[i]).val() < 0   ){
          monto_negativo= true;
          break;
      }    
  }

if(monto_vacio){
    alert("No se permiten cobros en vacio") ;  
    event.preventDefault(); 
    return;
}


if(monto_negativo){
    alert("No se permiten cobros negativos") ;  
    event.preventDefault(); 
    return;
}



//VALIDACIONES: NO PERMITIR QUE EL TOTAL COBRADO SEA MENOR AL TOTAL DE LA CUENTA
if ( calculoDiferencia() < 0 ) {
  alert("No se ha completado el total de la cuenta") ;  
    event.preventDefault(); 
    return;
}



//VALIDACIONES: NO PERMITIR QUE METODOS DE COBRO QUE NO SON EFECTIVO SEA MAYOR AL TOTAL
  let totalCuenta = Number($(".nuevaVentaTotalPagar").html()); 
  
for(let i = 0 ; i < tipoCobros.length ; i++)
  if(  $(tipoCobros[i]).select2('data')[0].text.charAt(0) =='O' && $(totalCobros[i]).val() > totalCuenta  ){

          alert("No se permite cobros que no sean efectivo mayor al total de la cuenta") ;
          event.preventDefault(); 
          return;
        
  }     


// SI TODO FUE SATISFACTORIO, GUARDAMOS LA VENTA
 

    


    let listaCobros_par =   $('[name=nuevaVentaFormaCobro]').val();
    let id_venta =   $('[name=actualizacionVentaID]').val();
    let vuelto_par =   $('[name=nuevaVentaVuelto]').val();
    let id_sesion_caja_par = <?php echo $id_sesion_caja   ?> ;

    var datos = new FormData();

    datos.append("actualizacionFormaPagoVenta", "yes"  );
    datos.append("listaCobros", listaCobros_par  );
    datos.append("id_venta", id_venta  );
    datos.append("vuelto",vuelto_par);
    datos.append("id_sesion_caja",id_sesion_caja_par);

//console.log("datos",datos);

       $.ajax({
      url:"ajax/ventas.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){

        //console.log("llegue ksm");

                  swal({
            type: "success",
            title: "Los pagos han sido editados correctamente.",
            showConfirmButton: true,
            confirmButtonText: "Cerrar"
            }).then((result) => {
                if (result.value) {

                      window.location = "ventas";

                }
              })



      }

    })





 });


</script>