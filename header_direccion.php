<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : header_direccion.php
 	Fecha  Creacion : 22/09/2016
	Descripcion  : 
	  header  diseñado  para  los  usuarios  con   nivel  de  direccion 
 *        se  utiliza como   base el  archivo  header_gerentes.php
	Modificado  Fecha  : 
*/
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
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
      
    
      
      
      
    </style>
    

  <div class="navbar navbar-inverse navbar-default navbar-fixed-top style_nav_001" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
          </button>
         <!-- <a class="navbar-brand" href="index_direccion.php">Cronos <?php if ($_SESSION['beta']==2){echo ' BETA';}?></a>
         <!-- <a class="navbar-brand" href="index_gerentes.php">Cronos <?php if ($_SESSION['beta']==2){echo ' BETA';}?></a>-->
            <a  href="index_direccion.php">  <img class="logo_cronos" src="arte_cronos/fon/logo_auto_12_2016.png"></a>
        
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index_direccion.php">Inicio</a></li>          
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
              <ul class="dropdown-menu">
             
                <li><a href="pedidos_autoriza_gerentes.php">Autorización Precio</a></li>
                <li><a href="vista-credito-dircom.php">Autorización Credito</a></li>
                <li><a href="seguimiento-credito-pedidos-dircom.php">Seguimiento Pedidos</a></li>
                <li><a href="reportes9.php">Reporte Pedidos</a></li>
              </ul>
            </li>
            <!--Inicio  Convenios-->
            <li  class="dropdown">
                <a   href="#"  class ="dropdown"    data-toggle="dropdown">Convenios<b class="caret"></b> </a> 
                <ul   class="dropdown-menu">
                    <li><a   href ="convenios_reportes.php" >Reportes convenios </a></li> 
                </ul> 
            <!--Fin Convenios-->
            </li>
            <!--Inicio  Clientes -->
            <li  class="dropdown">
                <a   href="#"  class ="dropdown"    data-toggle="dropdown">Clientes<b class="caret"></b> </a> 
                <ul   class="dropdown-menu">
                      <li><a href="clientes-edocta_dircom.php">Estados de Cuenta</a></li>
                    <li><a   href ="dirnc.php" >Notas  Credito</a></li> 
                    <li><a   href ="expedientes_asidirco.php" >Expedientes</a></li> 
                </ul> 
            <!--Fin   Clientes-->
            </li> 
            
            
            
            
          <!--   <li><a href="existencias_gerentes.php">Existencias</a></li>
             <li><a href="clientes-edocta_gerentes.php">Estados de Cuenta</a></li>
             -->
             <?php if ($_SESSION['beta']==1){?>
             <?php }else{ echo "<li><a href='#'>BETA</a></li>";} ?>     
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Salir: <?php echo $_SESSION["usuario_valido"]; ?></a></li>
          </ul>
        </div>
      
      </div>
    </div>
      

   <div class="container">