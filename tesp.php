<?php  
 				$encriptar = crypt('123', '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
//$serverName = "DESKTOP-S1IGBM3\SQLEXPRESS"; //Hostname/IP,...
$serverName = "12KPRVPEIC013"; 
//$serverName = "localhost";
$uid = "user_ic";   
$pwd = "Derco.2020";  
$databaseName = "recall_proyecto"; 

class Conexion 
{
    public $host = "12KPRVPEIC013";
    public $Database = "recall_proyecto";
    public $Uid = "user_ic";
    public $PWD = "Derco.2020";

    static public function conectar(){
         //$link = new PDO("mysql:host=".$host.";dbname=".$dbname,$usuario , $password);
         $link = new PDO('sqlsrv:Server=localhost;Database=recall_proyecto', 'user_ic', 'Derco.2020');

         $link ->exec("set names utf8");


        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$link->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

    
         return $link;
    }		

}
$conex = Conexion::conectar();
$usuario  = 'USUARIO';
//$password = '$2a$07$asxx54ahjppf45sd87a5auGZEtGHuyZwm.Ur.FJvWLCql3nmsMbXy';

$select = $conex->prepare("SELECT * from usuario");
 
$select->execute();
$return = $select->fetchAll(PDO::FETCH_ASSOC);
print_r($return);
?>  