<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_delete_adicionales.php 
 	Fecha  Creacion : 16/11/2016
	Descripcion  : 
	Scrip para  eliminar  adicionales  mediante  ID  
	Modificado  Fecha  : 
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///****Objeto Mysql
$obj_mysql =  new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
///***Obtenemos la  cadena 
$ID_ADI = filter_input(INPUT_POST, 'ELEM');
///***Generesmo  cadena para el  Qery 
$string_qery_delete_adi = sprintf("DELETE FROM adicionales  where id=%s", 
 GetSQLValueString($ID_ADI, "int"));
///***Ejecutamos 
$obj_mysql->query($string_qery_delete_adi);

