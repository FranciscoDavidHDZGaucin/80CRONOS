<?php
///****coti_index_option.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_index_option.php 
 	Fecha  Creacion : 20/07/2017
	Descripcion  : 
 *            Index   con los Diferentes  Modulos  de Cotizacion para el  Jefe  DE Inteligencia comercial 
 * 
 *     Modificaciones : 
 *          20/07/2017   Se Define la  Variable  TypePg 
 *                      Entiendase  que  la  Varible  TypePg  en  Estado  =>  1  =>La  Pagina   Se ejecutara  con opciones de Update   y Estatus  
 *                      Entiendase  que  la  Varible  TypePg  en  Estado  =>  2  =>La  Pagina   Se ejecutara  con opciones de Vista  Nada  de  Modificaciones    
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
/// mssql_select_db("AGROVERSA"); 

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
                            <h3 class="tile-title">Estatus  Cotizaciones</h3>
                            <p>
                                Revisar  Cotizaciones 
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="coti_estcontizacionJIC.php?TypePg=1">Estatus Cotizacion</a>
                      
                        </div>
                    </div>
                 <!-----Publicidad---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                             <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Historial de Cotizaciones</h3>
                            <p>
                              Historial 
                            </p>
                            <a class="btn btn-primary btn-large btn-block" href="coti_estcontizacionJIC.php?TypePg=2">Ver Historial Cotizaciones</a>
                      
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
