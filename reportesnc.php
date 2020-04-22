<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : reportesnc.php  
 	Fecha  Creacion :  20/09/2016    
	Descripcion  : 
           Copia del   archivo   reportesnc.php  del  Proyecto  Pedidos
 *         
	Modificado  Fecha  : 
*/
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 
require_once('header_gerentes.php');
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
///mssql_select_db("AGROVERSA");
////***************************
require('correos.php');   //funcion para mandar correos
///***********************************
require('calculodv.php');   //funcion para obtener el email de un usuario en especifico
///****FIN    Librerias  Utilizadas  en Cronos 

$string_agentes="SELECT distinctrow(n_agente) as n_agente, agente from notas order by agente";
$query_agentes=  mysqli_query($conecta1, $string_agentes);


$string_clientes="SELECT distinctrow(cve_cte) as cve_cte, cliente from notas order by cliente";
$query_clientes=  mysqli_query($conecta1, $string_clientes);

if (isset($_REQUEST['filtrar'])){
    $op1="0";
    $op2="0";
    $op3="0";
    
    
    
    $status=$_POST['status'];
    $agente=$_POST['agente'];
    $cliente=$_POST['cliente'];
    
    switch ($status) {
        

         case "1":
              $comodo="(status='A' or status='RA') and not(isnull(nc_sap)) order by cliente";

            break;
         case "2":
              $comodo="(status='A' or status='RA') and isnull(nc_sap) order by cliente";

            break;
         case "3":
              $comodo="status='R' order by cliente";

            break;
    }
    
    
    if ($status!=""){
        $op1="1";   
    }
    
     if ($agente!=""){
        $op2="1";   
    }
    
     if ($cliente!=""){
        $op3="1";  
    }
    
    $junto=$op1.$op2.$op3;
    
    
    
    switch ($junto) {
        case "000":
                    //TODO
                 $str_consulta="SELECT * FROM notas  order by cliente";

            break;

        case "001":
        //Por Cliente
                 $str_consulta=sprintf("SELECT * FROM notas WHERE cve_cte=%s Order by  cliente",
                          GetSQLValueString($cliente,"text"));
            break;
         case "010":
             //Por Agente
                 $str_consulta=sprintf("SELECT * FROM notas WHERE n_agente=%s Order by cliente",
                          GetSQLValueString($agente,"int"));
            break;
         case "011":
                //Por Cliente y Agente
                  $str_consulta=sprintf("SELECT * FROM notas WHERE n_agente=%s and cve_cte=%s Order by cliente",
                          GetSQLValueString($cliente,"text"),
                          GetSQLValueString($agente,"int"));
             
            break;
         case "100":
             //Por estatus
                 $str_consulta="SELECT * FROM notas  WHERE $comodo";
            break;
         case "101":
             //Por estatus, Cliente
              $str_consulta=sprintf("SELECT * FROM notas  WHERE cve_cte=%s and  $comodo",
                            GetSQLValueString($cliente,"text"));

            break;
          case "110":
               //Por estatus, Agente
                  $str_consulta=sprintf("SELECT * FROM notas  WHERE n_agente=%s and $comodo",
                                 GetSQLValueString($agente,"int"));
            break;
           case "111":
               //Por estatus, Agente,Cliente
                $str_consulta=sprintf("SELECT * FROM notas  WHERE n_agente=%s and cve_cte=%s and  $comodo",
                                 GetSQLValueString($agente,"int"),
                                 GetSQLValueString($cliente,"text"));   

            break; 
    }   
    
    
    
  //echo $str_consulta;
    $q_consulta=  mysqli_query($conecta1, $str_consulta) or die (mysqli_error($conecta1));
    
    
}
?> 
<div  class="container"> 
        <form name="form2" id="form2" method="POST" action="reportesnc.php">
                
                 <select name="status" id ="status">
                     <option value="">Notas Todas</option>
                     <option value="1">Notas Aplicadas</option>
                     <option value="2">Notas Pendientes</option>
                     <option value="3">Notas Rechazadas</option>
                     
                     
                 </select>
                 
                 <br> 
                <select name="agente" id ="agente">
                     <option value="">--Elija Agente--</option>
                     <?php while ($reg = mysqli_fetch_array($query_agentes)) {  
                         if ($agente==$reg['n_agente']){
                              echo '<option value="'.$reg['n_agente'].'" selected>'.$reg['agente'].'</option>';
                         }else{
                              echo '<option value="'.$reg['n_agente'].'">'.$reg['agente'].'</option>';
                         }
                         
                       
                         
                      }?> 
                 </select>
                    <select name="cliente" id ="cliente">
                     <option value="">--Elija Cliente--</option>
                      <?php while ($reg1 = mysqli_fetch_array($query_clientes)) {  
                          if ($cliente==$reg1['cve_cte']){
                               echo '<option value="'.$reg1['cve_cte'].'" selected>'.$reg1['cliente'].'</option>';
                          }else{
                               echo '<option value="'.$reg1['cve_cte'].'">'.$reg1['cliente'].'</option>';
                          }
                       
                         
                      }?> 
                     
                 </select>
                 <input type="submit" name="filtrar" value="Filtrar">
             </form>       
    <div class="table-responsive">
    <table  class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th>Factura</th>
                
                <th>Cliente</th>
                <th>Agente</th>
                <th>Moneda</th>
                <th>Total</th>
                 <th>Dv</th>
                <th>Concepto</th>
                <th>$Desc</th>
                <th>%Desc </th>
                <th>Status</th>
               
               
               
                
            </tr> 
            
        </thead>   
        
        <tbody>
            <?php 
                
                
                
            while ($reg = mysqli_fetch_array($q_consulta)) { 
                 ?>   
            
            <tr>
                <td><?php echo $reg['factura'];?> </td>
                
                <td><a href="popnc.php?id=<?php echo $reg['id']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=430,height=500,scrollbars=yes'); return false;"><?php echo $reg['cliente']; ?></a></td>
                <td><?php echo $reg['agente']; ?></td>
                <td><?php echo $reg['moneda']; ?></td>
                <td><?php echo number_format($reg['total_fac'], 2, '.', ','); ?></td>
               <td title="Días Vencido"><?php echo $reg['n_diasv']; ?></td>
                <td><?php echo $reg['nombre']; ?></td>
                <td><?php   echo number_format($reg['importe_desc'], 2, '.', ','); ?></td>
                <td><?php echo $reg['porce_desc']; ?></td>
                <td><?php
                   
                    
               if ($reg['nc_sap']!=""){
                   echo "Aplicada";
               
               }
              
               if (($reg['status']=='A' || $reg['status']=='RA' ) and $reg['nc_sap']=="" ){
                    echo "Pendiente";
               }
               
               if ($reg['status']=='R'){
                   echo "Rechazada";
               }
                
                
                ?></td>
                
                
            </tr>
            
            <?php } ?>

                
     
            
        </tbody>
        
        
    </table>
    </div>    
</div>
 <?php require_once('foot.php');?>  