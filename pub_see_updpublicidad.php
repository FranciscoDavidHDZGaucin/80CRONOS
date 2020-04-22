<?php
///****pub_see_updpublicidad.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_see_updpublicidad.php
 	Fecha  Creacion : 05/05/2017
	Descripcion  : 
 *              Escrip Para  Ver Solicitudes
*
 */
///***Obtenemos  el  Numero  de  Folio 
$st_Nf = filter_input(INPUT_POST, 'nf');

////**Inicio De Session 
	session_start();
///****Cabecera Cronos
require_once('header.php');
require_once('Connections/conecta1.php');
///*****        
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Obtenemos el nombre del Agente 
$string_get_nomAg = sprintf("SELECT nom_empleado FROM pedidos.relacion_gerentes where   cve_age =%s",
 GetSQLValueString($_SESSION["usuario_agente"], "int"));
$qery_get_Nom = mysqli_query($conecta1, $string_get_nomAg);
$fethNombre = mysqli_fetch_array($qery_get_Nom);


$string_Cab = sprintf("SELECT cliente,pub_proveedor,pub_moti_sol  FROM pedidos.pub_encabeza_publicidad  where  pub_folio =%s ",
 GetSQLValueString($st_Nf, "int"));

$qery_Cab = mysqli_query($conecta1,$string_Cab );
$fetch_Ca = mysqli_fetch_array($qery_Cab);
?>
<style> 
    .CabInfo ,.TbCont,.CtFin {
    border-style: solid;
    border-radius: 12px;
    border-color: rgba(27, 165, 59, 0.09);
}


</style> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
<div  class="container">
    
    <!---Cabezera  Informacion----> 
    <div class="CabInfo col-lg-12 col-sm-12">
        <div class="row"><div class="col-sm-8"><h2>Solicitud Publicidad</h2></div><div class="col-sm-4"><h3>Folio: <?php echo $st_Nf; ?></h3></div></div>  
        <div class="row">
            <div class=" col-lg-6 col-sm-6">
                <div class ="form-group">
                    <h6> Agente
                    </h6>
                    <input disabled class="form-control input-lg" type="text" id="NomAge" value="<?php echo utf8_encode($fethNombre['nom_empleado']); ?>"> 
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class ="form-inline">
                    <h6>Fecha</h6>
                    <input disabled class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>"  id="fechSol" ></input>
                </div>
            </div>
            <div class="col-lg-2 col-sm-2"></div> 
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class ="form-group">
                    <h6>Zona</h6>
                    <input  disabled class="form-control" type="text"value="<?php echo $_SESSION["Zona"]; ?>" id="ZNa"> 
                </div>
            </div>
            <div class=" col-sm-6">
                <div class ="form-group">
                    <h6>Región o unidad</h6>
                    <input disabled class="form-control " type="text" id="reg" value="<?php echo $_SESSION["usuario_nombre"]; ?>"> 
                </div>
            </div>
             <div class="col-lg-2 col-sm-2"></div> 
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class ="form-group">
                    <h6>Cliente</h6>
                    <input  disabled class="form-control" type="text" id="client" value="<?php echo $fetch_Ca['cliente']; ?>"> 
                </div>
            </div>
            <div class=" col-sm-6">
                <div class ="form-group">
                    <h6>Proveedor</h6>
                    <input disabled class="form-control" type="text" id="PROV" value="<?php echo  $fetch_Ca['pub_proveedor']; ?>"> 
                </div>
            </div>
        </div>
        <div  class="row">
            <h5 class="est_dar">Tiempo de entrega 15 días (No se modifica)</h5> 
        </div>
    </div>
    <br> 
    <!----Contenedor Tabla-->
    <div class="TbCont col-lg-12  col-sm-12"> 
        <table class="table  table-hover" >
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th></th>
                
           </thead>
           <tbody>
               <?php  
                    $string_DET = sprintf("select  nom_producto,Descripcion_produc,cantidad_solici from pedidos.pub_detalle_publicidad    where  pub_folio =%s",
                    GetSQLValueString($st_Nf, "int"));
                    $qery_prod = mysqli_query($conecta1, $string_DET);
                   while($fetch = mysqli_fetch_array($qery_prod)){
                    echo '<tr>';
                                echo  '<td>'.$fetch['nom_producto'].' </td>';
                                echo  '<td>'.$fetch['Descripcion_produc'].'</td>';
                                echo   '<td>'.$fetch['cantidad_solici'].'</td>';
                    echo '</tr>';
                  }
               ?> 
           </tbody>
        </table>
    </div>
    <br>
    <br>
    <!---Comentarios   y  Btns ---->
    <div class="CtFin col-lg-12  col-sm-12 panel   panel-primary"> 
     
        <h4>Motivo de la solicitud / Compromiso de Venta</h4>
            <div disable class="panel-body"><textarea id="motvSOL" class="form-control" ><?php echo $fetch_Ca['pub_moti_sol']; ?></textarea></div>
        
    </div> 
    <!--------------------------------------------->
    
   

</div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 
