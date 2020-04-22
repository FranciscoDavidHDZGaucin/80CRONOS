<?php

require_once('header.php');
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);
   
require_once('conexion_sap/sap.php');

//mssql_select_db("AGROVERSA");    

$nom_ag = $_SESSION["usuario_agente"];
$nom_ag2 = $_SESSION["DocNum"];
//CONSULTA PARA SACAR LOS PEDIDOS DEL AGENTE SELECT * from logistica_entregas where nom_age=%s and  cve_prod=%s and whscode=%s and isnull(n_factura)
//exsitencia_comprometida_entregas(facturas creadas)   SELECT * from pedidos.logistica_entregas where n_agente='151' and  isnull(n_factura)
 /*$stringtabla = sprintf("SELECT * from logistica_entregas where nom_age=%s and  cve_prod=%s and whscode=%s and isnull(n_factura)",
                       GetSQLValueString($nom_ag, "text"),
                       GetSQLValueString($cve_prod, "int"),
                       GetSQLValueString($alm, "text"));*/

/*$stringtabla = sprintf("SELECT * from logistica_entregas where n_agente=%s and isnull(n_factura)",
                       GetSQLValueString($nom_ag, "text"));*/

 
        // $conecta2= mssql_select_db('AGROVERSA_PRODUCTIVA');


  ///$tabla2=mssql_fetch_array($tabla);
  ///mssql_free_result($tabla);
         // $tablaquery= mssql_select_db( $conecta2);
                            
//$tablaquery=mysqli_query($conecta2, $stringtabla) or die (mysqli_error($conecta2));




















/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?> 



<div class="container">
        <div class="page-header">
          <h3>Facturas Creadas<?php //echo $instruccion; ?></h3>

        </div>
        <!--  <?php  echo  $stringtabla ?> -->
         
           
      </div><!-- /.container -->
      
      <form action="facturas_creadas.php" method="get">

        <input type="text" name="fe"/>
        


       
           <p>
                    

                 </p>

                 <button name="buscar" type="submit">Buscar</button>
             </div>
        
    </form>
      
 <?php require_once('foot.php');?>     