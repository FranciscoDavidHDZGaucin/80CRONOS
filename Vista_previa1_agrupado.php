<?php require_once('Connections/conecta1.php');?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}


$fecha_ini= $_GET['fecha_ini'];
$fecha_fin= $_GET['fecha_fin'];
$date1= $_GET['fecha_ini'];
$date2= $_GET['fecha_fin'];
$fecha_pago= $_GET['fecha_pago'];
$gasto=$_GET['gasto'];
$pagada=$_GET['pagada'];
$agente=$_SESSION['id_age'];


if ($fecha_ini!="" or $fecha_fin!=""){
	$op1="1";
	$leyenda_fecha='Periodo del '.$fecha_ini.' al '.$fecha_fin;
}else{
	$op1="0";
	$leyenda_fecha='Todas las Fechas';
}
	
if ($gasto!=0){
	$op2="1";
}else{
	$op2="0";
}	

if ($pagada!=0){
	$op3="1";
}else{
	$op3="0";
}

if ($date1<=$date2)
   {
       $dates_range[]=$date1;
       $date1=strtotime($date1);
       $date2=strtotime($date2);
       while ($date1!=$date2)
       {
           $date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1));
           $dates_range[]=date('Y-m-d', $date1);
       }
   }



$junto=$op1.$op2.$op3;





 mysql_select_db($database_conecta1, $conecta1);
switch ($junto) {
    case '000':
     $query = sprintf("select * from poliza where id_gto<>99 and pago=0 and agente=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"));
	  $query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza where id_gto<>99 and pago=0 and agente=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where  pago=0 and id_gto<>99 and agente=%s",getSQLValueString(	                           $agente,"int"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where  pago=0 and id_gto<>99 and agente=%s",	   
	                        getSQLValueString($agente,"int"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where  pago=0 and id_gto<>99 and agente=%s",	   
	                        getSQLValueString($agente,"int"));	
							
	 $query_totret=sprintf("select sum(retencion) as total_ret from poliza where  pago=0 and id_gto<>99 and agente=%s",	   
	                        getSQLValueString($agente,"int"));						
						
	
	//$row_listado=mysql_fetch_assoc($listado);
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
    break;
 case '001':
		$query = sprintf("select * from poliza  where id_gto<>99 and pago>0 and agente=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"));
		$query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza  where id_gto<>99 and pago>0 and agente=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where id_gto<>99 and pago>0 and agente=%s",getSQLValueString($agente,"int"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and pago>0 and agente=%s",	                           getSQLValueString($agente,"int"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99 and pago>0 and agente=%s",getSQLValueString($agente,"int"));
	 
	  $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and pago>0 and agente=%s",getSQLValueString($agente,"int"));									
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
	//$row_listado=mysql_fetch_assoc($listado);
	
	break;
	
	
	case '010':
		$query = sprintf("select * from poliza        where id_gto<>99 and pago=0 and  agente=%s and id_gto=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
		$query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza  where id_gto<>99 and pago=0 and  agente=%s and id_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where id_gto<>99 and pago=0 and agente=%s and id_gto=%s" ,getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
	 
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and pago=0 and agente=%s and id_gto=%s",	                           getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99 and pago=0 and agente=%s and id_gto=%s",getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
	 
	  $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and pago=0 and agente=%s and id_gto=%s",getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));						
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
	//$row_listado=mysql_fetch_assoc($listado);
	
	break;
	
	case '011':
		$query = sprintf("select * from poliza        where id_gto<>99 and pago>0 and  agente=%s and id_gto=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
		$query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza        where id_gto<>99 and pago>0 and  agente=%s and id_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where id_gto<>99 and pago>0 and agente=%s and id_gto=%s" ,getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and pago>0 and agente=%s and id_gto=%s",	                           getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99 and pago>0 and agente=%s and id_gto=%s",getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));	
	 
	  $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and pago>0 and agente=%s and id_gto=%s",getSQLValueString($agente,"int"), GetSQLValueString($gasto,"int"));						
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
	//$row_listado=mysql_fetch_assoc($listado);
	
	 
      break;    
 case '100':    //Solo FECHA
     $query = sprintf("select * from poliza where id_gto<>99 and pago=0 and agente=%s and fecha>=%s and fecha<=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
       // Actualizar los registros en el campo de impresa=1 para saber que ya se imprimieron 22-01-2013
	   
	 $updateSQL=sprintf("UPDATE poliza SET impresa=1 where id_gto<>99 and pago=0 and agente=%s and fecha>=%s and fecha<=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	$result=mysql_query($updateSQL,$conecta1) or die (mysql_error());	
	// FIN   22-01-2013
	
	  $query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza where id_gto<>99 and pago=0 and agente=%s and fecha>=%s and fecha<=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where id_gto<>99 and pago=0 and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and pago=0 and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99 and pago=0 and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));		
	 
	  $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and pago=0 and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));						
	
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
	//$row_listado=mysql_fetch_assoc($listado);
	break;
case '101':    // FECHA y PAGADA
     $query = sprintf("select * from poliza where id_gto<>99 and pago>0 and agente=%s and fecha>=%s and fecha<=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza where id_gto<>99 and pago>0 and agente=%s and fecha>=%s and fecha<=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where id_gto<>99 and pago>0 and agente=%s  and fecha>=%s and fecha<=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and pago>0 and agente=%s and fecha>=%s and fecha<=%s",	   
	                        getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99 and pago>0 and agente=%s and fecha>=%s and fecha<=%s",	   
	                        getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));		
							
	 $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and pago>0 and agente=%s and fecha>=%s and fecha<=%s",	   
	                        getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));												
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
	 
      break;    
	  
	  
	   case '110':    // FECHA y CONCEPTO
     $query = sprintf("select * from poliza where id_gto<>99  and pago=0 and agente=%s and fecha>=%s and fecha<=%s and id_gto=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"), GetSQLValueString($gasto,"int"));
	 $query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza where id_gto<>99  and pago=0 and agente=%s and fecha>=%s and fecha<=%s and id_gto=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"), GetSQLValueString($gasto,"int"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where id_gto<>99 and pago=0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and pago=0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),  getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99 and pago=0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 
	 
	  $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and pago=0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));						
	
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
	 
      break;    
	  
	  
	   case '111':    // FECHA y CONCEPTO
     $query = sprintf("select * from poliza where id_gto<>99  and pago>0 and agente=%s and fecha>=%s and fecha<=%s and id_gto=%s order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"), GetSQLValueString($gasto,"int"));
	  $query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza where id_gto<>99  and pago>0 and agente=%s and fecha>=%s and fecha<=%s and id_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"), GetSQLValueString($gasto,"int"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where id_gto<>99 and pago>0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and pago>0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),  getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99 and pago>0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));	
	 
	 $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and pago>0 and id_gto=%s and agente=%s and fecha>=%s and fecha<=%s",getSQLValueString($gasto,"int"),getSQLValueString($agente,"int"), GetSQLValueString($fecha_ini,"date"), GetSQLValueString($fecha_fin,"date"));					
	
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());
	 
      break;    
}

