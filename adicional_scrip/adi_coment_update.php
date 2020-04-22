<?php
///***adi_coment_update
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_coment_update.php 
 	Fecha  Creacion : 25/11/2016
	Descripcion  : 
               Script  para  Actualizar  los comentarios
  *         
	Modificado  Fecha  : 
*////***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///*****************
$id_adicional   = filter_input(INPUT_POST, 'cve_adicional');
$comentarios = filter_input(INPUT_POST, 'coments');

///UPDATE  cre_pagares  SET archivo=%s WHERE id=%s UPDATE  cre_pagares  SET archivo=%s WHERE id=%s
$string_update_autorizar = sprintf("UPDATE adicionales  SET comentarios=%s  WHERE id=%s ",
  GetSQLValueString($comentarios, "text"),
 /// GetSQLValueString($fech_now, "date"),
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


