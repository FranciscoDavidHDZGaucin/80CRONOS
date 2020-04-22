<?php

require_once('header_gerentes.php');
require_once('Connections/conecta1.php');
require_once('funciones.php');
/*
require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);
   
require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");    
*/
$idagente = $_SESSION["usuario_agente"];
/*
if ($_SESSION['usuario_valido']=='dircomer'){
        $almacen="SELECT almacen FROM matriz_almacen";
        $query_string1=mysqli_query($conecta1, $almacen) or die (mysqli_error($conecta1));
}else{
        $almacen=sprintf("SELECT distinct(t1.almacen) as almacen FROM pedidos.relacion_gerentes t0  inner join matriz_almacen t1 on t0.cve_age=t1.cve_gte  where t0.cve_gte=%s order by almacen",
                    GetSQLValueString($_SESSION["zona2"],"int"));
        $query_string1=mysqli_query($conecta1, $almacen) or die (mysqli_error($conecta1));
}
*/
//CONSULTA PARA MOSTRAR LOS ALMACENES COMO COLUMNAS
if ($_SESSION['usuario_valido']=='dircomer'){
    
    $almacen2="SELECT distinct(almacen) as almacen FROM matriz_almacen";
    $query_string12=mysqli_query($conecta1, $almacen2) or die (mysqli_error($conecta1));

}else{
    
    $almacen2=sprintf("SELECT distinct(t1.almacen) as almacen FROM pedidos.relacion_gerentes t0  inner join matriz_almacen t1 on t0.cve_age=t1.cve_gte  where t0.cve_gte=%s order by almacen",
                    GetSQLValueString($_SESSION["zona2"],"int"));
    /*
    $almacen2=sprintf("SELECT distinct(almacen) as almacen FROM matriz_almacen WHERE cve_gte = %s",
                 GetSQLValueString($_SESSION["usuario_agente"],"int"));
    
     */
    
    $query_string12=mysqli_query($conecta1, $almacen2) or die (mysqli_error($conecta1));
}



    
    
if (isset($_REQUEST['almacen'])){
    ///Filtra el almacen
    $almacen=$_REQUEST['almacen'];
    $string_sql=sprintf("SELECT * FROM cronos_existencias WHERE WhsCode = %s AND OnHand>0 ORDER BY ItemName ASC",
                GetSQLValueString($almacen,"text"));
   $query_existe= mssql_query($string_sql); 
    
    
}    


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?> 

<div class="container">
        <div class="page-header">
          <h4>Existencias <?php //echo $instruccion; ?></h4>
          
          <h5>Almacen
          
              <form name="formab" method="POST">
                  
                  <select name="almacen" onchange="this.form.submit()">
                      <option value="">Elija Almacen</option>
                      <?php 
                        while($combo1=mysqli_fetch_array($query_string12)){
                             if ($combo1['almacen']==$almacen){

                             echo '<option selected value="'.$combo1['almacen'].'">'. nombre_almacen($combo1['almacen']).'-'.$combo1['almacen'].'</option>';	
                            }else{
                                    echo '<option value="'.$combo1['almacen'].'">'.nombre_almacen($combo1['almacen']).'-'.$combo1['almacen'].'</option>';	
                            }	
                    
                            
                            
                        }
      
                      ?>
                      
                  </select>
                  
                  
              </form> 
          
          </h5>
          
          
          
          
        </div>
   
          <form name="forma1" method="POST" action="clientes.php">
              
     
                <div class="table-responsive">
                  <table  class="table table-responsive table-hover">
                      <!--<thead style="position: fixed;  margin-top: -40px; background-color: white;">-->
                      <thead>
                          <tr>
                           
                              <th width="10%">Codigo</th>
                              <th width="10%">Nombre</th>
                              <th width="10%">Existencia</th>
                              
                        
                          </tr>
                      </thead>
                      
                      <tbody>
                          <?php
                          while ($var = mssql_fetch_array($query_existe)) {
                             
                              ?>
                              <tr>
                                
                                  <td width="10%"><?php echo $var['ItemCode'];  ?></td>
                                  <td width="30%"><?php echo $var['ItemName']; ?></td>
                                  <td width="30%"><?php echo number_format($var['OnHand'], 2, '.', ','); ?></td>
                                  
                              </tr>
                            
                     <?php    } ?>             
                      
                         
                    
                      </tbody>    
                      
                  </table>
                  
              </div> 
          </form>
      </div><!-- /.container -->
      
 <?php require_once('foot.php');?>     