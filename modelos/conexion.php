<?php
/*
//$serverName = "serverName\\sqlexpress"; 
$serverName = "10.110.20.59"; //serverName\instanceName
$serverName = "DESKTOP-S1IGBM3\SQLEXPRESS"; //Hostname/IP,...12KPRVPEIC01
//$serverName = "localhost"; //Hostname/IP,...12KPRVPEIC01
//$connectionString="data source=DESKTOP-S1IGBM3\SQLEXPRESS;initial catalog=FENIXBD;integrated security=True;MultipleActiveResultSets=True;App=EntityFramework" 
$connectionOptions = array(
    "Database" => "proyecto_tracking",
    "Uid" => "root",
    "PWD" => "123"
);

//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true)); //See why it fails
} */


class Conexion {

     static public function conectar(){
        $contraseña = "123456";
        $usuario = "postgres";
        $nombreBaseDeDatos = "cn_ventas";
        # Puede ser 127.0.0.1 o el nombre de tu equipo; o la IP de un servidor remoto
        $rutaServidor = "localhost";
        $puerto = "5433";
        $link = new PDO("pgsql:host=$rutaServidor;port=$puerto;dbname=$nombreBaseDeDatos", $usuario, $contraseña);
        $link ->exec("SET names utf8;");
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
          

     
          return $link;
     }			

}