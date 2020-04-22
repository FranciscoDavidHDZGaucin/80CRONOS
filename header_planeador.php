
<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : header_planeador.php 
 	Fecha  Creacion : 14/10/2016
	Descripcion  : 
	  
 *      Servidor : .17   
	Modificado  Fecha  : 
*/
////**********
session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="../../dist/js/vendor/html5shiv.js"></script>
      <script src="../../dist/js/vendor/respond.min.js"></script>
    <![endif]-->
       <!-- Estribimos el  temas --> 
    <link rel="stylesheet" href ="arte_cronos/cronos_header_foot.css">     
 
  </head>

  <body>
    <style>
      body {
      /*  min-height: 2000px;   */
        padding-top: 70px;
      }
      
    
      
      
      
    </style>
    

  <div class="navbar navbar-inverse navbar-default navbar-fixed-top  style_nav_001" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
          </button>
         <!--   <a class="navbar-brand" href="index_planeador.php">Cronos <?php //if ($_SESSION['beta']==2){echo ' BETA';}?></a> -->
            <a  href="index_planeador.php">  <img class="logo_cronos" src="arte_cronos/fon/logo_auto_12_2016.png"></a>
        
         
         </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
              <li class="active"><a href="index_planeador.php">Inicio</a></li>          
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Proyección de Ventas<b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="control.php">Control</a></li>
                 <li><a href="listado_pland.php">Reporte</a></li>
                 <li><a href="crear13.php">Captura Demanda</a></li>
                 <li><a href="proyeccion_carga_masiva.php">Carga Masiva</a></li>
                 <li><a href="desv_planea_getDes.php">Generador 80/20 </a></li>
              </ul>
            </li>  
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Salir: <?php echo $_SESSION["usuario_valido"]; ?></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      
      </div>
    </div>
      

   <div class="container">
  


