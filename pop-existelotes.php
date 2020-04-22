<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}



require_once('formato_datos.php');
require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");    
require_once('Connections/conecta1.php');
$producto=$_REQUEST['cve_prod'];
$almacen=$_REQUEST['almacen'];
$nombreprod =$_REQUEST['nombreprod'];
mysqli_select_db($conecta1, $database_conecta1);


//Buscar los lotes del producto en el almacen especificado

 $string_lotes =sprintf("SELECT ItemCode,ItemName, BatchNum, Quantity FROM OIBT WHERE ItemCode=%s and WhsCode=%s and Quantity>0",
                         GetSQLValueString($producto, 'text'),
                        GetSQLValueString($almacen, 'text'));
$query_lotes= mssql_query($string_lotes);




       $productos_string=sprintf("SELECT  cant_prod,entrega from logistica_entregas where cve_prod=%s and whscode=%s and isnull(n_factura)",
                                      GetSQLValueString($_GET['cve_prod'], "text"),
                                      GetSQLValueString($_GET['almacen'], "text"));
        $resQery_Exitencia=mysqli_query($conecta1, $productos_string) or die (mysqli_error($conecta1));
        /// $resfECHQ   =mysqli_fetch_array($resQery_Exitencia);



        






 





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
    <title>Existencia x Lotes</title>
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
      
          <p>Producto:<?php echo " ".$producto."-".$nombreprod;?></p>
          <p>Almacén:<?php echo " ".$almacen; ?></p>
          
           <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>Lote</th>
                           <th>Cantidad</th>
                           <th>Fecha Creación</th>
                           <th>Días</th>
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php while ($rowl = mssql_fetch_array($query_lotes)) {  ?>
                            <tr bgcolor="<?php 
                                            list($ndias,$flote)=ndias($rowl['BatchNum']);                                
                                            echo color($ndias);                                 
                                ?>"> 
                                <td><?php echo $rowl['BatchNum'];   ?></td>
                           
                                <td><?php echo $rowl['Quantity']; ?></td>
                                <td><?php  
                                             list($ndias,$flote)=ndias($rowl['BatchNum']);                                                                            
                                             echo $flote->format('Y-m-d');  
                                ?></td>
 
                               
                                <td><?php 
                                         list($ndias,$flote)=ndias($rowl['BatchNum']);
                                         echo $ndias; ?></td> 
                           </tr>
                           
                        <?php     }  ?>
                   </tbody>    
                   
               </table>
           </div>   




<!--


            <div class="container">   
      
         
           <div class="table-responsive">

                       <?php 

                       $numcol  =  mysqli_num_rows($resQery_Exitencia); 
                       if($numcol!=0)
                       { ?> 

               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>Cantidad Producto</th> 
                           <th>Numero Pedido </th>                           
                       </tr>
                       
                   </thead>
                   <tbody>

                      <?php
                        while ($rowl1 = mysqli_fetch_array($resQery_Exitencia )) { 

                        ?>




                           
                                <td><?php echo $rowl1['cant_prod'];   ?></td>

                           
                                <td><?php echo $rowl1['entrega']; ?></tr>
                               
                           
                        <?php     } } ?>
                   </tbody>    
                   
               </table>
           </div>       
          -->
          
          
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