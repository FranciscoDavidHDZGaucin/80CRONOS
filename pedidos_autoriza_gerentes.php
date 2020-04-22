<?php 
 session_start ();
if($_SESSION["usuario_rol"]==10){
    require_once('header_direccion.php');
   
}else{
  
    require_once 'header_gerentes.php';
   
}
///*****Agregamos Index Inteligencia 

if($_SESSION["usuario_rol"]==96 || $_SESSION["usuario_rol"]==69 ){
    require_once('header_inteligencia.php');
   
}

if($_SESSION["usuario_rol"]==100  ){
    require_once('header_planeador.php');
   
}

  require_once('funciones.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");    
 
 $idgerente=$_SESSION["usuario_rol"];
 
 $date = date('Y-m-d H:i:s');

 

//$listacomercial = "plataformaproductosl7";
$numerogerente = $_SESSION["usuario_rol"];


switch ($numerogerente) {
    case 1:  //Gerente Zona Centro

         $listaprecios = "plataformaproductosl5";
        break;

    case 2:   //Gerente Zona //Gerente Zona sur

         $listaprecios = "plataformaproductosl3";
        break;
    case 3:  //Gerente Zona Norte

         $listaprecios = "plataformaproductosl4";
        break;
    case 5:    //Gerente Zona Verur

         $listaprecios = "plataformaproductosl7";
        break;
    case 6:     //Gerente zona Local

         $listaprecios = "plataformaproductosl6";
        break;
    case 10:    //Director Comercial

         $listaprecios = "plataformaproductosl8";
        break;

       case  69:    //Jefe   Inteligencia Comercial

         $listaprecios = "plataformaproductosl8";
        break;

      case  96:    //Analista  Jr

         $listaprecios = "plataformaproductosl8";
        break;

}



//****************************************IFS DE GERENTES
 IF (isset($_REQUEST['autorizar'])){
     
     $id_detalle=$_REQUEST['autorizar'];
     $cveprod_autoriza =$_REQUEST['productoid'];
     $precio_autoriza =$_REQUEST['precio'];
     $precio_gerente=$_REQUEST['preciogerente'];
     
    /* 
     ///consulta a la lista de precios de direccion comercial
    $querylistaau=sprintf("SELECT * FROM plataformaproductosl8 WHERE ItemCode=%s",
                     GetSQLValueString($cveprod_autoriza, "text"));
    $resultadolistaau = mssql_query($querylistaau);
    $fetchlistaau = mssql_fetch_array($resultadolistaau);
    $codigolistaau = $fetchlistaau['ItemCode'];
    $preciolistaau = $fetchlistaau['Price'];
    ///////                 
  */
      /*        


      */


    if($precio_autoriza>=$precio_gerente){     ///si el precio esta en el rango del gerente se debe cambiar el estatus a E=Emitida
         $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autoriza=%s, au_gerente=1 WHERE id_detalle=%s",
                             GetSQLValueString($date, "date"),
                            GetSQLValueString($id_detalle,'int'));
                       
        $queryrautorizar=mysqli_query($conecta1, $actualizautorizar) or die (mysqli_error($conecta1));
    }else{   ///No entro en el rango de autorización por el gerente aquí solo se registra la respuesta del gerente quedando pendiente el campo de estatus
          $actualizautorizar = sprintf("UPDATE detalle_pedido SET fecha_autoriza=%s, au_gerente=1 WHERE id_detalle=%s",
                             GetSQLValueString($date, "date"),
                            GetSQLValueString($id_detalle,'int'));
                       
         $queryrautorizar=mysqli_query($conecta1, $actualizautorizar) or die (mysqli_error($conecta1));
        
    }
    
    
    
    /*   codigo original
   $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autoriza=%s WHERE id_detalle=%s",
                         GetSQLValueString($date, "date"),
                           GetSQLValueString($id_detalle,'int'));
                       
    $queryrautorizar=mysqli_query($conecta1, $actualizautorizar) or die (mysqli_error($conecta1));
    
    
      if($precio_autoriza>$preciolistaau && $precio_autoriza<$precio_gerente || $precio_autoriza<$preciolistaau){
        $actualizardc = sprintf("UPDATE detalle_pedido SET au_dc = 0, au_gerente=1,estatus='A',fecha_autoriza=%s WHERE n_remision=%s  AND cve_prod=%s",
                         GetSQLValueString($date, "date"),
                         GetSQLValueString($_REQUEST['autorizar'],'int'),
                          GetSQLValueString($_REQUEST['productoid'],'text'));
        $queryactualizardc = mysqli_query($conecta1, $actualizardc) or die (mysqli_error($conecta1));
      }
              
     */
     
 }
 
  IF (isset($_REQUEST['rechazar'])){
        $id_detalle=$_REQUEST['rechazar'];
         $actualizarrechazar = sprintf("UPDATE detalle_pedido SET estatus='NA',au_gerente=2, fecha_autoriza=%s WHERE id_detalle=%s",
                                GetSQLValueString($date, "date"),
                                GetSQLValueString($id_detalle,'int'));
       $queryrechazar=mysqli_query($conecta1, $actualizarrechazar) or die (mysqli_error($conecta1));
      ///Notificar mail al rechazar
      noautoriza_precio($id_detalle); ///manda correo notificando la no autorizacion del Producto
  }
  
  
  
  //****************************************IFS DE DIRECCIÓN COMERCIAL
