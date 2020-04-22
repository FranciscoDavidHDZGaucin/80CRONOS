<?php
////***adi_update_autoriza_gerente
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_update_autoriza_gerente.php
 	Fecha  Creacion : 22/11/2016 
	Descripcion  : 
	    Modificar  la  Autorizacion   Scrip  Implementado  por  los 
 *          Gerentes para  los  Agentes.
	Modificado  Fecha  : 
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///*****************
$id_adicional   = filter_input(INPUT_POST, 'cve_adicional');
$autoriza = filter_input(INPUT_POST, 'aut');
///**Obtenemos   la fecha
$fech_now =date("Y-m-d H:i:s"); 
///UPDATE  cre_pagares  SET archivo=%s WHERE id=%s UPDATE  cre_pagares  SET archivo=%s WHERE id=%s
$string_update_autorizar = sprintf("UPDATE adicionales  SET auto=%s ,fech_auto=%s  WHERE id=%s ",
  GetSQLValueString($autoriza, "int"),
  GetSQLValueString($fech_now, "date"),
   GetSQLValueString($id_adicional,"int") );


$mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

if(!$ROW =$mysqli_PRO->query($string_update_autorizar)){
   $array_json  =  array(
    'RE'=>"ER"
 ); 
    
}else{ 
 $array_json  =  array(
    'RE'=>"Exito"
 ); 
}




$jsonres= json_encode($array_json);
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $jsonres; 


?>