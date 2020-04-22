<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : expedientes-agentes.php 
 	Fecha  Creacion : 19/09/2016
	Descripcion  : 
         Copia  del  archivo   expedientes-agente.php  del  proyecto  Pedidos
	Modificado  Fecha  : 
 *        *****  19/09/2016  Inicio de  copiado  del  Codigo   del   proyecto  Pedidos  
 *                           para  este  archivo  se  copio  las  imagenes   del    proyecto   pedidos 
 *                           ademas  de que se  agrego   un   pop    con el  nombre  de  popupentrega.php 
 *                           el  cual  es  nescesario   para el  archivo.
*/
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 
require_once('header.php');
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
mssql_select_db("AGROVERSA"); 
///****FIN    Librerias  Utilizadas  en Cronos 
///Se agregan los siguientes archivos de    correos.php    y   buscar_email.php 
require('correos.php');   //funcion para mandar correos
require('buscar_email.php');   //funcion para obtener el email de un usuario en especifico
//*****Inicio  Codigo  Copia

//Conexion PDO para que funcione la funcion avisovta
function dbConnect (){
    $conn = null;
    $host = 'localhost';
    $db =   'pedidos';
    $user = 'root';
    $pwd =  'avsa0543';
    try {
        $conn = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pwd);
        //echo 'Connected succesfully.<br>';
    }
    catch (PDOException $e) {
        echo '<p>Cannot connect to database !!</p>';
        exit;
    }
    return $conn;
 }



function documento($prospecto,$documento){
   
    $busqueda=sprintf("SELECT count(id_e) as contador FROM entrega WHERE id_p=%s and id_d=%s",
             GetSQLValueString($prospecto, "int"),
                GetSQLValueString($documento, "int"));
     $conn = dbConnect();
      
    // Extract the values from $result
  
  $stmt = $conn->prepare($busqueda);
  $stmt->execute();
  $datos = $stmt->fetch();
  $registros= $datos[0];
 
  return $registros;
        
    
    
    
}

if ( $_SESSION["zona1"]==1){      //la variable de sesion zona1 si el valor es 1 esto nos indica que el logeado es un gerente
    //Usuario logeado es Gerente
    $string_1=sprintf("SELECT * FROM vista_prospecto where cve_gte =%s order by nombre",
          GetSQLValueString($_SESSION['usuario_rol'], "int"));
}else{
    $string_1=sprintf("SELECT * FROM vista_prospecto where agente=%s order by nombre",
          GetSQLValueString($_SESSION['usuario_agente'], "int"));
}


$sql_1=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));


//Obtener los agentes Integradora
$sql_result=("SELECT SlpName, SlpCode, U_email FROM OSLP WHERE CAST(U_email as varchar)<>'' order by SlpName ");

$result3=mssql_query($sql_result);
  
///*****Fin  Codigo  Copia

