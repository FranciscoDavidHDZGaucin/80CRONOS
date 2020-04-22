<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : index_desarrollo.php  
 	Fecha  Creacion : 11/11/2017
	Descripcion  :
 *         Escrip  COntenedor  Paltaformas   Cronos     
 *           
 *  
  */

require_once('heder_desarrollo.php');
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
                <div class="row">
               
                     <!--Gastos-->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Gastos</h3>
                            <p>Captura de Gastos</p>
                            <a class="btn btn-primary btn-large btn-block" href="gast_index_option.php">Ir a Gastos</a>
                          </div>

                    </div> 
                      <!--*********************************************-->
            
                <div class="row">
               
                     <!--Gastos-->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_pedidos_especiales.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Reclamos</h3>
                            <p>Captura de Reclamos</p>
                            <a class="btn btn-primary btn-large btn-block" href="reclamaciones_index_options.php">Ir a Reclamos</a>
                          </div>

                    </div> 
                     
                    
                </div> <!-- /row -->
          


              <!--*********************************************-->
                    
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