<?php
class bd extends Conexion
{
	static public function sanear_strings_especiales($string)
	{
		$string = str_replace(
			array('ä', 'â', 'à', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'Á', 'A', 'A'),
			$string
		); 
		$string = str_replace(
			array('ë', 'ê','è', 'È', 'Ê', 'Ë'),
			array('e', 'e','e', 'É', 'E', 'E'),
			$string
		);	 
		$string = str_replace(
			array('ï', 'î','ì', 'Ì', 'Ï', 'Î'),
			array('i', 'i','i', 'Í', 'I', 'I'),
			$string
		);	 
		$string = str_replace(
			array('ö', 'ô', 'ò', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'Ó', 'O', 'O'),
			$string
		);
		$string = str_replace(
			array('ü', 'û', 'ù', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'Ú', 'U', 'U'),
			$string
		);	 	 
		$string = str_replace(
			array('ç', 'Ç','!','"','#','%','&','/','?','¿','~','`','^','¬',"'",'{','}','[',']',':'),
			array('c', 'C','','','','','','','','','','','','','','','','','',''),
			$string
		);  
		return $string;

    }
    static public function mdlLimpiezaTransaccion(){

        

        $conn = Conexion::conectar();
        
        $conn->beginTransaction();
        
        $stamnt = $conn->prepare("DELETE FROM deudas_venta;");
        $stamnt = $conn->prepare("ALTER TABLE deudas_venta auto_increment = 1 ;");
        $stamnt = $conn->prepare("DELETE FROM comprobante;");
        $stamnt = $conn->prepare("ALTER TABLE comprobante auto_increment = 1;");
        $stamnt = $conn->prepare("UPDATE inventario SET actual_cantidad = 0 , actual_costo_valorizado = 0;");
        $stamnt = $conn->prepare("DELETE FROM inventario_transaccion;");
        $stamnt = $conn->prepare("ALTER TABLE inventario_transaccion auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM ventas_detalle;");
        $stamnt = $conn->prepare("ALTER TABLE ventas_detalle auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM ventas_x_tipo_cobro;");
        $stamnt = $conn->prepare("ALTER TABLE ventas_x_tipo_cobro auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM ventas;");
        $stamnt = $conn->prepare("ALTER TABLE ventas auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM cliente;");
        $stamnt = $conn->prepare("ALTER TABLE cliente auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM sesion_caja_x_tipo_cobro;");
        $stamnt = $conn->prepare("ALTER TABLE sesion_caja_x_tipo_cobro auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM otros_ingresos_egresos;");
        $stamnt = $conn->prepare("ALTER TABLE otros_ingresos_egresos auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM cliente;");
        $stamnt = $conn->prepare("ALTER TABLE cliente auto_increment = 1;");
        $stamnt = $conn->prepare("DELETE FROM sesion_caja;");
        $stamnt = $conn->prepare("ALTER TABLE sesion_caja auto_increment = 1;");


        $stamnt->execute();

        
		$conn->commit();

    }    
    static public function mdlGenerarLicencias()
    {
        $sql = "SELECT CONCAT('30',"-",
        CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),"-",
        CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),"-",
        CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))),CHAR((FLOOR((RAND() * 25)+65))))";
    }
    static public function insertarAbcVenta($valor,$id_local){

        $conn = Conexion::conectar();
        $stmt = $conn->prepare("INSERT INTO abcventas (valor, id_local) VALUES (:valor,:id_local);");	

        $stmt -> bindParam(":valor", $valor ,PDO::PARAM_STR );
        $stmt -> bindParam(":id_local", $id_local, PDO::PARAM_STR);

        $stmt -> execute();

    }
    static public function mdlBackupBd(){

        date_default_timezone_set('America/Bogota');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $database = 'abcventas';
        $user = 'root';
        $pass = '';
        //$pass = 'ynckgaJp';
        $host = 'localhost';
        $dir = '..' . DS .'content-download' .DS .'backup_bd' . DS . $database.'_'.date('YmdHis').'_'.'.sql';
        $dir_temp = '.' . DS .'content-download' .DS .'backup_bd' . DS . $database.'_'.date('YmdHis').'_'.'.sql';
        $mysqlDir = 'C:'.DS.'laragon'.DS.'bin'.DS.'mysql'.DS.'mysql-5.7.24-win32'.DS.'bin';    // Paste your mysql directory here and be happy
        $mysqldump = $mysqlDir.DS.'mysqldump';
        
        exec("{$mysqldump} --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1", $output);
        
        //var_dump($output);
        return $dir_temp;

    }
    
    static public function mdlBackupBdByLocal( $param_local ){
        //$datos_local = json_decode(file_get_contents($param_local), true);
        $conn = Conexion::conectar();
        date_default_timezone_set('America/Bogota');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        $database = 'abcventas';
        $user = 'root';
        //$pass = '';
        $pass = 'ynckgaJp';
        $host = 'localhost';
        
        $dir          = '..' . DS .'content-download' .DS .'backup_bd' . DS . $param_local['id'] . '_' . $param_local['identificador_local']  ;
        $archivo      = $dir. DS. $database.'_'.date('YmdHis').'.sql';
        $dir_temp     = '.' . DS .'content-download' .DS .'backup_bd' . DS . $param_local['id'] . '_' . $param_local['identificador_local']  ;
        $archivo_temp = $dir_temp. DS. $database.'_'.date('YmdHis').'.sql';


		file_put_contents('mdlBackupBdByLocal.txt',$dir);
		if(!is_dir($dir))
            mkdir($dir, 0755);

        $mysqlDir = 'C:'.DS.'laragon'.DS.'bin'.DS.'mysql'.DS.'mysql-5.7.24-win32'.DS.'bin';
        $mysqldump = $mysqlDir.DS.'mysqldump';
        
        exec("{$mysqldump} --user={$user} --password={$pass} --host={$host} {$database} --result-file={$archivo} 2>&1", $output);
        
        //var_dump($output);
        return $archivo_temp;

    }
    static public function chequearAbcVenta($id_local){
        $conn = Conexion::conectar();
        $stmt = $conn->prepare("SELECT token_licencia,fecha_ultimo_ingreso, fecha_vencimiento,tipo_local,url_tienda_virtual, auxiliar_abcventas from local WHERE id = :id_local;");
        $stmt -> bindParam(":id_local", $id_local ,PDO::PARAM_INT );
        $stmt -> execute();
        $return = $stmt -> fetchAll(PDO::FETCH_ASSOC);	
        return $return[0];

    }
	/*
	static public function decrypt($data, $secret)
	{
		//Generate a key from a hash
		$key = md5(utf8_encode($secret), true);
	
		//Take first 8 bytes of $key and append them to the end of $key.
		$key .= substr($key, 0, 8);
	
		$data = base64_decode($data);
	
		$data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');
	
		$block = 8 ; // mcrypt_get_block_size('tripledes', 'ecb');
      //  $file ="./jose123123123.txt";
      //  file_put_contents($file, $block);

		$len = strlen($data);
		$pad = ord($data[$len-1]);
	
		return substr($data, 0, strlen($data) - $pad);
	} */


 static public  function decrypt($string , $secret ) 
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key =   $secret ;
        $secret_iv = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
        // hash
        $key = hash('sha256', $secret_key);    
        // iv - encrypt method AES-256-CBC expects 16 bytes 
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        
        return $output;
    }


 static public  function encrypt( $string , $secret ) 
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = $secret ;
        $secret_iv = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
        // hash
        $key = hash('sha256', $secret_key);    
        // iv - encrypt method AES-256-CBC expects 16 bytes 
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
       
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        
        return $output;
    }

