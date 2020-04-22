<?php
////**desv_OptiAgenteDesviacion.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_OptiAgenteDesviacion.php
 	Fecha  Creacion : 27/05/2017
	Descripcion  : 
 *              Escrip  Diseñado  para Mostrar  las Opciones  de las  Desviaciones
  */

////**Inicio De Session 
session_start();
require_once('header.php');
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
               <!-----Publicidad---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/icono_pendientes.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Desviaciones Pendientes</h3>
                            <p>
                                Responder Desviaciones
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="desv_estAgeDesvi.php?TypePg=1">Ver Desviaciones</a>
                      
                        </div>
                    </div>
                 <!-----Publicidad---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/icono_historial.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Historial de Desviaciones</h3>
                            <p>
                              Historial 
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="desv_estAgeDesvi.php?TypePg=2">Ver Historial Desviaciones</a>
                      
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