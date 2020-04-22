<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php 
 require_once('header_direccion.php');
 
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");  

function before_last ($this, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this));
    }


    
 ///Zonas    
  $string_zona="SELECT distinct nom_gte, cve_gte FROM relacion_gerentes ORDER BY nom_gte"; 
  $query_zona=mysqli_query($conecta1, $string_zona) or die (mysqli_error($conecta1));
     
IF (isset($_REQUEST['zona'])){  ///Definir Zona
    
    $zona=$_REQUEST['zona'];
    $_SESSION['zona']=$zona;
    //Consulta para mostrar los agentes que le corresponden al gerente actual
    $strinagente=sprintf("SELECT (cve_age), nom_age FROM relacion_gerentes where cve_gte=%s  ORDER BY nom_gte DESC",
                    GetSQLValueString($zona,"int"));
                  
     $quey_strinagente=mysqli_query($conecta1, $strinagente) or die (mysqli_error($conecta1));
    
}

IF ($_POST['representante']!=""){
    //echo 'Entro al filtro Agente';
     $representante = $_POST['representante'];
     $_SESSION['representante']=$representante;
    
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
    
    <form name="form1" method="post" action="clientes-edocta_dircom.php">
        <p>
          <select name="zona" onchange="this.form.submit()">
            <option value="">Elija Zona</option>
              <?php

                while ($rowzona=mysqli_fetch_array($query_zona))

                 {
                     if ($rowzona['cve_gte']==$_SESSION['zona']){

                         echo '<option selected value="'.$rowzona['cve_gte'].'">'.$rowzona['nom_gte'].'</option>';	
                     }else{
                         echo '<option value="'.$rowzona['cve_gte'].'">'.$rowzona['nom_gte'].'</option>';	
                     }	
                 }
              ?>
            
         </select>   
      
        <select name="representante" onchange="this.form.submit()">
            <option value="">Elija Representante</option>
              <?php

               while ($rowrepresentantes2=mysqli_fetch_array($quey_strinagente))

                 {
                     if ($rowrepresentantes2['cve_age']==$_SESSION['representante']){

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
        
        IF (($_REQUEST['cliente'])!="" ){  
            ///////////////////Mostrar los datos Generales y detallados del cliente elegido
             $querycliente=sprintf("SELECT * FROM clientes_cronos WHERE  CardCode = %s ",
                           GetSQLValueString($_REQUEST['cliente'], "text"));      
             $cliente2=mssql_query($querycliente);
        
        
            $codigo2=$_POST['cliente'];
            $datoscliente=  mssql_fetch_array($cliente2);
     
            $saldo = $datoscliente['Balance'];
            $dias = $datoscliente['ExtraDays'];
            $limite = $datoscliente['CreditLine'];
            $mail = $datoscliente['E_Mail'];
            
             $queryfactuas = sprintf("SELECT * FROM saldos_facturas_cronos WHERE CardCode = %s",
                                             GetSQLValueString($codigo2, "text"));
                          
             $facturadatos2 = mssql_query($queryfactuas);
             $facturadatos = mssql_query($queryfactuas);

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
            
            
            
            ?>
            
              <div class="table-responsive">
                   <table  class="table table-responsive table-hover">
                         <tr>
                           
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
                             <td><?php echo "$".number_format($sumasaldo,2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($sumacorriente,2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($sumavencido,2, '.', ',') ; ?></td>
                            <td><?php echo  "$".number_format($suma,2, '.', ','); ?></td>
                            <td><?php echo "$".number_format($suma30,2, '.', ',');?></td>
                            <td><?php echo "$".number_format($suma60,2, '.', ','); ?></td>
                            <td><?php echo "$".number_format($suma90,2, '.', ','); ?></td>
                            <td><?php echo "$".number_format($suma120,2, '.', ',');?></td>    
                            <td><?php echo number_format($porcentajeagente,2, '.', ',')."%";?></td> 
                            
                        </tbody>  
                   </table>     
              
              </div>
        
        
               <div class="table-responsive">
                    <table  class="table table-responsive table-hover">
                       <thead>
                           <tr>
                               
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

                           WHILE ($registro1= mssql_fetch_array($facturadatos)){  ?>
                           <tr>
                                <td><a href="pop-detallefactura.php?factura_sap=<?php echo $registro1['DocNum'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=700,height=500,scrollbars=yes'); return false;"><?php echo $registro1['DocNum'];?></a></td>        
                                <td><?php echo $registro1['FolioPref'].$registro1['FolioNum'];?></td>                
                                <td><?php echo $registro1['DocCur'];?></td>
                               <td><?php echo '$'.number_format($registro1['DocTotal'],2, '.', ',');?></td>
                               <td><?php echo '$'.number_format($registro1['saldo'],2, '.', ',');?></td>
                               <td><?php echo date("Y-m-d",strtotime($registro1['DocDueDate']));?></td>    
                               <td><?php echo $registro1['dv'];?></td>
                           
                             

                           </tr>
                             <?php 



                           } ?>
                       </tbody>


                   </table>
             </div>
            
            
        <?php    
        }
        IF ($_REQUEST['representante']!=""){
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
                  GetSQLValueString($_REQUEST['representante'], "int"));      
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
                                    <td><?php echo floor($porcentajeagente)."%";?></td>    
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
                            <td><?php echo  floor($grantotalporcentajeagente)."%"; ?></td>
                            
                            
                        </tr>
                    </tbody>


                </table>
        </div>
            
            
        
          <?php 
        
        
              
         }
         ?>
        
    </div>   <!-- /fin Accordean genearal -->  
    
    
    
</div>


 <?php require_once('foot.php');?>     