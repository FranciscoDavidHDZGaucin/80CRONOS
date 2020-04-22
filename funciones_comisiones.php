<?php

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


function meta($agente,$mes,$anio){
    
    
     $string_mm=sprintf("select meta_mes from objetivo_agentes where agente=%s and mes=%s and anio=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"), 
                                            GetSQLValueString($anio, "int"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string_mm);
    $stmt->execute();
    $datos = $stmt->fetch();
    $meta_mes= $datos[0];  //Posicion del campo en la tabla empieza desde 0
 
    return $meta_mes;
   
    
}

function meta_excelencia($agente,$mes,$anio){
    
    
     $string_mm=sprintf("select excelencia_mes from objetivo_agentes where agente=%s and mes=%s and anio=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"), 
                                            GetSQLValueString($anio, "int"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string_mm);
    $stmt->execute();
    $datos = $stmt->fetch();
    $meta_mes= $datos[0];  //Posicion del campo en la tabla empieza desde 0
 
    return $meta_mes;
   
    
}




function venta($agente,$mes,$anio){
    
    
     $string_venta=sprintf("select sum(tot_linea) as tot_linea from ventas where agente2=%s and month(falta_fac2)=%s and year(falta_fac2)=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"), 
                                            GetSQLValueString($anio, "int"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string_venta);
    $stmt->execute();
    $datos = $stmt->fetch();
    $venta= $datos[0];  //Posicion del campo en la tabla empieza desde 0
 
    return $venta;
   
    
}

function ventaxprod($agente,$mes,$anio,$producto){
    
    ///Muesta la cantdiad en volumen de venta
     $string_venta=sprintf("select sum(tot_cant) as tot_cant from ventas where agente2=%s and month(falta_fac2)=%s and year(falta_fac2)=%s and codigo2=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"), 
                                            GetSQLValueString($anio, "int"),
                                             GetSQLValueString($producto, "text"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string_venta);
    $stmt->execute();
    $datos = $stmt->fetch();
    $venta= $datos[0];  //Posicion del campo en la tabla empieza desde 0
 
    return $venta;
   
    
}

function suma_proyeccion_agente($agente,$mes,$anio){
    
    ///Muesta la cantdiad en volumen de venta
     $string_proy=sprintf("select sum(monto_costo) as monto_costo from vista_pronostico where cve_age=%s and mes=%s and anio=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"), 
                                            GetSQLValueString($anio, "int"));
                                            
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string_proy);
    $stmt->execute();
    $datos = $stmt->fetch();
    $proy= $datos[0];  //Posicion del campo en la tabla empieza desde 0
 
    return $proy;
   
    
}


///Proyeccion 
function comision_mes($agente,$mes,$anio){
    
    
     $string_comi=sprintf("select comision from resumen_comisiones where agente=%s and mes=%s and anio=%s",
                GetSQLValueString($agente, "int"),
                GetSQLValueString($mes, "int"), 
                GetSQLValueString($anio, "int"));
      
      $conn = dbConnect();
      
    // Extract the values from $result
  
    $stmt = $conn->prepare($string_comi);
    $stmt->execute();
    $datosc = $stmt->fetch();
    $comi= $datosc[0];  //Posicion del campo en la tabla empieza desde 0
 
    return $comi;
   
    
}



function  nombre_mes($nmes){
    
    switch ($nmes) {
        case 1:
            $nombre="Enero";

            break;

          case 2:
            $nombre="Febrero";

            break;
           case 3:
            $nombre="Marzo";

            break;
           case 4:
            $nombre="Abril";

            break;
           case 5:
            $nombre="Mayo";

            break;
           case 6:
            $nombre="Junio";

            break;  
            case 7:
            $nombre="Julio";

            break;
        case 7:
            $nombre="Julio";

            break;
        case 7:
            $nombre="Julio";

            break;
         case 8:
            $nombre="Agosto";

            break;
        case 9:
            $nombre="Septiembre";

            break;
          case 10:
            $nombre="Octubre";

            break;
          case 11:
            $nombre="Noviembre";

            break;
          case 12:
            $nombre="Diciembre";

            break;
        
    }
    return $nombre;
    
}

function campo_comi($tipo){
    switch ($tipo) {
        case 5:
                $campo="U_com_verur";

            break;
         case 6:
                $campo="U_com_local";

            break;

        default:
              $campo="U_com_reg";
            break;
    }
    return $campo;
}

function leyenda($valor1){
    
    if ($valor1==1){
       $leyenda="SI"; 
    }else{
       $leyenda="NO"; 
    }
    return $leyenda;
    
}


///Funcion para conocer si el mes en el que estamos es un trimestre
function estrimestre($nmes){
    
    switch ($nmes) {
        case 3:
            $resultado=1;


            break;

        case 6:
            $resultado=1;


            break;
         case 9:
            $resultado=1;


            break;
         case 12:
            $resultado=1;


            break;
    }
    return $resultado;
}

?>