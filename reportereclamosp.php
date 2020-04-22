<?php
/*
********* INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : reportereclamosp.php
 	Fecha  Creacion : 20/09/2016
	Descripcion  : 
	   Copia del archivo   reportereclamosp.php  del  Proyecto  Pedidos 
 *         Para  este  archivo  tambien  es  necesario  el   productopromotor_detalle.php  
 *         que tambien  fue  copiado. 
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
mssql_select_db("AGROVERSA"); 
///****FIN    Librerias  Utilizadas  en Cronos 

$consulta = sprintf("SELECT * FROM reclamop WHERE n_agente = %s",
        GetSQLValueString($_SESSION["usuario_agente"], "int"));

$resconsulta = mysqli_query($conecta1, $consulta) or die (mysqli_errno($conecta1));

?> 
<div  class="container"> 
    <h1> Reporte de reclamos (Productos)<?php //echo $consulta; ?> </h1>
<form method="post" enctype="multipart/form-data">
    
    <fieldset class="fieldset">
        <legend>Tabla de reportes <?php //echo $consulta ?></legend>
        <div name="tablareporte">
            <table rules="all" border="1" style="white-space: nowrap">
                <tr>
                    <th>Folio</th> 
                 <th >Cliente</th>
                  <th>Fecha</th>	
                  <th>Motivo</th>
                  <th>Documento</th>
                  <!--Documento Actualizado == Evidencia  de  Cierre -->
                  <th>Evidencia  de  Cierre</th>
                  <th>Status</th>
                  <th>Etapa</th>
                  <th>Observaciones</th>
                  <th>Progreso</th>
                  <!--Cierre Externo  ====   Cierre con el cliente -->
                  <th>Cierre Con El Cliente</th>
                </tr>
                <?php WHILE ($registro1=  mysqli_fetch_array($resconsulta)){  ?>
                    <tr>
                     <td><?php echo $registro1['id_reclamop'];?></td>   
                      <td><?php echo $registro1['nom_cte'];?></td>
                      <td><?php echo $registro1['fecha'];?></td>		
                      <td>
                      <?php 
                           switch ($registro1['motivo']){
                               case 0:
                                   echo "Efectividad";
                                   break;
                               case 1:
                                   echo "Anomalía empaque (cajas, envases)";
                                   break;
                               case 2:
                                   echo "Anomalía en etiqueta";
                                   break;
                               case 3:
                                   echo "Derrame";
                                   break;
                               case 4:
                                   echo "Asentamiento/suspensibilidad";
                                   break;
                               case 5:
                                   echo "Precipitado";
                                   break;
                               case 6:
                                   echo "Olor no característivo";
                                   break;
                               case 7:
                                   echo "Falta de contenido neto";
                                   break;
  
                           }
                      
                     
                      
                      ?></td>
                      <td><?php 
                                      if(is_null($registro1['documento'])){
                                            //  echo $registro['documentacion']; 
                                      }else{
                                          echo '<a href='.$registro1['documento'].' target="_blank">Descargar</a>';
                                       //  echo $registro['documentacion']; 
                                      }
                            ?></td>
                      <!---Actulizar  Documento--> <!--Documento Actualizado == Evidencia  de  Cierre -->
                      <td><?php 
                                      if(is_null($registro1['documento_actualizado'])){
                                            //  echo $registro['documentacion']; 
                                      }else{
                                          $gotoPedidos = "../pedidos/".$registro1['documento_actualizado'];
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
                 
                 <td><a href="productopromotor_detalle.php?dato_id=<?php echo $registro1['id_reclamop'];?>" target="_blank" onClick="window.open(this.href, this.target, 'width=500,height=300,scrollbars=yes'); return false;" ><img src="images/edit.png"/></a></td>
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
                      <!--Cierre Externo  ====   Cierre con el cliente -->
                      <td><?php echo $registro1['cierre_externo'];?></td>
                      
                      
                    </tr>
    
                    <?php } ?>
            </table>
       </div>
    </fieldset>
</form>

</div>
 <?php require_once('foot.php');?>     
