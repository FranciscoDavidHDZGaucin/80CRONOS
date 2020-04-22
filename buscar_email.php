<?php

//require_once('Connections/conecta1.php');
//require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

function email($usuario){
/*
mysql_select_db($database_conecta1,$conecta1);
$string=sprintf("Select email from usuarios_locales where usuario=%s",
            GetSQLValueString($usuario,"text"));
			
$query_string=mysql_query($string,$conecta1) or die (mysql_error());
$datos_string=mysql_fetch_array($query_string);

return $datos_string['email'];
*/
    
 switch ($usuario){
     
     case "facforanea":
         $correo="jalba@agroversa.com.mx";
       break;
     case "facforanea2":
         $correo="facturacion2@agroversa.com.mx";
       break;
     case "faclocal":
         $correo="facturacion2@agroversa.com.mx";
      break;   
     case "facverur":    
         $correo="jalba@verur.com.mx";
     break;
  case "credito":
         $correo="jgarcia@agroversa.com.mx";
       break;
   case "credito_aux":
         $correo="badame@agroversa.com.mx";
       break; 
  case "logistica":
         $correo="auxlogistica@agroversa.com.mx";
       break;   
    case "logistica2":
         $correo="auxlogistica@agroversa.com.mx"; //Victoria vallejo
       break;   
    case "logistica3":
         $correo="grangel@agroversa.com.mx";   //Maria del Socorro
       break;   
    case "logistica_boss":
         $correo="igarcia@agroversa.com.mx";   //Ismael
       break;   
    case "sistemas":
         $correo="egonzalez@agroversa.com.mx";   //Erik
       break; 
    case "sistemas_qv":
         $correo="efuentes@agroversa.com.mx";   //Enrique
       break;   
   case "dircom":
         $correo="lromero@agroversa.com.mx";
       break;   
   case "corlocal":
         $correo="sverag@agroversa.com.mx";
       break; 
     case "scliente":   //Servicio al cliente
         $correo="emena@agroversa.com.mx";
       break; 
 }   
    return $correo;
}

?>