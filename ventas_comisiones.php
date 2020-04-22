<?php 
require_once 'header_comisiones.php';
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);

require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");

  $mes=$_REQUEST['mes'];
  $anio=$_REQUEST['anio'];
             
$string_agentes="select * from relacion_gerentes order by cve_gte";
$query_agentes=mysqli_query($conecta1, $string_agentes) or die (mysqli_error($conecta1));


//Consultar si se tiene captura la cartera del mes correspondiente
$string_cartera=sprintf("SELECT * FROM cartera_vencida where mes=%s and anio=%s",
                 GetSQLValueString($mes, "int"),
                 GetSQLValueString($anio, "int"));
$query_cartera=mysqli_query($conecta1, $string_cartera) or die (mysqli_error($conecta1));
 $datos_cartera=  mysqli_num_rows($query_cartera);
 
 //Consultar si se tiene captura la asertividad del mes correspondiente
 $string_as=sprintf("SELECT * FROM asertividad where mes=%s and anio=%s",
                 GetSQLValueString($mes, "int"),
                 GetSQLValueString($anio, "int"));
$query_as=mysqli_query($conecta1, $string_as) or die (mysqli_error($conecta1));
 $datos_as=  mysqli_num_rows($query_as);
 
 
 //Consultar si se tiene captura los conceptos generales  del mes correspondiente
$string_gr=sprintf("SELECT * FROM cumple_gral where mes=%s and anio=%s",
                 GetSQLValueString($mes, "int"),
                 GetSQLValueString($anio, "int"));
$query_gr=mysqli_query($conecta1, $string_gr) or die (mysqli_error($conecta1));
$datos_gr=  mysqli_num_rows($query_gr);
 
$pasa=0;
if ($datos_cartera>0){
    $pasa=1;
   
}else{
    $pasa=0;
     $mensaje_error= '<p></p> 
                        <div class="alert alert-warning" role="alert">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong>Falta!</strong> No se ha Calculado los Saldos de Facturas del Mes
                     </div>';
}

if ($datos_as>0){
    $pasa=1;
}else{
    $pasa=0;
    $mensaje_error= '<p></p> 
                        <div class="alert alert-warning" role="alert">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong>Falta!</strong> No se ha Calculado la Asertividad del Mes
                     </div>';
}
if ($datos_gr>0){
    $pasa=1;
}else{
    $pasa=0;
     $mensaje_error= '<p></p> 
                        <div class="alert alert-warning" role="alert">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <strong>Falta!</strong> No se ha Capturado los datos Generales del Mes
                     </div>';
}

