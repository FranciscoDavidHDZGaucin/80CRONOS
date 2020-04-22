<?php 
require_once 'header_comisiones.php';
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);


$string_agentes="select * from relacion_gerentes order by nom_empleado";
$query_agentes=mysqli_query($conecta1, $string_agentes) or die (mysqli_error($conecta1));


//Mostrar que años tiene capturado los objetivos de las comisiones
$string_anios="SELECT distinct(anio) as anio FROM pedidos.objetivo_agentes order by anio";
$query_anios=mysqli_query($conecta1, $string_anios) or die (mysqli_error($conecta1));

require_once('funciones_comisiones.php');


?>   

    <div class="container">
          <h4>Listado de Agentes y Objetivos Mensuales</h4>
          <form name="forma1" method="POST" >
            <p>AÑO<select name="anio"  onchange="this.form.submit()" >  
                    <option value="">Seleccione el año de Consulta</option>
                    <?php
                       while ($rowa = mysqli_fetch_array($query_anios)) {
                           if ($_REQUEST['anio']==$rowa['anio']){
                                echo '<option selected value="'.$rowa['anio'].'">'.$rowa['anio'].'</opion>';
                           }else{
                               echo '<option  value="'.$rowa['anio'].'">'.$rowa['anio'].'</opion>';
                           }
                           
                          
                           
                       }
                    
                    ?>
                          
              </select></p>
          </form>    
          <?php
          if (isset($_REQUEST['anio'])){ 
            
              ?>
          <div class="table-responsive">
              <table  class="table table-responsive table-hover">
                    <thead>
                          <tr>
                              <th>Clave Agente</th>  
                              <th>Agente</th>
                              <th>Empleado</th>
                              <th>Enero</th>
                              <th>Febrero</th>
                              <th>Marzo</th>
                              <th>Abril</th>
                              <th>Mayo</th>
                              <th>Junio</th>
                              <th>Julio</th>
                              <th>Agosto</th>
                              <th>Septiembre</th>
                              <th>Octubre</th>
                              <th>Noviembre</th>
                              <th>Diciembre</th>
                              <th>Total</th>
                          </tr>
                    </thead>      
                          
                    <tbody>
                        <?php
                        $suma1=0;
                        while ($row = mysqli_fetch_array($query_agentes)) {
                         
                      
                        ?>
                        <tr>
                            <td><?php echo $row['cve_age']; ?></td>
                           <td><?php echo $row['nom_age']; ?></td>
                           <td><?php echo utf8_encode($row['nom_empleado']) ; ?></td>
                         <?php 
                            $suma_agente=0;    
                          for ($i = 1; $i <= 12; $i++) {
                                $mes=$i;
                                $anio=$_REQUEST['anio'];
                                $agente=$row['cve_age'];
                                $dato= meta($agente, $mes, $anio);
                                $suma_agente=$suma_agente+$dato;
                                echo '<td>'.number_format($dato, 2, '.', ',').'</td>';
                          }
                            echo '<td>'.number_format($suma_agente, 2, '.', ',').'</td>';
                           $suma1=$suma1+$suma_agente;  
                           ?>
                        </tr>
                        <?php    } ?>
 
                    </tbody>
                  
                  
                  
              </table>
              
              
          </div>
          <?php }?>    
        
    </div><!-- /.container -->

<?php require_once 'foot.php';?>