<?php
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
  </head>

  <body>
    <style>
      body {
        min-height: 2000px;
        padding-top: 70px;
      }
      
    
      
      
      
    </style>
    

  <div class="navbar navbar-inverse navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
          </button>
          <a class="navbar-brand" href="index_gerentes.php">Cronos <?php if ($_SESSION['beta']==2){echo ' BETA';}?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index_gerentes.php">Inicio</a></li>          
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
              <ul class="dropdown-menu">
             
                <li><a href="pedidos_autoriza_gerentes.php">Autorización</a></li>
                
              </ul>
            </li>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Convenios <b class="caret"></b></a>
              <ul class="dropdown-menu">
             
                <li><a href="convenios_autoriza_gerentes.php">Autorización</a></li>
                
              </ul>
            </li>
            <li><a href="clientes-edocta.php">Clientes</a></li>
<!--             <li class="dropdown">
                 
                 
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reclamaciones <b class="caret"></b></a>
              
              <ul class="dropdown-menu">
                  <?php if ($_SESSION['beta']==1){?>
                <li><a href="#">Reclamaciones Producto</a></li>
                <li><a href="#">Reportes Producto/Empaque Pedido</a></li>
                <li class="divider"></li>
                <li><a href="#">Reclamaciones Entrega</a></li>
                <li><a href="#">Reportes Entrega/Servicio</a></li>
                       <?php }else{ echo "<li><a href='#'>BETA</a></li>";} ?>
              </ul>
              
       
            </li>-->
            <li><a href="#">Guía Visitas</a></li>
            <li><a href="#">Link</a></li>
          </ul>
            
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Salir: <?php echo $_SESSION["usuario_nombre"]; ?></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      
      </div>
    </div>
      

   <div class="container">
  