<?php



require_once('formato_datos.php');


//Conexion PDO para que funcione la funcion avisovta
function dbConnect (){
    $conn = null;
    $host = 'localhost';
    $db =   'pedidos';
    $user = 'root';
    $pwd =  'avsa0543';
    try {
        $conn = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pwd);
        //echo 'Connected succesfully.<br>';
    }
    catch (PDOException $e) {
        echo '<p>Cannot connect to database !!</p>';
        exit;
    }
    return $conn;
 }

 
 
 
///************* Inicio Funciones  Copia  Proyecto  Proyeccion**************************************
 ///Buscar el correo del gerente se pide el código del Agente 

 
function name_mes($nmes) 
{
 

  
	 switch ($nmes)
	{
	 case 1:
			$nombremes1="ENERO";
			
			
			break;
	 case 2:
			$nombremes1="FEBRERO";
			
			break;
	 case 3:
			$nombremes1="MARZO";
			
			break;
	 case 4:
			$nombremes1="ABRIL";
			
			break;
	case 5:
			$nombremes1="MAYO";
			
			break;		
	case 6:
			$nombremes1="JUNIO";
			
			break;
	case 7:
			$nombremes1="JULIO";
			
			break;
	case 8:
			$nombremes1="AGOSTO";
			
			break;
	case 9:
			$nombremes1="SEPTIEMBRE";
			break;
	case 10:
			$nombremes1="OCTUBRE";
			break;
	case 11:
			$nombremes1="NOVIEMBRE";
			break;
	case 12:
			$nombremes1="DICIEMBRE";
			break;
		

	}

  return $nombremes1;

}
//****************************************************************************************************

  function zona($agente){
    
    try
    {
      $conn = dbConnect();
     
    // Extract the values from $result
  
    $stmt = $conn->prepare('SELECT * FROM relacion_gerentes WHERE cve_age=:agente');
  $stmt->execute(array(':agente'=>$agente));
  $datos = $stmt->fetch();
  $namezona= $datos[3];
   return $namezona;
    }
    catch(PDOException $e){
        // echo '<p>Error!!</p>';
    }
   
   
    
 }
 
 
 ///funcion para revisar si existe el dato en la tabla de proyeccion
 // el resultado es 0=No existe   1= Si existe
 function buscar_datoproy_centi($agente,$almacen,$mes,$anio,$prod){
     
      $string1=sprintf("SELECT id from pronostico where cve_age=%s and cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                       GetSQLValueString($agente, "int"),
                       GetSQLValueString($almacen, "int"),
                       GetSQLValueString($mes, "int"),
                       GetSQLValueString($anio, "int"),
                     GetSQLValueString($prod, "text"));
      
    $conn = dbConnect();
    
    $stmt = $conn->prepare($string1);
    $stmt->execute();
    $datos = $stmt->fetch();
    $valor= $datos[0];  //Posicion del campo en la tabla empieza desde 0
    
     if (is_null($valor)){
         $resultado=0;  ///No existe el dato
     }else{
         $resultado=1;  ///Si existe el dato 
     }
     
     return $resultado;
 }
 
 
 ///Obtener la cantidad y demanda de un dato en proyeccion en especifico
  function buscar_datoproy($agente,$almacen,$mes,$anio,$prod){
        $string1=sprintf("SELECT cantidad,demanda from pronostico where cve_age=%s and cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                       GetSQLValueString($agente, "int"),
                       GetSQLValueString($almacen, "int"),
                       GetSQLValueString($mes, "int"),
                       GetSQLValueString($anio, "int"),
                     GetSQLValueString($prod, "text"));
    $conn = dbConnect();
    
    $stmt = $conn->prepare($string1);
    $stmt->execute();
    $datos = $stmt->fetch();
    $cantidad= $datos[0];  //Posicion del campo en la tabla empieza desde 0   
    $demanda= $datos[1]; 
    
     if (is_null($cantidad)){
         $cantidad=0;
    
         
     }
    
      if (is_null($demanda)){
         
          $demanda=0;
     }
    
     return array($cantidad,$demanda);
      
      
  }
 
 
  
  function modificar_proyeccion($usuario_proyeccion){
      
      
           $string1=("SELECT capturar, capturarg from configurar limit 1");
                      
      
    $conn = dbConnect();
    
    $stmt = $conn->prepare($string1);
    $stmt->execute();
    $datos = $stmt->fetch();
    $valor= $datos[0];  //Permiso para el Agente
    $valor2= $datos[1];  //Permiso para el Gerente
      
      
    switch ($usuario_proyeccion) {
        case 101:
                 ///Validar para el agente
            if($valor==1){
                //permite accesar
                $accesar=1;
            }else{
                //No permite accesar
                 $accesar=0;
            }
            
            break;

        default:
             ///Validar para el Gerente
                ///Validar para el agente
            if($valor2==1){
                //permite accesar
                $accesar=1;
            }else{
                //No permite accesar
                 $accesar=0;
            }
            
            
            break;
    }
    
    
    return $accesar;
    
  }
 
 
?>
