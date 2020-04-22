<?php
///***adi_update_fech_compro
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_update_fech_compro.php 
 	Fecha  Creacion : 25/11/2016 
	Descripcion  : 
                sECRIPT PARA   ACTUAIZAR  LA  FECHA  COMPROMISO  S
	Modificado  Fecha  : 
*/
//***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///*****************
$id_adicional   = filter_input(INPUT_POST, 'cve_adicional');
$fech_compro = filter_input(INPUT_POST, 'Fech');

///UPDATE  cre_pagares  SET archivo=%s WHERE id=%s UPDATE  cre_pagares  SET archivo=%s WHERE id=%s
$string_update_fech_compro  = sprintf("UPDATE adicionales  SET fech_compro=%s  WHERE id=%s ",
  GetSQLValueString($fech_compro, "date"),
 /// GetSQLValueString($fech_now, "date"),
   GetSQLValueString($id_adicional,"int") );


$mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

if(!$ROW =$mysqli_PRO->query($string_update_fech_compro)){
   $array_json  =  array(
    'RE'=>"ER",
       'Est'=>0
 ); 
    
}else{ 
 $array_json  =  array(
    'RE'=>"Exito",
     'Est'=>1
 ); 
}

$jsonres= json_encode($array_json);
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $jsonres; 


?> 


