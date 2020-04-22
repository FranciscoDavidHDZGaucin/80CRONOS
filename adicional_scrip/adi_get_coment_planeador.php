<?php

///***adi_get_coment_planeador
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_get_coment_planeador.php
 	Fecha  Creacion : 25/11/2016
	Descripcion  : 
                  Script Para  retornar  los comentarios  del  Planeador  mediante  Id
	Modificado  Fecha  : 
*/
//***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///*****************
$id_adicional   = filter_input(INPUT_POST, 'cve_adicional');
///*** Cadena para  Obtener  el Comentario
$string_update_autorizar = sprintf("select comentarios  from  adicionales  WHERE id=%s ",
   GetSQLValueString($id_adicional,"int"));
///***Objeto  Myslq
$mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

if(!$ROW =$mysqli_PRO->query($string_update_autorizar)){
   $array_json  =  array(
    'RE'=>"ER"
 ); 
    
}else{ 
    $fec =$ROW->fetch_array(MYSQLI_ASSOC);
 $array_json  =  array(
    'RE'=>"Exito",
     'CO'=>$fec['comentarios']
 ); 
}

$jsonres= json_encode($array_json);
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $jsonres; 


?> 


