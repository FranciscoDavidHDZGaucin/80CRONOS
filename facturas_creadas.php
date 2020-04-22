<?php

require_once('header.php');
require_once('Connections/conecta1.php');

require_once('formato_datos.php');
mysqli_select_db($conecta1, $database_conecta1);
   
require_once('conexion_sap/sap.php');

//mssql_select_db("AGROVERSA");    
$busca=$_GET["buscar"];

$nom_ag = $_SESSION["usuario_agente"];
$nom_ag2 = $_SESSION["DocNum"];
$nom_ag3 = $_SESSION["CardName"];
$nom_ag3 = $_SESSION["OnHand"];
$nom_ag3 = $_SESSION["WhsCode"];
$nom_ag3 = $_SESSION["DocCur"];

$nom_ag2=1;

 $stringtabla2=("SELECT DISTINCT(U_formaPago) from Facturas_sap where Facturas_sap.SlpCode='$nom_ag' ");

//// ESTO ES LO DE MYSQL SERVIDOR 5



                       






if (isset($_POST['totalxagente'])){

  

 if($_POST['tipoPag']!=0 || $_POST['fecha1']!=null || $_POST['fecha2']!=null ){
       $stringtabla = sprintf("SELECT * from Facturas_sap where  Facturas_sap.U_formaPago =%s and Facturas_sap.SlpCode=%s and Facturas_sap.DocDate>=%s and Facturas_sap.DocDate<=%s ORDER BY DocDate desc", 
      
          GetSQLValueString($_POST['tipoPag'],"text"),
          GetSQLValueString($nom_ag,"int"),
          GetSQLValueString($_POST['fecha1'],"date"),
          GetSQLValueString($_POST['fecha2'],"date")
      );

     
    } 


 
    
  echo "<br>";				
	 echo   $stringtabla;
 

 }


$tabla2=mssql_query($stringtabla2);
$tabla=mssql_query($stringtabla);










?> 








<div class="container">
        <div class="page-header">
          <h3>Facturas Creadas<?php //echo $instruccion; ?></h3>

        </div>
      
         
           
      </div><!-- /.container -->



      
      <form name="forma1" method="POST" action="facturas_creadas.php">
        <div class="  col-lg-12 col-sm-12  col-xs-12" >
                            
                    <div class="col-lg-2 col-sm-12  col-xs-12" >
                            <strong>Fecha  Inicial</strong>
                             <input type="date"  class="form-control" value="" name="fecha1" title="Fecha Inicio Pago">
                    </div>
                    <div class="col-lg-2 col-sm-12  col-xs-12" >
                            <strong>Fecha  Final</strong>
                             <input type="date"  class="form-control"  value=""   name="fecha2" title="Fecha Fin Pago">
                    </div>

                     <div class="col-lg-2 col-sm-12  col-xs-12" >
                            <strong>Tipo</strong>
                            <select name="tipoPag"  id="tipoPag" class ="form-control col-lg-4 col-md-4"  style="width:220px" width="140">
                       <option  value="0">N/A</option>
                     <?php

                       while ($rowc=mssql_fetch_array($tabla2))
                               
                         {
                                 
                                    echo '<option value="'.$rowc['U_formaPago'].'">'.$rowc['U_formaPago'].'</option>';  
                                
                         }
                         
                         
                     ?>
                     </select>
                          
                   
                    <td >     
                    </div>





                    


                    
    	</div>
        <br><br><br>
    <div class="row"> 
    <input type="submit" class="btn btn-info" name="totalxagente" id="totalxagente"  value="Total x Agente" />

       <div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Fecha Alta</th>
                     
                     <th>Cliente</th>
                     <th>Folio</th>
<!--                     <th>Observacion</th>-->
                     <th>Total</th>
                     <th>Tipo Pago</th>
                      <th>Moneda</th>
                    
            

                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mssql_fetch_array($tabla))



                  //mysqli_fetch_array($tablaquery))
                  {  ?>
                 <tr>
                                              
                     <td><?php echo $registro1['DocDate'];?></td> 
                     <td><?php echo $registro1['CardName'];?></td> 
                     <td><?php echo $registro1['DocNum'];?></td> 
                     <td><?php  echo $registro1['LineTotal'];?></td> 
                      <td><?php  echo $registro1['U_formaPago'];?></td> 
                       <td><?php  echo $registro1['DocCur'];?></td> 
                      
<!--                     <td><?php// echo $registro1['observacion'];?></td> -->
                     <td><?php    ?> </td> 
                        
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