?> 
<div class="container">
    <?php
           if (isset($_REQUEST['prospecto']))
            { 
                  //Seccion en donde ya eligio algun jefe 
                   $cve_pros=$_REQUEST['prospecto'];
                   $sql_datos=sprintf("select * from prospecto where id_p=%s",GetSQLValueString($cve_pros,"int"));
                   $query=mysqli_query($conecta1,$sql_datos) or die (mysqli_error($conecta1)); 
                   $row_consulta_sql_datos=  mysqli_fetch_assoc($query);
                   $nfilas = mysqli_num_rows ($query);

   
           ?>
           <form name=form2 action="expedientes-agente.php" method="POST" >
        
                <p> <label for="nombre">Nombre o Razón Social</label> 
                <input  type="text" placeholder="Nombre Razón Social" name="nombre" id="nombre" value="<?php echo $row_consulta_sql_datos['nombre'];?>" size="70"  />
                <label for="t_persona">Persona</label>
                <select name="t_persona" id="dir" disabled>
                    <option value="F" <?php if ($row_consulta_sql_datos['t_persona']=='F'){
                                           echo "selected";
                                       }
                        ?>>Fisica</option>
                    <option value="M"  <?php if ($row_consulta_sql_datos['t_persona']=='M'){
                                           echo "selected";
                                       }
                        ?>>Moral</option>

                </select>
                </p>
                 <label for="rfc">RFC</label> 
                <input  type="text" name="rfc" id="rfc" value="<?php echo $row_consulta_sql_datos['rfc'];?>" size="15"  />
                
                <label for="giro">Giro</label> 
                <input  type="text" name="giro" id="giro" value="<?php echo $row_consulta_sql_datos['giro'];?>" size="30"  />
                
                
                 <label for="domicilio">Domicilio</label> 
                <input  type="text" name="domicilio" id="domicilio" value="<?php echo $row_consulta_sql_datos['domicilio'];?>" size="50"  />
                 <br>

                 <label for="colonia">Colonia</label> 
                <input  type="text" name="colonia" id="colonia" value="<?php echo $row_consulta_sql_datos['colonia'];?>" size="50"  />
                 <label for="poblacion">Poblacion</label> 
                <input  type="text" name="poblacion" id="poblacion" value="<?php echo $row_consulta_sql_datos['poblacion'];?>" size="30"  />
                <br>
                 <label for="estado">Estado</label> 
                <input  type="text" name="estado" id="estado" value="<?php echo $row_consulta_sql_datos['estado'];?>" size="20"  />
                
                 <label for="cp">C.P.</label> 
                <input  type="text" name="cp" id="cp" value="<?php echo $row_consulta_sql_datos['cp'];?>" size="5"  />
                <label for="telcel">Celular</label> 
                <input  type="text" placeholder="Teléfono Celular" name="telcel" id="telcel" value="<?php echo $row_consulta_sql_datos['telcel'];?>" size="10"  />
                
                 <label for="telefono">Teléfono</label> 
                <input  type="text" name="telefono" id="telefono" value="<?php echo $row_consulta_sql_datos['telefono'];?>" size="10"  />
                
                
                <p> <label for="contacto">Contacto</label> 
                <input  type="text" name="contacto" id="contacto" value="<?php echo $row_consulta_sql_datos['contacto'];?>" size="25"  />
                 <label for="plazo">Codigo SAP</label> 
                <input  type="text" placeholder="Clave SAP" name="codigo_sap"  id="codigo_sap" value="<?php echo $row_consulta_sql_datos['clave_sap'];?>" size="10"  /></p>
                
                 <label for="email">E mail</label> 
                <input  type="email"  name="email" id="email" value="<?php echo $row_consulta_sql_datos['email'];?>" size="25"  />
                
                 <label for="monto">Monto Crédto</label> 
                <input  type="text" name="monto" required id="monto" value="<?php echo $row_consulta_sql_datos['monto_credito'];?>" size="10"  />
                 <label for="plazo">Plazo</label> 
                <input  type="text" placeholder="Días"name="plazo" required id="plazo" value="<?php echo $row_consulta_sql_datos['plazo'];?>" size="10"  />
                
                <select name="agente" id="agente" required disabled >
		<option value="" >Elige Agente</option>
		<?php
		  
		  while ($row2=mssql_fetch_array($result3))
                       {
                        if ($row2['SlpCode']==$row_consulta_sql_datos['agente']){
                            echo '<option selected value="'.$row2['SlpCode'].'">'.$row2['SlpName'].'</option>';	
                        }else{
                            echo '<option value="'.$row2['SlpCode'].'">'.$row2['SlpName'].'</option>';	
                        }	
                  }
				
				
		?>
		</select> 
                
                
                 <input type="hidden" name="id" value=" <?php echo $cve_pros;?>"  />
                
                
                
                <?php if ($nfilas>0)  {
                        
                      $persona="PERSONA MORAL";
                      $p="M"; 
                      if ($row_consulta_sql_datos['t_persona']=='F'){
                        $persona="PERSONA FISICA";
                         $p="F"; 
                            
                        }
                        /* 
                        $string_2=sprintf("SELECT * FROM documento where tp=%s",
                                  GetSQLValueString($p, "text"));
                        $msql2= mysqli_query($conecta1, $string_2) or die (mysqli_error($conecta1));  
                        */
                        $string_2=sprintf("SELECT * FROM documento where  tp = %s  && ( (id_d <> 1 && id_d <> 6 && id_d <> 2 && id_d <> 8 && id_d <> 10)  &&  (id_d <> 20 && id_d <> 21 && id_d <> 25 && id_d <> 27 && id_d <> 29) )",
                                  GetSQLValueString($p, "text"),GetSQLValueString($p, "text"));
                        $msql2= mysqli_query($conecta1, $string_2) or die (mysqli_error($conecta1));  
                        ///*****Generamos  Cadena   y Qery  para    PRECALIFICACION  &&  
                        $string_precalificacion  = sprintf("SELECT *FROM  pedidos.documento  where  'F'= %s   && (id_d =  1 || id_d = 2  || id_d =  6  || id_d = 8  || id_d = 10)|| ('M'=%s   && (id_d =20 || id_d =21   || id_d  =25  || id_d  =27 || id_d  = 29))",
                                                    GetSQLValueString($p, "text"), GetSQLValueString($p, "text"));
                        $qery_precalificacion = mysqli_query($conecta1, $string_precalificacion);
                    
                    ?>
                <br>
                 <br>
                <!---Inicio  Tabla PRECALIFICACION   ---> 
                <table border="1" > 
                    <thead>
                        <tr> 
                    <th  colspan="2">PRECALIFICACION</th>
                    <th> </th> 
                    <th >PREAUTORIZADO </th>
                    <th >NO AUTORIZADO </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                           while ($row =  mysqli_fetch_array($qery_precalificacion)) {
                               
                               echo  '<tr>';
                                        ///**Col N#1  
                                        echo   '<td>';
                                          if (documento($cve_pros, $row['id_d'])==1){
                                             echo "<img src='images/apply-icon.png' />";
                                             }else{
                                                echo "<img src='images/cancel-icon.png' />";
                                            }
                                         echo   '</td>';
                                        ///** Fin Col N#1
                                        ///**Col N#2
                                           echo '<td>'; 
                                                echo utf8_encode($row['nombre']); 
                                           echo '</td>';
                                        ///** Fin Col N#2
                                        ///***Col  N#3 
                                        /*
                                           echo '<td> ';  
                                                 echo  $row['id_d']; 
                                          echo '</td>'; */
                                        ///**Fin Col  N#3 
                                        ///***Col  N#4
                                        echo '<td> ';  
                                              echo  '<div class="impresion-oculta">'; 
                                                   /* $string_buscar=sprintf("Select * from entrega Where id_p=%s and id_d=%s and not isnull(archivo)",
                                                                   GetSQLValueString($cve_pros, "int"),
                                                                    GetSQLValueString($row['id_d'], "int"));
                                                    $sql_buscar=mysqli_query($conecta1, $string_buscar) or die (mysqli_error($conecta1));
                                                    $cuantos= mysqli_num_rows($sql_buscar);
                                                    if ($cuantos>0){
                                                        $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                                        echo '<a href='.$datos_buscar['archivo'].' target="_blank"><img src="images/view-icon.png" /></a>';
                                                    }    */
                                                     $string_buscar=sprintf("Select * from entrega Where id_p=%s and id_d=%s and not isnull(archivo)",
                                                           GetSQLValueString($cve_pros, "int"),
                                                            GetSQLValueString($row['id_d'], "int"));
                                                    $sql_buscar=mysqli_query($conecta1, $string_buscar) or die (mysqli_error($conecta1));
                                                    $cuantos= mysqli_num_rows($sql_buscar);
                                                    if ($cuantos>0){
                                                        $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                                        $rutaarchivo="../pedidos/".$datos_buscar['archivo'];
                                                        if($row3['visible']==0){
                                                            echo '<a href='.$rutaarchivo.' target="_blank"><img src="images/view-icon.png" /></a>'; 
                                                        }else{
                                                            echo '<img src="images/view-icon.png">'; 
                                                        }

                                                    }  
                                              
                                              
                                              echo '</div>'; 
                                        echo '</td>'; 
                                       ///**Fin Col  N#4
                                       ///***Col  N#5
                                         /* echo '<td> ';  
                                                 echo  '<div class="impresion-oculta">';
                                                 if (documento($cve_pros, $row['id_d'])==1){
                                                     
                                                 ?>    
                                                  <a href="expedientes.php?eliminar=<?php echo $row['id_d']; ?>&prospecto2=<?php echo $cve_pros; ?>"  onclick='return confirm("¿Esta Seguro de Eliminar?")'><img src="images/eliminar.png"/></a>
                                                 <?php 
                                                }
                                                 echo '</div>';
                                          echo '</td>'; */
                                        ///**Fin Col  N#5
                                      ////*****Consultamos los estatus   $_REQUEST['prospecto'];
                                          $string_bus_estatus = sprintf("SELECT estatus FROM exp_precalificacion_estatus  WHERE id_d=%s && id_p=%s ",
                                                          GetSQLValueString($row['id_d'], "int"),GetSQLValueString( $_REQUEST['prospecto'], "int") );
                                          $qery_bus_status = mysqli_query($conecta1, $string_bus_estatus);
                                          $status_precalificacion  = mysqli_fetch_array($qery_bus_status); 
                                          
                                        ///**Col N#6  
                                        echo   '<td>';
                                          if ($status_precalificacion['estatus'] ==1){
                                             echo "<img src='images/apply-icon.png' />";
                                             }
                                         echo   '</td>';
                                        ///** Fin Col N#6  
                                        ///**Col N#7  
                                        echo   '<td>';
                                          if ($status_precalificacion['estatus'] ==0){
                                             echo "<img src='images/cancel-icon.png' />";
                                             }
                                         echo   '</td>';
                                        ///** Fin Col N#7  
                                          
                               echo  '</tr>';
                               
                           }    
                        ?>
                    </tbody>
                   
                </table>
                <br>
                <br> 
                <!--Fin    Tabla PRECALIFICACION---> 
               
                <table border="1">
                    <thead>
                        
                        <th colspan="5"><?php echo $persona;?></th>
                        
                    </thead>
                    <tbody>
                        <?php  while($row3= mysqli_fetch_array($msql2)) { ?>
                           
                            <tr>
                                <td><?php 
                                       if (documento($cve_pros, $row3['id_d'])==1){
                                           echo "<img src='images/apply-icon.png' />";
                                       }else{
                                           echo "<img src='images/cancel-icon.png' />";
                                           
                                       }
                                           
                        
                                            
                                
                                ?></td>
                                 <td><?php echo utf8_encode($row3['nombre']);?></td>
                                
                                 <td><?php 
                                            
                                            $string_buscar=sprintf("Select * from entrega Where id_p=%s and id_d=%s and not isnull(archivo)",
                                                           GetSQLValueString($cve_pros, "int"),
                                                            GetSQLValueString($row3['id_d'], "int"));
                                            $sql_buscar=mysqli_query($conecta1, $string_buscar) or die (mysqli_error($conecta1));
                                            $cuantos= mysqli_num_rows($sql_buscar);
                                            if ($cuantos>0){
                                                $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                                $rutaarchivo="../pedidos/".$datos_buscar['archivo'];
                                                if($row3['visible']==0){
                                                    echo '<a href='.$rutaarchivo.' target="_blank"><img src="images/view-icon.png" /></a>'; 
                                                }else{
                                                    echo '<img src="images/view-icon.png">'; 
                                                }
                                               
                                            }        
                                  ?></td>          
                                 
                                
                                
                            </tr>
                        
                        <?php } ?> 
                    </tbody>
                        
                        
                    
                    
                </table>
                    
                    <a href="popupentrega.php?id=<?php echo $row_consulta_sql_datos['id_p'];?>" class="input_submit" target="_blank" onClick="window.open(this.href, this.target, 'width=430,height=300,scrollbars=yes'); return false;">Agregar Documento </a>
                     <input type="submit" class="input_submit" name="cancelar" id="Cancelar" value="Regresar"   />

                <?php }else{ ?>
                      <br>
                    <input type="submit" class="input_submit" name="guardar" id="guardar" value="Guardar" />
                    <input type="submit"  class="input_submit" name="cancelar" id="Cancelar" value="Regresar" onclick="this.form.submit()"  />
                <?php } ?> 
                
             
               
           </form> 
           
           <?php } else { ?>
            
           
           <form id="forma1" method="POST" action="expedientes-agente.php">
                    <select name="prospecto" id="prospecto" onchange="this.form.submit()">
                        <option value="">Listado de Clientes</option>
                         <?php                   
                          while($row = mysqli_fetch_array($sql_1)) {
                              if ($cve_pros==$row['id_p']){
                                    echo '<option value="'.$row['id_p'].'" selected>'.$row['nombre'].'</option>';	
                              }
                              echo '<option value="'.$row['id_p'].'">'.$row['nombre'].'</option>';	 

                          }	
                        ?>
                        
                        
                    </select>
                </form>    
         <?php   } ?>

    
</div> 
 <?php require_once('foot.php');?>     