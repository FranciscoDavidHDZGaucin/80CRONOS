<?php
/////gast_ReporteAgenPago.php 





require_once('header_conta_gastos.php');
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
   require_once('funciones.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
      

//CONSULTA PARA MOSTRAR los gastos capturados
$string_agentes=sprintf("SELECT * FROM vista_poliza_agentes ORDER BY nom_age ASC ");
 
$query_agentes=mysqli_query($conecta1, $string_agentes) or die (mysqli_error($conecta1));

if (isset($_REQUEST['totalxagente'])){
	$fecha_ini= trim($_POST['fecha1']);
     $fecha_fin= trim($_POST['fecha2']);
     $empleado=$_POST['empleado'];
      $_SESSION['empleado']=$empleado;
	 if (strlen($fecha_ini)>0 && strlen($fecha_fin)){
		 $_SESSION['fecha_start']=$fecha_ini;
		 $_SESSION['fecha_end']=$fecha_fin;
		 $_SESSION['empleado']=$empleado;
		
	}

}

?> 

<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="script_gastos/fechas_fail_other.js"></script>
<script type="text/javascript">
  
    
</script> 
<script type="text/javascript">
$(document).ready(function(){


});

</script>

<div class="container">
    <form id="filtro" name="filtro" method="post" action="buscafac.php">      
    <div class="  col-lg-12 col-sm-12  col-xs-12" >
                            
                    <div class="col-lg-2 col-sm-12  col-xs-12" >
                            <strong>Fecha Pago Inicial</strong>
                             <input type="date"  class="form-control" value="" name="fecha1" title="Fecha Inicio Pago">
                    </div>
                    <div class="col-lg-2 col-sm-12  col-xs-12" >
                            <strong>Fecha Pago Final</strong>
                             <input type="date"  class="form-control"  value=""   name="fecha2" title="Fecha Fin Pago">
                    </div>
                    
    </div>
        <br><br><br>
    <div class="row"> 
    <input type="submit" class="btn btn-info" name="totalxagente" id="totalxagente"  value="Total x Agente" />
    </div>
    <?php   
        if (isset($_REQUEST['totalxagente'])){
            
         echo   $_SESSION['fecha_start']."<br>";
	echo	 $_SESSION['fecha_end']."<br>";
	echo 	 $_SESSION['empleado']."<br>"; 
        
            ?>     
    <a href="gast_generadorXaGente.php" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><img src="images/file_pdf.png" title="Imprimir" /></a></td>        
        

    
    
    
    <?php } ?>  
</form>   
    
    
    <div class="container">
        <div class="page-header">
          <h3>Facturas Creadas<?php //echo $instruccion; ?></h3>

        </div>
         <?php  echo  $stringtabla ?> 
         
           
      </div><!-- /.container -->
      
      <form name="forma1" method="POST">

        


       <div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Fecha Alta</th>
                     
                     <th>Cliente</th>
                     <th>Folio</th>
<!--                     <th>Observacion</th>-->
                     <th>Total</th>
           
            

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
    
    
    
</div><!-- /.container -->
<?php require_once('foot.php');?>   