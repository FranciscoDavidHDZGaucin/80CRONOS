<?php
////*pub_index_option.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_index_option.php 
 	Fecha  Creacion : 05/05/2017
	Descripcion  : 
 *            Index   con los diferentes Modulos  de   Publicidad 
 * 
 *     Modificaciones : 
 *              08/05/2017    Se  Agrega  la  variable    UbdatePub encargada  de controlar cuando se  realiza  una modificacion a
 *                            una solicitud del  pedido. 
 *      
 *          
  */
 session_start ();
   $MM_restrictGoTo = "index.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
require_once('header.php');
require_once('formato_datos.php');
require_once('funciones.php');
require_once('Connections/conecta1.php');
require_once('conexion_sap/sap.php');
 mssql_select_db("AGROVERSA"); 
  
///******** Obpcion Update 
 $_SESSION['UbdatePub'] = 0 ;//// Estados  Entiendase  que  0 => Modificar  Off  1 => Modificacion On 

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
                    
                 <!-----Publicidad---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Nuevo</h3>
                            <p>
                                Generar Nueva Solicitud
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="pup_selecprod_agente.php">Generar Solicitud</a>
                      
                        </div>
                    </div>
                 <!-----Publicidad---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Estado</h3>
                            <p>
                              Estado de Solicitudes
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="pub_estado_publicidad.php ">Revisar Estado</a>
                      
                        </div>
                    </div>
                 <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Evidencias</h3>
                            <p>
                              Cargar Evidencia
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="pub_eviestagente.php">Subir Evidencias</a>
                      
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