/*
	static public function encrypt($data, $secret)
	{
		//Generate a key from a hash
		$key = md5(utf8_encode($secret), true);
	
		//Take first 8 bytes of $key and append them to the end of $key.
		$key .= substr($key, 0, 8);
	
		//Pad for PKCS7
		$blockSize = 8 ; // mcrypt_get_block_size('tripledes', 'ecb');
		$len = strlen($data);
		$pad = $blockSize - ($len % $blockSize);
		$data .= str_repeat(chr($pad), $pad);
	
		//Encrypt data
		$encData = mcrypt_encrypt('tripledes', $key, $data, 'ecb');
	
		return base64_encode($encData);
	}
    */
    static public function ingresarLicenciaAbcVenta($valor,$id_local){
        $conn = Conexion::conectar();
        //Buscar que la licencia no haya sido ingresada
        $conn->beginTransaction();
        $stmt = $conn->prepare("SELECT * from licencia WHERE licencia_ingresada = :valor AND id_local = :id_local;");	

        $stmt -> bindParam(":valor", $valor ,PDO::PARAM_STR );
        $stmt -> bindParam(":id_local", $id_local ,PDO::PARAM_INT );
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_format = $fecha.' '.$hora;

        $stmt -> execute();
        $licencia = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        if(count($licencia) > 0)
        {
            $conn->rollback();
            return array("mensaje"=>"ESTA LICENCIA YA HA SIDO INGRESADA","error"=>1);
        }
        else{
            // Buscar la licencia en la tabla ABCVENTAS
            $stmt_local = $conn->prepare("SELECT token_licencia, fecha_vencimiento from local WHERE id = :id_local2;");	
    
            $stmt_local -> bindParam(":id_local2", $id_local ,PDO::PARAM_INT );
    
            $stmt_local -> execute();
            $local = $stmt_local -> fetchAll(PDO::FETCH_ASSOC);
            $local = $local[0];
            // Buscar la licencia en la tabla ABCVENTAS
            $valor_licencia = self::encrypt($valor,$local["token_licencia"]);$file ="./licencia.txt";
            file_put_contents($file, $valor_licencia);

            $stmt_abc = $conn->prepare("SELECT valor from abcventas WHERE valor = :valor2;");	
    
            $stmt_abc -> bindParam(":valor2", $valor_licencia ,PDO::PARAM_INT );
    
            $stmt_abc -> execute();
            $abc = $stmt_abc -> fetchAll(PDO::FETCH_ASSOC);
            //$abc = $abc[0];
            if(count($abc)>0)
            {
                
                //$licencia_encriptada = self::encrypt($valor,$local["token_licencia"])
                $fecha_vencimiento = self::decrypt($local["fecha_vencimiento"],$local["token_licencia"]);
                $dias_agregar = explode("-",$valor);
                if(strtotime($fecha_vencimiento) < strtotime("now"))
                {
                    //Si la fecha de la licencia es menor, se suman los días a la fecha de vencimiento
                    $date = new DateTime(date('Y-m-d'));
                    $date->modify('+'.((int)$dias_agregar[0]).' day');
                    $fecha_activacion = $date->format('Y-m-d');
                }
                else{
                    //Si la fecha de la licencia es mayor, se suman los días de la activación
                    $date = new DateTime($fecha_vencimiento);
                    $date->modify('+'.((int)$dias_agregar[0]).' day');
                    $fecha_activacion = $date->format('Y-m-d');
                }
                //$fecha_activacion = date("Y-m-d",$fecha_vencimiento);
                $nueva_fecha_vencimiento = self::encrypt($fecha_activacion,$local["token_licencia"]);
                //Se registra la licencia ingresada
                $insert = $conn->prepare( "INSERT INTO licencia (licencia_ingresada,fecha_ingreso, fecha_termino_licencia, id_local) VALUES
                (:lic_ing,:fecha_ing, :fecha_fin_lic, :id_local3);");
                $insert->bindParam(":lic_ing",$valor, PDO::PARAM_STR);
                $insert->bindParam(":fecha_ing",$fecha_format, PDO::PARAM_STR);
                $insert->bindParam(":fecha_fin_lic",$fecha_activacion, PDO::PARAM_STR);
                $insert->bindParam(":id_local3",$id_local, PDO::PARAM_INT);
                $insert->execute();

                $update = $conn->prepare( "UPDATE local 
                SET 
                fecha_vencimiento = :fecha_vencimiento
                WHERE
                id = :id_local4");
                $update->bindParam(":fecha_vencimiento",$nueva_fecha_vencimiento, PDO::PARAM_STR);
                $update->bindParam(":id_local4",$id_local, PDO::PARAM_INT);
                $update->execute();
                $conn->commit();
                /*$file ="./licencia.txt";
                file_put_contents($file, $nueva_fecha_vencimiento);*/
                return array("mensaje"=>"LICENCIA ACTIVADA","error"=>0);
            

            }
            else{
                $conn->rollback();
                return array("mensaje"=>"LICENCIA NO VÁLIDA","error"=>1);

            }
            //Se actualiza la tabla local
            
            
            /*$file ="./licencia.txt";;
            
            file_put_contents($file, $licencia_encriptada);*/

        }
		/*$file ="./licencia.txt";
		file_put_contents($file, json_encode($licencia) );*/


    }
    
    var $Void = "";
    var $SP   = " ";
    var $Dot  = ".";
    var $Zero = "0";
    var $Neg  = "Menos";
    
    function ValorEnLetras($x, $Moneda )  
    { 
        $s=""; 
        $Ent=""; 
        $Frc=""; 
        $Signo=""; 
            
        if(floatVal($x) < 0) 
        $Signo = $this->Neg . " "; 
        else 
        $Signo = ""; 
        
        if(intval(number_format($x,2,'.','') )!=$x) //<- averiguar si tiene decimales 
        $s = number_format($x,2,'.',''); 
        else 
        $s = number_format($x,2,'.',''); 
            
        $Pto = strpos($s, $this->Dot); 
            
        if ($Pto === false) 
        { 
        $Ent = $s; 
        $Frc = $this->Void; 
        } 
        else 
        { 
        $Ent = substr($s, 0, $Pto ); 
        $Frc =  substr($s, $Pto+1); 
        } 

        if($Ent == $this->Zero || $Ent == $this->Void) 
        $s = "Cero "; 
        elseif( strlen($Ent) > 7) 
        { 
        $s = $this->SubValLetra(intval( substr($Ent, 0,  strlen($Ent) - 6))) .  
                "Millones " . $this->SubValLetra(intval(substr($Ent,-6, 6))); 
        } 
        else 
        { 
        $s = $this->SubValLetra(intval($Ent)); 
        } 

        if (substr($s,-9, 9) == "Millones " || substr($s,-7, 7) == "Millón ") 
        $s = $s . "de "; 

        $s = $s . $Moneda; 

        if($Frc != $this->Void) 
        { 
        $s = $s . " " . $Frc. "/100"; 
        //$s = $s . " " . $Frc . "/100"; 
        } 
        $letrass=$Signo . $s;// . " M.N."; 
        return ($Signo . $s);// . " M.N."); 
        
    } 


    function SubValLetra($numero)  
    { 
        $Ptr=""; 
        $n=0; 
        $i=0; 
        $x =""; 
        $Rtn =""; 
        $Tem =""; 

        $x = trim("$numero"); 
        $n = strlen($x); 

        $Tem = $this->Void; 
        $i = $n; 
        
        while( $i > 0) 
        { 
        $Tem = $this->Parte(intval(substr($x, $n - $i, 1).  
                            str_repeat($this->Zero, $i - 1 ))); 
        If( $Tem != "Cero" ) 
            $Rtn .= $Tem . $this->SP; 
        $i = $i - 1; 
        } 

        
        //--------------------- GoSub FiltroMil ------------------------------ 
        $Rtn=str_replace(" Mil Mil", " Un Mil", $Rtn ); 
        while(1) 
        { 
        $Ptr = strpos($Rtn, "Mil ");        
        If(!($Ptr===false)) 
        { 
            If(! (strpos($Rtn, "Mil ",$Ptr + 1) === false )) 
                $this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr); 
            Else 
            break; 
        } 
        else break; 
        } 

        //--------------------- GoSub FiltroCiento ------------------------------ 
        $Ptr = -1; 
        do{ 
        $Ptr = strpos($Rtn, "Cien ", $Ptr+1); 
        if(!($Ptr===false)) 
        { 
            $Tem = substr($Rtn, $Ptr + 5 ,1); 
            if( $Tem == "M" || $Tem == $this->Void) 
                ; 
            else           
                $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr); 
        } 
        }while(!($Ptr === false)); 

        //--------------------- FiltroEspeciales ------------------------------ 
        $Rtn=str_replace("Diez Un", "Once", $Rtn ); 
        $Rtn=str_replace("Diez Dos", "Doce", $Rtn ); 
        $Rtn=str_replace("Diez Tres", "Trece", $Rtn ); 
        $Rtn=str_replace("Diez Cuatro", "Catorce", $Rtn ); 
        $Rtn=str_replace("Diez Cinco", "Quince", $Rtn ); 
        $Rtn=str_replace("Diez Seis", "Dieciseis", $Rtn ); 
        $Rtn=str_replace("Diez Siete", "Diecisiete", $Rtn ); 
        $Rtn=str_replace("Diez Ocho", "Dieciocho", $Rtn ); 
        $Rtn=str_replace("Diez Nueve", "Diecinueve", $Rtn ); 
        $Rtn=str_replace("Veinte Un", "Veintiun", $Rtn ); 
        $Rtn=str_replace("Veinte Dos", "Veintidos", $Rtn ); 
        $Rtn=str_replace("Veinte Tres", "Veintitres", $Rtn ); 
        $Rtn=str_replace("Veinte Cuatro", "Veinticuatro", $Rtn ); 
        $Rtn=str_replace("Veinte Cinco", "Veinticinco", $Rtn ); 
        $Rtn=str_replace("Veinte Seis", "Veintiseís", $Rtn ); 
        $Rtn=str_replace("Veinte Siete", "Veintisiete", $Rtn ); 
        $Rtn=str_replace("Veinte Ocho", "Veintiocho", $Rtn ); 
        $Rtn=str_replace("Veinte Nueve", "Veintinueve", $Rtn ); 

        //--------------------- FiltroUn ------------------------------ 
        If(substr($Rtn,0,1) == "M") $Rtn = "Un " . $Rtn; 
        //--------------------- Adicionar Y ------------------------------ 
        for($i=65; $i<=88; $i++) 
        { 
        If($i != 77) 
            $Rtn=str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn); 
        } 
        $Rtn=str_replace("*", "a" , $Rtn); 
        return($Rtn); 
    } 


    function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr) 
    { 
    $x = substr($x, 0, $Ptr)  . $NewWrd . substr($x, strlen($OldWrd) + $Ptr); 
    } 


    function Parte($x) 
    { 
        $Rtn=''; 
        $t=''; 
        $i=''; 
        Do 
        { 
        switch($x) 
        { 
            case 0:  $t = "Cero";break; 
            case 1:  $t = "Un";break; 
            case 2:  $t = "Dos";break; 
            case 3:  $t = "Tres";break; 
            case 4:  $t = "Cuatro";break; 
            case 5:  $t = "Cinco";break; 
            case 6:  $t = "Seis";break; 
            case 7:  $t = "Siete";break; 
            case 8:  $t = "Ocho";break; 
            case 9:  $t = "Nueve";break; 
            case 10: $t = "Diez";break; 
            case 20: $t = "Veinte";break; 
            case 30: $t = "Treinta";break; 
            case 40: $t = "Cuarenta";break; 
            case 50: $t = "Cincuenta";break; 
            case 60: $t = "Sesenta";break; 
            case 70: $t = "Setenta";break; 
            case 80: $t = "Ochenta";break; 
            case 90: $t = "Noventa";break; 
            case 100: $t = "Cien";break; 
            case 200: $t = "Doscientos";break; 
            case 300: $t = "Trescientos";break; 
            case 400: $t = "Cuatrocientos";break; 
            case 500: $t = "Quinientos";break; 
            case 600: $t = "Seiscientos";break; 
            case 700: $t = "Setecientos";break; 
            case 800: $t = "Ochocientos";break; 
            case 900: $t = "Novecientos";break; 
            case 1000: $t = "Mil";break; 
            case 1000000: $t = "Millón";break; 
        } 

        if($t == $this->Void) 
        { 
            $i = $i + 1; 
            $x = $x / 1000; 
            If($x== 0) $i = 0; 
        } 
        else 
            break; 
                
        }while($i != 0); 
        
        $Rtn = $t; 
        switch($i) 
        { 
        case 0: $t = $this->Void;break; 
        case 1: $t = " Mil";break; 
        case 2: $t = " Millones";break; 
        case 3: $t = " Billones";break; 
        } 
        return($Rtn . $t); 
    } 
    static public function agregarLog($modulo, $accion, $datos = "")
    {
        date_default_timezone_set('America/Bogota');
        $conex = Conexion::Conectar();
        $conex->beginTransaction();
        $fecha = date("Y-m-d H:i:s");
        
        //Se borran los registros anteriores a 5 días

        try{
        $delete_log = "DELETE FROM log WHERE datediff(:fecha_now, log.fecha) > 5; ";
        
        $delete = $conex->prepare("$delete_log");
        $delete->bindParam(":fecha_now", $fecha, PDO::PARAM_STR);
        $delete->execute();
		

        $insert_log =  "INSERT INTO log (modulo, accion, datos, fecha) VALUES 
        (:modulo, :accion, :datos, :fecha);";
        $insert = $conex->prepare("$insert_log");
        $insert->bindParam(":modulo", $modulo, PDO::PARAM_STR);
        $insert->bindParam(":accion", $accion, PDO::PARAM_STR);
        $insert->bindParam(":datos", $datos, PDO::PARAM_STR);
        $insert->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $insert->execute();
            $conex->commit();
        }
        catch( Exception $th ){
            $conex->rollback();
			file_put_contents("agregarLog.txt",$th->getMessage());
            return $th->getMessage();
        }

    }


}


?>