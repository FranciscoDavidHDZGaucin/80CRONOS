<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : index_planeador.php 
 	Fecha  Creacion : 14/10/2016
	Descripcion  : 
	
 *      Servidor : .17   
	Modificado  Fecha  : 
*/
?>
<?php require_once 'header_planeador.php';?>

 <!-- Estilo  Arte  Cronos-->  
 <link  href="arte_cronos/cronos_index.css"  rel="stylesheet" >           
<div class="container">

    <h3 class="plan">Planeador de la demanda, Administración de  Proyecciones</h3>
    <!--<h2>Zona: <?php// echo $_SESSION["Zona"]; ?></h2>-->
    <br>

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="row">

                <?php if ($_SESSION['beta']==1){?>
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
 if ($_SESSION['beta']==1){
?>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_control.svg" alt="Infinity-Loop" class="tile-image">
                            <h3 class="tile-title">Control</h3>
                            <p> Control De Acceso</p> 
                            <a class="btn btn-primary btn-large btn-block" href="control.php">Ir a Control</a>
                        </div>
                    </div>
 <?php }if ($_SESSION['beta']==0){?>
<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/pencils.svg" alt="Pensils" class="tile-image">
                            <h3 class="tile-title">Reportes</h3>
                            <a class="btn btn-primary btn-large btn-block" href="#">Ir a Reportes</a>
                        </div>
                    </div>
<?php 
}
if ($_SESSION['beta']==1){
?>

<!--                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Backorder</h3>
                            <p>Revisa los backorder</p>
                            <a class="btn btn-primary btn-large btn-block" href="#">Ir a Backorder</a>
                        </div>

                    </div>-->
<?php } if ($_SESSION['beta']==1){?>                    
                      <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_reporte.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Reporte</h3>
                            <p>Reporte  General</p>
                            <a class="btn btn-primary btn-large btn-block" href="listado_pland.php">Ir a Reporte</a>
                        </div>

                    </div>

<?php 
}
?>
<?php  if ($_SESSION['beta']==1){?>                    
                      <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_capturas.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Captura</h3>
                            <p>Captura Demanda</p> 
                            <a class="btn btn-primary btn-large btn-block" href="crear13.php">Ir a Captura</a>
                        </div>

                    </div>

<?php 
}
?>
                    <!-----Adicionales---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_pedidos.svg" alt="Calendario" class="tile-image big-illustration">
                            <h3 class="tile-title">Autorización de Pedidos</h3>
                            <p>Autoriza un pedido</p>
                            <a class="btn btn-primary btn-large btn-block" href="pedidos_autoriza_gerentes.php">Ir a pedidos</a>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/icono_adicionales.svg" alt="Adicionales" class="tile-image">
                            <h3 class="tile-title">Adicionales</h3>
                            <p>Revisa y Muestra Adicionales</p>
                            <a class="btn btn-primary btn-large btn-block" href="adi_lista_planeador.php">Ir a Adicionales</a>
                        </div>

                    </div>
                    <!-----Desviaciones---->
                     <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/icono_pendientes.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Desviaciones De Ventas VS Demanda </h3>
                            <p>Revision de Desviaciones De Ventas </p>
                            <a class="btn btn-primary btn-large btn-block" href="desv_opcplaneadesvia.php">Ir a Desviaciones</a>
                        </div>

                    </div>

                </div> <!-- /row -->
            </div>
        </div> <!-- /row -->
    </div><!-- /.container -->
</div>

        
        
       
    
  
<?php require_once 'foot.php';?>