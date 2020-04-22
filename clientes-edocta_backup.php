<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php 
 require_once('header.php');
 
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   
 require_once('conexion_sap/sap.php');
 mssql_select_db("AGROVERSA");  
 
 $idagente = $_SESSION["usuario_agente"];
 



function before_last ($this, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this));
    };


  IF (isset($_POST['cliente'])){
      
       $querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s and CardCode = %s ",
                  GetSQLValueString($idagente, "int"),
                  GetSQLValueString($_POST['cliente'], "text"));      
        $cliente2=mssql_query($querycliente);
      
  }else{
       $querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s",
                GetSQLValueString($idagente, "int"));      
        $cliente2=mssql_query($querycliente);
      
      
  }
    $querycliente1=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s ",
                  GetSQLValueString($idagente, "int"));
           
    $cliente = mssql_query($querycliente1);
  

?>

<div class="container">
        <div class="page-header">
          <h3>Estado de cuenta</h3>
          
        
        </div>
    <form name="forma" method="POST" action="clientes-edocta.php">
        <div class="col-md-10">
        
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
        </div>
       
        
    </form>
    <P>.</P>
    
    <div class="accordion" id="accordion2">
        <?php  
        
        IF (($_POST['cliente'])!="" ){  
            ///////////////////Mostrar los datos Generales y detallados del cliente elegido
             $querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s and CardCode = %s ",
                  GetSQLValueString($idagente, "int"),
                  GetSQLValueString($_POST['cliente'], "text"));      
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
                             <td><?php echo "$".number_format(($sumasaldo), 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($sumacorriente, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($sumavencido, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($suma, 2, '.', ','); ?></td>
                            <td><?php echo "$".number_format($suma30, 2, '.', ',');?></td>
                            <td><?php echo "$".number_format($suma60, 2, '.', ','); ?></td>
                            <td><?php echo "$".number_format($suma90, 2, '.', ','); ?></td>
                            <td><?php echo "$".number_format($suma120, 2, '.', ',');?></td>    
                            <td><?php echo "%".number_format($porcentajeagente, 2, '.', ',');?></td> 
                            
                        </tbody>  
                   </table>     
              
              </div>
        
        
               <div class="table-responsive">
                    <table  class="table table-responsive table-hover">
                       <thead>
                           <tr>
                               
                               <th>Fact. Sap</th>
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
                               <td><?php echo $registro1['DocCur'];?></td>
                               <td><?php echo number_format($registro1['DocTotal'], 2, '.', ',');?></td>
                               <td><?php echo number_format($registro1['saldo'], 2, '.', ',');?></td>
                               <td><?php echo date("Y-m-d",strtotime($registro1['DocDueDate']));?></td>    
                               <td><?php echo $registro1['dv'];?></td>
                           
                             

                           </tr>
                             <?php 



                           } ?>
                       </tbody>


                   </table>
             </div>
            
            
        <?php    
        }else{
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
                                    <td ><?php echo  "$".number_format(($sumasaldo), 2, '.', ','); ?></td>
                                    <td ><?php echo  "$".number_format($sumacorriente, 2, '.', ','); ?></td>
                                    <td ><?php echo  "$".number_format($sumavencido, 2, '.', ','); ?></td>
                                    <td><?php echo  "$".number_format($suma, 2, '.', ','); ?></td>
                                    <td><?php echo "$".number_format($suma30, 2, '.', ',');?></td>
                                    <td><?php echo "$".number_format($suma60, 2, '.', ','); ?></td>
                                    <td><?php echo "$".number_format($suma90, 2, '.', ','); ?></td>
                                    <td><?php echo "$".number_format($suma120, 2, '.', ',');?></td>    
                                    <td><?php echo "%".number_format($porcentajeagente, 2, '.', ',');?></td>    
                                </tr>
                        
                        
                        
                     <?php  }
                          
                          }
                          ?>
                        
                        <tr>
                            <td>Total</td>
                            <td><?php echo  "$".number_format($grantotalsaldo, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($grantotalcorriente, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($grantotalvencido, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($grantotalsuma, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($grantotalsuma30, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($grantotalsuma60, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($grantotalsuma90, 2, '.', ','); ?></td>
                            <td><?php echo  "$".number_format($grantotalsuma120, 2, '.', ','); ?></td>
                            <td><?php echo  "%".number_format($grantotalporcentajeagente, 2, '.', ','); ?></td>
                            
                            
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