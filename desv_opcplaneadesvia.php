<?php

////***desv_opcplaneadesvia.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :desv_opcplaneadesvia.php
 	Fecha  Creacion : 01/08/2017
	Descripcion  : 
 *              Escrip  Diseñado  para Mostrar  Los estatus de las  Desviaciones   */

////**Inicio De Session 
session_start();
///****Cabecera Cronos +
require_once 'header_planeador.php';
//require_once('Connections/conecta1.php');
require_once('formato_datos.php');
require_once('funciones.php');
//mysqli_select_db($conecta1, $database_conecta1);
require_once('Connections/conecta1.php');
require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");     


?>

  <link  href="arte_cronos/cronos_index.css"  rel="stylesheet" >     
<div class="container">

    <h5  >Bienvenido: <?php echo $_SESSION["usuario_nombre"]; ?></h5>
<!--    <h2>Zona: <?php //echo $ultimo; ?></h2>-->
    <br>

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
               <!-----Desviaciones ---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/icono_generador.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Generador Desviaciones</h3>
                            <p>
                                Generar  Desviaciones
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="desv_planea_getDes.php">Ir al Generador</a>
                      
                        </div>
                    </div>
                 <!-----Desviaciones---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/icono_pendientes.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Desviaciones Pendientes</h3>
                            <p>
                              Desviaciones  Pendientes
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="desv_estseePlan.php?TypePg=1">Ver Desviaciones Pendiente</a>
                      
                        </div>
                    </div>
                 <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/icono_historial.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Registro</h3>
                            <p>
                               Registro  
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="desv_estseePlan.php?TypePg=2">Ir a Registro</a>
                      
                        </div>
                    </div>
                    
                
                
                
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