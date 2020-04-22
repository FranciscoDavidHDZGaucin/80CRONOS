<?php
///***expedientes_asidirco.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : expedientes_asidirco.php
 	Fecha  Creacion : 25/04/20047
	Descripcion  : 
 *      
 *      Modificaciones:         
 *           14/03/2017  Se Creo la  Carpeta scrip_expedientes la cual  contiene  los  escrips  
 *                       correspondientes  a la pagina Expedientes.php  
  */
session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 

if($_SESSION["usuario_rol"]==101){
require_once('header_asisdir.php');
}else{
    
require_once('header_direccion.php');  
    
}
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


$string_1=("SELECT * FROM prospecto order by  nombre LIMIT 30");
$sql_1=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));

$sql_clientes=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));

///***Obtenemos  Numero de  Elementos 
 $Num_ROWS = mysqli_num_rows($sql_clientes);

 ///***Obtenemos el Id del  ultimo Elemento 
 $ID_ELEM = $Num_ROWS;
 ///****Generamos   Segundo  qery  sin limite
$string_select_cliente=("SELECT * FROM prospecto order by nombre ");
$sql_selec_cli=mysqli_query($conecta1,$string_select_cliente) or die (mysqli_error($conecta1));

//Obtener los agentes Integradora
$sql_result=("SELECT SlpName, SlpCode, U_email FROM OSLP WHERE CAST(U_email as varchar)<>'' order by SlpName ");

$result3=mssql_query($sql_result);


///obtener las columnas de los documentos que utiliza crédito
$string_doc="select * from pedidos.documento where tp='M' order by id_d";
$sql_doc=mysqli_query($conecta1,$string_doc) or die (mysqli_error($conecta1));
$sql_doc2=mysqli_query($conecta1,$string_doc) or die (mysqli_error($conecta1));

//Veririfcar si eligio algun prospecto ya dado de alta
if (isset($_REQUEST['prospecto'])){
    $cve_pros=$_REQUEST['prospecto'];
    
    
    
}


?>


<!--Librerias  para   Bootstrap    y  jquery --> 
   <!-- Bootstrap Core CSS -->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script> 
<!-- Bootstrap js -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js" ></script> 
 
<!--*****Inicio   Codigo Jqery  ------>
<script  type="text/javascript">
    $(document).ready(function(){
        var NumE = 31 ;
        var Iden=0; 
        var win = $(window);
	var doc = $(document);

	// Each time the user scrolls
	win.scroll(function() {
		// Vertical end reached?
		/*
                if (doc.height() - win.height() == win.scrollTop()) {
			// New row
			var tr = $('<tr />').append($('<th />')).appendTo($('#spreadsheet'));

			// Current number of columns to create
			var n_cols = $('#spreadsheet tr:first-child th').length;
			for (var i = 0; i < n_cols; ++i)
				tr.append($('<td />'));
		}
                */
		// Horizontal end reached?
		if (doc.width() - win.width() == win.scrollLeft()) {
			// New column in the heading row
		/*	$('#spreadsheet tr:first-child').append($('<th />'));

			// New column in each row
			$('#spreadsheet tr:not(:first-child)').each(function() {
				$(this).append($('<td />'));
			});*/
    
                        
                        // New row
			//var tr = $('<tr />').append($('<th />')).appendTo($('#spreadsheet'));
                         var tr = $('<tr />').appendTo($('#spreadsheet'));

			// Current number of columns to create
			var n_cols = $('#spreadsheet tr:first-child th').length;
                        
                      /// var elemetos =  "<th>JJ</th><th>KK</th><th>QQQQQQQQQQQQQQQ</th><th>AA</th><th>22</th>";
                         
                         
                         $.ajax({
                                type: 'POST',
                                url:   'scrip_expedientes/get_row_tb_EX.php', ///'/ejemplo_ajax_001/inser.php',           //'inser.php' , ///'http://localhost/ejemplo_ajax_001/inser.php',
                                data:  {"Num":NumE },
                                success: function (datos) {
                                       ////alert(datos.Ttl)
                                       if(Iden==0 || Iden != datos.Datowork)
                                       {
                                       tr.append(datos.R1+datos.R2+datos.R3);
                                       Iden=datos.Datowork;
                                     
                                        }else{
                                       /// console.log(datos.R1+datos.R2+datos.R3);
                                            NumE++;
                                        }     
                                            
                                            Total =datos.TotalElem
                                    }});
                            
			///for (var i = 0; i < n_cols; ++i)
				///tr.append($('<td />'));
                            
		}
	});
       
        
        
    });
</script>
<!--*****Fin   Codigo Jqery  --------->
<style> 


#spreadsheet tr:first-child th:before {
    counter-increment: col;
    /*content: counter(col, upper-alpha);*/
}

#spreadsheet tr th:first-child:before {
    counter-increment: row;
    /*content: counter(row);*/
}
#cont_divInfo {
   border: 2px solid #ccc;
    border-radius: 23px;
}


</style> 






