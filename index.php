<?php

require_once "controladores/plantilla.controlador.php";

require_once "controladores/productos.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/ventas.controlador.php";

require_once "controladores/tipo-cobro.controlador.php";
require_once "controladores/usuario.controlador.php";

require_once "modelos/usuario.modelo.php";

require_once "modelos/productos.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/ventas.modelo.php";

require_once "modelos/tipo-cobro.modelo.php";
$plantilla = new controladorPlantilla(); // Clase que esta dentro plantilla.controlador.php
$plantilla -> ctrPlantilla();
/*
$pdf = new TCPDF('p','mm','A4');

//add page
$pdf ->AddPage();

//output
$pdf->Output();
*/

