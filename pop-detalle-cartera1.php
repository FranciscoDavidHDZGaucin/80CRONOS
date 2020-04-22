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

///Sumar toda la cartera
$string_carteratot=sprintf("SELECT sum(saldo) as saldo FROM saldos_facturas where slpcode=%s and mes_corte=%s and anio_corte=%s",
                 GetSQLValueString($agente, "int"),
                 GetSQLValueString($mes, "int"),
                 GetSQLValueString($anio, "int"));
$query_carteratot=mysqli_query($conecta1, $string_carteratot) or die (mysqli_error($conecta1));
$datos_carteratot=  mysqli_fetch_assoc($query_carteratot);
$cartera_total=$datos_carteratot['saldo'];

///Sumar toda la cartera > a  30 días
$string_carteraage=sprintf("SELECT sum(saldo) as saldo FROM saldos_facturas where dv>30 and slpcode=%s and mes_corte=%s and anio_corte=%s",
                 GetSQLValueString($agente, "int"),
                 GetSQLValueString($mes, "int"),
                 GetSQLValueString($anio, "int"));
$query_carteraage=mysqli_query($conecta1, $string_carteraage) or die (mysqli_error($conecta1));
$datos_carteraage=  mysqli_fetch_assoc($query_carteraage);
$cartera_agente=$datos_carteraage['saldo'];


//Consultar si se tiene captura la cartera del mes correspondiente
$string_cartera=sprintf("SELECT * FROM saldos_facturas where slpcode=%s and mes_corte=%s and anio_corte=%s",
                 GetSQLValueString($agente, "int"),
                 GetSQLValueString($mes, "int"),
                 GetSQLValueString($anio, "int"));
$query_cartera=mysqli_query($conecta1, $string_cartera) or die (mysqli_error($conecta1));

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
    <title>Detalle Cartera Vencida</title>
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
      
          <h5>Detalle Cartera Vencida</h5>
          <p>Cartera Total: <?php echo number_format($cartera_total, 2, '.', ',');?></p>
          <p>Cartera >30 días: <?php echo  number_format($cartera_agente, 2, '.', ',');?></p>
          <p>% Vencido:  <?php echo number_format(($cartera_agente/$cartera_total)*100, 2, '.', ',');?></p>
       
          
           <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>Factura</th> 
                           <th>Cliente</th>
                           <th>Moneda</th>
                           <th>$Monto</th>
                           <th>$Saldo</th>
                           <th>Vencimiento</th>
                           <th>Días Vencidos</th>
                           
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php 
                       $suma_comi=0;
                       while ($rowl = mysqli_fetch_array($query_cartera)) { 
                           $suma_comi=$suma_comi+$rowl['comision'];
                           
                        ?>
                       <tr>
                           <td><?php echo $rowl['docnum']; ?></td>
                           <td><?php echo $rowl['cardname']; ?></td>
                           <td><?php echo $rowl['doccur']; ?></td>
                           <td><?php echo $rowl['doctotal']; ?></td>
                           <td><?php echo $rowl['saldo']; ?></td>
                           <td><?php echo $rowl['docduedate']; ?></td>
                           <td><?php echo $rowl['dv']; ?></td>
                               
                           
                           
                       </tr>
                           
                        <?php     }  ?>
                      
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