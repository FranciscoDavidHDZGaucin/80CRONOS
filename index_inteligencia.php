<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :index_direccion.php  
 	Fecha  Creacion : 22/09/2016
	Descripcion  : 
            
	Modificado  Fecha  : 
*/
 require_once 'header_inteligencia.php';

?> 
    <!-- Estilo  Arte  Cronos-->  
 <link  href="arte_cronos/cronos_index.css"  rel="stylesheet" >     
  
<div class="container">

   <!-- <h1>Bienvenido</h1>--> 
    <!--<h2>Zona: <?php// echo $_SESSION["Zona"]; ?></h2>-->
    <br>

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <?php 
if ($_SESSION['beta']==0){
?>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/calendar.svg" alt="Calendario" class="tile-image big-illustration">
                            <h3 class="tile-title">Autorización de Pedidos</h3>
                            <p>Autoriza un pedido</p>
                            <a class="btn btn-primary btn-large btn-block" href="pedidos_autoriza_gerentes.php">Ir a pedidos</a>
                        </div>
                    </div>
<?php }
 if ($_SESSION['beta']==1){
?>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_registro_de_pedidos.svg" alt="Infinity-Loop" class="tile-image">
                            <h3 class="tile-title">Reportes Pedidos</h3>
                            <p>Reportes Pedidos</p>
                            <a class="btn btn-primary btn-large btn-block" href="reportes9.php">Ir a Reportes</a>
                        </div>
                    </div>
 <?php }if ($_SESSION['beta']==1){?>
<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_convenios.svg" alt="Pensils" class="tile-image">
                            <h3 class="tile-title">Convenios</h3>
                            <p>Revisa los convenios</p>
                            <a class="btn btn-primary btn-large btn-block" href="convenios_autoriza_gerentes.php">Ir a Convenios</a>
                        </div>
                    </div>
<?php 
}
if ($_SESSION['beta']==0){
?>

                 <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Backorder</h3>
                            <p>Revisa los backorder</p>
                            <a class="btn btn-primary btn-large btn-block" href="#">Ir a Backorder</a>
                        </div>

                    </div>

<?php }?>                 
                     <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Autorizar Publicidad</h3>
                            <p>Revisa los backorder</p>
                            <a class="btn btn-primary btn-large btn-block" href="pub_autoJICpublicidad.php">Ir a Backorder</a>
                        </div>

                    </div>
                  <!------>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Estatus  Cotizaciones</h3>
                            <p>Revisa los Cotizaciones </p>
                            <a class="btn btn-primary btn-large btn-block" href="coti_index_option.php">Ir a Cotizaciones</a>
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_pedidos.svg" alt="Calendario" class="tile-image big-illustration">
                            <h3 class="tile-title">Autorización de Pedidos</h3>
                            <p>Autoriza un pedido</p>
                            <a class="btn btn-primary btn-large btn-block" href="pedidos_autoriza_gerentes.php">Ir a pedidos</a>
                        </div>
                    </div>



                </div> <!-- /row -->
            </div>
        </div> <!-- /row -->
    </div><!-- /.container -->
</div>

        
        
       
    
  
<?php require_once 'foot.php';?>
