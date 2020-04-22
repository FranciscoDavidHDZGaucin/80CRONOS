<?php 
require_once 'header_comisiones.php';
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);

require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");


$string_agentes="select * from relacion_gerentes order by nom_empleado";
$query_agentes=mysqli_query($conecta1, $string_agentes) or die (mysqli_error($conecta1));

$mes=$_REQUEST['mes'];
$anio=$_REQUEST['anio'];




require_once('funciones_comisiones.php');

  if (isset($_REQUEST['importar'])){ 

       //Eliminar los datos actuales
            $string_eliminar=sprintf("delete from saldos_facturas where mes_corte=%s and anio_corte=%s",
                            GetSQLValueString($mes, "int"),
                            GetSQLValueString($anio, "int"));
            @mysqli_query($conecta1, $string_eliminar) or die (mysqli_error($conecta1));
            
      //Ir  a SAP para descargar los saldos SAP
        $string_saldossap = "SELECT * from saldos_facturas_cronos Where GroupCode<>109";   //se excluye a los clientes que se encuentran en Status Legal
        $query_saldossap= mssql_query($string_saldossap);
        while ($reg = mssql_fetch_array($query_saldossap)) {
            
            $r1=$reg['CardCode'];
            $r2=$reg['CardName'];
            $r3=$reg['DocNum'];
            $r4=date('Y-m-d',strtotime($reg['DocDate']));
            $r5=$reg['DocTotal'];
            $r6=$reg['PaidToDate'];
            $r7=$reg['saldo'];
            $r8=$reg['DocTotalFc'];
            $r9=$reg['PaidSumFc'];
            $r10=$reg['DocCur'];
            $r11=$reg['SlpCode'];
            $r12=date('Y-m-d',strtotime($reg['DocDueDate']));
            $r13=$reg['DocStatus'];
            $r14=$reg['dv'];
            $r15=$reg['plazo_sap'];
            $r16=$reg['FolioPref'];
            $r17=$reg['FolioNum'];
            
            
          
            
             ///Insertat la información en la tabla Saldos Facturas
            $string_insertar=sprintf("insert into saldos_facturas set cardcode=%s, cardname=%s, docnum=%s, docdate=%s, doctotal=%s, paidtodate=%s, saldo=%s, "
                    . "doctotalfc=%s, paidsumfc=%s, doccur=%s, slpcode=%s, docduedate=%s, docstatus=%s, dv=%s, plazo_sap=%s, foliopref=%s, folionum=%s, mes_corte=%s, anio_corte=%s",
                        GetSQLValueString($r1, "text"),
                        GetSQLValueString($r2, "text"), 
                        GetSQLValueString($r3, "int"),
                        GetSQLValueString($r4, "date"),
                        GetSQLValueString($r5, "double"),
                        GetSQLValueString($r6, "double"),
                        GetSQLValueString($r7, "double"),
                        GetSQLValueString($r8, "double"),
                        GetSQLValueString($r9, "double"),
                        GetSQLValueString($r10, "text"),
                        GetSQLValueString($r11, "int"),
                        GetSQLValueString($r12, "date"),
                        GetSQLValueString($r13, "text"),
                        GetSQLValueString($r14, "int"),
                        GetSQLValueString($r15, "int"),
                        GetSQLValueString($r16, "text"),
                        GetSQLValueString($r17, "int"),
                        GetSQLValueString($mes, "int"),
                        GetSQLValueString($anio, "int"));
             @mysqli_query($conecta1, $string_insertar) or die (mysqli_error($conecta1));
                
        }
 
      
  }

   if (isset($_REQUEST['guardar'])){ 
       $mes=$_REQUEST['mes'];
       $anio=$_REQUEST['anio'];
       $string_agentes2="select * from relacion_gerentes order by nom_empleado";
       $query_agentes2=mysqli_query($conecta1, $string_agentes2) or die (mysqli_error($conecta1));
        while ($row2 = mysqli_fetch_array($query_agentes2)) {
            $objeto="cartera".$row2['cve_age'];
            $valor_objeto=$_REQUEST[$objeto];
        $string_insert=  sprintf("insert into cartera_vencida set agente=%s, mes=%s, anio=%s, porce_real=%s",
                        GetSQLValueString($row2['cve_age'], "int"),
                        GetSQLValueString($mes, "int"),
                        GetSQLValueString($anio, "int"),
                        GetSQLValueString($valor_objeto, "double"));
        @mysqli_query($conecta1, $string_insert) or die (mysqli_error($conecta1));
                        
          // echo  $string_insert."<br>";
        }
       
   }
