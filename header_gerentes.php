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
   <!-- Estribimos el  temas --> 
    <link rel="stylesheet" href ="arte_cronos/cronos_header_foot.css"> 
    
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
    

  <div class="navbar navbar-inverse navbar-default navbar-fixed-top style_nav_001" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
          </button>
         <!-- <a class="navbar-brand" href="index_gerentes.php">Cronos <?php if ($_SESSION['beta']==2){echo ' BETA';}?></a>-->
            <img class="logo_cronos" src="arte_cronos/fon/logo_auto_12_2016.png">
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index_gerentes.php">Inicio</a></li>          
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
              <ul class="dropdown-menu">
             
                <li><a href="pedidos_autoriza_gerentes.php">Autorizar Precios</a></li>
                 <li><a href="vista-credito-gerente.php"> Pedidos x Autorizar</a></li> 
                  <li><a href="seguimiento-credito-pedidos-gerentes.php">Seguimiento Pedidos</a></li>
                   <li><a href ="reportes9.php">Reporte Pedidos</a></li>
                      <li><a href ="estado_pedidos_gerentes.php">Estatus Pedidos</a></li>
                  
              </ul>
            </li>
            <!--Inicio  Agregado  de  Notas-->
            <li  class="dropdown">
                <a   href="#"  class ="dropdown"    data-toggle="dropdown">Notas Crédito<b class="caret"></b> </a> 
                <ul   class="dropdown-menu">
                 <li><a   href ="gerentenc.php" >Autorizar  Notas  de Crédito</a></li> 
                 <li><a href="reportesnc.php">Reportes Notas Crédito</a></li>
                
                
                
                
                
                </ul> 
            <!--Fin   Agregado  de  Notas-->
            </li> 
<!--            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Convenios <b class="caret"></b></a>
              <ul class="dropdown-menu">
             
                <li><a href="#">Autorización</a></li>
                
              </ul>
            </li>-->
<!--            <li><a href="clientes-edocta.php">Clientes</a></li>-->


          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Clientes <b class="caret"></b></a>
              <ul class="dropdown-menu">
             
                <li><a href="clientes-edocta_gerentes.php">Estados de Cuenta</a></li>
                <li><a href="expedientes-gerente.php">Expedientes</a></li> 
                  
              </ul>
            </li>
            
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reportes Varios <b class="caret"></b></a>
              <ul class="dropdown-menu">
             
                <li><a href="reclamos-gerentes.php">Reportes Reclamaciones</a></li> 
                 <li><a href="Nuevo.php">Reporte Guia de Visitas</a> </li>  
                 <li><a href="ReportesEntomologicosSC.php">Reporte Entomologico</a>  </li> 
                 <li><a href="existencias_gerentes.php">Existencias</a></li>
                 <li><a href="Nuevo.php">Guia Visitas</a></li> 
                  
              </ul>
            </li>
             
            
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
<!--            <li><a href="#">Guía Visitas</a></li>
       
          </ul>-->
            
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Salir: <?php echo $_SESSION["usuario_valido"]; ?></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      
      </div>
    </div>
      

   <div class="container">
  