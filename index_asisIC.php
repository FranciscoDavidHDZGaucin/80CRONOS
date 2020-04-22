<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : index_asisIC.php
 	Fecha  Creacion : 08/06/2017 
	Descripcion  : 
 *              Index para   la Asistente de  Inteligencia  Comercial 
  */
  require_once 'header_asisIC.php';
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
          
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Estatus  Publicidad</h3>
                            <p>Revisa Solicitudes Publicidad</p>
                           <form  action ="pub_makepaquechassis.php"  method="POST"> 
                                <div class="btn btn-primary btn-large btn-block"><input hidden type="int" name="typesee" value="1" ><button type="submit" class="btn btn-primary btn-large btn-block">Ir a Backorder</button></div>
                     
                            </form>
                            
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Nuevo Producto</h3>
                            <p>Agregar Nuevo Producto</p>
                            <a class="btn btn-primary btn-large btn-block" href="pub_addprodcatalogo.php">Ir a Nuevo</a>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Generar Paquete </h3>
                            <p>Generar Paquete de Publicidad</p>
                             <form  action ="pub_makepaquechassis.php"  method="POST"> 
                                <div class="btn btn-primary btn-large btn-block"><input hidden type="int" name="typesee" value="2" ><button type="submit" class="btn btn-primary btn-large btn-block">Ir a Paquetes</button></div>
                     
                            </form>
                         </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title"> Paquetes </h3>
                            <p>Definir Fecha de Recepción </p>
                             <form  action ="pub_makepaquechassis.php"  method="POST"> 
                                <div class="btn btn-primary btn-large btn-block"><input hidden type="int" name="typesee" value="3" ><button type="submit" class="btn btn-primary btn-large btn-block">Ir a Recepción de Paquetes</button></div>
                     
                            </form>
                         </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Historial Publicidad</h3>
                            <p>Historial  Publicidad</p>
                           <a class="btn btn-primary btn-large btn-block" href="pub_seeAsisevidencia.php?TypePg=1">Ir a Historial</a>
                            
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="tile">
                            <img src="select3/img/icons/svg/ribbon.svg" alt="ribbon" class="tile-hot-ribbon">
                            <img src="select3/img/icons/svg/clocks.svg" alt="clocks" class="tile-image">
                            <h3 class="tile-title">Pendiente de videncia</h3>
                            <p>Publicidad  Pendiente de  Evidencia</p>
                            <a class="btn btn-primary btn-large btn-block" href="pub_seeAsisevidencia.php?TypePg=2">Ir a Pendientes</a>
                        </div>
                    </div>
                    
              </div> <!-- /row -->
            </div>
        </div> <!-- /row -->
    </div><!-- /.container -->
</div>

        
       
    
  
<?php require_once 'foot.php';?>