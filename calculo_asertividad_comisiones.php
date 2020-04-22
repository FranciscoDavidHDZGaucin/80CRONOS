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

  

   if (isset($_REQUEST['guardar'])){ 
       $mes=$_REQUEST['mes'];
       $anio=$_REQUEST['anio'];
         //Eliminar los datos actuales
            $string_eliminar=sprintf("delete from asertividad where mes=%s and anio=%s",
                            GetSQLValueString($mes, "int"),
                            GetSQLValueString($anio, "int"));
            @mysqli_query($conecta1, $string_eliminar) or die (mysqli_error($conecta1));
       
       $string_agentes2="select * from relacion_gerentes order by nom_empleado";
       $query_agentes2=mysqli_query($conecta1, $string_agentes2) or die (mysqli_error($conecta1));
        while ($row2 = mysqli_fetch_array($query_agentes2)) {
            $objeto="asertivo".$row2['cve_age'];
            $valor_objeto=($_REQUEST[$objeto]/100);
        $string_insert=  sprintf("insert into asertividad set agente=%s, mes=%s, anio=%s, porce_real=%s",
                        GetSQLValueString($row2['cve_age'], "int"),
                        GetSQLValueString($mes, "int"),
                        GetSQLValueString($anio, "int"),
                        GetSQLValueString($valor_objeto, "double"));
        @mysqli_query($conecta1, $string_insert) or die (mysqli_error($conecta1));
                        
          // echo  $string_insert."<br>";
        }
       
   }


?>   

    <div class="container">
          <h4>Asertividad</h4>
           <h5>Año:<?php echo $anio; ?>
                 Mes: <?php echo nombre_mes($mes); ?>
              </h5>
         
          <?php
         
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
                          <tr  class='success'>
                              <th>Clave Agente</th>  
                              <th>Agente</th>
                               <th>Empleado</th>
                               <th>%Asertividad</th>
                             
                          </tr>
                    
                    </thead>      
                          
                    <tbody>
                        <?php
                        
                        while ($row = mysqli_fetch_array($query_agentes)) {
                            
                             $suma_comi=0;
                             $accuracy_suma=0;
                             $agente=$row['cve_age'];
                             
                             
                             
                             $monto_proyectado=suma_proyeccion_agente($agente, $mes, $anio);
                             //Consultar si se tiene captura la cartera del mes correspondiente
                                $string_pron=sprintf("SELECT cve_age,mes, anio, cve_prod, sum(cantidad) as cantidad, sum(monto_costo) monto_costo  from vista_pronostico where cve_age=%s and mes=%s and anio=%s group by cve_prod",
                                                       GetSQLValueString($agente, "int"),
                                                       GetSQLValueString($mes, "int"),
                                                       GetSQLValueString($anio, "int"));
                               $query_pron=mysqli_query($conecta1, $string_pron) or die (mysqli_error($conecta1));
                               
                              while ($rowp = mysqli_fetch_array($query_pron)) { 
                          
                                    //Consultar si se tiene captura la cartera del mes correspondiente

                                      $venta=ventaxprod($agente, $mes, $anio, $rowp['cve_prod']); 
                                      $diferencia=abs($rowp['cantidad']-$venta);
                                      $certeza=1-($diferencia/$rowp['cantidad']);
                                      if ($certeza<0){
                                          $certeza=0;
                                      }

                                      $comp_costo=$rowp['monto_costo']/$monto_proyectado;
                                      $accuracy=$certeza*$comp_costo;
                                      $accuracy_suma=$accuracy_suma+$accuracy; 
                              }     
                            
                              $as=$accuracy_suma;
                              $porce_acertivo=$as*100;
                            
                            
                            
                            ///Obtener la carte del agente mes y anio Total
                              /*
                             $string_as=sprintf("select * FROM pedidos.asertividad where agente=%s and mes=%s and anio=%s",
                                       GetSQLValueString($row['cve_age'], "int"),
                                        GetSQLValueString($mes, "int"),
                                        GetSQLValueString($anio, "int"));
                             $quey_as=mysqli_query($conecta1, $string_as) or die (mysqli_error($conecta1));
                             $datos_as=  mysqli_fetch_assoc($quey_as);
                               
                               */
                            // $as= $datos_as['porce_real'];
                           
                              $contador=$contador+1;
                          if($contador==15){
                            //  echo "<tr><td>Clave Agente</td><td>Agente</td><td>Empleado</td><td>Cumplio</td></tr>";
                              echo "<tr class='success'><th>Clave Agente</th><th>Agente</th><th>Empleado</th><th>%Asertividad</th></tr>";
                              $contador=0;
                          } 

                        ?>
                        <tr>
                            <td> <a href="pop-detalle-certeza.php?agente=<?php echo $row['cve_age'];  ?>&mes=<?php echo $mes;?>&anio=<?php echo $anio;?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $row['cve_age']; ?></a></td>
                            <td><?php echo $row['nom_age']; ?></td>
                             <td><?php echo utf8_encode($row['nom_empleado']); ?></td>
                           
                             <td> <input type="text" name="asertivo<?php echo $row['cve_age'];?>" value="<?php echo number_format($porce_acertivo,2);?>" ></td>
                    
                        </tr>
         <?php   } ?>
                        
    
                    </tbody>
                  
                  
                  
              </table>
              <input type="hidden" name="mes" value="<?php echo $mes;?>" >
              <input type="hidden" name="anio" value="<?php echo $anio;?>" >
                 <input type="submit" class="btn btn-hg btn-success" name="guardar" value="Guardar el Calculo" onclick="return confirm('¿Esta seguro?');">
              </form>
              
          </div>
        
        
    </div><!-- /.container -->

<?php require_once 'foot.php';?>