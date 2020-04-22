<?php

require_once('header.php');
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);
   
require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");    

$idagente = $_SESSION["usuario_agente"];

if ($_SESSION['usuario_valido']=='dircomer'){
$almacen="SELECT almacen FROM matriz_almacen";
$query_string1=mysqli_query($conecta1, $almacen) or die (mysqli_error($conecta1));
}else{
$almacen=sprintf("SELECT almacen FROM matriz_almacen WHERE cve_gte = %s",
 GetSQLValueString($_SESSION["usuario_agente"],"int"));
$query_string1=mysqli_query($conecta1, $almacen) or die (mysqli_error($conecta1));
}

//CONSULTA PARA MOSTRAR LOS ALMACENES COMO COLUMNAS
if ($_SESSION['usuario_valido']=='dircomer'){
    
    $almacen2="SELECT distinct(almacen) as almacen FROM matriz_almacen";
    $query_string12=mysqli_query($conecta1, $almacen2) or die (mysqli_error($conecta1));

}else{
$almacen2=sprintf("SELECT distinct(almacen) as almacen FROM matriz_almacen WHERE cve_gte = %s",
 GetSQLValueString($_SESSION["usuario_agente"],"int"));
$query_string12=mysqli_query($conecta1, $almacen2) or die (mysqli_error($conecta1));
}

//CONSULTA PARA MOSTRAR 1 REGISTRO DEL PRODUCTO
$string1 = "SELECT DISTINCT(ItemCode),ItemName, U_LineaProd FROM cronos_existencias WHERE ";
$string3 = '';
$cont = mysqli_num_rows($query_string1);
$contalmacen = mysqli_num_rows($query_string1);

while ($fila=mysqli_fetch_array($query_string1)){

 $cont2 = $cont2 + 1;
 
  $string2 = sprintf(" WhsCode = %s ",
            GetSQLValueString($fila['almacen'],"text"));
   
  IF ($cont == $cont2){
   $junto = $junto.$string2; 
  }else{
    $junto = $junto.$string2." OR ";   
  }
    
}
//CONSULTA PARA LAS PRIMERAS 2 COLUMNAS DE LA TABLA
 $instruccion = $string1.$junto.'ORDER BY U_LineaProd DESC';
$query_instruccion= mssql_query($instruccion);


//CONSULTA PARA POSICION ALMACENES



/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?> 

<div class="container">
        <div class="page-header">
          <h4>Existencias <?php //echo $instruccion; ?></h4>
        </div>
   
          <form name="forma1" method="POST" action="clientes.php">
              
     
                <div class="table-responsive">
                  <table  class="table table-responsive table-hover">
                      <!--<thead style="position: fixed;  margin-top: -40px; background-color: white;">-->
                      <thead>
                          <tr>
                              <th width="5%">Línea</th>
                              <th width="10%">Codigo</th>
                              <th width="10%">Nombre</th>
                              
                               <?php 
                         
                          while($fila3=mysqli_fetch_array($query_string12)){
                          
                          ?>
                              <th width="10%">Almacen <?php echo $fila3['almacen'];?></th>
                              
                                <?php }?>
                              
                       
                              <th width="10%">Total</th>  
                          </tr>
                      </thead>
                      
                      <tbody>
                          <?php
                          while ($var = mssql_fetch_array($query_instruccion)) {
                              if ($_SESSION['usuario_valido']=='dircomer'){
                                $almacen3="SELECT distinct(almacen) FROM matriz_almacen";
                                $query_string123=mysqli_query($conecta1, $almacen3) or die (mysqli_error($conecta1));
                              }else{
                              $almacen3=sprintf("SELECT distinct(almacen) FROM matriz_almacen WHERE cve_gte = %s",
                                        GetSQLValueString($_SESSION["usuario_agente"],"int"));
                                $query_string123=mysqli_query($conecta1, $almacen3) or die (mysqli_error($conecta1));
                              }
                              ?>
                              <tr>
                                  <td width="10%"><?php echo $var['U_LineaProd']; ?></td>
                                  <td width="10%"><?php echo $var['ItemCode'];  ?></td>
                                  <td width="30%"><?php echo $var['ItemName']; ?></td>
                                  

                             
                                  
                                  
                            <?php   
                            
                            $total=0;
                               while($fila2=mysqli_fetch_array($query_string123)){
                                    
                                     $almacen4=sprintf("SELECT OnHand FROM cronos_existencias WHERE WhsCode = %s AND ItemCode=%s",
                                        GetSQLValueString($fila2['almacen'],"text"),
                                           GetSQLValueString($var['ItemCode'],"text"));
                                     
                                        
                                       $query_string4=mssql_query($almacen4);
                                       $Fetchstring=  mssql_fetch_assoc($query_string4);
                                       
                                       
                                      $total= $total +$Fetchstring['OnHand'];
                                      IF($Fetchstring['OnHand']>0){   
                                          ?>
                                            
                                  <td>  <a href="pop-existelotes.php?cve_prod=<?php echo $var['ItemCode'];  ?>&nombreprod=<?php echo $var['ItemName'];?>&almacen=<?php echo $fila2['almacen'];?>" target="_blank" onClick="window.open(this.href, this.target, 'width=700,height=500,scrollbars=yes'); return false;"><?php echo number_format(floor($Fetchstring['OnHand'])); ?></a></td>
                                      <?php       
                                      }else{
                                           echo  '<td width="10%">0</td>';   
                                      }
                              ?>   
                            
                              
                              
                          <?php } 
                          echo '<td>'.number_format(floor($total)).'</td>';
                          echo ' </tr>';
                               } ?>
                         
                    
                      </tbody>    
                      
                  </table>
                  
              </div> 
          </form>
      </div><!-- /.container -->
      
 <?php require_once('foot.php');?>     