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
 ///mssql_select_db("AGROVERSA");  
 
 $idagente = $_SESSION["usuario_agente"];
 
$querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s",
GetSQLValueString($idagente, "int"));
 
$cliente = mssql_query($querycliente);

function before_last ($this, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this));
    };


  IF (isset($_POST['cliente'])){
      
    $codigo = $_POST['cliente'];
    
    $querydatos = sprintf("Select * FROM clientes_cronos WHERE CardCode = %s",
    GetSQLValueString($codigo, "text"));
    $clientedatos = mssql_query($querydatos);
    
    $datoscliente=  mssql_fetch_array($clientedatos);
     
     $saldo = $datoscliente['Balance'];
     $dias = $datoscliente['ExtraDays'];
     $limite = $datoscliente['CreditLine'];
     $mail = $datoscliente['E_Mail'];
     
     
     //script para sacar mail
     $string = $mail;
     if(substr($string, 0, strpos($string, ','))){
     $substring = substr($string, 0, strpos($string, ','));}
     else{
         $substring = $mail;
     }

     
     $queryfactuas = sprintf("SELECT * FROM saldos_facturas_cronos WHERE CardCode = %s",
    GetSQLValueString($codigo, "text"));
    $facturadatos = mssql_query($queryfactuas);
    $facturadatos2 = mssql_query($queryfactuas);
     
  
    
  }
                 $sumacorriente;
                 $suma30;
                 $suma60;
                 $suma90;
                 $suma120;
                 
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
                 
                 

?>


<div class="container">
        <div class="page-header">
          <h3>Estado de cuenta</h3>
          
        
        </div>
    <form name="forma" method="POST" action="clientesdetalle.php">
        <div class="col-md-10">
        
               <div class="input-group input-group select2-bootstrap-prepend">
                   <span class="input-group-btn">
                            <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                                    <span class="glyphicon glyphicon-search"></span>
                            </button>
                    </span>
                    <select name="cliente" class="form-control select2" id="cliente"  onchange="this.form.submit()" >
                        <option>Elija cliente</option>  
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
         <br>
         <div class="col-md-6">
         <label class="label label-success">Saldo: <?php echo number_format($saldo, 2, '.', ',');?></label>
         </div>
         <div class="col-md-6">
         <label class="label label-success">Plazo autorizado: <?php echo $dias;?></label>
         </div>
         <div class="col-md-6">
         <label class="label label-success">Límite de Crédito: <?php echo number_format($limite, 2, '.', ','); ?></label>
         </div>
         
         <div class="col-md-6">
         <label class="label label-success">Saldo disponible: <?php 
         $corriente = $limite-$saldo;
         echo number_format($corriente, 2, '.', ','); ?></label>
         </div>
         
         <br>
            <div class="container">
             <div class="col-md-10">
                 <div class="table-responsive">
                     <table  class="table table-responsive table-hover">
                         <thead>
                             <tr>
                                 <th>Corriente</th>
                                 <th>1-30</th>
                                 <th>31-60</th>
                                 <th>61-90</th>
                                 <th>91-120</th>
                                 <th>+120</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr>
                     <td><?php echo  number_format($sumacorriente, 2, '.', ','); ?></td>
                     <td><?php echo  number_format($suma, 2, '.', ','); ?></td>
                     <td><?php echo number_format($suma30, 2, '.', ',');?></td>
                     <td><?php echo number_format($suma60, 2, '.', ','); ?></td>
                     <td><?php echo number_format($suma90, 2, '.', ','); ?></td>
                     <td><?php echo number_format($suma120, 2, '.', ',');?></td>    
                             </tr>
                         </tbody>


                     </table>
                 </div>
             </div>
         </div>
         <div class="container">
         <div class="col-md-10">
             <div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     
                     <th>Fact. Sap</th>
                     <th>Monto</th>
                     <th>Días Vencidos</th>
                     <th>Vencimiento</th>
                     <th>Saldo</th>
            

                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mssql_fetch_array($facturadatos)){  ?>
                 <tr>
                        
                     <td><a href="pop-detallefactura.php?factura_sap=<?php echo $registro1['DocNum'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=700,height=500,scrollbars=yes'); return false;"><?php echo $registro1['DocNum'];?></a></td>
                     <td><?php echo number_format($registro1['DocTotal'], 2, '.', ',');?></td>
                     <td><?php echo $registro1['dv'];?></td>
                     <td><?php echo date("Y-m-d",strtotime($registro1['DocDueDate']));?></td>
                     <td><?php echo number_format($registro1['saldo'], 2, '.', ',');?></td>
                     
                 </tr>
                   <?php 
                   

                   
                 } ?>
             </tbody>


         </table>
             </div>
         </div>
         </div>
         
      
        
    </form>
</div>



 <?php require_once('foot.php');?>     