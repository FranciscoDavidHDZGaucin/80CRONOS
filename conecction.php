
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
/*
$conectID = mssql_connect("192.168.101.154","sa","DB@gr0V3rs@");   
if (!$conectID) {
    die('Erro al conectarse a  MSSQL SAP');
   
}else{
    mssql_select_db('INEFABLE');
   	
}

?>*/

$serverName = "192.168.101.154"; //serverName\instanceName
$connectionInfo = array( "Database"=>"INEFABLE", "UID"=>"sa", "PWD"=>"DB@gr0V3rs@");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Conexión establecida.<br />";
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>