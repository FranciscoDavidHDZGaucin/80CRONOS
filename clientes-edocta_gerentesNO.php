<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php 
 require_once('header_gerentes.php');
 
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 mssql_select_db("AGROVERSA");  
 
 $idagente = $_SESSION["usuario_agente"];
 



function before_last ($this, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this));
    }


     
     $stringzona="SELECT DISTINCT(cve_gte), nom_gte FROM relacion_gerentes ORDER BY nom_gte DESC";
     $zona=mysqli_query($conecta1, $stringzona) or die (mysqli_error($conecta1));
 
IF ($_POST['zona']!=""){
    
    
    $zonadorada = $_POST['zona'];
    
    $stringrepresentantes = sprintf("SELECT * FROM relacion_gerentes WHERE cve_gte = %s",
    GetSQLValueString($zonadorada, "int"));
    $queryrepresentantes2 = mysqli_query($conecta1, $stringrepresentantes) or die (mysqli_error($conecta1));
    $queryrepresentantes = mysqli_query($conecta1, $stringrepresentantes) or die (mysqli_error($conecta1));
    
    while($bucle = mysqli_fetch_array($queryrepresentantes)){
    
    $queryfactuas = sprintf("SELECT * FROM saldos_facturas_cronos WHERE SlpCode = %s",
                                             GetSQLValueString($bucle['cve_age'], "int"));
                          
     $facturadatos2 = mssql_query($queryfactuas);
     $facturadatos = mssql_query($queryfactuas);
    }
    
 
}

IF ($_POST['representante']!=""){
    
    $representante = $_POST['representante'];
    
     $querycliente1=sprintf("SELECT * FROM saldos_facturas_cronos WHERE SlpCode=%s ",
                  GetSQLValueString($representante, "int"));
           
    $clienterepresentante = mssql_query($querycliente1);
    
     $querycliente13=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s ",
                  GetSQLValueString($representante, "int"));
           
    $cliente = mssql_query($querycliente13);
   
     
    
}

  IF ($_POST['cliente']!=""){
      
      
      $clientevariable = $_POST['cliente'];
      
       $querycliente1=sprintf("SELECT * FROM saldos_facturas_cronos WHERE CardCode=%s ",
                  GetSQLValueString($clientevariable, "text"));     
        $clienterepresentante=mssql_query($querycliente1);
      
  }
  


?>

