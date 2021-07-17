
<?php
  if(isset($_POST['nuevoNombre']))
  {
    $crearUsuario = new ControladorUsuarios();
    $crearUsuario -> ctrCrearUsuario();
  }
  if(isset($_POST['editarUsuario']))
  {
    $crearUsuario = new ControladorUsuarios();
    $crearUsuario -> ctrEditarUsuario();
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
              Administrar Usuarios
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-tachometer-alt mr-2"></i>Inicio</a></li>
              <li class="breadcrumb-item active">Administrar Usuarios</li>
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
              <button type="button" class="btn btn-primary" data-toggle = "modal" data-target ="#modalAgregarUsuario">
                <i class="fas fa-plus-circle"></i> Agregar usuario
              </button>

            </div>
            <div class="card-body">
            <table class="table table-bordered table-striped example1">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Nombre</th>
                  <th>Usuario</th>
                  <th>Perfil</th>
                  <th style="width: 100px">Estado</th>
                  <th>Último ingreso</th>
                  <th style="width: 100px">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $item  = null ;
                $valor = null ;
                  $usuarios = ControladorUsuarios::ctrMostrarUsuario( $item, $valor );
                  //var_dump($usuarios);
                  foreach ($usuarios as $key => $value) {
                  echo '<tr  id="num"  opcion="'.$value["codusuario"].'">
                  <td>'.($key+1).'</td>
                  <td>'.$value["nomusuario"].' '.$value["apepatusuario"].'</td>
                  <td>'.$value["codusuario"].'</td>';


                    echo '<td>'.$value["codperfil"].'</td>';

                    if($value["bloqueado"] == 0 )
                      echo '<td><button class="btn btn-success btn-xs  btnActivar" idUsuario ='.$value["codusuario"].' estadoUsuario= '.$value["bloqueado"].'>Activado</button></td>';
                    else
                      echo '<td><button class="btn btn-danger btn-xs btnActivar" idUsuario ='.$value["codusuario"].' estadoUsuario= '.$value["bloqueado"].'>Desactivado </button></td>';

                     echo '<td>'.$value["fecexpiracion"].'</td>
                      <td>
                        <div class="btn-group">
                          <button class= "btn btn-warning btnEditarUsuario " idUsuario="'.$value["codusuario"].'" data-toggle="modal" data-target="#modalEditarUsuario"><i class = "fas fa-edit"> </i></button>
                          <button class= "btn btn-danger btnEliminarUsuario" idUsuarioEliminar="'.$value["codusuario"].'"  usuarioEliminar="'.$value["codusuario"].'"><i class = "far fa-trash-alt"> </i></button>
                        </div>
                      </td>
                    </tr>';
                  }
                ?>
              </tbody>
            <tfoot>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Nombre</th>
                  <th>Usuario</th>
                  <th>Perfil</th>
                  <th style="width: 100px">Estado</th>
                  <th>Último ingreso</th>
                  <th style="width: 100px">Acciones</th>
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
<!--=====================================
=          Agregar Usuario Modal        =
======================================-->
<!-- Modal -->
<div id="modalAgregarUsuario" class="modal fade">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Agregar Usuario</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">

        <div class = "form-group">
          <div class = "input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class = "fas fa-user"> </i></span>
            </div>
              <input type="text" class="form-control"  name= "nuevoNombre" placeholder="Ingrese Nombre" required>
          </div>
        </div>


        <div class = "form-group">
          <div class = "input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class = "fas fa-key"> </i></span>
            </div>
              <input type="text" class="form-control input-lg"  name= "nuevoUsuario" id= "nuevoUsuario" placeholder="Ingrese Usuario" required>
          </div>
        </div>

        <div class = "form-group">
          <div class = "input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class = "fas fa-lock"> </i></span>
            </div>
              <input type="password" class="form-control input-lg"  name= "nuevoPassword" placeholder="Ingrese Contraseña" required>
          </div>
        </div>

        <div class = "form-group">
          <div class = "input-group">
            <select name="nuevoPerfil" class ="form-control select2" style="width: 100%;">
              <option value="">Seleccionar perfil</option>
               <option value="1">Administrador</option>
               <option value="2">Vendedor</option>
              
              
            </select>
          </div>
        </div>

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
=          Editar Usuario Modal        =
======================================-->
<!-- Modal -->
<div id="modalEditarUsuario" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
      <div class="modal-header bg-info">
        <h4 class="modal-title">Editar Usuario</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <div class="modal-body">
        <div class = "box-body">
          <div class = "form-group">
            <div class = "input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class = "fas fa-user"> </i></span>
              </div>
                <input type="text" class="form-control input-lg" id="editarNombre" name= "editarNombre" placeholder="Edite el Nombre" readonly>


            </div>
          </div>
          <div class = "form-group">
            <div class = "input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class = "fas fa-key"> </i></span>
              </div>
                <input type="text" class="form-control input-lg" id="editarUsuario" name= "editarUsuario" placeholder="Edite el Usuario" required>
            </div>
          </div>

          <div class = "form-group">
            <div class = "input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class = "fas fa-lock"> </i></span>
              </div>
                <input type="password" class="form-control input-lg"  name= "editarPassword" placeholder="Escriba la nueva Contraseña" required>
                <input type="hidden" id= "passwordActual" name= "passwordActual">
            </div>
          </div>

          <div class = "form-group">
            <div class = "input-group">
              <select  id="editarPerfil" name="editarPerfil" class ="form-control select2" style="width: 100%;">
               <option value="">Seleccionar perfil</option>
               <option value="1">Administrador</option>
               <option value="2">Vendedor</option>
               
             </select>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
        <button type="submit" class="btn btn-primary" >Actualizar</button>
      </div>

      </form>
    </div>

  </div>
</div>

<?php date_default_timezone_set('America/Bogota'); ?>
<script src="vistas/js/usuarios.js?<?= date("smH") ?>"></script>
<?php
  $borrarUsuario = new ControladorUsuarios();
  $borrarUsuario -> ctrBorrarUsuario();
?>

