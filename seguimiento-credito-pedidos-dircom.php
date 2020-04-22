<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : seguimiento-credito-pedidos-dircom.php
 	Fecha  Creacion : 23/09/2016
	Descripcion  : 
	Copia  archivo   seguimiento-credito-pedidos-dircom.php  parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
*/

///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 
require_once('header_direccion.php');
///***Conexion  sap
require_once('conexion_sap/sap.php');
/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///**Uso de  la Base  de Datos
mssql_select_db("AGROVERSA"); 
///****FIN    Librerias  Utilizadas  en Cronos
require('correos.php');   //funcion para mandar correos
require('buscar_email.php');   //funcion para obtener el email de un usuario en especifico

//Funcion para validar si el Pedido se bloquea por credito
 function razonescxc($cardcode,$monto,$dxcx,$tipoventa){
 
      $query=("SELECT CardCode, CardName, DocNum, DocDate, DocTotal, PaidToDate, DocTotalFC, PaidSumFC, DocCur, SlpCode, DocDueDate, DocStatus, DATEDIFF(day,DocDueDate,getdate()) as dv  FROM OINV WHERE CardCode='$cardcode' and DocStatus='O' and DATEDIFF(day,DocDueDate,getdate())>60");
     $tabla = mssqli_query($query);
     $total_vencido= mssqli_num_rows($tabla);
      $query_cliente=("SELECT OCRD.CardCode,OCRD.CardName, OCRD.Balance, OCRD.CreditLine, OCRD.Frozenfor, OCTG.Extradays FROM  OCRD INNER JOIN OCTG ON OCRD.GroupNum=OCTG.GroupNum WHERE OCRD.CardCode='$cardcode'");
     $d_cliente=  mssqli_query($query_cliente);
     $datos_cliente=  mssqli_fetch_array($d_cliente);
     $dato1=$datos_cliente['Balance'];  //Balance actual
     $dato2=$datos_cliente['CreditLine'];
     $dato3=trim($datos_cliente['Frozenfor']);
     $dato4=$datos_cliente['Extradays'];
     $dato5=$datos_cliente['CardName'];
     
     
   $venta=$tipoventa;
   if ($venta==0){
       $leyendaventa="CONTADO";
   }else{
       $leyendaventa="CREDITO";
   }
   
   $total=$monto;
   $diasc=$dxcx;
   $estatus="E";
   $balance=$dato1+$total;
   $razon="SE PUEDE FACTURAR";
     
   
    if ($venta==0){
       $estatus="C";
       $razon1="Bloqueado por ser venta de Contado";
   
   }
       
   if ($balance>$dato2){
       $estatus="C";
       $razon2="Limite excedido";
       if ($dato2>0){
            $porce_excede=(($balance/$dato2)-1)*100;
       }else{
           $porce_excede=100;
       }
      
   
   }else{
       $porce_excede=0;
   }  
   
   
       $plazo=0;
   if ($diasc>$dato4){
       $estatus="C";
       $razon3="dias de credito excedidos, plazo capturado ".$diasc." días cliente ".$dato4;
        $plazo=1;  //El plazo esta arriba del que esta dado de alta en SAP 
   
   }  
   
   
   
     $fv30=0;
   if ($total_vencido){
        $estatus="C";
       $razon4="Facturas Vencidas";
       $fv30=1;   ///tiene facturas vencidas mayores a 30 días
       
   }
   
   if ($dato3=="Y"){
         $estatus="C";
       $razon5="Cliente Bloqueado";
   }
     
   $autorizar=0;
   
   if ($porce_excede>15 or $plazo==1 or $fv30==1 ){
       $autoriza=1;  //no se puede autorizar por el Gerente de Zona
   }
   
   
    $razones=$razon1.", ".$razon2.", ".$razon3.", ".$razon4.", ".$razon5;
   // return $razones;
    
     return array($razones,$porce_excede,$plazo,$fv30,$autoriza);
 }    

?> 
<div  class="container"> 
    