<div class="container">
        <div class="page-header">
          <h3>Estado de cuenta</h3>      
        </div>
    
    <form method="post">
        <p>
            
        <select name="zona" onchange="this.form.submit()">
            <option value="" >Elija Zona</option>
             <?php

                       while ($rowzona=mysqli_fetch_array($zona))
                               
                         {
                             if ($rowzona['cve_gte']==$_REQUEST['zona']){

                             echo '<option value="'.$rowzona['cve_gte'].'">'.$rowzona['nom_gte'].'</option>';	
                             }else{
                                     echo '<option value="'.$rowzona['cve_gte'].'">'.$rowzona['nom_gte'].'</option>';	
                             }	
                         }
                     ?>
        </select>
        <select name="representante" onchange="this.form.submit()">
            <option value="">Elija Representante</option>
              <?php

                       while ($rowrepresentantes2=mysqli_fetch_array($queryrepresentantes2))
                               
                         {
                             if ($rowrepresentantes2['cve_age']==$_REQUEST['representante']){

                             echo '<option selected value="'.$rowrepresentantes2['cve_age'].'">'.$rowrepresentantes2['nom_age'].'</option>';	
                             }else{
                                     echo '<option value="'.$rowrepresentantes2['cve_age'].'">'.$rowrepresentantes2['nom_age'].'</option>';	
                             }	
                         }
                     ?>
            
        </select>
             <div class="input-group input-group select2-bootstrap-prepend">
                   <span class="input-group-btn">
                            <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                                    <span class="glyphicon glyphicon-search"></span>
                            </button>
                    </span>
                    <select name="cliente" class="form-control select2" id="cliente"  onchange="this.form.submit()" >
                        <option value="">Todos los Clientes</option>  
                     <?php

                       while ($row=mssql_fetch_array($cliente))
                               
                         {
                             if ($row['CardCode']==$_REQUEST['cliente']){

                             echo '<option selected value="'.$row['CardCode'].'">'.$row['CardCode'].'-'.utf8_encode($row['CardName']).'</option>';	
                             }else{
                                     echo '<option value="'.$row['CardCode'].'">'.$row['CardCode'].'-'.utf8_encode($row['CardName']).'</option>';	
                             }	
                         }
                     ?>
                     </select>
               </div>
        </p>
    

    
    
 
       
        
    </form>
    <P>.</P>
    
    <div class="accordion" id="accordion2">
        <?php  
        
       
            ///////////////////Mostrar los datos Generales y detallados del cliente elegido
          
                IF ($_POST['zona']!=""){
                        
                     
                        
                          $stringrepresentantestabla = sprintf("SELECT * FROM relacion_gerentes WHERE cve_gte = %s",
                    GetSQLValueString($zonadorada, "int"));
                    $queryrepresentantestabla = mysqli_query($conecta1, $stringrepresentantestabla) or die (mysqli_error($conecta1));
                    
                    while($bucletabla = mysqli_fetch_array($queryrepresentantestabla)){
                        
       
                        $representantetabla = sprintf("SELECT * FROM saldos_facturas_cronos WHERE SlpCode = %s",
                                            GetSQLValueString($bucletabla['cve_age'], "int"));
                            $facturaclietabla = mssql_query($representantetabla);
                            
                            while($consultabucletabla = mssql_fetch_array($facturaclietabla)){
                            
     
            $saldo = $consultabucletabla['Balance'];
            $dias = $consultabucletabla['ExtraDays'];
            $limite = $consultabucletabla['CreditLine'];
            $mail = $consultabucletabla['E_Mail'];
            
             $queryfactuastabla = sprintf("SELECT * FROM saldos_facturas_cronos WHERE CardCode = %s",
                                             GetSQLValueString($consultabucletabla['CardCode'], "text"));
                          
             $facturadatostabla2 = mssql_query($queryfactuastabla);
             $facturadatos = mssql_query($queryfactuastabla);

                            $sumacorriente=0;
                            $suma=0;
                            $suma30=0;
                            $suma60=0;
                            $suma90=0;
                            $suma120=0;

                            WHILE ($registro2tabla= mssql_fetch_array($facturadatostabla2)){  
                                   $registro2tabla['DocNum'];
                                   $registro2tabla['DocTotal'];
                                   $registro2tabla['dv'];
                                   $registro2tabla['saldo']; 
                                  if ($registro2tabla['dv']<0){ $sumacorriente = $sumacorriente + $registro2tabla['saldo'];}
                                  if ($registro2tabla['dv']>=1 && $registro2tabla['dv']<=30){ $suma = $suma + $registro2tabla['saldo'];}
                                  if ($registro2tabla['dv']>= 31 && $registro2tabla['dv']<=60){ $suma30 = $suma30 + $registro2tabla['saldo'];}
                                  if ($registro2tabla['dv']>= 61 && $registro2tabla['dv']<=90){ $suma60 = $suma60 + $registro2tabla['saldo'];}
                                  if ($registro2tabla['dv']>= 91 && $registro2tabla['dv']<=120){ $suma90 = $suma90 + $registro2tabla['saldo'];}
                                  if ($registro2tabla['dv']>=121){ $suma120 = $suma120 + $registro2tabla['saldo'];}
                                }

                          $sumavencido=$suma+$suma30+$suma60+$suma90+$suma120;
                            
                           
                            $sumasaldo=$sumacorriente+$sumavencido;
                             //Dato para obtener la cartera vencida solo se incluya >30 días
                                         $vencidoagente=$suma30+$suma60+$suma90+$suma120;
                                         $grantotalvencidoagente=$vencidoagente+$grantotalvencidoagente;
                                         $porcentajeagente=($vencidoagente/$sumasaldo)*100;
                            //// 
                                        
                            }
                  $zonasumasaldo+=$sumasaldo;
                    
                    
            ?>
            
              <div class="table-responsive">
                   <table  class="table table-responsive table-hover">
                         <tr>
                             <th>Agente</th>
                            <th>Suma Saldo</th>
                            <th>Corriente</th>
                            <th>Vencido</th>
                            <th>1-30</th>
                            <th>31-60</th>
                            <th>61-90</th>
                            <th>91-120</th>
                            <th>+120</th>
                            <th>%</th>
                        </tr>
                        <tbody>
                            <td><?php echo $bucletabla['cve_age']; ?></td>
                             <td><?php echo "$".floor(($zonasumasaldo)); ?></td>
                            <td><?php echo  "$".floor($sumacorriente); ?></td>
                            <td><?php echo  "$".floor($sumavencido); ?></td>
                            <td><?php echo  "$".floor($suma); ?></td>
                            <td><?php echo "$".floor($suma30);?></td>
                            <td><?php echo "$".floor($suma60); ?></td>
                            <td><?php echo "$".floor($suma90); ?></td>
                            <td><?php echo "$".floor($suma120);?></td>    
                            <td><?php echo "%".floor($porcentajeagente);?></td> 
                            
                        </tbody>  
                   </table>     
              
              </div>
                    <?php 
                    }
                                  }?>