require_once('funciones_comisiones.php');


  if (isset($_REQUEST['guardar'])){ 
       $mes=$_REQUEST['mes'];
       $anio=$_REQUEST['anio'];
         //Eliminar los datos actuales
            $string_eliminar=sprintf("delete from resumen_comisiones where mes=%s and anio=%s",
                            GetSQLValueString($mes, "int"),
                            GetSQLValueString($anio, "int"));
            @mysqli_query($conecta1, $string_eliminar) or die (mysqli_error($conecta1));
       
       $string_agentes2="select * from relacion_gerentes order by nom_empleado";
       $query_agentes2=mysqli_query($conecta1, $string_agentes2) or die (mysqli_error($conecta1));
        while ($row2 = mysqli_fetch_array($query_agentes2)) {
            $objeto="comlogro".$row2['cve_age'];
            $valor_objeto=($_REQUEST[$objeto]);
        $string_insert=  sprintf("insert into resumen_comisiones set agente=%s, mes=%s, anio=%s, comision=%s",
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
          <h4>Calculo de Comisiones Año:<?php echo $anio; ?>
                 Mes: <?php echo nombre_mes($mes); ?></h4>
       
          <br>
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
             
             if ($datos_cartera>0 and $datos_as>0 and $datos_gr>0 ){
                  $trimestre=  estrimestre($mes);
                    $mes_1=$mes-2;
                    $mes_2=$mes-1;
                    $mes_3=$mes;
            
             
              ?>
          <div class="table-responsive">
           <form name="form1" method="POST">   
              <table  class="table table-bordered">
                   <!-- <thead> -->
                 
                  <thead>
      
                          <tr  class='success'>
                              <th>Zona</th>
                              <th>Clave </th>  
                              <th>Agente</th>
                              <th>Empleado</th>
                              <th>Venta_<?php 
                                 $mes=$_REQUEST['mes'];
                                echo nombre_mes($mes); ?></th>
                            
                              
                              <th>Objetivo_<?php 
                                 $mes=$_REQUEST['mes'];
                                echo nombre_mes($mes); ?></th>
                              <th>Com Base</th>
                              <th>%Cartera </th>
                               <th>%Certeza </th>
                               <th>Generales</th>
                               <th>Comision Logro</th>
                               <th>Excelencia</th>
                               <th>Total Pagar</th>
                               
                             <?php    if ($trimestre==1){  
                                echo '<th>Meta Excelencia</th>';
                                echo '<th>Vta  Trim</th>';
                                echo '<th>Comision '.nombre_mes($mes_1).'</th>'; 
                                echo '<th>Comision '.nombre_mes($mes_2).'</th>'; 
                             } ?>
                          </tr>
                    
                    </thead>      
                          
                    <tbody>
                        <?php
                           $contador=0;
                        while ($row = mysqli_fetch_array($query_agentes)) {
                            $comision_logro=0;
                              $agente=$row['cve_age'];
                               $meta_mes= meta($agente, $mes, $anio);
                               $venta_mes= venta($agente, $mes, $anio);
                            
                               ///condicion para saber si obtuvo la meta del Mes
                             
                                   ///Si tiene Comisión
                                   
                                   ///aqui se incluye el codigo para actualizar los campos de comision de la tabla de ventas dependiendo de datos del SAP
                            
                                    $string_detvta=sprintf("select * from ventas where  agente2=%s and year(falta_fac2)=%s and month(falta_fac2)=%s",
                                                       GetSQLValueString($row['cve_age'], "int"),
                                                       GetSQLValueString($anio, "int"), 
                                                       GetSQLValueString($mes, "int"));
                                    $query_detvta=mysqli_query($conecta1, $string_detvta) or die (mysqli_error($conecta1));

                           
                           
                                      ////los registros obtendios hay que actualizar los campos de porce_comi y comision
                                    while ($rowd = mysqli_fetch_array($query_detvta)) {
                                        $id_venta=$rowd['id'];

                                         //Encontrar el artículo en SAP  y obtener la clave de porcentaje de comision
                                        //Armar instrucción para ir a buscar a msql la información
                                        $campo=  campo_comi($row['cve_gte']);
                                        $articulo=$rowd['codigo2'];
                                        $fin = sprintf(" FROM OITM Where ItemCode = %s ",
                                                     GetSQLValueString($articulo,"text"));                           
                                       $string="SELECT ".$campo.$fin;
                                       $query_mssql=mssql_query($string);
                                       $datos_mmsql=  mssql_fetch_assoc($query_mssql); 
                                       $clave_tabulador=$datos_mmsql[$campo];

                                       //ir a buscar a la tabla tabulador_procentajes para conocer cual es el porcentaje que le corresponde
                                       $string_tabulador=sprintf("select * from tabulador_porcentajes where valor=%s",
                                                         GetSQLValueString($clave_tabulador, "int"));
                                       $query_tabulador=mysqli_query($conecta1, $string_tabulador) or die (mysqli_error($conecta1));
                                       $dato_tabulador=  mysqli_fetch_assoc($query_tabulador);
                                       $comision_correspondiente=$dato_tabulador['porce_dato'];

                                       //Actualizar el registro de la tabla de ventas el campo porce_comi
                                       $string_update=sprintf("update ventas set porce_comi=%s, comision=(%s*tot_linea) where id=%s",
                                                      GetSQLValueString($comision_correspondiente,"double"),
                                                      GetSQLValueString($comision_correspondiente,"double"),
                                                      GetSQLValueString($id_venta, "int"));
                                       @mysqli_query($conecta1, $string_update) or die (mysqli_error($conecta1));

                                    }
                                    //Obtener la suma de las comision base
                                    
                                      $string_comibase=sprintf("select sum(comision) as comision from ventas where  agente2=%s and year(falta_fac2)=%s and month(falta_fac2)=%s",
                                                       GetSQLValueString($row['cve_age'], "int"),
                                                       GetSQLValueString($anio, "int"), 
                                                       GetSQLValueString($mes, "int"));
                                      $query_comibase=mysqli_query($conecta1, $string_comibase) or die (mysqli_error($conecta1));
                                      $dato_comibase=  mysqli_fetch_assoc($query_comibase);
                                      $comision_base=$dato_comibase['comision'];
                    
                                   //$comision_base=0;
                                if ($venta_mes>=$meta_mes){
                                   
                                   
                                   $sentinela_comision=1;    
                                  }else{  
                                     $sentinela_comision=0;
                               }
                            
                               
                               //Revisar el % de cartera Vencida                               
                                $string_cartera=  sprintf("select * from  cartera_vencida where  agente=%s and mes=%s and anio=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"),
                                            GetSQLValueString($anio, "int"));
                                           
                               $query_cartera=mysqli_query($conecta1, $string_cartera) or die (mysqli_error($conecta1));
                               $dato_cartera=  mysqli_fetch_assoc($query_cartera);
                               $cartera=$dato_cartera['porce_real'];
                               
                                 //Revisar el % de asertividad                              
                                $string_asertivo=  sprintf("select * from  asertividad where  agente=%s and  mes=%s and  anio=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"),
                                            GetSQLValueString($anio, "int"));
                                           
                               $query_asertivo=mysqli_query($conecta1, $string_asertivo) or die (mysqli_error($conecta1));
                               $dato_asertivo=  mysqli_fetch_assoc($query_asertivo);
                               $asertivo=$dato_asertivo['porce_real'];
                               
                               
                                //Cumple Onjetivos Generales                           
                                $string_gral=  sprintf("select * from  cumple_gral where  agente=%s and mes=%s and anio=%s",
                                            GetSQLValueString($agente, "int"),
                                            GetSQLValueString($mes, "int"),
                                            GetSQLValueString($anio, "int"));
                                           
                               $query_gral=mysqli_query($conecta1, $string_gral) or die (mysqli_error($conecta1));
                               $dato_gral=  mysqli_fetch_assoc($query_gral);
                               $gral=$dato_gral['cumplio'];
                               
                               
                               ///Empieza el calculo de las comisiones
                                      
                          if ($sentinela_comision>0){    //Validad si logro el objetivo de ventas por mes
                               
                              $calculo1=$comision_base*(1-$cartera);
                              
                              if ($asertivo>.7){
                                  $calculo2=$comision_base*.1;   ///Dato especificado por Ventas
                              }else{
                                  $calculo2=0;
                              }
                              
                               if ($gral>0){
                                  $calculo3=$comision_base*.1;   ///Datos especificado por Ventas
                              }else{
                                  $calculo3=0;
                              }
                              $comision_logro=$calculo1+$calculo2+$calculo3;
                              
                          }
                              
                          ///Condicion que nos indica si se va a calcular el trimestre
                           if ($trimestre==1){   
                                //Sumar la meta por excelencia del trimestre
                                     $excelencia_1=  meta_excelencia($agente, $mes_1, $anio);
                                     $excelencia_2=  meta_excelencia($agente, $mes_2, $anio);
                                     $excelencia_3=  meta_excelencia($agente, $mes_3, $anio);
                                     
                                     $meta_excelencia=$excelencia_1+$excelencia_2+$excelencia_3;
                                     
                                     
                                     ///Sumar las ventas del trimestre
                                     $venta_1=  venta($agente, $mes_1, $anio);
                                     $venta_2=  venta($agente, $mes_2, $anio);
                                     $venta_3=  venta($agente, $mes_3, $anio);
                                     
                                     $venta_trim=$venta_1+$venta_2+$venta_3;
                                     
                                     if ($venta_trim>=$meta_excelencia){
                                         //Calcular comision por excelencia
                                         
                                         $comi_m1=  comision_mes($agente, $mes_1, $anio);
                                         $comi_m2=  comision_mes($agente, $mes_2, $anio);
                                         $comi_m3=$comision_logro;
                                         $comi_excelencia=($comi_m1+$comi_m2+$comi_m3)*.5;   //Calculo manual establecido por ventas
                                         
                                     }else{
                                         $comi_m1=  comision_mes($agente, $mes_1, $anio);  //agregado EAGA
                                         $comi_m2=  comision_mes($agente, $mes_2, $anio);   //agregado EAGA
                                         $comi_m3=$comision_logro;                          //agregado EAGA    
                                         $comi_excelencia=0;
                                     }
                                     
                               
                           }else{
                                ///Sin calculo Trimestral
                                 $comi_excelencia=0;
                                 /////agregado EAGA
                                    $comi_m1=0;
                                    $comi_m2=0;
                                    $comi_m3=0;
                                 
                               
                           }
                          $total_pagar=$comision_logro+$comi_excelencia;
                               
                               //
                               
          
                        $contador=$contador+1;
                          if($contador>1 and $centi_zona<>$row['cve_gte']){
                            //  echo "<tr><td>Clave Agente</td><td>Agente</td><td>Empleado</td><td>Cumplio</td></tr>";
                              echo "<tr class='success'><th>Zona</th><th>Clave</th><th>Agente</th><th>Empleado</th><th>Venta_".nombre_mes($mes)."</th><th>Objetivo_".nombre_mes($mes)."</th><th>Com Base</th><th>%Cartera </th> <th>%Certeza </th> <th>Generales</th> <th>Comision Logro</th><th>Excelencia</th><th>Total Pagar</th></tr>";
                             // $contador=0;
                          } 
                          $centi_zona=$row['cve_gte'];
                        ?>
                        <tr>
                            <td><?php echo $row['nom_gte']; ?></td>    
                            <td><?php echo $row['cve_age']; ?></td>
                           <td><?php echo $row['nom_age']; ?></td>
                           <td><?php echo utf8_encode($row['nom_empleado']) ; ?></td>                                                        
                           <td><?php  echo number_format($venta_mes, 2, '.', ','); ?> </td>
                           <td><?php  echo number_format($meta_mes, 2, '.', ','); ?> </td>
                           <td><a href="pop-detalle_comi1.php?agente=<?php echo $row['cve_age'];  ?>&mes=<?php echo $mes;?>&anio=<?php echo $anio;?>&cve_gte=<?php echo $row['cve_gte'];?>&anio=<?php echo $anio;?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php  echo number_format($comision_base, 2, '.', ','); ?></a> </td>
                           <td><a href="pop-detalle-cartera1.php?agente=<?php echo $row['cve_age'];  ?>&mes=<?php echo $mes;?>&anio=<?php echo $anio;?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php  echo ($cartera*100)."%"; ?></a></td>
                           <td><a href="pop-detalle-certeza.php?agente=<?php echo $row['cve_age'];  ?>&mes=<?php echo $mes;?>&anio=<?php echo $anio;?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php  echo ($asertivo*100)."%"; ?></a></td>
                           <td><?php  echo leyenda($gral); ?></td>
                           <td><?php  echo number_format($comision_logro, 2, '.', ',');   ?> 
                           
                               <input type="hidden" name="comlogro<?php echo $row['cve_age'];?>" value="<?php echo  $comision_logro;?>" >
                           </td>
                           
                         
                           <td><?php  echo number_format($comi_excelencia, 2, '.', ','); ?> </td>
                           <td><?php  echo number_format($total_pagar, 2, '.', ','); ?> </td>
                           <?php  
                               if ($trimestre==1){   
                                   
                                    
                                   ?>
                              
                              <td><?php  echo number_format($meta_excelencia, 2, '.', ','); ?> </td>                             
                              <td><?php  echo number_format($venta_trim, 2, '.', ','); ?> </td>
                               <td><?php  echo number_format($comi_m1, 2, '.', ','); ?> </td>
                               <td><?php  echo number_format($comi_m2, 2, '.', ','); ?> </td>
                                   
                                   
                            <?php   }?>
                           
                           
                           
                           
                           
                           
     
     
                        </tr>
         <?php   } ?>
                        
    
                    </tbody>
                  
                  
                  
              </table>
                 <input type="hidden" name="mes" value="<?php echo $mes;?>" >
              <input type="hidden" name="anio" value="<?php echo $anio;?>" >
                 <input type="submit" class="btn btn-hg btn-success" name="guardar" value="Guardar Comisiones" onclick="return confirm('¿Esta seguro?');">
           </form>     
              
          </div>
         <?php   } else{
             
             echo $mensaje_error;
         }
         
         ?>
        
    </div><!-- /.container -->

<?php require_once 'foot.php';?>