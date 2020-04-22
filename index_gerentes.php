

<?php 
require_once 'header_gerentes.php';

require_once('funciones_proyecciones.php'); 

?>
 <!-- Estilo  Arte  Cronos-->  
 <link  href="arte_cronos/cronos_index.css"  rel="stylesheet" >     
<div class="container">

  <!--  <h1>Bienvenido</h1>-->
    <!--<h2>Zona: <?php// echo $_SESSION["Zona"]; ?></h2>-->
    <br>

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <?php 
if ($_SESSION['beta']==1){
?>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_pedidos.svg" alt="Calendario" class="tile-image big-illustration">
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
                            <img src="arte_cronos/ico/ico_existencias.svg" alt="Infinity-Loop" class="tile-image">
                            <h3 class="tile-title">Existencias</h3>
                            <p>Revisa las existencias</p>
                            <a class="btn btn-primary btn-large btn-block" href="existencias_gerentes.php">Ir a existencias</a>
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
                            <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Estado de cuenta</h3>
                            <p>Revisa los estados de cuenta</p>
                            <a class="btn btn-primary btn-large btn-block" href="clientes-edocta_gerentes.php">Ir a estado de cuenta</a>
                        </div>

                    </div>

<?php 
}

?>
                         <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_proyecciones.svg" alt="proyecciones" class="tile-image">
                            <h3 class="tile-title">Proyecciones</h3>
                            <p>Revisa y Muestra las Proyeccion</p>
                            
                              <?php 
                               ///revisar si tiene permitido el acceso a capturar o modificar la proyeccion
                            
                            $res_acceso=  modificar_proyeccion($_SESSION['tipousuario_proyeccion']);
                            if ($res_acceso==1){
                                $pagina_p="crear12.php";
                            }else{
                                $pagina_p="listado2.php";
                            }
                            
                            ?>
                            <a class="btn btn-primary btn-large btn-block" href="<?php echo $pagina_p;?>">Proyecciones</a>
                            <br>
                        </div>

                    </div>
                        <!-----Adicionales---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/icono_adicionales.svg" alt="Adicionales" class="tile-image">
                            <h3 class="tile-title">Adicionales</h3>
                            <p>Revisa y Muestra Adicionales</p>
                              <a  href="adi_tuto_agentes.php" target="_blank" onClick="window.open(this.href, this.target, 'width=700,height=600,scrollbars=yes'); return false;">Tutorial Agregar Adicional <span class="glyphicon glyphicon-facetime-video"></span></a> 

                            <a class="btn btn-primary btn-large btn-block" href="adi_cre_gerentes.php">Ir a Adicionales</a>
                        </div>

                    </div>
                  <!-------->
                  <!-----Cotizaciones---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_pedidos_adicionales.svg" alt="Adicionales" class="tile-image">
                            <h3 class="tile-title">Cotizaciones</h3>
                            <p>Revisa Cotizaciones</p>
                              <a class="btn btn-primary btn-large btn-block" href="coti_index_gerente_option.php">Ir a Cotizaciones</a>
                        </div>

                    </div>
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
                  
                  
                       
                </div> <!-- /row -->
            </div>
        </div> <!-- /row -->
    </div><!-- /.container -->
</div>

        
        
       
    
  
<?php require_once 'foot.php';?>