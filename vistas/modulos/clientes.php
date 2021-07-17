<?php
if(isset($_POST['accion']) AND $_POST['accion'] == "EditarCliente"){
  $editarCliente = new ControladorClientes();
  $editarCliente -> ctrEditarCliente();
}
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>
              Administrar clientes
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
              <li class="breadcrumb-item active">Administrar clientes</li>
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
              <button type="button" class="btn btn-primary" data-toggle = "modal" data-target ="#modalAgregarCliente">
                <i class="fas fa-plus-circle"></i> Agregar cliente
              </button>

            </div>
            <div class="card-body">
            <table class="table table-bordered table-striped example1">
              <thead>
                <tr>
                  <th style="width:10px">#</th>
                  <th>Nombre</th>
                  <th>Tipo Documento</th>
                  <th>Documento ID</th>
                  <th>Dirección</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $item = null;
                  $valor = null;

                  $clientes = ControladorClientes::ctrMostrarClientes($item, $valor);
                  foreach ($clientes as $key => $value) {


                    echo '<tr>

                            <td>'.$value["codcliente"].'</td>

                            <td>'.$value["nomrznsocial"].'</td>

                            <td>'.$value["nomcodtipodocumento"].'</td>

                            <td>'.$value["docidentidad"].'</td>
                            
                            <td>'.$value["direccion"].'</td>

                            <td>

                              <div class="btn-group">

                                <button class="btn btn-warning btnEditarCliente" data-toggle="modal" data-target="#modalEditarCliente" idcliente="'.$value["codcliente"].'"><i class="fas fa-edit"></i></button>

                                <button class="btn btn-danger btnEliminarCliente" idCliente="'.$value["codcliente"].'"><i class="far fa-trash-alt"></i></button>

                              </div>

                            </td>

                          </tr>';

                    }

                ?>
              </tbody>
            <tfoot>
                <tr>
                  <th style="width:10px">#</th>
                  <th>Nombre</th>
                  <th>Tipo Documento</th>
                  <th>Documento ID</th>
                  <th>Dirección</th>
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
<input id="id_usuario" type="text" value="<?= $_SESSION['id'] ?>" />

<!--=====================================
MODAL AGREGAR CLIENTE
======================================-->

<div id="modalAgregarCliente" class="modal fade">

  <div class="modal-dialog">

    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Agregar cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

             <!-- ENTRADA PARA EL DOCUMENTO ID -->

          <div class = "form-group">

            <div class="input-group">

                <input type="number" min="0" class="form-control input-lg"  placeholder="DNI / RUC" id = "valorBuscarDocumentoRucDniClienteCrearVentas"  name = "nuevoDocumentoId">
                <div class="input-group-append">
                  <button type="button" class="btn btn-success" id = "buscarDocumentoRucDniClienteCrearVentas"><span><i class="fas fa-search"></i></span> Buscar</button>
                </div>
            </div>

          </div>

            <!-- ENTRADA PARA EL NOMBRE -->
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-user"> </i></span>
                </div>
                <input type="text" class="form-control input-lg"   id ="crearVentaNuevoClienteNombre" name="nuevoCliente" placeholder="Ingresar nombre" required>
              </div>
            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-key"></i></span>

                <input type="number" min="0" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar documento" required>

              </div>

            </div>-->

            <!-- ENTRADA PARA EL EMAIL -->

            <div class="form-group">

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-envelope"> </i></span>
                </div>
                <input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Ingresar email">
              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                </div>
                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" id ="crearVentaNuevoClienteDireccion" name="nuevaDireccion" placeholder="Ingresar dirección">
              </div>
            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->

            <div class="form-group">
              <div class="input-group">
                  <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" class="form-control" name="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
              </div>
            </div>
        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary" >Guardar</button>
        </div>

      </form>

      <?php

        $crearCliente = new ControladorClientes();
        $crearCliente -> ctrCrearCliente(true);

      ?>

    </div>

  </div>

</div>

