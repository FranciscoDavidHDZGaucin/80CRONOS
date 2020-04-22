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
    

  <div class="navbar navbar-inverse navbar-default navbar-fixed-top style_nav_001" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
          </button>
          <!-- <a class="navbar-brand" href="index_inteligencia.php">Cronos </a>--> 
          <a  href="index_inteligencia.php">  <img class="logo_cronos" src="arte_cronos/fon/logo_auto_12_2016.png"></a>
        
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li ><a href="index_inteligencia.php">Inicio</a></li>          
            <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
              <ul class="dropdown-menu">
                 
                 
                <li><a href="reportes9.php">Reportes Pedidos</a></li>
                <li><a href="pedidos-productos-na.php">Productos No Autorizados</a></li>
                <li><a href="convenios_autoriza_gerentes.php">Consulta Convenios</a></li>
              </ul>
            </li>
            <!--Convenios-->
                <li class="dropdown">
                 <a href="" class="dropdown-toggle" data-toggle="dropdown">Convenios<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="convenios_autoriza_gerentes.php">Por Autorizar</a></li>
                    <li><a href="convenios_reportes.php">Reporte</a></li>
                </ul>
            </li>
            <!--Fin Convenios--> 
             <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown">Clientes <b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="#">proximamente</a></li>
                 
                  
               

              </ul>
            </li>
            <li><a href="#">proximamente</a></li>
            
            
            
            
           
          </ul>
            
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Salir: <?php echo $_SESSION["usuario_valido"]; ?></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      
      </div>
    </div>

 
 
   <div class="container">
  