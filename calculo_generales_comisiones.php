<?php 
require_once 'header_comisiones.php';
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);

require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");


$string_agentes="select * from relacion_gerentes order by nom_empleado";
$query_agentes=mysqli_query($conecta1, $string_agentes) or die (mysqli_error($conecta1));

$mes=$_REQUEST['mes'];
$anio=$_REQUEST['anio'];



require_once('funciones_comisiones.php');

  

   if (isset($_REQUEST['guardar'])){ 
       $mes=$_REQUEST['mes'];
       $anio=$_REQUEST['anio'];
         //Eliminar los datos actuales
            $string_eliminar=sprintf("delete from cumple_gral where mes=%s and anio=%s",
                            GetSQLValueString($mes, "int"),
                            GetSQLValueString($anio, "int"));
            @mysqli_query($conecta1, $string_eliminar) or die (mysqli_error($conecta1));
       
       $string_agentes2="select * from relacion_gerentes order by nom_empleado";
       $query_agentes2=mysqli_query($conecta1, $string_agentes2) or die (mysqli_error($conecta1));
        while ($row2 = mysqli_fetch_array($query_agentes2)) {
            $objeto="asertivo".$row2['cve_age'];
            $valor_objeto=($_REQUEST[$objeto]);
        $string_insert=  sprintf("insert into cumple_gral set agente=%s, mes=%s, anio=%s, cumplio=%s",
                        GetSQLValueString($row2['cve_age'], "int"),
                        GetSQLValueString($mes, "int"),
                        GetSQLValueString($anio, "int"),
                        GetSQLValueString($valor_objeto, "int"));
        @mysqli_query($conecta1, $string_insert) or die (mysqli_error($conecta1));
                        
        //  echo  $string_insert."<br>";
        }
       
   }


?>   



    <div class="container">
          <h4>Cumplimiento a objetivos Generales Año:<?php echo $anio; ?>
                 Mes: <?php echo nombre_mes($mes); ?></h4>
          
         
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
         
              <form name="form1" method="POST">
              <table class="table table-bordered">
                   
                       <thead>
                          <tr  class='success'>
                              <th>Clave Agente</th>  
                              <th>Agente</th>
                              <th>Empleado</th>
                              <th>Cumplio</th>
                             
                          </tr>
                    
                    </thead>      
                  
                  
                          
                    <tbody>
                        <?php
                        $contador=0;
                        while ($row = mysqli_fetch_array($query_agentes)) {
                             
                            
                            ///Obtener la carte del agente mes y anio Total
                             $string_as=sprintf("select * FROM pedidos.cumple_gral where agente=%s and mes=%s and anio=%s",
                                       GetSQLValueString($row['cve_age'], "int"),
                                        GetSQLValueString($mes, "int"),
                                        GetSQLValueString($anio, "int"));
                             $quey_as=mysqli_query($conecta1, $string_as) or die (mysqli_error($conecta1));
                             $datos_as=  mysqli_fetch_assoc($quey_as);
                             $as= $datos_as['cumplio'];
                             
                            $contador=$contador+1;
                          if($contador==15){
                            //  echo "<tr><td>Clave Agente</td><td>Agente</td><td>Empleado</td><td>Cumplio</td></tr>";
                              echo "<tr class='success'><th>Clave Agente</th><th>Agente</th><th>Empleado</th><th>Cumplio</th></tr>";
                              $contador=0;
                          }      
                        ?>
                        <tr>
                            
                            <td><?php echo $row['cve_age']; ?></td>
                            <td><?php echo $row['nom_age']; ?></td>
                             <td><?php echo utf8_encode($row['nom_empleado']); ?></td>
                           
                            
                             <td>  <input type="checkbox"   name="asertivo<?php echo $row['cve_age'];?>" data-toggle="checkbox">  </td>
                           
                        </tr>
                 <?php   
                       
         
                     } ?>
                        
    
                    </tbody>
                  
                  
                  
              </table>
              <input type="hidden" name="mes" value="<?php echo $mes;?>" >
              <input type="hidden" name="anio" value="<?php echo $anio;?>" >
                 <input type="submit" class="btn btn-hg btn-success" name="guardar" value="Guardar el Calculo" onclick="return confirm('¿Esta seguro?');">
              </form>
              
       
        
        
    </div><!-- /.container -->

<?php require_once 'foot.php';?>