if ($fecha_pago!=""){
	 $query = sprintf("select * from poliza where id_gto<>99   and agente=%s and f_pago=%s  order by nom_gto, fecha", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_pago,"date"));
	  $query_agrupado = sprintf("select sum(subtot) as subtot, sum(iva) as iva, sum(retencion) as retencion, sum(total) as total, nom_gto from poliza where id_gto<>99   and agente=%s and f_pago=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($fecha_pago,"date"));
	 $query_totsub=sprintf("select sum(subtot) as total_sub from poliza where  id_gto<>99 and  agente=%s and f_pago=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_pago,"date"));
	 $query_totiva=sprintf("select sum(iva) as total_iva from poliza where id_gto<>99 and  agente=%s and f_pago=%s", getSQLValueString($agente,"int"), GetSQLValueString($fecha_pago,"date"));
	 $query_tottotal=sprintf("select sum(total) as total_total from poliza where id_gto<>99  and agente=%s and f_pago=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_pago,"date"));	
	 
	 $query_totret=sprintf("select sum(retencion) as total_ret from poliza where id_gto<>99 and agente=%s and f_pago=%s",getSQLValueString($agente,"int"), GetSQLValueString($fecha_pago,"date"));					
	
	$listado=mysql_query($query_agrupado,$conecta1) or die(mysql_error());
	$listado1=mysql_query($query_tottotal,$conecta1) or die(mysql_error());
 	$listado2=mysql_query($query_totsub,$conecta1) or die(mysql_error());
 	$listado3=mysql_query($query_totiva,$conecta1) or die(mysql_error());
 	$listado4=mysql_query($query_totret,$conecta1) or die(mysql_error());

	
}



 
$query_agente=sprintf("Select * from agente where usu_consecutivo=%s",GetSQLValueString($agente,"int"));
$consulta_agente=mysql_query($query_agente,$conecta1)or die(mysql_error());

$row_agente = mysql_fetch_assoc($consulta_agente);

 $row_listado1 = mysql_fetch_assoc($listado1);
 $row_listado2 = mysql_fetch_assoc($listado2);
 $row_listado3 = mysql_fetch_assoc($listado3);
 $row_listado4 = mysql_fetch_assoc($listado4);
 
 
 
/* $valorc_civa=$row_listado1['total_total']/$row_listado4['total_litros'];
 $valorc_siva=$row_listado2['total_sub']/$row_listado4['total_litros'];
 $rendimiento=$row_listado5['total_rec']/$row_listado4['total_litros'];
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Captura de Gastos</title>
<style type="text/css">
.letra {
	font-size: 12px;
}
.caja {
	height: 0px;
	width: 0px;
}
</style>
</head>

<body>
<div>
  <p><img src="fondos/versa.png" width="170" height="38" /></p>
  <table width="650" border="0">
    <tr><td width="320">BITACORA GASTOS <?php echo $junto;?></td>
      <td width="314" align="right">ZONA <strong><?php echo $row_agente['zona'];?></strong></td>
    </tr>
  </table>
  <p><strong><?php echo $leyenda_fecha;?></strong></p>
    <div class="letra">
    <table width="901" border="1" cellpadding="0" cellspacing="0">
      <tr>
        <td width="164">GASTO</td>
        <td width="60"><?php echo $dates_range[0];  ?></td>
        <td width="60"><?php echo $dates_range[1];  ?></td>
        <td width="60"><?php echo $dates_range[2];  ?></td>
        <td width="60"><?php echo $dates_range[3];  ?></td>
        <td width="60"><?php echo $dates_range[4];  ?></td>
        <td width="60"><?php echo $dates_range[5];  ?></td>
        <td width="102">SUBTOT</td>
        <td width="89">IVA</td>
        <td width="89">RETENCION</td>
        <td width="114">TOTAL</td>
      </tr>
      <?php do { ?>
      <tr>
        <td><?php echo $row_listado['nom_gto']; ?></td>
         <?php
			switch ($junto) {
    		case '000': /* TODOS*/ 
				    break;		
			case '001': /* Pagada*/ 
					break;		
			case '010':  /* Concepto*/ 
					break;		
			case '011':  /* Concepto y pagada*/ 
					break;		
			case '100':  /* FEcha*/ 
					$concepto=$row_listado['nom_gto'];
					
					//Para la fecha 0/5 que seria la fecha inicial
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[0],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha=mysql_fetch_assoc($consulta_xfecha);
					
					///Para la segunda Fecha 1/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=
					0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[1],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha1=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha1=mysql_fetch_assoc($consulta_xfecha1);
					
					///Para la segunda Fecha 2/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[2],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha2=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha2=mysql_fetch_assoc($consulta_xfecha2);
					
					
					///Para la segunda Fecha 3/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[3],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha3=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha3=mysql_fetch_assoc($consulta_xfecha3);
					
					///Para la segunda Fecha 4/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[4],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha4=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha4=mysql_fetch_assoc($consulta_xfecha4);
					
					///Para la segunda Fecha 5/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[5],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha5=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha5=mysql_fetch_assoc($consulta_xfecha5);
					break;		
			case '101':  /* FEcha y Pagada*/
			 		$concepto=$row_listado['nom_gto'];
					
					//Para la fecha 0/5 que seria la fecha inicial
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[0],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha=mysql_fetch_assoc($consulta_xfecha);
					
					///Para la segunda Fecha 1/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[1],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha1=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha1=mysql_fetch_assoc($consulta_xfecha1);
					
					///Para la segunda Fecha 2/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[2],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha2=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha2=mysql_fetch_assoc($consulta_xfecha2);
					
					
					///Para la segunda Fecha 3/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[3],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha3=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha3=mysql_fetch_assoc($consulta_xfecha3);
					
					///Para la segunda Fecha 4/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[4],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha4=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha4=mysql_fetch_assoc($consulta_xfecha4);
					
					///Para la segunda Fecha 5/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[5],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha5=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha5=mysql_fetch_assoc($consulta_xfecha5);
					
					
					
					break;		
			case '110':	/* Fecha y Concepto*/ 
			$concepto=$row_listado['nom_gto'];
					
					//Para la fecha 0/5 que seria la fecha inicial
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[0],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha=mysql_fetch_assoc($consulta_xfecha);
					
					///Para la segunda Fecha 1/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=
					0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[1],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha1=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha1=mysql_fetch_assoc($consulta_xfecha1);
					
					///Para la segunda Fecha 2/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[2],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha2=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha2=mysql_fetch_assoc($consulta_xfecha2);
					
					
					///Para la segunda Fecha 3/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[3],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha3=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha3=mysql_fetch_assoc($consulta_xfecha3);
					
					///Para la segunda Fecha 4/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[4],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha4=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha4=mysql_fetch_assoc($consulta_xfecha4);
					
					///Para la segunda Fecha 5/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago=0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[5],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha5=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha5=mysql_fetch_assoc($consulta_xfecha5);
					break;		
			case '101':  /* FEcha y Pagada*/
			 		$concepto=$row_listado['nom_gto'];
					
					//Para la fecha 0/5 que seria la fecha inicial
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[0],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha=mysql_fetch_assoc($consulta_xfecha);
					
					///Para la segunda Fecha 1/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[1],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha1=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha1=mysql_fetch_assoc($consulta_xfecha1);
					
					///Para la segunda Fecha 2/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[2],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha2=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha2=mysql_fetch_assoc($consulta_xfecha2);
					
					
					///Para la segunda Fecha 3/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[3],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha3=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha3=mysql_fetch_assoc($consulta_xfecha3);
					
					///Para la segunda Fecha 4/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[4],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha4=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha4=mysql_fetch_assoc($consulta_xfecha4);
					
					///Para la segunda Fecha 5/5
					$queryxfecha = sprintf("select sum(subtot) as subtot from poliza where id_gto<>99 and pago>0 and agente=%s and fecha=%s and nom_gto=%s group by nom_gto order by nom_gto", GetSQLValueString($agente,"int"), GetSQLValueString($dates_range[5],"date"), GetSQLValueString($concepto,"text"));
					$consulta_xfecha5=mysql_query($queryxfecha,$conecta1)or die(mysql_error());
					$listado_consulta_xfecha5=mysql_fetch_assoc($consulta_xfecha5);
					
					break;		
			case '111':		/* FEcha Pagada y Concepto*/ 
			}
		?>        
        <td><?php echo number_format($listado_consulta_xfecha['subtot'], 2, '.', ',');  ?></td>
        <td><?php echo number_format($listado_consulta_xfecha1['subtot'], 2, '.', ',');  ?></td>
        <td><?php echo number_format($listado_consulta_xfecha2['subtot'], 2, '.', ',');  ?></td>
        <td><?php echo number_format($listado_consulta_xfecha3['subtot'], 2, '.', ',');  ?></td>        
        <td><?php echo number_format($listado_consulta_xfecha4['subtot'], 2, '.', ',');  ?></td>
        <td><?php echo number_format($listado_consulta_xfecha5['subtot'], 2, '.', ',');  ?></td>
        <td width="102"><?php echo number_format($row_listado['subtot'], 2, '.', ',');  ?></td>
        <td><?php echo number_format($row_listado['iva'], 2, '.', ','); ?></td>
        <td width="99"><?php echo number_format($row_listado['retencion'], 2, '.', ','); ?></td>
        <td><?php echo number_format($row_listado['total'], 2, '.', ','); ?></td>
      </tr>
      <?php } while ($row_listado = mysql_fetch_assoc($listado)); ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><strong><?php echo number_format($row_listado2['total_sub'], 2, '.', ',');?></strong></td>
        <td><strong><?php echo number_format($row_listado3['total_iva'], 2, '.', ',');?></strong></td>
        <td><strong><?php echo  number_format($row_listado4['total_ret'], 2, '.', ','); ?></strong></td>
        <td><strong><?php echo  number_format($row_listado1['total_total'], 2, '.', ','); ?></strong></td>
      </tr>
    </table>
  </div>
  <p>
     <textarea name="observa" id="textarea" cols="85" rows="3"></textarea>
  </p>
  <div>
    <p>&nbsp;</p>
    <table width="650" border="0">
      <tr>
        <td width="340"><strong><?php echo $_SESSION['nombre_age'];?></strong></td>
        <td width="294" align="right"><strong><?php echo $row_agente['jefe'];?></strong></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</div>
</body>
</html>
<?php
//mysql_free_result($Result1);
/*mysql_free_result($listado_consulta_xfecha);
mysql_free_result($listado_consulta_xfecha1);
mysql_free_result($listado_consulta_xfecha2);
mysql_free_result($listado_consulta_xfecha3);
mysql_free_result($listado_consulta_xfecha4);
mysql_free_result($listado_consulta_xfecha5);
*/
?>