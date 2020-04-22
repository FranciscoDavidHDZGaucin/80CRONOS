<?php

 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :adi_get_proyeccion.php  
 	Fecha  Creacion : 10/11/2016  
	Descripcion  : 
            Script   para obtener  la  Proyeccio de  un Determinado
  *         Producto  por cierto  almacen  por cierta  Fecha 
  *         Nombre  del  Procedimiento  Usado = call  Get_Pronostico (128,1780,'2016-12-05');
	Modificado  Fecha  : 
*/

///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

$fecha_req  = filter_input(INPUT_POST, 'fech_req');
$cve_agente = filter_input(INPUT_POST, 'usuario_agente');
$cve_producto = filter_input(INPUT_POST, 'cdg_pro');
$cve_alamacen    =  filter_input(INPUT_POST,'alma');

///****Generamos la  Cadena 
///Call Get_Pronostico(cve_agente int ,cve_producto int , fecha  date)       
$cadena_procedure = "select  function_get_pronostico(".$cve_agente.",'".$cve_producto."','".$cve_alamacen."','".$fecha_req."') as Res";///'Call Get_Pronostico('.$cve_agente.','.$cve_producto.','."'".  GetSQLValueString($fecha_req, "DATE")."'".' )'; 
////***********
$mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

if(!$ROW =$mysqli_PRO->query($cadena_procedure)){
   $array_json  =  array(
    'DMA'=>"ER"
 ); 
    
}else{ 
///***Convertimos    asocitivo  la  respuesta
   $RESASO = $ROW->fetch_array(MYSQLI_ASSOC);
$array_json  =   array(
    'DMA'=>$RESASO['Res']  ///$result->fetch_array(MYSQLI_BOTH);
 );
}
///****Convercion 
$Json_Obje = json_encode($array_json); 
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $Json_Obje;             

?> 


