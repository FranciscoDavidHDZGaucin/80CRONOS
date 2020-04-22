

<?php 

require_once('header.php');
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
   require_once('funciones.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
/// mssql_select_db("AGROVERSA");     

  /*                          
  $ultimoid= "SELECT id FROM pedidos.encabeza_convenio ORDER BY id DESC LIMIT 1";
  he
  $queryultimo = mysqli_query($conecta1, $ultimoid) or die (mysqli_error($conecta1));
    $fetchultimo= mysqli_fetch_assoc($queryultimo);
    $anioactual = date("y");
    $ultimo=$fetchultimo['id'];
    
                            
   $foliogenerado=$_SESSION["usuario_agente"].$anioactual.$ultimo;
   $foliogeneradoviejo=$_SESSION["usuario_agente"].$ultimo;
   
   
  $ultimoidpedido= "SELECT id FROM pedidos.encabeza_pedido ORDER BY id DESC LIMIT 1";
  
  $queryultimopedido = mysqli_query($conecta1, $ultimoidpedido) or die (mysqli_error($conecta1));
    $fetchultimopedido= mysqli_fetch_assoc($queryultimopedido);

    $ultimopedido=$fetchultimopedido['id'];
   $foliogeneradopedido=$_SESSION["usuario_agente"].$anioactual.$ultimopedido;
   * 
   * 
   */
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
                    <?php 
if ($_SESSION['beta']==1){
?>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_pedidos.svg" alt="Calendario" class="tile-image big-illustration">
                           
                            <h3 class="tile-title">Pedidos</h3>
                            <p>Crea un pedido</p>
                            <a class="btn btn-primary btn-large btn-block" href="pedidos.php?folio=<?php  
                            
                           //echo $foliogeneradoviejo.$todayyyy;
                         echo folio_pedido($_SESSION["usuario_agente"])
                           ?>"
                           
                           >Ir a pedidos</a>
                        </div>
                    </div>
<?php }

?>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="arte_cronos/ico/ico_existencias.svg" alt="Infinity-Loop" class="tile-image">
                            <h3 class="tile-title">Existencias</h3>
                            <p>Revisa las existencias</p>
                            <a class="btn btn-primary btn-large btn-block" href="existencias.php">Ir a existencias</a>
                        </div>
                    </div>
<?php if ($_SESSION['beta']==1){?>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile tile-hot">
                            
                            <img src="arte_cronos/ico/ico_convenios.svg" alt="Pensils" class="tile-image">
                            <h3 class="tile-title">Convenios</h3>
                            <p>Crea un nuevo convenio</p>
                            <a class="btn btn-primary btn-large btn-block" href="convenios_representantes.php?folio=<?php 
                                    
                           $today = date("s"); 
                           // echo $foliogeneradoviejo.$today;
                              echo folio_convenio($_SESSION["usuario_agente"])
                            
                            ?>" >Ir a Convenios</a>
                        </div>
                    </div>
<?php 
}
if ($_SESSION['beta']==1){
?>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_pedidos_especiales.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Pedidos Especiales</h3>
                            <p>Crea un pedido especial</p>
                            <a class="btn btn-primary btn-large btn-block" href="pedidos-especial.php?folio=<?php 
                                    
                           $today = date("s"); 
                           // echo $foliogeneradoviejo.$today;
                              echo folio_pedido($_SESSION["usuario_agente"])
                            
                            ?>">Ir a Pedidos Especiales</a>
                        </div>

                    </div>
<?php }?>                    
                      <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_edo_de_cuenta.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Estado de cuenta</h3>
                            <p>Revisa los estados de cuenta</p>
                            <a class="btn btn-primary btn-large btn-block" href="clientes-edocta.php">Ir a estado de cuenta</a>
                                 <br>
                        </div>
                          
                          
                    </div>
                    
   

                       <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_proyecciones.svg" alt="proyecciones" class="tile-image">
                            <h3 class="tile-title">Proyecciones Por Cliente</h3>
                            <p>Revisa Proyeccion Por Cliente</p>
                       
                            <a class="btn btn-primary btn-large btn-block" href="<?php echo "proyecli_addproyeccionesagente_net.php";?>">Proyecciones</a>
                                 <br>
                        </div>

                    </div>

                    <!-----Adicionales---->
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="arte_cronos/ico/ico_pedidos_adicionales.svg" alt="Adicionales" class="tile-image">
                            <h3 class="tile-title">Adicionales</h3>
                            <p>Revisa y Muestra Adicionales
                              <a  href="adi_tuto_agentes.php" target="_blank" onClick="window.open(this.href, this.target, 'width=700,height=600,scrollbars=yes'); return false;">Tutorial Agregar Adicional <span class="glyphicon glyphicon-facetime-video"></span></a> 
                           </p>
                           <a class="btn btn-primary btn-large btn-block" href="adi_cre_agente.php">Ir a Adicionales</a>
                      
                        </div>

                    </div>
                     <!-----Desviaciones---->
                     
                     
                     
                    
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