IF (isset($_REQUEST['autorizardc'])){
     
     $id_detalle=$_REQUEST['autorizardc'];  //campo id_detalle
     $precio_autoriza =$_REQUEST['precio'];  //Precio final remision
     
     ///consulta a la lista de precios de direccion comercial
    $querylistaau=sprintf("SELECT * FROM plataformaproductosl8 WHERE ItemCode=%s",
                     GetSQLValueString($cveprod_autoriza, "text"));
    $resultadolistaau = mssql_query($querylistaau);
    $fetchlistaau = mssql_fetch_array($resultadolistaau);
    $codigolistaau = $fetchlistaau['ItemCode'];
    $preciolistaau = $fetchlistaau['Price'];
    ///////                 
  
    //////******Direccion Comercial 
     if($idgerente ==10)
     {
        if($precio_autoriza>=$preciolistaau){     ///Si el director Comercial autoriza este producto se tiene que liberar si el rango de precios excede al permitido, solo debe mandar notificación a Direccion Operacione y Comercial notificando esta acción
        $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autorizadc=%s,  au_dc=1 WHERE id_detalle=%s",
                                  GetSQLValueString($date, "date"),
                                  GetSQLValueString($id_detalle,'int'));
         }else{
               $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autorizadc=%s, au_dc=1 WHERE id_detalle=%s",
                                        GetSQLValueString($date, "date"),
                                        GetSQLValueString($id_detalle,'int'));
               ///Se incluye la notificación a Direccion de Operaciones y General
             //  echo 'Notifica';
               
         }

     }
     ////****Autoriza Jefe de INTELIGENCIA Comercial
     if($idgerente == 69)
     {
        if($precio_autoriza>=$preciolistaau){     ///Si el director Comercial autoriza este producto se tiene que liberar si el rango de precios excede al permitido, solo debe mandar notificación a Direccion Operacione y Comercial notificando esta acción
        $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autorizadc=%s,fecha_autorizajefecomer=%s , au_dc=1 WHERE id_detalle=%s",
                                  GetSQLValueString($date, "date"),
                                  GetSQLValueString($date, "date"),
                                  GetSQLValueString($id_detalle,'int'));
         }else{
               $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autorizadc=%s,fecha_autorizajefecomer=%s, au_dc=1 WHERE id_detalle=%s",
                                        GetSQLValueString($date, "date"),
                                        GetSQLValueString($date, "date"),
                                        GetSQLValueString($id_detalle,'int'));
               ///Se incluye la notificación a Direccion de Operaciones y General
             //  echo 'Notifica';
               
         }

     }
     /////***Autoriza Analista  Jr 
      if($idgerente ==96 || $idgerente == 100)
     {
            if($precio_autoriza>=$preciolistaau){     ///Si el director Comercial autoriza este producto se tiene que liberar si el rango de precios excede al permitido, solo debe mandar notificación a Direccion Operacione y Comercial notificando esta acción
        $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autorizadc=%s,fecha_autorizanalisajr=%s , au_dc=1 WHERE id_detalle=%s",
                                  GetSQLValueString($date, "date"),
                                  GetSQLValueString($date, "date"),
                                  GetSQLValueString($id_detalle,'int'));
         }else{
               $actualizautorizar = sprintf("UPDATE detalle_pedido SET estatus='E',fecha_autorizadc=%s,fecha_autorizanalisajr=%s, au_dc=1 WHERE id_detalle=%s",
                                        GetSQLValueString($date, "date"),
                                        GetSQLValueString($date, "date"),
                                        GetSQLValueString($id_detalle,'int'));
               ///Se incluye la notificación a Direccion de Operaciones y General
             //  echo 'Notifica';
               
         }

     }  

/////******************************************************************************************    
   
    
//echo $actualizautorizar.'<br>';
@mysqli_query($conecta1, $actualizautorizar) or die (mysqli_error($conecta1));
 
     
     
 }
  
