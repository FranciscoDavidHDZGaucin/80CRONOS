<?php
////****gast_index_option.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_index_option.php 
 	Fecha  Creacion : 24/10/2017
	Descripcion  : 
 *            Index   con los diferentes Modulos  de Plataforma de  Gastos
 *                    Estado  nicial  
 *                              ***Captura de Gastos
 *                              ***Reporte de Gastos 
 * 
 *     Modificaciones : 
 *             
 *      
 *          
  */
 session_start ();
   $MM_restrictGoTo = "index.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
            ///***Heder  Gerentes
            if($_SESSION["usuario_agente"] ==1 ||
                $_SESSION["usuario_agente"] ==2 ||
              $_SESSION["usuario_agente"] ==3 ||
              $_SESSION["usuario_agente"] ==1 ||
              $_SESSION["usuario_agente"] ==1 ||
              $_SESSION["usuario_agente"] ==1 ){
    
                 require_once('header_gerentes.php');   
              }else {
                if($_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] < 499 )
                {
                     require_once('heder_desarrollo.php'); 
                }else {
                   
                    require_once('header.php');

 
                }    

              }


require_once('formato_datos.php');
require_once('funciones.php');
require_once('Connections/conecta1.php');
require_once('conexion_sap/sap.php');

?> 

 <link  href="arte_cronos/cronos_index.css"  rel="stylesheet" >     
<div class="container">

    <h5  >Bienvenido: <?php echo $_SESSION["usuario_nombre"]; ?></h5>
<!--    <h2>Zona: <?php //echo $ultimo; ?></h2>-->
    <br>

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    
                 <!-----Gastos Captura---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                             <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Nuevo</h3>
                            <p>
                               Captura de  Gastos
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="gast_captAgent.php">Ir A Capturar Gastos</a>
                      
                        </div>
                    </div>
                 
                    
                   
                   <!-----Gastos Reportes  Agentes Y Desarrollo---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                             <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Reporte</h3>
                            <p>
                             Reporte De Gastos
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="gast_seegastAll.php">Ver  Reporte</a>
                      
                        </div>
                    </div>
               
                    
                    
                    
                    
                </div> <!-- /row -->
            </div>
        </div> <!-- /row -->
    </div><!-- /.container -->
</div>
  <style> 
  h5, .h5 {
    font-size: 48px;
     color: white;
    font-size: 36px;
    font-style: inherit;
}

  </style> 
   
<?php require_once 'foot.php';?>