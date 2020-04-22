<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : reportereclamos.php  
 	Fecha  Creacion : 20/09/2016 
	Descripcion  : 	
 *      Copia  archivo  reportereclamos.php    parte  del  Proyecto  Pedidos
 *      Archivo  pop    Copia  archivo   entregapromoto_detalle.php   parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
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
mssql_select_db("INTEGRADORA"); 
///****FIN    Librerias  Utilizadas  en Cronos 
$consulta = sprintf("SELECT * FROM reclamoe WHERE n_agente = %s",
        GetSQLValueString($_SESSION["usuario_agente"], "int"));

$resconsulta = mysqli_query($conecta1, $consulta) or die (mysqli_errno($conecta1));

?> 
<div  class="container"> 
    
<h1> Reporte de reclamos (Servicio/Entregas)<?php //echo $consulta; ?> </h1>
<form method="post" enctype="multipart/form-data">
    
    <fieldset class="fieldset">
        <legend>Tabla de reportes</legend>
        <div name="tablareporte">
            <table rules="all" border="1" style="white-space: nowrap">
                <tr>
                  <th>Folio</th> 
                  <th >Cliente</th>
                  <th>Fecha</th>	
                  <th>Motivo</th>
                  <th>Documento</th>
                  <th>Status</th>
                  <th>Etapa</th>
                  <th>Observaciones</th>
                  <th>Historial Comentarios</th>
                   <th>Progreso</th>
                </tr>
                <?php WHILE ($registro1=  mysqli_fetch_array($resconsulta)){  ?>
                    <tr>
                        <td><?php echo $registro1['id_reclamoe']; ?></td>
                      <td><?php echo $registro1['nom_cte'];?></td>
                      <td><?php echo $registro1['fecha'];?></td>		
                      <td>
                      <?php 
                           switch ($registro1['motivo']){
                               case 0:
                                   echo "Faltante de producto (cajas/envases)";
                                   break;
                               case 1:
                                   echo "Daño (cajas/envases)";
                                   break;
                               case 2:
                                   echo "Tardanza en la entrega (fuera de política)";
                                   break;
                               case 3:
                                   echo "Comunicación interna";
                                   break;
                           }
                      
                     
                      
                      ?>
                      </td>
                      <td><?php 
                                      if(is_null($registro1['documento'])){
                                            //  echo $registro['documentacion']; 
                                      }else{
                                           $gotoPedidos = "../pedidos/".$registro1['documento'];
                                          echo '<a href='.$gotoPedidos.' target="_blank">Descargar</a>';
                                       //  echo $registro['documentacion']; 
                                      }
                            ?></td>
                      <td>
                      <?php 
                           switch ($registro1['procede']){
                               case 0:
                                   echo "Pendiente";
                                   break;
                               case 1:
                                   echo "Procede";
                                   break;
                               case 2:
                                   echo "No procede";
                                   break;
                           }
                      
                     
                      
                      ?>
                      </td>
                   <td>
                      <?php 
                           switch ($registro1['etapa']){
                               case 0:
                                   echo "Análisis previo";
                                   break;
                               case 1:
                                   echo "Contención";
                                   break;
                               case 2:
                                   echo "Causa raiz";
                                   break;
                               case 3:
                                   echo "Acciones correctivas";
                                   break;
                               case 4:
                                   echo "cierre";
                                   break;
                               case 5:
                                   echo "Sin análisis";
                                   break;
  
                           }
                      
                     
                      
                      ?>
                 </td>
                 <!--Observaciones-->
                  <td>
                          <a href="reporteRecla_EntrSer_Obser.php?NUM=<?php echo $registro1['id_reclamoe'];?>" target="_blank" onClick="window.open(this.href, this.target, 'width=500,height=300,scrollbars=yes'); return false;" ><img src="images/lupa.gif"/></a>
                   </td>
                 <td><a href="entregapromoto_detalle.php?dato_id=<?php echo $registro1['id_reclamoe'];?>" target="_blank" onClick="window.open(this.href, this.target, 'width=500,height=300,scrollbars=yes'); return false;" ><img src="images/edit.png"/></a></td>
                        <td>
                    <progress value="<?php 
                    $leyenda = "0";
                           switch ($registro1['etapa']){
                                case 0:
                                    echo 20;
                                    $leyenda = '20%';
                                   break;
                               case 1:
                                   echo 40;
                                   $leyenda = '40%';
                                   break;

                               case 2:
                                   echo 60;
                                   $leyenda = '60%';
                                   break;
                               case 3:
                                   echo 80;
                                   $leyenda = '80%';
                                   break;
                               case 4:
                                   echo 100;
                                   $leyenda = '100%';
                                   break;
                           }
                      
                     
                      
                      ?>" max="100">
                    </progress><?php echo $leyenda?>
                      </td>
                    </tr>
    
                    <?php } ?>
            </table>
       </div>
    </fieldset>
</form>
</div>
 <?php require_once('foot.php');?>    
