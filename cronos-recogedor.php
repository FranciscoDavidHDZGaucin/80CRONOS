<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
//  require_once('formato_datos.php');
   require_once('funciones.php');
   require_once('Connections/conecta1.php');
//require_once('conexion_sap/sap.php');
//mssql_select_db("AGROVERSA");    
 
/*
//Conexion PDO para que funcione la funcion avisovta
function dbConnect (){
    $conn = null;
    $host = 'localhost';
    $db =   'pedidos';
    $user = 'root';
    $pwd =  'avsa0543';
    try {
        $conn = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pwd);
        //echo 'Connected succesfully.<br>';
    }
    catch (PDOException $e) {
        echo '<p>Cannot connect to database !!</p>';
        exit;
    }
    return $conn;
 }
 */
 
 
 
 
$remision = $_REQUEST['remision'];
$agente = $_REQUEST['agente'];
$cliente = $_REQUEST['cliente'];


$string_productos =sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s",
                         GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));          
$sql_consulta=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));


//Datos Pedido encabezado
$string_encabezado=  sprintf("select * from encabeza_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
                      GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));     
$query_encabezado=mysqli_query($conecta1, $string_encabezado) or die (mysqli_error($conecta1));
$encabezado=  mysqli_fetch_assoc($query_encabezado);


///Suma del Pedido

$consulta_sql=sprintf("Select  sum(total_prod) as subtotal from detalle_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
               GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));   

