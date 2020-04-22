
<?php

	 	////*****  conecction.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : conecction.php 
 	Fecha  Creacion :  27/04/2018
	Descripcion  : 
 *              
	 Escript  para  conectarse al   servidor 
	 nuevo  con las base de datos   unificadas



  */

$conectID = mssql_connect("192.168.101.153","sa","DB@gr0V3rs@");   ///Nuevo Servidor Sql 2016 26-11-2017 
if (!$conectID) {
    die('Erro al conectarse a  MSSQL SAP');
}else{
    mssql_select_db('INEFABLE');	
}

?>

