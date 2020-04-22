<?php
////***adi_add_adicional_gerentes
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_add_adicional_gerentes.php 
 	Fecha  Creacion : 19/11/2016
	Descripcion  : 
               Script  para  agregar  el adicional  para los  gerentes
	Modificado  Fecha  : 
  *         23/11/2016  Se  agrega la  insert de   adicionales  gerentes 
  *          la variable  $autorizacion  =1   dado  que  ellos  no   necesitan  de autorizacion  
*/

///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
////***Obtenemos los datos  
$nombre_usuario = filter_input(INPUT_POST, 'nomUsu');
$tipo_usuario = filter_input(INPUT_POST, 'type_usu');
$codigo_producto = filter_input(INPUT_POST, 'cdg_pro');
$nomb_producto = filter_input(INPUT_POST, 'nomPro');
$fec_sol = filter_input(INPUT_POST, 'fec_sol');
$fec_rq = filter_input(INPUT_POST, 'fec_rq');
$pre_solPV = filter_input(INPUT_POST, 'pre_solPV');
$can_rq = filter_input(INPUT_POST, 'can_rq');
$almacen = filter_input(INPUT_POST, 'almacen');
$invt= filter_input(INPUT_POST, 'invt');
$proycc= filter_input(INPUT_POST, 'proycc'); ///***Proyeccion por mes  total 
$num_usuario = filter_input(INPUT_POST, 'Num_USU');
///****AUTORIZACION   POR DEFAULT   
$autorizacion  =1;   //// Los  Gerentes  no necesitan  de autorizacion para  los adicionaless
/////****Generamos la cadena d e insert
$string_insert_adicionales = sprintf("INSERT INTO  adicionales SET nombre_usu=%s ,tipo_usuario=%s ,codigo_pro=%s,nom_pro=%s,fecha_sol=%s,fecha_rq=%s,precio_sol_pv=%s,cant_req=%s,almacen=%s,inventario=%s,proyec_for_mes=%s,cve_usuario=%s,auto=%s",
 GetSQLValueString($nombre_usuario, "text"),
 GetSQLValueString($tipo_usuario, "int"),
 GetSQLValueString($codigo_producto,"text" ),
 GetSQLValueString($nomb_producto, "text"),
 GetSQLValueString($fec_sol, "date"),
 GetSQLValueString($fec_rq, "date"),
 GetSQLValueString($pre_solPV, "int"),
 GetSQLValueString($can_rq, "int"),
 GetSQLValueString($almacen, "int"),
 GetSQLValueString($invt, "int"),
 GetSQLValueString($proycc, "int"),
 GetSQLValueString($num_usuario, "int"),
  GetSQLValueString($autorizacion, "int"));
///********************************
///$resultQuerey=mysqli_query($conecata1,$string_insert_adicionales)or  die (mysqli_error($conecata1));

$obj_mysql =  new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
 

if(!$obj_mysql->query($string_insert_adicionales))
{
   $array_result =  array(
       "RE"=>"0",
       "cadena" => $string_insert_adicionales
   );   
    
}else{
    
   $array_result =  array(
       "RE"=>"1" 
   );  
}
$CONVER_JSON = json_encode($array_result);       
header('Content-type: application/json');
echo  $CONVER_JSON;
              


?>

