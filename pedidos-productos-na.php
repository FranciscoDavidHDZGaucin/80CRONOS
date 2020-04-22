<?php require_once 'header_inteligencia.php';

  require_once('formato_datos.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");    
 
 $idgerente=$_SESSION["usuario_rol"];
 
 $date = date('Y-m-d H:i:s');

 

  
IF (isset($_REQUEST['eliminar'])){
 $eliminar= $_REQUEST['eliminar'];  
$remision = $_REQUEST['remision'];
$agente = $_REQUEST['agente'];
$cliente = $_REQUEST['cliente'];
    
    
    
      ///Rechazo por parte de Direccion Comercial
     //revisar producto por producto
 $string_productos =sprintf("SELECT * FROM detalle_pedido WHERE id_detalle=%s",
                         GetSQLValueString($eliminar, 'int'));        

$result_detalle=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));


while ($rowp = mysqli_fetch_array($result_detalle)) {
     $insertSQL2 = sprintf("INSERT INTO borrados_x_precio (n_remision, n_agente, nom_age, fecha_alta, cve_cte, nom_cte, cve_prod,
                  nom_prod, cant_prod, precio_prod, dcto_prod, total_prod, moneda_prod, cant_falta, autorizado,total_prodmxp,
                 tipo_cambio, precio_condcto, precio_politica, estatus, bonificacion, comentario,fecha_autoriza,estatus2,fecha_autorizadc,au_gerente,au_dc,usuario_cancela)VALUES (%s,%s,%s,%s,%s,%s,%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s)",
                   GetSQLValueString($rowp['n_remision'], "int"),
                   GetSQLValueString($rowp['n_agente'], "int"),
                   GetSQLValueString($rowp['nom_age'], "text"),
                   GetSQLValueString($rowp['fecha_alta'], "date"),
                   GetSQLValueString($rowp['cve_cte'], "text"),
                   GetSQLValueString($rowp['nom_cte'], "text"),
                   GetSQLValueString($rowp['cve_prod'], "text"),
                   GetSQLValueString($rowp['nom_prod'], "text"),
                   GetSQLValueString($rowp['cant_prod'], "double"),
                   GetSQLValueString($rowp['precio_prod'], "double"),
                   GetSQLValueString($rowp['dcto_prod'], "double"),
                   GetSQLValueString($rowp['total_prod'], "double"),
                   GetSQLValueString($rowp['moneda_prod'], "int"),
                   GetSQLValueString($rowp['cant_falta'], "double"),
                    GetSQLValueString($rowp['autorizado'], "int"),
                   GetSQLValueString($rowp['total_prodmxp'], "double"),
                   GetSQLValueString($rowp['tipo_cambio'], "double"),
                   GetSQLValueString($rowp['precio_condcto'], "double"),
                   GetSQLValueString($rowp['precio_politica'], "double"),
                   GetSQLValueString($rowp['estatus'], "text"),
                   GetSQLValueString($rowp['bonificacion'], "text"),
              GetSQLValueString($rowp['comentario'], "text"),
              GetSQLValueString($rowp['fecha_autoriza'], "date"),
              GetSQLValueString($rowp['estatus2'], "text"),
              GetSQLValueString($rowp['fecha_autorizadc'], "date"),
              GetSQLValueString($rowp['au_gerente'], "int"),
              GetSQLValueString($rowp['au_dc'], "int"),
              GetSQLValueString($_SESSION['id'], "int"));
                   
             
             
   @mysqli_query($conecta1, $insertSQL2) or die (mysqli_error($conecta1));
   
   
    
}


//elimina todos los productos de la remision
 $deleteSQLprod=sprintf("DELETE FROM  detalle_pedido where id_detalle=%s",
	        GetSQLValueString($eliminar, 'int'));    

@mysqli_query($conecta1, $deleteSQLprod) or die (mysqli_error($conecta1));
 
 
 
      
  }
  
  
  

 
 $consultaremisiones = "SELECT * FROM relacion_gerentes_detalle_pedido WHERE  estatus = 'NA' ORDER BY fecha_alta DESC  ";    
 
 $queryremisiones=mysqli_query($conecta1, $consultaremisiones) or die (mysqli_error($conecta1));
 
 
 ?>

<form method="post" action="pedidos_autoriza_gerentes.php">
<h3>Productos No Autorizados por Precio $<?php //echo $actualizrechazardc; ?></h3>
<div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Rem</th>
                     <th>Cliente</th>
                     <th>Clave</th>
                     <th>Producto</th>
                     <th>Agente</th>
                     <th>Fecha </th>
                     <th>Cantidad </th>
                     <th>Precio</th>
                     <th>Autorizar</th>
           
            

                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mysqli_fetch_array($queryremisiones)){ 
                     
                     $querylista=sprintf("SELECT * FROM ".$listaprecios." WHERE ItemCode=%s",
                                GetSQLValueString($registro1['cve_prod'], "text"));
                     $resultadolista = mssql_query($querylista);
                     $fetchlista = mssql_fetch_array($resultadolista);
                     $codigolista = $fetchlista['ItemCode'];
                     
                     ?>
                 <tr>
                    <td><a href="pedido_detalle_gerentes.php?remision=<?php echo $registro1['n_remision'];  ?> &agente=<?php echo $registro1['n_agente']; ?>&cliente=<?php echo $registro1['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $registro1['n_remision'];?></a></td>        
                    <td><?php echo $registro1['nom_cte'];?></td> 
                    <td><?php echo $registro1['cve_prod'];?></td> 
                    <td><?php echo $registro1['nom_prod'];?></td> 
                    <td><?php echo $registro1['nom_age'];?></td> 
                     <td><?php echo $registro1['fecha_alta'];?></td> 
                        <td><?php echo $registro1['cant_prod'];?></td> 
                     <td><?php echo '$'.$registro1['precio_condcto'];?></td> 
                      <td><a href="pedidos-productos-na.php?eliminar=<?php echo $registro1['id_detalle']; ?>&productoiddc=<?php echo $registro1['cve_prod']; ?>"  onclick="return confirm('¿Está Seguro de Eliminar?')"><img src="images/delete.png"/></a></td>
                     
                 </tr>
                   <?php 
                   

                   
                 } ?>
             </tbody>


         </table>

             </div>


</form>


<?php require_once 'foot.php';?>