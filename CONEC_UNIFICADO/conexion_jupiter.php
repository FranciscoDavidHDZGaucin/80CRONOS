<?php
///conexion_jupiter.php

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :conexion_jupiter.php
 	Fecha  Creacion :  05/07/2018
	Descripcion  : 
 *              
	 Escript  para  conectarse al   servidor 
	 nuevo  con las base de datos   unificadas



  */

$conectID = mssql_connect("192.168.101.154/","sa","DB@gr0V3rs@");   ///Nuevo Servidor Sql 2016 26-11-2017 
if (!$conectID) {
    die('Erro al conectarse a  MSSQL SAP');
    $algo ="algotrono";
}else{
    mssql_select_db('JUPITER');	

}

?>