<div class="espacio-datos">
	
       <center><h3>Pedidos detenidos por Crédito</h3>    </center>
 
	  		<?PHP
	    
		//mysqli_select_db($database_conecta1,$conecta1);
                mysqli_select_db($conecta2, $database_conecta1);
                
                //Rutina por mientras para que solo muestre los pedidos que estan pendientes de su zona
                $consulta=sprintf("SELECT * FROM encabezado_x_zona WHERE  estatus='C'");
                          
               // $sql_consulta=mysql_query($consulta,$conecta1) or die (mysql_error());
                $sql_consulta=mysqli_query($conecta1, $consulta) or die (mysqli_error($conecta1)); 
		$Total = mysqli_num_rows ($sql_consulta);
                
                
                
                /*
		$consulta=sprintf("SELECT * FROM encabezado_x_zona WHERE  isnull(vbo_gerente) and not isnull(vbo_gestor) and estatus='C' and (cve_gte=%s)",
                               GetSQLValueString($_SESSION['usuario_rol'], "int"));
					
                */
		?>			
		 <form id="form2" name="form2" method="post" >
		 
		<?php if($Total<>0) {  ?>
		 
                              
                               <div class="table-responsive">
				  <table  class="table table-responsive table-bordered">
                                    <thead>
					<tr>
						<th width="200">Cliente:</th>
						<th width="60">Agente:</th>
						<th width="60">Remision:</th>
						<th width="60">fecha:</th>
						<th width="40">Plazo:</th>
						<th width="50">Moneda:</th>
						<th width="50">Total:</th>
                                                <th></th>
                                               <!-- <th width="30">Gestor</th>-->
                                                <th width="30">Gerente</th>
                                                <th width="30">CyC</th>
                                                <th width="30">DirCom</th>
                                                <th width="30">Dirgral</th>
                                               
                                                
						
						
					</tr>
                                    </thead>
                                    <tbody>
						
				<?php		while ($reg = mysqli_fetch_array($sql_consulta)) {   ?>
							  
                                                    <tr>
                                                    <td  title="<?php echo $reg['observacion']; ?>"><?php echo $reg['nom_cte']; ?></td>
                                                    <td><?php echo $reg['nom_age']; ?></td>
                                                    <td><?php echo $reg['n_remision']; ?></td>
                                                    <td><?php echo $reg['fecha_alta']; ?></td>
                                                    <td><?php echo $reg['plazo']; ?></td>
                                                    <td><?php if ($reg['moneda']==1){ 
                                                               echo "Pesos" ;
                                                            }else{
                                                             echo "Dolar";
                                                            }											   
                                                                      ?></td>
                                                    <td  title="<?php echo $reg['total']; ?>"><?php echo number_format($reg['total_p'], 2, '.', ','); ?></td>
                                                    <td><a href="cronos-recogedor.php?con_consecutivo=<?php echo $reg['id']; ?>&remision=<?php echo $reg['n_remision']; ?>&agente=<?php echo $reg['n_agente']; ?>&cliente=<?php echo $reg['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=550,height=500,scrollbars=yes'); return false;"> <img src="iconos/detalle2.png"/></a>
                                                        <a href="pop-credito-historia.php?respuesta=1&id=<?php echo $reg['id']; ?>&remision=<?php echo $reg['n_remision']; ?>&agente=<?php echo $reg['n_agente']; ?>&cliente=<?php echo $reg['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=500,height=500,scrollbars=yes'); return false;"><img src="images/edit.png"/></a>
                                                    </td>
                                                      
                                                    <!--<td>  <?php ///Estatus Gestor
                                                          /* if (is_null($reg['vbo_gestor'])){
                                                                echo '<img src="images/clock2.png" title="Pendiente">';
                                                           }else{
                                                               echo '<img src="images/revisado.png" title="Revisado">';
                                                           }*/?> </td> -->
                                                     <td>  <?php ///Estatus Gerente
                                                           if (is_null($reg['vbo_gestor']) || is_null($reg['vbo_gerente'])){
                                                                echo '<img src="images/clock2.png" title="Pendiente">';
                                                           }else{
                                                               if ((!is_null($reg['vbo_gestor']) and !is_null($reg['vbo_gerente']))){
                                                                 echo '<img src="images/revisado.png" title="Revisado">';
                                                               }else{
                                                                     echo '';
                                                               }
                                                              
                                                           }?> </td> 
                                                     
                                                       <td>  <?php ///Estatus JefeCyc
                                                           if (!is_null($reg['vbo_gerente']) and is_null($reg['vbo_jefecyc'])){
                                                                echo '<img src="images/clock2.png" title="Pendiente">';
                                                           }else{
                                                               if ((!is_null($reg['vbo_gerente']) and !is_null($reg['vbo_jefecyc']))){
                                                                   echo '<img src="images/revisado.png" title="Revisado">';
                                                               }else{
                                                                     echo '';
                                                               }
                                                              
                                                           }?> </td> 
                                                        <td>  <?php ///Estatus Dircom
                                                           if (!is_null($reg['vbo_jefecyc']) and is_null($reg['vbo_dircom'])){
                                                               echo '<img src="images/clock2.png" title="Pendiente">';
                                                           }else{
                                                               if ((!is_null($reg['vbo_jefecyc']) and !is_null($reg['vbo_dircom']))){
                                                                   echo '<img src="images/revisado.png" title="Revisado">';
                                                               }else{
                                                                     echo '';
                                                               }
                                                              
                                                           }?> </td> 
                                                        
                                                         <td>  <?php ///Estatus DirGral
                                                           if (!is_null($reg['vbo_dircom']) and is_null($reg['vbo_dirgral'])){
                                                                echo '<img src="images/clock2.png" title="Pendiente">';
                                                           }else{
                                                               if ((!is_null($reg['vbo_dircom']) and !is_null($reg['vbo_dirgral']))){
                                                                    echo '<img src="images/revisado.png" title="Revisado">';
                                                               }else{
                                                                     echo '';
                                                               }
                                                              
                                                           }?> </td> 
                                                     
                                                                                      
                                                       <input type="hidden" name="id_dato" value=" <?php echo $reg['id'];?>"  />
                                                       <input type="hidden" name="id_agente" value=" <?php echo $reg['n_agente'];?>"  />
                                                       <input type="hidden" name="id_rem" value=" <?php echo $reg['n_remision'];?>"  />
                                                       <input type="hidden" name="id_cte" value=" <?php echo $reg['cve_cte'];?>"  />
                                                       <input type="hidden" name="nom_cte" value=" <?php echo $reg['nom_cte'];?>"  />
                                                       <input type="hidden" name="nom_age" value=" <?php echo $reg['nom_age'];?>"  />
                                                       <input type="hidden" name="fecha_alta" value=" <?php echo $reg['fecha_alta'];?>"  />
                                                       <input type="hidden" name="total" value=" <?php echo $reg['total'];?>"  />
                                                       <input type="hidden" name="plazo" value=" <?php echo $reg['plazo'];?>"  />


                                                    </tr>
					  <?php } ?>
                      
				<?php	   }  ?>
                                </tbody>                                        
				</table>
                                </div>
		</form>			   

</div> <!-- Div de Contenido  -->

</div>
 <?php require_once('foot.php');?>     