<?php require_once 'header_gerentes.php';

  require_once('formato_datos.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
/// mssql_select_db("AGROVERSA");    
 
 $idgerente=$_SESSION["usuario_rol"];
 
 $date = date('Y-m-d H:i:s');

 
 $listaprecios;
$listacomercial = "plataformaproductosl7";
$numerogerente = $_SESSION["usuario_rol"];



if($numerogerente=="3"){
    $listaprecios = "plataformaproductosl4";
} else if ($numerogerente=="1"){
    $listaprecios = "plataformaproductosl5";
} else if ($zona=="6"){
    $listaprecios = "plataformaproductosl6";
} else if ($zona==""){
    
} else if ($zona=="2"){
    $listaprecios = "plataformaproductosl3";    
} else if ($zona=="10"){
    $listaprecios = "plataformaproductosl8";    
}


//****************************************IFS DE GERENTES
 IF (isset($_REQUEST['autorizar'])){
     
     $cveprod_autoriza = $_REQUEST['productoid'];
     $precio_autoriza =$_REQUEST['precio'];
     $precio_gerente=$_REQUEST['preciogerente'];
     
     $querylistaau=sprintf("SELECT * FROM plataformaproductosl8 WHERE ItemCode=%s",
                     GetSQLValueString($cveprod_autoriza, "text"));
                     $resultadolistaau = mssql_query($querylistaau);
                     $fetchlistaau = mssql_fetch_array($resultadolistaau);
                     $codigolistaau = $fetchlistaau['ItemCode'];
                     $preciolistaau = $fetchlistaau['Price'];
                     
  
   $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autoriza=%s WHERE n_remision=%s  AND cve_prod=%s",
     GetSQLValueString($date, "date"),
           GetSQLValueString($_REQUEST['autorizar'],'int'),
           GetSQLValueString($_REQUEST['productoid'],'text'));
    $queryrautorizar=mysqli_query($conecta1, $actualizautorizar) or die (mysqli_error($conecta1));
    
    
      if($precio_autoriza>$preciolistaau && $precio_autoriza<$precio_gerente || $precio_autoriza<$preciolistaau){
        $actualizardc = sprintf("UPDATE detalle_pedido SET au_dc = 0, au_gerente=1,estatus='A',fecha_autoriza=%s WHERE n_remision=%s  AND cve_prod=%s",
             GetSQLValueString($date, "date"),
                  GetSQLValueString($_REQUEST['autorizar'],'int'),
           GetSQLValueString($_REQUEST['productoid'],'text'));
          $queryactualizardc = mysqli_query($conecta1, $actualizardc) or die (mysqli_error($conecta1));
      }
              
     
     
 }
 
  IF (isset($_REQUEST['rechazar'])){
      
         $actualizarrechazar = sprintf("UPDATE detalle_pedido SET estatus='N',au_gerente=0, fecha_autoriza=%s WHERE n_remision=%s  AND cve_prod=%s",
  GetSQLValueString($date, "date"),
                 GetSQLValueString($_REQUEST['rechazar'],'int'),
           GetSQLValueString($_REQUEST['productoid'],'text'));
    $queryrechazar=mysqli_query($conecta1, $actualizarrechazar) or die (mysqli_error($conecta1));
      
  }
  
  
  
  //****************************************IFS DE DIRECCIÓN COMERCIAL
   IF (isset($_REQUEST['autorizardc'])){
     
     $cveprod_autoriza = $_REQUEST['productoid'];
     $precio_autoriza =$_REQUEST['precio'];
     $precio_gerente=$_REQUEST['preciogerente'];
     
     $querylistaau=sprintf("SELECT * FROM plataformaproductosl8 WHERE ItemCode=%s",
                     GetSQLValueString($cveprod_autoriza, "text"));
                     $resultadolistaau = mssql_query($querylistaau);
                     $fetchlistaau = mssql_fetch_array($resultadolistaau);
                     $codigolistaau = $fetchlistaau['ItemCode'];
                     $preciolistaau = $fetchlistaau['Price'];
                     
  
   $actualizautorizardc = sprintf("UPDATE detalle_pedido SET estatus='E',au_dc=1,fecha_autorizadc=%s WHERE n_remision=%s  AND cve_prod=%s",
  GetSQLValueString($date, "date"),
           GetSQLValueString($_REQUEST['autorizardc'],'int'),
           GetSQLValueString($_REQUEST['productoiddc'],'text'));
    $queryrautorizardc=mysqli_query($conecta1, $actualizautorizardc) or die (mysqli_error($conecta1));
 
     
     
 }
  
