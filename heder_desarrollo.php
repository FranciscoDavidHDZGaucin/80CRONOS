<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : index_desarrollo.php  
 	Fecha  Creacion : 11/11/2017
	Descripcion  :
 *         Escrip  Cabecera  Paltaformas   Cronos     
 *           
 *  
  */


session_start ();
$MM_restrictGoTo = "login.php";
if (!(isset($_SESSION['usuario_valido']))){
header("Location: ". $MM_restrictGoTo);
exit;
}
//require_once('funciones.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>CRONOS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Loading Bootstrap -->
<link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Loading Flat UI -->
<link href="select3/dist/css/flat-ui.css" rel="stylesheet">
<link href="select2/gh-pages.css" rel="stylesheet">
<link href="select2/select2.css" rel="stylesheet">
<link rel="shortcut icon" href="select3/dist/img/favicon.ico">
<!-- Estribimos el  temas --> 
      <link rel="stylesheet" href ="arte_cronos/cronos_header_foot.css"> 
</head>
<body>
<style>
body {
/*  min-height: 2000px;   */
padding-top: 70px;
}
/*Estilo Logo  Cronos  para  los Agentes */
img.glg_cro {
    height: 80px;
    margin-top: -31px;
    margin-right: 40px;
}
</style>
<div class="navbar navbar-inverse navbar-default navbar-fixed-top   style_nav_001" role="navigation">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="sr-only">Toggle navigation</span>
</button>
<a class="navbar-brand" href="index_desarrollo.php"><img class="glg_cro" src="arte_cronos/fon/logo_auto_12_2016.png"> </a>
</div>
<div class="navbar-collapse collapse">
<ul class="nav navbar-nav">
<li   class="dropdown">
<a  href="#" class="dropdown-toggle" data-toggle="dropdown">Gastos <b class="caret"></b> </a>
<ul  class="dropdown-menu">
<li><a href="gast_captAgent.php ">Captura Gastos</a></li>
</ul>
</li>
</ul>
<ul class="nav navbar-nav">
<li   class="dropdown">
<a  href="" class="dropdown-toggle" data-toggle="dropdown">Reclamos<b class="caret"></b> </a>
<ul  class="dropdown-menu">
<li><a href="reclamacionesp.php">Calidad</a></li>
<li><a href="reclamacionese.php">Servicio</a></li>
</ul>
</li>
</ul>
<ul class="nav navbar-nav navbar-right">
<li><a href="logout.php">  <span class="fui-power"></span></a></li>
</ul>
</div>
</div>
</div>
<div id="onlyfor" class="container">

