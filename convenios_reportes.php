<?php

///convenios_reportes.php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : convenios_reportes.php
 	Fecha  Creacion : 30/12/2016
	Descripcion  : 
                 Script  para mostrar todos  los  convenios  y su  estado  Actual.
	Modificado  Fecha  : 
*/

require_once('header_inteligencia.php');
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);
   
require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");    

$idagente = $_SESSION["usuario_agente"];



//CONSULTA PARA SACAR LOS PEDIDOS DEL AGENTE

 $stringtabla = "SELECT * FROM encabeza_convenio ORDER BY fecha_alta DESC";
         
/* sprintf("SELECT * FROM encabeza_convenio WHERE n_agente=%s ORDER BY fecha_alta DESC ",
GetSQLValueString($idagente, "int")); */
$tablaquery=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?> 

<div class="container">
        <div class="page-header">
          <h3>Reporte  Convenios <?php //echo $instruccion; ?></h3>
        </div>
         
      </div><!-- /.container -->
      
      <form name="forma1" method="POST">
       <div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Convenio</th>
                     
                     <th>Cliente</th>
                     <th>Fecha Alta</th>
<!--                     <th>Observacion</th>-->
                     <th>Estado</th>
           
            

                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mysqli_fetch_array($tablaquery)){  ?>
                 <tr>
                     <td><a href="convenio_detalle_representante_vista.php?remision=<?php echo $registro1['n_remision'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $registro1['n_remision'];?></a></td>                           
                     <td><?php echo $registro1['nom_cte'];?></td> 
                     <td><?php echo $registro1['fecha_alta'];?></td> 
<!--                     <td><?php// echo $registro1['observacion'];?></td> -->
                     <td><?php if($registro1['estatus']=='A'){ 
                                    echo "Por autorizar";
                     } else 
                     if($registro1['estatus']=='E'){
                                  echo "Emitido";
                                  
                     }else 
                     if($registro1['estatus']=='N'){
                         echo "Rechazado";
                         
                     }?>  <?php if($registro1['estatus']=='N'){ ?> 
                        <a href="convenios_representantes_modifica.php?folio=<?php echo $registro1['n_remision'];  ?>&fechainicio=<?php echo $registro1['fechainicio']; ?>&fechafin=<?php echo $registro1['fechafin']; ?>&cliente=<?php echo $registro1['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=800,scrollbars=yes'); return false;">(Modifica)</a> 
                    <?php } ?>  
                   <?php  
                     if($registro1['estatus']=='C'){
                            echo "Cancelado";
                        }
                     ?> 
                    </td>
                    
                 </tr>
                   <?php 
                   

                   
                 } ?>
             </tbody>


         </table>
           <p>
                    

                 </p>
             </div>
        
    </form>
      
 <?php require_once('foot.php');?>     