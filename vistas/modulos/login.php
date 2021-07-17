<div class="login-page" style="background: url('vistas/img/plantilla/venta-1.jpg'); background-repeat: no-repeat; background-position: center center; background-size: cover">

<div class="login-box">

  <div class="login-logo">
  <img src="vistas/img/plantilla/logo-blanco-bloque.png" class="img-responsive" style="width:70%">
  </div>
  <!-- /.login-logo -->
  <div class="card">
  <div class="card-body login-card-body">
    <p class="login-box-msg">Buen día, ingrese al sistema.</p>

    <form autocomplete="off" method="post">
        <div class="form-group">
          <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Usuario" name="ingUsuario" required>
          <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          </div>
        </div>
        </div>
        <div class="form-group">
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name ="ingPassword" required>
            <div class="input-group-append">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
          </div>
        </div>
      <div class="row">
        <div class="col-7">&nbsp;
        </div>
        <!-- /.col -->
        <div class="col-5">
          <button type="submit" class="btn btn-danger btn-block">Ingresar <i class="fas fa-sign-in-alt"></i></button>
        </div>
        <!-- /.col -->
      </div>
<?php
$login = new ControladorUsuarios();
$login -> ctrIngresoUsuario();

?>


    </form>




  </div>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

</div>
