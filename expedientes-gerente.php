<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : expedientes-gerente.php  
 	Fecha  Creacion : 21/09/2016
	Descripcion  : 
	Copia  archivo  expedientes-gerente.php   parte  del  Proyecto  Pedidos
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
mssql_select_db("AGROVERSA"); 
////******************************
require('correos.php');   //funcion para mandar correos
///************************************************
require('buscar_email.php');   //funcion para obtener el email de un usuario en especifico
///****FIN    Librerias  Utilizadas  en Cronos 
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
    $string_1=sprintf("SELECT *FROM vista_prospecto where agente=%s order by nombre",
          GetSQLValueString($_SESSION['usuario_agente'], "int"));
}


$sql_1=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));
$sql_clientes=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));


///obtener las columnas de los documentos que utiliza crédito
$string_doc="select * from pedidos.documento where tp='M' order by id_d";
$sql_doc=mysqli_query($conecta1,$string_doc) or die (mysqli_error($conecta1));
$sql_doc2=mysqli_query($conecta1,$string_doc) or die (mysqli_error($conecta1));


//Obtener los agentes Integradora
$sql_result=("SELECT SlpName, SlpCode, U_email FROM OSLP WHERE CAST(U_email as varchar)<>'' order by SlpName ");

$result3=mssql_query($sql_result);

?> 
<div  class="container"> 
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
           <form name=form2 action="expedientes-gerente.php" method="POST" >
        
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
                    
                        $string_2=sprintf("SELECT * FROM documento where tp=%s",
                                  GetSQLValueString($p, "text"));
                        $msql2= mysqli_query($conecta1, $string_2) or die (mysqli_error($conecta1));  
                    
                    
                    ?>
                <br>
                
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
            
           
           <form id="forma1" method="POST" action="expedientes-gerente.php">
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
             
             
             <table border="1">
            
            <thead>
            
                <tr>
                    <th>Cliente</th>
                    <th>SAP</th>
                    
                    <?php         
                        while($rowd = mysqli_fetch_array($sql_doc)) {    
                       ?>     
                  
                           <th><?php  echo $rowd['id_d']; ?></th>
                         
                     <?php    }  ?>
                   
                </tr>    
                    
            </thead>
            <tbody>
            <?php         
             while($rowc = mysqli_fetch_array($sql_clientes)) {  
                    $nuevo=$rowc['c_nuevo'];   //1=nuevo
                 ?>   
               
              
            <tr <?php if($nuevo==1){  echo ' bgcolor="#92D025" title="Cliente Nuevo"'; }  ?>>
                <td><?php  echo $rowc['nombre']; ?></td>
                <td><?php  echo $rowc['clave_sap']; ?></td>
                <?php
                for ($i =20; $i <=41; $i++) {    ///21 son lo documentos a solicitar 
                    //Buscar en la tabla de documento que otro id de documento se contempla
                                    $string_busca=sprintf("select * from documento where id_d=%s",
                                                 GetSQLValueString($i, "int"));
                                    
                                   
                                    $sql_buscar= mysqli_query($conecta1,$string_busca) or die (mysqli_error($conecta1));
                                    $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                    $nombre= $datos_buscar['nombre'];
                                    $id1=$datos_buscar['id_d'];
                                    $id2=$datos_buscar['aux1']; 
                    
                    ?>
                      <td title="<?php echo utf8_encode($nombre); ?>"><?php 
                      
                                 
                      
                                    ///SE tiene que buscar en la tabla de entrega los id de los documento elegido
                                $string_bd=  sprintf("select * from entrega where (id_d=%s or id_d=%s) and id_p=%s ",
                                             GetSQLValueString($id1, "int"),
                                              GetSQLValueString($id2, "int"),
                                               GetSQLValueString($rowc['id_p'], "int")  );
                                $query_bd= mysqli_query($conecta1,$string_bd) or die (mysqli_error($conecta1));
                                $cuantos=  mysqli_num_rows($query_bd);
                                
                                if ($cuantos>0){
                                    echo "<img title='".utf8_encode($nombre)."' src='images/ico-fichero.gif'>"; 
                                }
                                  
                      ?></td>
                    
                <?php  } ?>
                
                
            </tr>
                 
                 
          <?php    }  ?>
            
            </tbody>  
   
            
        </table>    
             <br>
             <table border="1">
                  <caption>Tabla de Documentos</caption>
                 <thead>
                     <th>Codigo</th>
                     <th>Nombre Documento</th>                     
                 </thead>
                 <tbody>
                     <?php         
                         while($rowd2 = mysqli_fetch_array($sql_doc2)) {   ?> 
                     <tr>
                         <td><?php  echo $rowd2['id_d']; ?></td>
                         <td><?php  echo utf8_encode($rowd2['nombre']); ?></td>
                         
                     </tr>
        
                         <?php  }  ?>
                     
                     
                 </tbody>
                 
             </table>  
             
         <?php   } ?>
</div>
 <?php require_once('foot.php');?>    