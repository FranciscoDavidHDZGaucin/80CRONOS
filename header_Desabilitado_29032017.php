<?php
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
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="../../dist/js/vendor/html5shiv.js"></script>
      <script src="../../dist/js/vendor/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <style>
      body {
        /*  min-height: 2000px;   */
        padding-top: 70px;
      }
      
    
      
      
      
    </style>
    

  <div class="navbar navbar-inverse navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
          </button>
          <a class="navbar-brand" href="index.php"><img src="images/cronos_blanco.png" height="40" width="120"> </a>
        </div>
          <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                  
                  <li   class="dropdown"> 
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown">Reclamaciones <b class="caret"></b> </a>
                        <ul  class="dropdown-menu">
                           <li><a href="reclamacionesp.php">Reclamaciones Producto</a></li>
                  <li><a href="reportereclamosp.php">Reportes Producto/Empaque Pedido</a></li>
                <li class="divider"></li>
                <li><a href="reclamacionese.php">Reclamaciones Entrega</a></li>
                <li><a href="reportereclamos.php">Reportes Entrega/Servicio</a></li>
                        </ul>
                   </li> 
                  
                  <li   class="dropdown"> 
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown">Guia Visita <b class="caret"></b> </a>
                        <ul  class="dropdown-menu">
                            <li><a href="captura_gv.php">Capturas</a></li>
                            <li><a href="prospecto_gv.php">Prospectos</a></li>
                        </ul>
                   </li> 
                  
                   <li   class="dropdown"> 
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown">Reportes  <b class="caret"></b> </a>
                        <ul  class="dropdown-menu">
                             <li><a href="estado_pedidos.php">Consulta Pedidos</a></li>
                            <li><a href="estado_convenios.php">Consulta Convenios</a></li>
                        </ul>
                   </li> 
                   <li   class="dropdown"> 
                        <a  href="#" class="dropdown-toggle" data-toggle="dropdown">Otros  <b class="caret"></b> </a>
                        <ul  class="dropdown-menu">
                             <li><a href="#">Comisiones(prox)</a></li>
                            <li><a href="expedientes-agente.php">Expedientes</a></li>
                        </ul>
                   </li> 
                  
                  
              </ul>
                        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">  <span class="fui-power"></span></a></li>
          </ul>
          </div>
      
      </div>
    </div>

 
 
   <div class="container">
  
       
     