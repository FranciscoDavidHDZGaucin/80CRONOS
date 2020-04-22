<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
require_once('formato_datos.php');
require_once('conexion_sap/sap.php');
mssql_select_db("AGROVERSA");    

$factura=$_REQUEST['factura_sap'];



$string_productos =sprintf("SELECT ItemCode, Dscription, Quantity,LineTotal FROM cronos_factdetalle WHERE DocNum = %s",
                         GetSQLValueString($factura, 'text'));
$query_productos= mssql_query($string_productos);



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
      
          
          
           <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>ID</th>
                           <th>Nombre</th>
                           <th>Cantidad</th>
                           <th>Total</th>
                      
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php while ($rowl = mssql_fetch_array($query_productos)) {  ?>
                            <tr>                         
                                <td><?php echo $rowl['ItemCode']; ?></td> 
                                <td><?php echo $rowl['Dscription']; ?></td> 
                                <td><?php echo number_format($rowl['Quantity'], 2, '.', ','); ?></td> 
                                <td title="Sin impuestos"><?php echo '$'.number_format($rowl['LineTotal'], 2, '.', ','); ?></td> 
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