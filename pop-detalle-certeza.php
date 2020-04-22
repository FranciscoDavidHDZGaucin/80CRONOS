<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
require_once('formato_datos.php');
require_once('Connections/conecta1.php');  

$agente=$_REQUEST['agente'];
$mes=$_REQUEST['mes'];
$anio=$_REQUEST['anio'];


//Buscar los lotes del producto en el almacen especificado

//Consultar si se tiene captura la cartera del mes correspondiente
 $string_pron=sprintf("SELECT cve_age,mes, anio, cve_prod, sum(cantidad) as cantidad, sum(monto_costo) monto_costo  from vista_pronostico where cve_age=%s and mes=%s and anio=%s group by cve_prod",
                        GetSQLValueString($agente, "int"),
                        GetSQLValueString($mes, "int"),
                        GetSQLValueString($anio, "int"));
$query_pron=mysqli_query($conecta1, $string_pron) or die (mysqli_error($conecta1));

require_once('funciones_comisiones.php');


echo $monto_proyectado=suma_proyeccion_agente($agente, $mes, $anio);




?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Detalle Certeza</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Loading Flat UI -->
    <link href="select3/dist/css/flat-ui.css" rel="stylesheet">
 
    <link href="select2/gh-pages.css" rel="stylesheet">
    <link href="select2/select2.css" rel="stylesheet">
      
      
    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="../../dist/js/vendor/html5shiv.js"></script>
      <script src="../../dist/js/vendor/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
      <div class="container">   
      
          <p>Detalle Certeza por Producto</p>
       
          
           <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                         
                           <th>Clave</th>
                           <th>Producto</th>
                           <th>Proyectado</th>
                           <th>Venta</th>
                           <th>Cto Proy</th>
                           <th>ABS</th>
                           <th>%Certeza</th>
                           <th>Comp %Proy</th>
                            <th>%Accuaracy</th>
                        
                           
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php 
                       $suma_comi=0;
                       $accuracy_suma=0;
                       while ($rowp = mysqli_fetch_array($query_pron)) { 
                          
                            //Consultar si se tiene captura la cartera del mes correspondiente
                                           
                              $venta=ventaxprod($agente, $mes, $anio, $rowp['cve_prod']); 
                              $diferencia=abs($rowp['cantidad']-$venta);
                              $certeza=1-($diferencia/$rowp['cantidad']);
                              if ($certeza<0){
                                  $certeza=0;
                              }
                           
                              $comp_costo=$rowp['monto_costo']/$monto_proyectado;
                              $accuracy=$certeza*$comp_costo;
                              $accuracy_suma=$accuracy_suma+$accuracy;
                           ?>
                       <tr>
                          
                           <td><?php echo $rowp['cve_prod']; ?></td>
                           <td></td>
                           <td><?php echo $rowp['cantidad']; ?></td>
                         
                            <td><?php echo  number_format($venta, 2, '.', ',') ; ?></td>
                           <td><?php echo  number_format($rowp['monto_costo'], 2, '.', ','); ?></td>
                           <td><?php echo  number_format($diferencia, 2, '.', ','); ?></td>
                           <td><?php echo  number_format($certeza*100, 2, '.', ','); ?></td>
                           <td><?php echo  number_format($comp_costo*100, 2, '.', ','); ?></td>  
                           <td><?php echo  number_format($accuracy*100, 2, '.', ','); ?></td>  
                               
                           
                           
                       </tr>
                           
                        <?php     }  ?>
                       <tr>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                            <td><?php echo  number_format($accuracy_suma*100, 2, '.', ','); ?></td>  
                       </tr>
                     
                   </tbody>    
                   
               </table>
           </div>      
          
          
          
      <?php  
          
      
      
      
          
          
          
          
          
          
         ?>  
      
        </div> <!-- /.Canvas -->
      

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="select3/dist/js/vendor/jquery.min.js"></script>      
    <script src="select3/dist/js/flat-ui.min.js"></script>        
    <script src="select3/assets/js/application.js"></script>
    
    
    <script src="select2/buscar-cool.js"></script>   
    <script type="text/javascript" src="select2/jquery.min.1.10.2.js"></script>
    <script src="select2/select2.js"></script>   
    <!--<script src="select2/jasny-bootstrap.min.js"></script>-->
  </body>
</html>      