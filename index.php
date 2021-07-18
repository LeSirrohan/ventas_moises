<?php

require_once "controladores/plantilla.controlador.php";

require_once "controladores/productos.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/ventas.controlador.php";
require_once "controladores/inventario.controlador.php";
require_once "controladores/categorias.controlador.php";

require_once "controladores/tipo-pago.controlador.php";
require_once "controladores/tipo-afectacion.controlador.php";
require_once "controladores/tipo-cpe.controlador.php";
require_once "controladores/tipo-doc-identidad.controlador.php";
require_once "controladores/unidad-medida.controlador.php";

require_once "controladores/forma-pago.controlador.php";
require_once "controladores/usuario.controlador.php";
require_once "controladores/dashboard.controlador.php";

require_once "modelos/usuario.modelo.php";
require_once "modelos/dashboard.modelo.php";

require_once "modelos/productos.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/ventas.modelo.php";
require_once "modelos/inventario.modelo.php";
require_once "modelos/categorias.modelo.php";

require_once "modelos/tipo-pago.modelo.php";
require_once "modelos/tipo-afectacion.modelo.php";
require_once "modelos/tipo-cpe.modelo.php";
require_once "modelos/tipo-doc-identidad.modelo.php";
require_once "modelos/unidad-medida.modelo.php";

require_once "modelos/forma-pago.modelo.php";
require_once "modelos/bd.modelo.php";
$plantilla = new controladorPlantilla(); // Clase que esta dentro plantilla.controlador.php
$plantilla -> ctrPlantilla();
/*
$pdf = new TCPDF('p','mm','A4');

//add page
$pdf ->AddPage();

//output
$pdf->Output();
*/

