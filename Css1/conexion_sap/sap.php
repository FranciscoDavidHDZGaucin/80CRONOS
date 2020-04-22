
<?php
///$conectID = mssql_connect("192.168.101.9","sa","B1Admin"); antigo servidor sql 2005
$conectID = mssql_connect("192.168.101.22","sa","DB@gr0V3rs@");   ///Nuevo Servidor Sql 2016 26-11-2017 
if (!$conectID) {
    die('Erro al conectarse a  MSSQL SAP');
}else{
    mssql_select_db('AGROVERSA_PRODUCTIVA');	
}

?>