IF (isset($_REQUEST['rechazardc'])){
      
         $actualizrechazardc = sprintf("UPDATE detalle_pedido SET estatus='NA' WHERE n_remision=%s  AND cve_prod=%s",
        GetSQLValueString($_REQUEST['rechazardc'],'int'),
         GetSQLValueString($_REQUEST['productoiddc'],'text'));
    $queryrrechazardc=mysqli_query($conecta1, $actualizrechazardc) or die (mysqli_error($conecta1));
      
  }
  
  
  
   if($idgerente!='10'){
 $consultaremisiones = sprintf("SELECT * FROM relacion_gerentes_detalle_pedido WHERE cve_gte=%s AND au_gerente = 0 AND estatus = 'A' AND bandera_especial=1 ORDER BY fecha_alta DESC ",
 GetSQLValueString($idgerente, "int"));
 }else{
   $consultaremisiones = "SELECT * FROM relacion_gerentes_detalle_pedido WHERE au_dc = 0 AND au_gerente=1 AND estatus = 'A' AND bandera_especial=1 ORDER BY n_remision DESC ";    
 }
 $queryremisiones=mysqli_query($conecta1, $consultaremisiones) or die (mysqli_error($conecta1));
 
 
 ?>

<form method="post" action="pedidos_autoriza_gerentes.php">
<h3>Pedidos Especiales por autorizar <?php //echo $actualizrechazardc; ?></h3>
<div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Folio</th>
                     <th>Clave</th>
                     <th>Producto</th>
                     <th>Representante</th>
                     <th>Fecha Pedido</th>
                     <th>Precio final</th>
                     <th>Precio Gerente</th>
                     <th>Autorizado</th>
           
            

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
                    <td><a href="pedido_detalle_gerentes.php?remision=<?php echo $registro1['n_remision'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $registro1['n_remision'];?></a></td>        
                    <td><?php echo $registro1['cve_prod'];?></td> 
                    <td><?php echo $registro1['nom_prod'];?></td> 
                    <td><?php echo $registro1['nom_age'];?></td> 
                     <td><?php echo $registro1['fecha_alta'];?></td> 
                     <td><?php echo '$'.$registro1['precio_representante'];?></td> 
                     <td><?php echo '$'.$fetchlista['Price'];?></td>
                     
                    <?php if ($numerogerente!=10){ ?> 
                    <td><a href="pedidos_autoriza_gerentes.php?autorizar=<?php echo $registro1['n_remision']; ?>&productoid=<?php echo $registro1['cve_prod']; ?>&precio=<?php echo $registro1['precio_representante']; ?>&preciogerente=<?php echo $fetchlista['Price'] ?>"  onclick="return confirm('¿Está Seguro de AUTORIZAR?')"><img src="images/circle.png"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="pedidos_autoriza_gerentes.php?rechazar=<?php echo $registro1['n_remision']; ?>&productoid=<?php echo $registro1['cve_prod']; ?>"  onclick="return confirm('¿Está Seguro de RECHAZAR?')"><img src="images/delete.png"/></a></td>
                    
                    
                    <?php
                    
                    //<td> DE DIRECCIÓN COMERCIAL
                    }else if ($numerogerente==10){   ?>
                    <td><a href="pedidos_autoriza_gerentes.php?autorizardc=<?php echo $registro1['n_remision']; ?>&productoiddc=<?php echo $registro1['cve_prod']; ?>&preciodc=<?php echo $registro1['Price']; ?>&preciogerentedc=<?php echo $fetchlista['Price'] ?>"  onclick="return confirm('¿Está Seguro de AUTORIZAR?')"><img src="images/circle.png"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="pedidos_autoriza_gerentes.php?rechazardc=<?php echo $registro1['n_remision']; ?>&productoiddc=<?php echo $registro1['cve_prod']; ?>"  onclick="return confirm('¿Está Seguro de RECHAZAR?')"><img src="images/delete.png"/></a></td>

                     <?php }?>
                 </tr>
                   <?php 
                   

                   
                 } ?>
             </tbody>


         </table>

             </div>


</form>


<?php require_once 'foot.php';?>