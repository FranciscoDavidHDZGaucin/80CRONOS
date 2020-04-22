<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
  require_once('formato_datos.php');
   require_once('funciones.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");    
 
 $remision = $_REQUEST['remision'];
   $agente = $_REQUEST['agente'];
   $cliente = $_REQUEST['cliente'];


$string_productos =sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s",
                         GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));  
$queryremisiones=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));




$total_pedido=  total_pedido($remision, $agente, $cliente);

//Datos Pedido encabezado
 $string_encabezado=  sprintf("select * from encabeza_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
                       GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));
$query_encabezado=mysqli_query($conecta1, $string_encabezado) or die (mysqli_error($conecta1));
$encabezado=  mysqli_fetch_assoc($query_encabezado);
$cliente=$encabezado['nom_cte'];


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Detalle Pedido</title>
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
       <?php
           $id_entrega=$encabezado['id_entregas'];
             list($calle,$colonia,$ciudad,$cp,$estado,$pais)=dir_entregas($id_entrega);
         print'<p>Cliente: '.$encabezado['nom_cte'].'  </p> ';
            print'<p>Fecha: '.$encabezado['fecha_alta'].'  </p> ';
            print'<p>Plazo: '.$encabezado['plazo'].' dias </p> ';
           
            print'<p>Método de Pago: '.$encabezado['medio_pago'].' </p> ';
           
            print'<p>Observaciones: '.$encabezado['observacion'].' </p> ';
            print'<p>Total Remisión: $'.$total_pedido.' </p> ';
            
            print'<p>Comentario: '.$encabezado['observacion'].' </p> ';
             print'<p>Destino:  ';
            echo $calle.', '.$colonia.','.$ciudad.', '.$cp.', '.$estado.', '.$pais."</p>";
      
      
      ?>
          
           <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>Producto</th>
                           <th>Cantidad</th>
                           <th>Precio</th>
                           <th>Dcto%</th>
                           <th>P Final</th>
                           <th>Total</th>
                           <th>Estatus</th>
                      
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php while ($rowl = mysqli_fetch_array($queryremisiones)) {
                           
                           
                           
                           ?>
                            <tr>                         
                                <td><?php echo $rowl['cve_prod'].','.$rowl['nom_prod']; ?></td> 
                                <td><?php echo $rowl['cant_prod']; ?></td> 
                                <td><?php echo '$'.number_format(ceil($rowl['precio_prod'])); ?></td> 
                                <td><?php echo  number_format($rowl['dcto_prod'], 2, '.', ',').'%'; ?></td>
                                 <td><?php echo  '$'.number_format($rowl['precio_condcto'], 2, '.', ','); ?></td>
                                   <td><?php echo '$'.number_format($rowl['total_prod'], 2, '.', ','); ?></td>
                           
                                <td><?php echo $rowl['estatus']; ?></td> 
                           </tr>
                           
                        <?php     }  ?>
                   </tbody>    
                   
               </table>
           </div>      
          
     
      
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