IF (isset($_REQUEST['rechazardc'])){
      ///Rechazo por parte de Direccion Comercial
     $id_detalle=$_REQUEST['rechazardc'];
     $actualizrechazardc = sprintf("UPDATE detalle_pedido SET estatus='NA',fecha_autorizadc=%s, au_dc=2 WHERE id_detalle=%s",
                                  GetSQLValueString($date, "date"),
                                  GetSQLValueString($id_detalle,'int'));
    @mysqli_query($conecta1, $actualizrechazardc) or die (mysqli_error($conecta1));
    
    //Notificacion para Agente de que el producto no se autoriza
     noautoriza_precio($id_detalle); ///manda correo notificando la no autorizacion del Producto
    
      
  }
  
  


if($idgerente=='10' ||$idgerente=='69'||$idgerente=='96' || $idgerente == '100'){

  //Vista de los pendientes por autorizar para dirección Comercial
     
       $consultaremisiones = "SELECT * FROM relacion_gerentes_detalle_pedido WHERE au_dc = 0 AND au_gerente=1 AND estatus = 'A'|| (n_agente = 198  and  estatus = 'A') || (n_agente = 99  and  estatus = 'A') || (n_agente = 123  and  estatus = 'A')    ORDER BY n_remision DESC ";

}else{


    //Vista que muestra los productos por autorizar del gerente que le compete
      
        /*  ////***Consulta Remplazada para  que  el Gerente Pueda  ver los pedidos  Sin Nececidad de la  Autorizacion del  Gestor    
         * $consultaremisiones = sprintf("SELECT * FROM relacion_gerentes_detalle_pedido WHERE cve_gte=%s AND au_gerente = 0 AND estatus = 'A' ORDER BY fecha_alta DESC ",
                              GetSQLValueString($idgerente, "int"));
        */ 
         $consultaremisiones = sprintf("SELECT * FROM relacion_gerentes_detalle_pedido WHERE cve_gte=%s AND au_gerente = 0 AND ( estatus = 'A' OR estatus = 'C' ) ORDER BY fecha_alta DESC ",
                              GetSQLValueString($idgerente, "int"));
} 







/*
  
if($idgerente!='10'){
      //Vista que muestra los productos por autorizar del gerente que le compete
      
        /*  ////***Consulta Remplazada para  que  el Gerente Pueda  ver los pedidos  Sin Nececidad de la  Autorizacion del  Gestor    
         * $consultaremisiones = sprintf("SELECT * FROM relacion_gerentes_detalle_pedido WHERE cve_gte=%s AND au_gerente = 0 AND estatus = 'A' ORDER BY fecha_alta DESC ",
                              GetSQLValueString($idgerente, "int"));
        
         $consultaremisiones = sprintf("SELECT * FROM relacion_gerentes_detalle_pedido WHERE cve_gte=%s AND au_gerente = 0 AND ( estatus = 'A' OR estatus = 'C' ) ORDER BY fecha_alta DESC ",
                              GetSQLValueString($idgerente, "int"));
 }else{
      //Vista de los pendientes por autorizar para dirección Comercial
     
       $consultaremisiones = "SELECT * FROM relacion_gerentes_detalle_pedido WHERE au_dc = 0 AND au_gerente=1 AND estatus = 'A' ORDER BY n_remision DESC ";     
 }*/
 ///echo $consultaremisiones.'<br>';
 $queryremisiones=mysqli_query($conecta1, $consultaremisiones) or die (mysqli_error($conecta1));
 
 
 ?>