//Consultar si ya esta calculado la cartera de este mes y año
  $string_cartera=sprintf("SELECT * FROM saldos_facturas where mes_corte=%s and anio_corte=%s",
                 GetSQLValueString($mes, "int"),
                  GetSQLValueString($anio, "int"));
$query_cartera=mysqli_query($conecta1, $string_cartera) or die (mysqli_error($conecta1));
 $datos_cartera=  mysqli_num_rows($query_cartera);

?>   

    <div class="container">
          <h4>Cartera Vencida</h4>
          <form name="forma1" method="POST" >
              <h5>Año:<?php echo $anio; ?>
                 Mes: <?php echo nombre_mes($mes); ?>
              </h5>
             
              <input type="submit" class="btn btn-hg btn-info" name="importar" value="Descargar Saldos SAP">
              <input type="hidden" name="mes" value="<?php echo $mes;?>" >
              <input type="hidden" name="anio" value="<?php echo $anio;?>" >
          </form>    
          <?php
          if ($datos_cartera>0){ 
             $mes=$_REQUEST['mes'];
             $anio=$_REQUEST['anio'];
             
             if (isset($_REQUEST['guardar'])){ 
                 echo '<p></p> 
                        <div class="alert alert-success" role="alert">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong>Guardar!</strong> Datos Guardados con Éxito
                     </div>';
             }
             
              ?>
          <div class="table-responsive">
              <form name="form1" method="POST">
              <table  class="table table-responsive table-hover">
                    <thead>
                          <tr>
                              <th>Clave Agente</th>  
                              <th>Agente</th>
                               <th>Empleado</th>
                               <th>%Vencido</th>
                             
                          </tr>
                    
                    </thead>      
                          
                    <tbody>
                        <?php
                        
                        while ($row = mysqli_fetch_array($query_agentes)) {
                             
                            
                            ///Obtener la carte del agente mes y anio Total
                             $string_ct=sprintf("select sum(saldo) as saldo FROM pedidos.saldos_facturas where slpcode=%s and mes_corte=%s and anio_corte=%s",
                                       GetSQLValueString($row['cve_age'], "int"),
                                        GetSQLValueString($mes, "int"),
                                        GetSQLValueString($anio, "int"));
                             $quey_ct=mysqli_query($conecta1, $string_ct) or die (mysqli_error($conecta1));
                             $datos_ct=  mysqli_fetch_assoc($quey_ct);
                             $ct= $datos_ct['saldo'];
                             
                              ///Obtener la carte del agente mes y anio con facturas mayor a 30 días
                             $string_ca=sprintf("select sum(saldo) as saldo FROM pedidos.saldos_facturas where dv>30 and  slpcode=%s and mes_corte=%s and anio_corte=%s",
                                       GetSQLValueString($row['cve_age'], "int"),
                                        GetSQLValueString($mes, "int"),
                                        GetSQLValueString($anio, "int"));
                             $quey_ca=mysqli_query($conecta1, $string_ca) or die (mysqli_error($conecta1));
                             $datos_ca=  mysqli_fetch_assoc($quey_ca);
                             $ca= $datos_ca['saldo'];
                             $vencido=$ca/$ct;
                              

                        ?>
                        <tr>
                            <td><?php echo $row['cve_age']; ?></td>
                            <td><?php echo $row['nom_age']; ?></td>
                             <td><?php echo utf8_encode($row['nom_empleado']); ?></td>
                            <td><?php echo number_format(($vencido*100), 2, '.', ',')."%"; ?></td>
                           <input type="hidden" name="cartera<?php echo $row['cve_age'];?>" value="<?php echo  $vencido;?>" >
                            
                        </tr>
         <?php   } ?>
                        
    
                    </tbody>
                  
                  
                  
              </table>
              <input type="hidden" name="mes" value="<?php echo $mes;?>" >
              <input type="hidden" name="anio" value="<?php echo $anio;?>" >
                 <input type="submit" class="btn btn-hg btn-success" name="guardar" value="Guardar el Calculo" onclick="return confirm('¿Esta seguro?');">
              </form>
              
          </div>
          <?php }?>    
        
    </div><!-- /.container -->

<?php require_once 'foot.php';?>