<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conecta1 = "localhost";
$database_conecta1 = "pedidos";
$username_conecta1 = "root";
$password_conecta1 = "avsa0543";
//$conecta1 = mysql_pconnect($hostname_conecta1, $username_conecta1, $password_conecta1) or trigger_error(mysql_error(),E_USER_ERROR); 
$conecta1 = mysqli_connect("$hostname_conecta1","$username_conecta1","$password_conecta1","$database_conecta1") or die("Error " . mysqli_error($conecta1));
?>