<div class="container">
<form method="post" action="pedidos_autoriza_gerentes.php">
<h5>Productos  Pendientes por Autorizar <?php /// echo $idgerente; ?></h5>
<div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Folio</th>
                     <th>Clave</th>
                     <th>Producto</th>
                     <th>Agente</th>
                     <th>Plazo</th>
                     <th>Fecha </th>
                     <th>Cantidad</th> 
                     <th>Precio </th>
                     <?php    
                             if ($numerogerente==10){
                                echo '<th>Precio Direccion</th>';
                             }else{
                                 echo '<th>Precio Gerente</th>';
                                 
                             }
                     
                     ?>
                    
                     <th>Costo</th>
                     <th>CMG%</th>
                     <?php 
                       if(($numerogerente==10)){        
                       echo '<th>CMG Min%</th>'; ;
                       
                       }
                     ?>
                     <th>Autorizar</th>
                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mysqli_fetch_array($queryremisiones)){ 
                     
                       $remision=$registro1['n_remision'];
                       $agente=$registro1['n_agente'];
                        $cliente=$registro1['cve_cte'];
                       
                     
                     $querylista=sprintf("SELECT * FROM ".$listaprecios." WHERE ItemCode=%s",
                                GetSQLValueString($registro1['cve_prod'], "text"));
                     $resultadolista = mssql_query($querylista);
                     $fetchlista = mssql_fetch_array($resultadolista);
                     $codigolista = $fetchlista['ItemCode'];
                   
                     
                     $plazo=plazo_pedido($remision, $agente, $cliente);
                             
                     ///considerar si aplica el 6% por nota de credito a pago oportuno
                     //siempre y cuando sea a crédito
                             
                     if ($plazo>0){
                         $precio_ultimo=$registro1['precio_condcto']  ;///*.94; /////PRONTO PAGO 
                     } else{ 
                         
                          $precio_ultimo=$registro1['precio_condcto'];
                     }
                         
                             
                             
                             
                     ?>
                 <tr  <?php if( is_null($registro1['bonificacion'])){ echo "";}else{  echo "class='danger' title='Bonificacion'"; } ?>>
                    <td><a href="pedido_detalle_gerentes.php?remision=<?php echo $registro1['n_remision'];  ?> &agente=<?php echo $registro1['n_agente']; ?>&cliente=<?php echo $registro1['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $registro1['n_remision'];?></a></td>        
                    <td><?php echo $registro1['cve_prod'];?></td> 
                    <td><?php echo $registro1['nom_prod'];?></td> 
                    <td><?php echo $registro1['nom_age'];?></td> 
                     <td><?php echo $plazo;?></td> 
                     <td><?php echo $registro1['fecha_alta'];?></td> 
                      <td><?php echo $registro1['cant_prod'];?> </td> 
                     <td><?php echo '$'.$precio_ultimo;?></td> 
                     <!--Costo-->
                     <td><?php 
                     echo   '$'.$fetchlista['Price'];

                     ?>
                       
                     </td>
                        <td><?php 
							if ($idgerente==10 ||$idgerente == 69 || $idgerente ==  96 || $idgerente == 100  ){
								echo '$'.number_format(cmg($registro1['cve_prod']), 2, '.', ',');
							}	
									
						?></td> 
                      <td><?php
                            $costo=  cmg($registro1['cve_prod']);
                            $cmg=($precio_ultimo-$costo)/$precio_ultimo;
							if ($idgerente==10 || $idgerente == 96 || $idgerente == 100){
								 echo number_format($cmg*100, 2, '.', ',').'%';
							}
                           ?>
                      </td> 
                    <?php if ($numerogerente!=10 && $idgerente != 69 && $idgerente != 96 || $idgerente == 100){ ?> 
                    <td><a href="pedidos_autoriza_gerentes.php?autorizar=<?php echo $registro1['id_detalle']; ?>&productoid=<?php echo $registro1['cve_prod']; ?>&precio=<?php echo $precio_ultimo; ?>&preciogerente=<?php echo $fetchlista['Price'] ?>"  onclick="return confirm('¿Está Seguro de AUTORIZAR?')"><img src="images/circle.png" height="24" width="24" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="pedidos_autoriza_gerentes.php?rechazar=<?php echo $registro1['id_detalle']; ?>&productoid=<?php echo $registro1['cve_prod']; ?>"  onclick="return confirm('¿Está Seguro de RECHAZAR?')"><img src="images/delete.png" height="24" width="24" ></a></td>
                    
                  
                    <?php
                    
                    //<td> DE DIRECCIÓN COMERCIAL
                    }else if ($numerogerente==10 || $idgerente == 69|| $idgerente == 96 || $idgerente == 100){   ?>
                    <td><?php  
                    
                           $string_get_cmg_min  = sprintf("SELECT cmg_min  FROM  cmgm_dircom where  cve_producto =%s",
                                                    GetSQLValueString($registro1['cve_prod'], "text"));
                           $qery_asd = mysqli_query($conecta1, $string_get_cmg_min);
                            
                           $fetch_cmg_min  = mysqli_fetch_array($qery_asd);
                           echo   $fetch_cmg_min['cmg_min']."%";
                    
                    ?></td>  
                        <td><a href="pedidos_autoriza_gerentes.php?autorizardc=<?php echo $registro1['id_detalle']; ?>&productoiddc=<?php echo $registro1['cve_prod']; ?>&precio=<?php echo $precio_ultimo; ?>&preciogerentedc=<?php echo $fetchlista['Price'] ?>"  onclick="return confirm('¿Está Seguro de AUTORIZAR?')"><img src="images/circle.png" height="24" width="24" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="pedidos_autoriza_gerentes.php?rechazardc=<?php echo $registro1['id_detalle']; ?>&productoiddc=<?php echo $registro1['cve_prod']; ?>"  onclick="return confirm('¿Está Seguro de RECHAZAR?')"><img src="images/delete.png" height="24" width="24" /></a></td>
                    <?php }?>
                 </tr>
                   <?php 
                   

                   
                 } ?>
             </tbody>


         </table>

             </div>


    </form>
</div><!-- /.container -->

<?php require_once 'foot.php';?>