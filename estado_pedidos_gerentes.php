<?php

require_once('header_gerentes.php');
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
require_once('funciones.php');
mysqli_select_db($conecta1, $database_conecta1);
   
require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");    

$idagente = $_SESSION["usuario_agente"];


///$_SESSION["usuario_rol"]
//Consulta de los pedidos

 $stringtabla = sprintf("select t0.n_remision,t0.n_agente,t0.cve_cte,t0.nom_cte,t0.fecha_alta,t0.observacion,t0.estatus, t0.nom_age from encabeza_pedido t0 inner join relacion_gerentes t1 on t0.n_agente=t1.cve_age where cve_gte=%s and year(t0.fecha_alta)>2014 ORDER BY fecha_alta DESC ",
                GetSQLValueString($_SESSION["usuario_rol"], "int"));
$tablaquery=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?> 

<div class="container">
        <div class="page-header">
          <h3>Estado Pedidos <?php //echo $instruccion; ?></h3>
        </div>
         
      </div><!-- /.container -->
      
    
       <div class="table-responsive">
      
                  <table  class="table table-responsive table-hover" id="dataTables-estado-pedidos-gerentes" >
             <thead>
                 <tr>
                     <th>Remision</th>
                     <th>Agente</th>
                     <th>Cliente</th>
                     <th>Alta</th>
                     <th>Observacion</th>
                     <th>Estado</th>
           
                     <th></th>
                     <th></th>

                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mysqli_fetch_array($tablaquery)){  ?>
                 <tr>
                     <td><a href="pedido_detalle_representante.php?remision=<?php echo $registro1['n_remision'];  ?>&agente=<?php echo $registro1['n_agente']; ?>&cliente=<?php echo $registro1['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $registro1['n_remision'];?></a></td>        
                     <td><?php echo $registro1['nom_age'];?></td> 
                     <td><?php echo $registro1['nom_cte'];?></td> 
                     <td><?php echo $registro1['fecha_alta'];?></td> 
                     <td width="40%"><?php echo $registro1['observacion'];?></td> 
                    <td><?php if($registro1['estatus']=='C'){ 
                                echo "Cyc";
                                
                              } else{
                                 echo  revisa_pedido_xprod($registro1['n_remision'], $registro1['n_agente'], $registro1['cve_cte']);
                              }
                    
                    
                    
                    
                    
                    ?></td> 
                    <td><a href="cronos-recogedor.php?con_consecutivo=<?php echo $registro1['id']; ?>&remision=<?php echo $registro1['n_remision']; ?>&agente=<?php echo $registro1['n_agente']; ?>&cliente=<?php echo $registro1['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"> <img src="iconos/detalle2.png"/></a></td>
                    <td><a href="pop-pedido-impreso.php?remision=<?php echo $registro1['n_remision'];  ?>&agente=<?php echo $registro1['n_agente']; ?>&cliente=<?php echo $registro1['cve_cte']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><img src="images/file_pdf.png" title="Imprimir" /></a></td>        
                     
                 </tr>
                   <?php 
                   

                   
                 } ?>
             </tbody>


         </table>
        
             </div>
        

      
 <?php require_once('foot.php');?>     