<!--        /////////////////////////// AQUI ////////////////////////////////////////////////////////////////////////-->        
<!--        /////////////////////////// AQUI ////////////////////////////////////////////////////////////////////////-->
<!--        /////////////////////////// AQUI ////////////////////////////////////////////////////////////////////////-->
               <div class="table-responsive">
                    <table  class="table table-responsive table-hover">
                       <thead>
                           <tr>
                               <th>Representante</th>
                               <th>Fact. Sap</th>
                               <th>Folio Fiscal</th>
                               <th>Moneda</th>
                               <th>Monto</th>
                               <th>Saldo</th>                                               
                               <th>Vencimiento</th>
                               <th>Días Vencidos</th>


                           </tr>
                       </thead>
                       <tbody>
                           <?php 
              
                    
                    
                    IF ($_POST['zona']!=""){
                        
                        
                          $stringrepresentantes = sprintf("SELECT * FROM relacion_gerentes WHERE cve_gte = %s",
                    GetSQLValueString($zonadorada, "int"));
                    $queryrepresentantes = mysqli_query($conecta1, $stringrepresentantes) or die (mysqli_error($conecta1));
                    
                    while($bucle = mysqli_fetch_array($queryrepresentantes)){
                        
       
                        $representante = sprintf("SELECT * FROM saldos_facturas_cronos WHERE SlpCode = %s",
                                            GetSQLValueString($bucle['cve_age'], "int"));
                            $facturaclie = mssql_query($representante);
                            
                            while($consultabucle = mssql_fetch_array($facturaclie)){
                            
                         
                             
                               
                               ?>
                           <tr>
                               <td><?php echo $bucle['nom_age'];?></td>
                                <td><a href="pop-detallefactura.php?factura_sap=<?php echo $consultabucle['DocNum'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=700,height=500,scrollbars=yes'); return false;"><?php echo $consultabucle['DocNum'];?></a></td>        
                                <td><?php echo $consultabucle['FolioPref'].$consultabucle['FolioNum'];?></td>                
                                <td><?php echo $consultabucle['DocCur'];?></td>
                               <td><?php echo '$'.floor($consultabucle['DocTotal']);?></td>
                               <td><?php echo '$'.floor($consultabucle['saldo']);?></td>
                               <td><?php echo date("Y-m-d",strtotime($consultabucle['DocDueDate']));?></td>    
                               <td><?php echo $consultabucle['dv'];?></td>
                           
                             

                           </tr>
                             <?php 
                            }


                           } 
                    } else {
                    
                           
               
                 $queryyy = mssql_query($querycliente1);
                             while($consultabucle2 = mssql_fetch_array($queryyy)){
                              
                        ?>
                           
                           <tr>
                               <td><?php echo $consultabucle2['SlpCode'];?></td>
                                <td><a href="pop-detallefactura.php?factura_sap=<?php echo $consultabucle2['DocNum'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=700,height=500,scrollbars=yes'); return false;"><?php echo $consultabucle2['DocNum'];?></a></td>        
                                <td><?php echo $consultabucle2['FolioPref'].$consultabucle['FolioNum'];?></td>                
                                <td><?php echo $consultabucle2['DocCur'];?></td>
                               <td><?php echo '$'.floor($consultabucle2['DocTotal']);?></td>
                               <td><?php echo '$'.floor($consultabucle2['saldo']);?></td>
                               <td><?php echo date("Y-m-d",strtotime($consultabucle2['DocDueDate']));?></td>    
                               <td><?php echo $consultabucle2['dv'];?></td>
                           
                             

                           </tr>
                        
                 <?php   }
                    }
                           ?>
                       </tbody>


                   </table>
             </div>
            
            
        <?php    
        
            //////////////////Mostrar el General de todos los Clientes sin el detallado
             $grantotalsuma=0;
             $grantotalsuma30=0;
             $grantotalsuma60=0;
             $grantotalsuma90=0;
             $grantotalsuma120=0;
             $grantotalsaldo=0;
             $grantotalcorriente=0;
             $grantotalvencido=0;
             $grantotalvencidoagente=0;
             $querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s",
                  GetSQLValueString($idagente, "int"));      
             $cliente2=mssql_query($querycliente);   
        
        ?>
        <div class="table-responsive">
         

      
           
                   <table  class="table table-responsive table-hover">
                         <tr>
                            <th>Cliente</th>
                            <th>Suma Saldo</th>
                            <th>Corriente</th>
                            <th>Vencido</th>
                            <th>1-30</th>
                            <th>31-60</th>
                            <th>61-90</th>
                            <th>91-120</th>
                            <th>+120</th>
                            <th>%</th>
                        </tr>
                    <tbody>
                        <?php
                          while ($rowc2=mssql_fetch_array($cliente2)){
                
                           $codigo2 = $rowc2['CardCode'];
                           $querydatos2 = sprintf("Select * FROM clientes_cronos WHERE CardCode = %s",
                                          GetSQLValueString($codigo2, "text"));
                           $clientedatos2 = mssql_query($querydatos2);
                           $datoscliente2=  mssql_fetch_array($clientedatos2);

                            $nombre = $datoscliente2['CardName'];
                            $saldo = $datoscliente2['Balance'];
                            $dias = $datoscliente2['ExtraDays'];
                            $limite = $datoscliente2['CreditLine'];
                            $mail = $datoscliente2['E_Mail'];



                             $queryfactuas = sprintf("SELECT * FROM saldos_facturas_cronos WHERE CardCode = %s",
                                             GetSQLValueString($codigo2, "text"));
                             $facturadatos = mssql_query($queryfactuas);
                             $facturadatos2 = mssql_query($queryfactuas);

                            $sumacorriente=0;
                            $suma=0;
                            $suma30=0;
                            $suma60=0;
                            $suma90=0;
                            $suma120=0;

                            WHILE ($registro2= mssql_fetch_array($facturadatos2)){  
                                   $registro2['DocNum'];
                                   $registro2['DocTotal'];
                                   $registro2['dv'];
                                   $registro2['saldo']; 
                                  if ($registro2['dv']<0){ $sumacorriente = $sumacorriente + $registro2['saldo'];}
                                  if ($registro2['dv']>=1 && $registro2['dv']<=30){ $suma = $suma + $registro2['saldo'];}
                                  if ($registro2['dv']>= 31 && $registro2['dv']<=60){ $suma30 = $suma30 + $registro2['saldo'];}
                                  if ($registro2['dv']>= 61 && $registro2['dv']<=90){ $suma60 = $suma60 + $registro2['saldo'];}
                                  if ($registro2['dv']>= 91 && $registro2['dv']<=120){ $suma90 = $suma90 + $registro2['saldo'];}
                                  if ($registro2['dv']>=121){ $suma120 = $suma120 + $registro2['saldo'];}
                                }

                            $sumavencido=$suma+$suma30+$suma60+$suma90+$suma120;
                            
                           
                            $sumasaldo=$sumacorriente+$sumavencido;
                             //Dato para obtener la cartera vencida solo se incluya >30 días
                                         $vencidoagente=$suma30+$suma60+$suma90+$suma120;
                                         $grantotalvencidoagente=$vencidoagente+$grantotalvencidoagente;
                                         $porcentajeagente=($vencidoagente/$sumasaldo)*100;
                            ////
                            
                        
                            $grantotalsuma=$grantotalsuma+$suma;
                            $grantotalsuma30=$grantotalsuma30+$suma30;
                            $grantotalsuma60=$grantotalsuma60+$suma60;
                            $grantotalsuma90=$grantotalsuma90+$suma90;
                            $grantotalsuma120=$grantotalsuma120+$suma120;
                            $grantotalsaldo=$grantotalsaldo+$sumasaldo;
                            $grantotalcorriente=$grantotalcorriente+$sumacorriente;
                            $grantotalvencido=$grantotalvencido+$sumavencido;
                            
                            
                              $grantotalporcentajeagente=($grantotalvencidoagente/$grantotalsaldo)*100;
                        
                        if ($sumasaldo>0){
                            ////Solo se muestran los clientes que tengan saldo pendiente
                        
                        ?>
                                <tr>
                                    <td><?php echo  $codigo2.'-'.$nombre; ?></td>
                                    <td ><?php echo  number_format(floor($sumasaldo)); ?></td>
                                    <td ><?php echo  number_format(floor($sumacorriente)); ?></td>
                                    <td ><?php echo  number_format(floor($sumavencido)); ?></td>
                                    <td><?php echo  number_format(floor($suma)); ?></td>
                                    <td><?php echo number_format(floor($suma30));?></td>
                                    <td><?php echo number_format(floor($suma60)); ?></td>
                                    <td><?php echo number_format(floor($suma90)); ?></td>
                                    <td><?php echo number_format(floor($suma120));?></td>    
                                    <td><?php echo "%".floor($porcentajeagente);?></td>    
                                </tr>
                        
                        
                        
                     <?php  }
                          
                          }
                          ?>
                        
                        <tr>
                            <td>Total</td>
                            <td><?php echo  number_format(floor($grantotalsaldo)); ?></td>
                            <td><?php echo  number_format(floor($grantotalcorriente)); ?></td>
                            <td><?php echo  number_format(floor($grantotalvencido)); ?></td>
                            <td><?php echo  number_format(floor($grantotalsuma)); ?></td>
                            <td><?php echo  number_format(floor($grantotalsuma60)); ?></td>
                            <td><?php echo  number_format(floor($grantotalsuma90)); ?></td>
                            <td><?php echo  number_format(floor($grantotalsuma120)); ?></td>
                            <td><?php echo  "%".floor($grantotalporcentajeagente); ?></td>
                            
                            
                        </tr>
                    </tbody>


                </table>
        </div>
            
            
        
          <?php 
        
        
              
         
         ?>
        
    </div>   <!-- /fin Accordean genearal -->  
    
    
</div>


 <?php require_once('foot.php');?>     