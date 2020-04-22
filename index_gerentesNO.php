

<?php require_once 'header_gerentes.php';

  require_once('formato_datos.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 mssql_select_db("AGROVERSA");    
 
 
 $idgerente=$_SESSION["usuario_rol"];
 
 
   
   if($idgerente!=='10' && $idgerente!=='69'){
   $consultaremisiones = sprintf("SELECT * FROM relacion_gerentes_encabeza_convenio WHERE cve_gte=%s AND estatus = 'A' AND au_dc=0 AND au_an=0 AND au_ge=0 ORDER BY fecha_alta DESC ",
 GetSQLValueString($idgerente, "int"));
 }
 if ($idgerente=='10'){
   $consultaremisiones = "SELECT * FROM relacion_gerentes_encabeza_convenio WHERE au_dc = 0 AND au_ge=1 AND au_an=1 AND estatus = 'A' ORDER BY fecha_alta DESC ";    
 }
 if ($idgerente=='69'){
   $consultaremisiones = "SELECT * FROM relacion_gerentes_encabeza_convenio WHERE au_dc = 0 AND au_ge=1 AND au_an=0 AND estatus = 'A' ORDER BY fecha_alta DESC ";    
     
 }
 $queryremisiones=mysqli_query($conecta1, $consultaremisiones) or die (mysqli_error($conecta1));
 $filas = mysqli_num_rows($queryremisiones);
 
 




?>

      
<div class="container">

    <h1>Bienvenido</h1>
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
                        <div class="tile tile-hot">
                             <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/calendar.svg" alt="Calendario" class="tile-image big-illustration">
                            <h3 class="tile-title">Autorización de Pedidos</h3>
                            <p>Autoriza un pedido</p>
                            <a class="btn btn-primary btn-large btn-block" href="pedidos_autoriza_gerentes.php">Ir a pedidos</a>
                        </div>
                    </div>
<?php }
 if ($_SESSION['beta']==0){
?>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/clipboard.svg" alt="Infinity-Loop" class="tile-image">
                            <h3 class="tile-title">Existencias</h3>
                            <p>Revisa las existencias</p>
                            <a class="btn btn-primary btn-large btn-block" href="existencias_gerentes.php">Ir a existencias</a>
                        </div>
                    </div>
 <?php }if ($_SESSION['beta']==1){?>
<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
  <?php if($filas > 0){ ?>
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            
                            
  <?php } ?>
                            <img src="select3/img/icons/svg/pencils.svg" alt="Pensils" class="tile-image">
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
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="select3/img/icons/svg/gift-box.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Pedidos Especiales</h3>
                            <p>Crea un pedido especial</p>
                            <a class="btn btn-primary btn-large btn-block" href="pedidos_especiales_autoriza_gerentes.php">Ir a Pedidos Especiales</a>
                        </div>

                    </div>
                    
                    
<?php } if ($_SESSION['beta']==0){?>                    
                      <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <!--<img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">-->
                            <img src="select3/img/icons/svg/book.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Estado de cuenta</h3>
                            <p>Revisa los estados de cuenta</p>
                            <a class="btn btn-primary btn-large btn-block" href="clientes-edocta_gerentes.php">Ir a estado de cuenta</a>
                        </div>

                    </div>

<?php 
}

?>
                </div> <!-- /row -->
            </div>
        </div> <!-- /row -->
    </div><!-- /.container -->
</div>

        
        
       
    
  
<?php require_once 'foot.php';?>