<!--=====================================
          ANOTACIONES CLIENTE
======================================-->

<div id="modalAnotacionesCliente" class="modal fade">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">
      <form id="formAnotaciones" autocomplete="off" method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Anotaciones cliente <span id="TituloAnotacionesCliente"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
        </button>

      </div>

      <!--=====================================
                  CUERPO DEL MODAL
      ======================================-->

      <div class="modal-body">
        <div class="form-group">
          <label>Descripción Nota</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class = "fas fa-sticky-note"> </i></span>
            </div>
            <textarea class="form-control input-lg" id="descripcionNota" name="descripcionNota" placeholder="Descripción Nota"></textarea>
          </div>
        </div>
        <div class="form-group text-right">
          <button type="submit" class="btn btn-primary" >Guardar</button>
        </div>
        <hr>
        <div id="listaClientes">
        </div>
      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--=====================================
MODAL EDITAR CLIENTE
======================================-->

<div id="modalEditarCliente" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Editar cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

            <!-- ENTRADA PARA EL DOCUMENTO ID -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-key"> </i></span>
                </div>
                <input type="text" class="form-control input-lg" name="editarDocumentoId" id="editarDocumentoId" placeholder="NRO. DOC." required>
              </div>
            </div>

            <!-- ENTRADA PARA EL NOMBRE -->
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-user"> </i></span>
                </div>
                <input type="text" class="form-control" name="editarCliente" id="editarCliente" required>
                <input type="hidden" id="idCliente" name="idCliente" value="">
                <input type="hidden" id="accion" name="accion" value="EditarCliente">
              </div>

            </div>

            <!-- ENTRADA PARA EL NOMBRE COMERCIAL-->
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-store-alt"> </i></span>
                </div>
                <input type="text" class="form-control" name="editarNombreComercial" id="editarNombreComercial" required>
              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class = "fas fa-envelope"> </i></span>
                </div>
                <input type="email" class="form-control input-lg" name="editarEmail" id="editarEmail">
              </div>
            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                </div>
                <input type="text" class="form-control input-lg" name="editarTelefono" id="editarTelefono" data-inputmask="'mask':'(999) 999-9999'" data-mask>
              </div>
            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                </div>
                <input type="text" class="form-control input-lg" name="editarDireccion" id="editarDireccion">
              </div>
            </div>

            <!-- ENTRADA PARA LA NOTA -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                </div>
                <input type="text" class="form-control input-lg" name="editarNota" id="editarNota">
              </div>
            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->

            <div class="form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                </div>
                <input type="text" class="form-control input-lg" name="editarFechaNacimiento" id="editarFechaNacimiento" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"  data-mask>
              </div>
            </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary" >Guardar</button>
        </div>

      </form>




    </div>

  </div>

</div>

<?php

  $eliminarCliente = new ControladorClientes();
  $eliminarCliente -> ctrEliminarCliente();

?>

<?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/js/clientes.js?<?= date("smH") ?>"></script>
<script>

  $(document).on("click","#buscarDocumentoRucDniClienteCrearVentas" , function(){

    let id_local = <?php echo $_SESSION["id_local"]   ?> ;
    var idBusqueda =  $("#valorBuscarDocumentoRucDniClienteCrearVentas").val();

    var datos = new FormData();

    datos.append("id_local",id_local);
    datos.append("idRucDniBusqueda",idBusqueda);

   $.ajax({

      url:"ajax/clientes.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){

          if(respuesta.hasOwnProperty('dni')){

                $("#crearVentaNuevoClienteNombre").val(respuesta["nombres"]+  ' ' + respuesta["apellido_paterno"] + '  '   + respuesta["apellido_materno"] );

          }

          if(respuesta.hasOwnProperty('ruc')){

                $("#crearVentaNuevoClienteNombre").val(respuesta["nombre_o_razon_social"]);

                $("#crearVentaNuevoClienteDireccion").val(respuesta["direccion_completa"]);

            }

      }

    })

 });

</script>