$resultado2=mysqli_query($conecta1, $consulta_sql) or die (mysqli_error($conecta1));
$suma_pedidos=mysqli_fetch_assoc($resultado2);


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
     // echo agente_mail($encabezado['n_agente']);
       list($calle,$colonia,$ciudad,$cp,$estado,$pais)=dir_entregas($id_entrega);
            //echo "El formulario ha enviado variables.<br>"; 
            //echo "Id Movimiento: '".$_REQUEST["con_consecutivo"]."'"; 
            print'<p>Cliente: '.$encabezado['nom_cte'].'  </p> ';
            print'<p>Fecha: '.$encabezado['fecha_alta'].'  </p> ';
            print'<p>Plazo: '.$encabezado['plazo'].' dias </p> ';
            print'<p>Agente: '.$encabezado['nom_age'].' </p> ';
            print'<p>Comentario: '.$encabezado['observacion'].' </p> ';
            print'<p>MÃ©todo de Pago: '.$encabezado['medio_pago'].' </p> ';
            print'<p>Cuenta: '.$encabezado['cuenta'].' </p> ';
            print'<p>Destino:  ';
            echo $calle.', '.$colonia.','.$ciudad.', '.$cp.', '.$estado.', '.$pais."</p>";
            print'<p>Observaciones: '.$encabezado['observacion'].' </p> ';
            print'<p>Total RemisiÃ³n: $'.$suma_pedidos['subtotal'].' </p> ';

      ?> 
          
          
           <div class="table-responsive">
               <table  class="table table-bordered">
                   <thead>
                       <tr>
                           <th>Codigo</th>
                           <th>Producto</th>
                           <th>Cantidad</th>
                            <th>Pendiente</th>
                           <th>Precio</th>
                           <th>Dcto%</th>
                           <th>IEPS%</th>
                          
                           <th>IVA%</th>
                           <th>Importe</th>
                      
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php while ($rowl = mysqli_fetch_array($sql_consulta)) {
                           
                           
                           
                           ?>
                            <tr>                         
                                <td><?php echo $rowl['cve_prod']; ?></td> 
                                <td><?php echo $rowl['nom_prod']; ?></td> 
                                <td><?php echo $rowl['cant_prod']; ?></td> 
                                 <td><?php echo $rowl['cant_falta']; ?></td> 
                                <td><?php echo $rowl['precio_prod']; ?></td>
                                <td><?php echo $rowl['dcto_prod']; ?></td> 
                                <td><?php echo $rowl['ieps']; ?></td> 
                                 <td><?php echo $rowl['iva']; ?></td>
                                <td><?php echo $rowl['total_prod']; ?></td> 
                                <?php
                         switch ($rowl['estatus']){
                         case "E":
                                print '<td><img src="iconos/emitida.PNG" title="Emitida" /> </td>';
                                break;
                         case "C":
                                print '<td><img src="iconos/cancelada.PNG" title="Cancelada" /> </td>';
                                break;
                         case "P":
                                print '<td><img src="iconos/parcial.PNG"/ title="Facturada Parcial"> </td>';
                                break;
                         case "F":
                                print '<td><img src="iconos/facturada.PNG" title="Facturada" /> </td>';
                                break;
                         case "A":
                                print '<td><img src="iconos/autorizar.PNG" title="Por Autorizar" /> </td>';
                                break;
                        case "NA":
                                print '<td><img src="iconos/na.PNG" title="NO Autorizado" /> </td>';
                                break;	
                                }
                         ?>
                                
                           </tr>
                           
                        <?php     }  ?>
                   </tbody>    
                   
               </table>
           </div>      
          
          
          <?php   
               ///Codigo para revisar las Entregas realizadas a esta remision
          #string
            $string_entrega=sprintf("SELECT distinct(entrega) as entrega FROM logistica_entregas WHERE n_remision=%s and n_agente=%s and cve_cte=%s",
                            GetSQLValueString($remision,"int"),
                            GetSQLValueString($agente,"int"),
                            GetSQLValueString($cliente,"text"));
              $conn = dbConnect();
                  // Create the query
              $sql =$string_entrega;
                  // Create the query and asign the result to a variable call $result
              $result = $conn->query($sql);
                  // Extract the values from $result
              $rows = $result->fetchAll();

              foreach ($rows as $row) { 
                  
     
                  ?><div class="panel panel-success">
                       <div class="panel-heading"><?php 
                       
                       
                       $string_findfol=sprintf("SELECT * FROM envios_detalle_envios1 where factura=%s and entrega_final=1 limit 1",
                                       GetSQLValueString($row['entrega'],"int"));
                      
                       $query_findfol=mysqli_query($conecta1, $string_findfol) or die (mysqli_error($conecta1));
                       $datos_findfol=mysqli_fetch_assoc($query_findfol);
                       if (is_null($datos_findfol['empresa'])){
                           echo "Entrega#".$row['entrega']." Sin Transporte Asignado";
                       }else{
                            echo "Entrega#".$row['entrega']." Transporte: ".$datos_findfol['empresa']."<br> Fecha Salida:".$datos_findfol['fecha_salida']." ,Fecha Promesa Entrega:".$datos_findfol['fecha_promesa'];
                       }
                           
                       ?></div>
                       <div class="panel-body">
                     <div class="table-responsive">
                         <table  class="table table-bordered">
                           <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                   
                                    <th>Precio</th>
                                    <th>Dcto%</th>
                                    <th>IEPS%</th>

                                    <th>IVA%</th>
                                    <th>Importe</th>
                                    <th>Factura</th>

                                </tr>
                       
                             </thead>
                             <tbody>
                                 <?php
                                 $string_deliver=sprintf("SELECT * FROM logistica_entregas WHERE entrega=%s",
                                                 GetSQLValueString($row['entrega'],"int"));
                                 $sql_deliver=mysqli_query($conecta1, $string_deliver) or die (mysqli_error($conecta1));
                                 
                                  while ($rowe = mysqli_fetch_array($sql_deliver)) {
                                 
                                 ?>
                                 <tr>
                                     <td><?php echo $rowe['cve_prod']; ?></td> 
                                     <td><?php echo $rowe['nom_prod']; ?></td> 
                                     <td><?php echo $rowe['cant_prod']; ?></td> 
                                     
                                     <td><?php echo $rowe['precio_prod']; ?></td>
                                     <td><?php echo $rowe['dcto_prod']; ?></td> 
                                     <td><?php echo $rowe['ieps']; ?></td> 
                                     <td><?php echo $rowe['iva']; ?></td>
                                     <td><?php echo $rowe['total_prod']; ?></td> 
                                     <td><?php echo $rowe['n_factura']; ?></td>
                                   
                                 </tr>

                                  <?php  } ?>
                                 
                                 
                             </tbody> 
                             
                             
                             
                         </table> 
                     </div>
                  </div>
                  <div class="panel-footer">.</div>      
             </div>       
           <?php } ?>
                     
    
          <h5>HISTORIAL DE LA REMISION</h5>
               <table  class="table table-bordered">
                   <thead>
                       <tr>
                            <th>Remision</th>
                           <th>Agente</th>
                           <th>Cliente</th>
                           <th>Fecha</th>
                           <th>Producto</th>
                           <th>Precio</th>
                           <th>PrecioOb</th>
                          
                           <th>Cantidad</th>
                           <th>Dato</th>
                           <th>Dato2</th>
                      
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php 
                        $query1=sprintf("select * from historia_estatus where  n_remision=%s and n_agente=%s and cve_cte=%s  order by fecha_horaauto",
                                    GetSQLValueString($remision, 'int'),
                                    GetSQLValueString($agente, 'int'),
                                    GetSQLValueString($cliente, 'text'));   
                                                     

                       
                      	
                      $query_historia=mysqli_query($conecta1, $query1) or die (mysqli_error($conecta1)); 
                       
                       
                       
                       while ($rowh = mysqli_fetch_array($query_historia)) {
                           
                           
                           
                           ?>
                            <tr>                         
                                <td><?php echo $rowh['n_remision']; ?></td> 
                                <td><?php echo $rowh['nom_age']; ?></td> 
                                <td><?php echo $rowh['nom_cte']; ?></td> 
                                <td><?php echo $rowh['fecha_horaauto']; ?></td>
                               <td  title="<?php echo $rowh['cve_prod']; ?>"><?php echo $rowh['nom_prod']; ?></td>
                                <td><?php echo $rowh['estatus_a']; ?></td> 
                                 <td><?php echo $rowh['estatus_b']; ?></td>
                                <td><?php echo $rowh['comentario']; ?></td> 
                                 <td><?php echo $rowh['comentario1']; ?></td> 
                                  <td><?php echo $rowh['comentario2']; ?></td> 
                              
                                
                           </tr>
                           
                        <?php     }  ?>
                   </tbody>    
                   
               </table>
          
      
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