<div class="espacio-datos">
	
	
          
               
           <?php
           if (isset($_REQUEST['prospecto']))
            { 
                  //Seccion en donde ya eligio algun jefe 
                   $cve_pros=$_REQUEST['prospecto'];
                   $sql_datos=sprintf("select * from prospecto where id_p=%s",GetSQLValueString($cve_pros,"int"));
                   $query=mysqli_query($conecta1,$sql_datos) or die (mysqli_error($conecta1)); 
                   $row_consulta_sql_datos=  mysqli_fetch_assoc($query);
                   $nfilas = mysqli_num_rows ($query);
                   
                   ///***Qery  para Obtener el  Nombre del  Agente
                   $string_agenteconsul = sprintf("SELECT nom_age,nom_gte FROM pedidos.relacion_gerentes  where cve_age= %s",
                        GetSQLValueString($row_consulta_sql_datos['agente'], "int"));
                   $qery_agente = mysqli_query($conecta1,$string_agenteconsul) or die (mysqli_error($conecta1));
                   $result_agente= mysqli_fetch_assoc($qery_agente);
   
           ?>
           <form name=form2 action="expedientes.php" method="POST" >
                  
                 <div id="cont_divInfo" class="row"> 
                     <div class="form-inline"> 
                         <label>Nombre Cliente: <?php echo $row_consulta_sql_datos['nombre'];?></label> <label>Clave Cliente: <?php echo $row_consulta_sql_datos['clave_sap'];?></label>
                     </div>
                     <div  class="form-inline"> 
                             <label>Nombre Agente: </label>
                             <label><?php  echo  $result_agente['nom_age']; ?></label>                        
                     </div> 
                     <div  class="row"> 
                         <div class="col-md-6"> 
                             <div  class="form-group"> 
                                 <label>Domicilio :</label>
                                 <label><?php  echo  $row_consulta_sql_datos['domicilio']; ?> </label>  
                             </div>
                             <div  class="form-group"> 
                                 <label>Colonia: </label>
                                 <label> <?php  echo  $row_consulta_sql_datos['colonia']; ?></label>  
                             </div>
                             <div  class="form-group"> 
                                 <label>Poblacion:</label>
                                 <label><?php  echo  $row_consulta_sql_datos['poblacion']; ?> </label>  
                             </div>
                         
                         </div>
                         <div class="col-md-6"> 
                             <div  class="form-group"> 
                                 <label>Estado: </label>
                                 <label><?php  echo  $row_consulta_sql_datos['estado']; ?> </label>  
                             </div>
                             <div  class="form-group"> 
                                 <label>Cp:</label>
                                 <label><?php  echo  $row_consulta_sql_datos['cp']; ?> </label>  
                             </div>
                         
                         
                         </div>
                     </div> 
                 </div>
                <?php if ($nfilas>0)  {
                    
                    
                        
                      $persona="PERSONA MORAL";
                      $p="M"; 
                      if ($row_consulta_sql_datos['t_persona']=='F'){
                        $persona="PERSONA FISICA";
                         $p="F"; 
                            
                        }
                     
                        $string_2=sprintf("SELECT * FROM documento where  tp = %s  && ( (id_d <> 1 && id_d <> 6 && id_d <> 2 && id_d <> 8 && id_d <> 10)  &&  (id_d <> 20 && id_d <> 21 && id_d <> 25 && id_d <> 27 && id_d <> 29) )",
                                  GetSQLValueString($p, "text"),GetSQLValueString($p, "text"));
                        $msql2= mysqli_query($conecta1, $string_2) or die (mysqli_error($conecta1));  
                        ///*****Generamos  Cadena   y Qery  para    PRECALIFICACION  &&  
                        $string_precalificacion  = sprintf("SELECT *FROM  pedidos.documento  where  'F'= %s   && (id_d =  1 ||id_d = 2  || id_d =  6  || id_d = 8  || id_d = 10)|| ('M'=%s   && (id_d =20  || id_d =21  || id_d  =25  || id_d  =27 || id_d  = 29))",
                                                    GetSQLValueString($p, "text"), GetSQLValueString($p, "text"));
                        $qery_precalificacion = mysqli_query($conecta1, $string_precalificacion);
                    
                    ?>
                <div class="impresion-oculta">
                    <input type="hidden" name="id" value=" <?php echo $cve_pros;?>"  />
               <!--  <input type="submit" class="input_submit" name="actualizar" id="Cancelar" value="Actualizar"   />--> 
               </div>  
                <br>
                <!---Inicio  Tabla PRECALIFICACION   ---> 
                <table border="1"  class="table table-hover" > 
                    <thead>
                        <tr> 
                    <th  colspan="5">PRECALIFICACION</th>
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
                                          echo '<td> ';  
                                                 echo  $row['id_d']; 
                                          echo '</td>'; 
                                        ///**Fin Col  N#3 
                                        ///***Col  N#4
                                        echo '<td> ';  
                                              echo  '<div class="impresion-oculta">'; 
                                                    $string_buscar=sprintf("Select * from entrega Where id_p=%s and id_d=%s and not isnull(archivo)",
                                                                   GetSQLValueString($cve_pros, "int"),
                                                                    GetSQLValueString($row['id_d'], "int"));
                                                    $sql_buscar=mysqli_query($conecta1, $string_buscar) or die (mysqli_error($conecta1));
                                                    $cuantos= mysqli_num_rows($sql_buscar);
                                                    if ($cuantos>0){
                                                        $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                                        echo '<a href=../pedidos/'.$datos_buscar['archivo'].' target="_blank"><img src="images/view-icon.png" /></a>';
                                                    }        
                                              echo '</div>'; 
                                        echo '</td>'; 
                                       ///**Fin Col  N#4
                                       ///***Col  N#5
                                          echo '<td> ';  
                                                 echo  '<div class="impresion-oculta">';
                                                 if (documento($cve_pros, $row['id_d'])==1){
                                                     
                                                 ?>    
                                                <!--  <a href="expedientes.php?eliminar=<?php echo $row['id_d']; ?>&prospecto2=<?php echo $cve_pros; ?>"  onclick='return confirm("¿Esta Seguro de Eliminar?")'><img src="images/eliminar.png"/></a>-->
                                                 <?php 
                                                }
                                                 echo '</div>';
                                          echo '</td>'; 
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
                                 
                                    <td><?php echo  $row3['id_d']; ?></td> 
                                    <td> <div class="impresion-oculta"><?php 

                                               $string_buscar=sprintf("Select * from entrega Where id_p=%s and id_d=%s and not isnull(archivo)",
                                                              GetSQLValueString($cve_pros, "int"),
                                                               GetSQLValueString($row3['id_d'], "int"));
                                               $sql_buscar=mysqli_query($conecta1, $string_buscar) or die (mysqli_error($conecta1));
                                               $cuantos= mysqli_num_rows($sql_buscar);
                                               if ($cuantos>0){
                                                   $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                                   echo '<a href=../pedidos/'.$datos_buscar['archivo'].' target="_blank"><img src="images/view-icon.png" /></a>';
                                               }        
                                               ?></div></td>          

                                    <td><div class="impresion-oculta"><?php   if (documento($cve_pros, $row3['id_d'])==1){?>

                                           <!--    <a href="expedientes.php?eliminar=<?php echo $row3['id_d']; ?>&prospecto2=<?php echo $cve_pros; ?>"  onclick="return confirm('¿Esta Seguro de Eliminar?')"><img src="images/eliminar.png"/></a>--> 

                                         <?php  }?></div>

                                   </td>
                                  
                                
                            </tr>
                        
                        <?php } ?> 
                    </tbody>
                        
                        
                    
                    
                </table>
                     <div class="impresion-oculta">
                        <!-- <a href="popupentrega.php?id=<?php echo $row_consulta_sql_datos['id_p'];?>&pers=<?php echo $p;?>" class="input_submit" target="_blank" onClick="window.open(this.href, this.target, 'width=430,height=300,scrollbars=yes'); return false;">Agregar Documento </a>
                        <input type="submit" class="input_submit" name="cancelar" id="Cancelar" value="Regresar"   />--> 
                     </div>
                <?php }else{ ?>
                      <br>
                    <input type="submit" class="input_submit" name="guardar" id="guardar" value="Guardar" />
                    <input type="submit"  class="input_submit" name="cancelar" id="Cancelar" value="Regresar" onclick="this.form.submit()"  />
                <?php } ?> 
                
             
               
           </form> 
           
           <?php } else { ?>
            
           
    <form id="forma1" method="POST" action="expedientes_asidirco.php">
                    <select name="prospecto" class="form-control select2" id="prospecto" onchange="this.form.submit()">
                        <option value="">Listado de Clientes Prospectos</option>
                         <?php                   
                          while($row = mysqli_fetch_array($sql_selec_cli)) {
                              if ($cve_pros==$row['id_p']){
                                    echo '<option value="'.$row['id_p'].'" selected>'.$row['nombre'].'</option>';	
                              }
                              echo '<option value="'.$row['id_p'].'">'.$row['nombre'].'</option>';	 

                          }	
                        ?>
                        
                       
                    </select>
        <br><br>
        <div  class ="row">
        <a  class="btn btn-success"  href="report_expedi_assis.php"> <img src="images/excel.ico"/></a>    
        </div > 
        <br><br> 
                  <!---  <input type="submit" class="input_submit" name="prospecto"  value="+Nuevo" />--> 
                </form>  
           
    
                  <table border="1" id="spreadsheet" class="table table-hover" >
            
            <thead>
            
                <tr>
                    <th>Cliente</th>
                    <th>SAP</th>
					<th>Nuevo</th>
                    
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
               
              
            <tr <?php if($nuevo==1){  echo ' bgcolor="#92D025" title="Cliente Nuevo"'; }  ?>  >
                <td><?php  echo $rowc['nombre']; ?></td>
                <td><?php  echo $rowc['clave_sap']; ?></td>
				<td><?php if($nuevo==1){ echo 'SI'; }else{  echo 'NO'; }  ?></td>
                <?php
                for ($i =20; $i <=41; $i++) {    ///es el numero de documentos que se solicitan
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
           
          
           
           
    

</div> <!-- Div de Contenido  -->

<?php
//incluye el pie de pagina cuando se entra como vendedor
include('agente_footer_plus.php');
?>

