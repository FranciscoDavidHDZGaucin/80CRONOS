<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
require_once('formato_datos.php');
require_once('Connections/conecta1.php');  
require_once('funciones_comisiones.php');  

require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");

$agente=$_REQUEST['agente'];
$mes=$_REQUEST['mes'];
$anio=$_REQUEST['anio'];
$gte=$_REQUEST['cve_gte'];


//Buscar los lotes del producto en el almacen especificado

//Consultar si se tiene captura la cartera del mes correspondiente
$string_productos=sprintf("SELECT * FROM ventas where agente2=%s and month(falta_fac2)=%s and year(falta_fac2)=%s",
                 GetSQLValueString($agente, "int"),
                 GetSQLValueString($mes, "int"),
                 GetSQLValueString($anio, "int"));
$query_productos=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));

function color ($dias){
     if($dias>=0 && $dias<=90){
        $ncolor= '#3f88c5';}
        else if ($dias>90 && $dias<=366){
          $ncolor= '#00b34d';
        }
        else if ($dias>366 && $dias<=546){
          $ncolor= '#e5e517';
        }
        else if ($dias>546){
          $ncolor= '#e50000';
        }
    
    return $ncolor;
    
}
function ndias($lote){
       $anio= substr($lote, 0, -6);
    $aniocompleto = '20'.$anio;
    $mes = substr($lote, 2, -4);
    $dia = substr($lote, 4, -2);
    $creacion = $aniocompleto.'-'.$mes.'-'.$dia;

    $format = 'Y-m-d';
    $date = DateTime::createFromFormat($format, $creacion);
    $today = date("Y-m-d"); 


    $date1=date_create($date->format('Y-m-d'));
    $date2=date_create($today);
    $diff=date_diff($date1,$date2);

    
    //return $today;
     return array($diff->format("%a"),$date);
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Detalle Comisión Producto</title>
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
      
          <p>Detalle Comisión por Producto</p>
       
          
           <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>Folio</th> 
                           <th>Clave</th>
                           <th>Producto</th>
                           <th>Cantidad</th>
                           <th>Total$</th>
                           <th>%Comisión</th>
                           <th>$Comisión</th>
                           <th>CostoMP</th>
                           <th>CMG%</th>
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php 
                       $suma_comi=0;
                       while ($rowl = mysqli_fetch_array($query_productos)) { 
                           $suma_comi=$suma_comi+$rowl['comision'];
                           $campo=  campo_comi($gte);
                           $costomp=$rowl['costo']*$rowl['tot_cant'];
                           $costo_mppor=($rowl['tot_linea']-$costomp)/$rowl['tot_linea'];
                           
                           $articulo=$rowl['codigo2'];
                                        $fin = sprintf(" FROM OITM Where ItemCode = %s ",
                                                     GetSQLValueString($articulo,"text"));                           
                                       $string="SELECT ".$campo.$fin;
                                       $query_mssql=mssql_query($string);
                                       $datos_mmsql=  mssql_fetch_assoc($query_mssql); 
                                       $clave_tabulador=$datos_mmsql[$campo];
                           
                           
                           ?>
                       <tr <?php  if ($clave_tabulador==0){ echo "class='danger'";   } ?>>
                           <td><?php echo $rowl['n_doc2']; ?></td>
                           <td><?php echo $rowl['codigo2']; ?></td>
                           <td><?php echo $rowl['desc2']; ?></td>
                           <td><?php echo $rowl['tot_cant']; ?></td>
                           <td><?php echo $rowl['tot_linea']; ?></td>
                           <td><?php if ($clave_tabulador==0){ echo "S/P"; }else {   echo $rowl['porce_comi']; }    ?></td>
                           <td><?php echo $rowl['comision']; ?></td>
                           <td><?php echo $costomp; ?></td>
                            <td><?php  echo number_format($costo_mppor*100, 2, '.', ','); ?> </td>
                           
                           
                       </tr>
                           
                        <?php     }  ?>
                       <tr>
                           <td colspan="7"> <?php echo number_format($suma_comi, 2, '.', ',')?></td>
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