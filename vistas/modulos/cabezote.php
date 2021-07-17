<?php
date_default_timezone_set('America/Bogota'); ?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light pt-2 bg-danger" >
	<!-- Left navbar links -->
	<ul class="navbar-nav" >
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color:#ffffff"></i></a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="inicio" class="nav-link" style="color:#ffffff">INICIO</a>
		</li>
	</ul>

	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
		<!-- Horario -->
		<li class="nav-item dropdown">
			<a class="clock nav-link" href="#"style="color:#ffffff" id="lblDate"></a>
		</li>
		<li class="dropdown user user-menu ml-3 mt-2">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:#ffffff">

				<?php
					/*if($_SESSION["foto"] == "" )
						echo '<img src="vistas/img/usuarios/default/anonymous.png" class="user-image">';
					else
						//echo '<img src="'.$_SESSION["foto"].'" class="user-image">';*/
						echo '<i class="fa fa-user"></i>';
				?>
				<span class="hidden-xs" style="color:#ffffff;"> <?php echo $_SESSION["nombre"]  ?></span>
			</a>

						<!-- drop down-->
			<ul class="dropdown-menu">
				<li class="user-body" >
					<div class="pull-right" >
						<a href="salir" class="btn btn-default btn-flat">Salir</a>
					</div>
				</li>
			</ul>
		</li>

	</ul>
</nav>
<!-- /.navbar -->

<!--Comprobante-->


<script>
	
function updateTime() {
    var dtString = new Date().toString();
    $('#lblDate').html(dtString);
    setTimeout(updateTime,1000);
}
updateTime();

</script>
