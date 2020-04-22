<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
  require_once('formato_datos.php');
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
   require_once('funciones.php');
 mssql_select_db("AGROVERSA");    
 

$remision = $_REQUEST['remision'];
$agente = $_REQUEST['agente'];
$cliente = $_REQUEST['cliente'];


//Cancelar el pedido seleccionado
IF (isset($_POST['cancelar_pedido'])){ 
   $remision = $_REQUEST['remision'];
   $agente = $_REQUEST['agente'];
   $cliente = $_REQUEST['cliente'];
   $motivo=$_REQUEST['motivo'];
   $comentario=$_REQUEST['comentario'];
   $fecha_hoy=date("Y-m-d");
  
   
   //Datos Pedido encabezado
$string_encabezado=  sprintf("select * from encabeza_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
                      GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));     
$query_encabezado=mysqli_query($conecta1, $string_encabezado) or die (mysqli_error($conecta1));
$result0_datos=  mysqli_fetch_assoc($query_encabezado);



 //codigo escrito el 14-07-2015
$vbo_gestor=$result0_datos['vbo_gestor'];
$comentario_gestor=$result0_datos['comentario_gestor'];
$timeres_gestor=$result0_datos['timeres_gestor'];

$vbo_gerente=$result0_datos['vbo_gerente'];
$comentario_gerente=$result0_datos['comentario_gerente'];
$timeres_gerente=$result0_datos['timeres_gerente'];

$vbo_jefecyc=$result0_datos['vbo_jefecyc'];
$comentario_jefecyc=$result0_datos['comentario_jefecyc'];
$timeres_jefecyc=$result0_datos['timeres_jefecyc'];

$vbo_dircom=$result0_datos['vbo_dircom'];
$comentario_dircom=$result0_datos['comentario_dircom'];
$timeres_dircom=$result0_datos['timeres_dircom'];

$vbo_digral=$result0_datos['vbo_digral'];
$comentario_digral=$result0_datos['comentario_digral'];
$timeres_digral=$result0_datos['timeres_digral'];
///////////////  



	///Copiar lo borrado a la tabla borrados encabezado
 $insertSQL = sprintf("INSERT INTO borrados_encabeza (n_remision, n_agente, nom_age, fecha_alta, cve_cte, nom_cte, estatus,
    observacion, moneda, plazo, tipo_venta, total, motivo, fecha_cancela,vbo_gestor,comentario_gestor,timeres_gestor,vbo_gerente,comentario_gerente,timeres_gerente, vbo_jefecyc, comentario_jefecyc,timeres_jefecyc,
    vbo_dircom, comentario_dircom, timeres_dircom, vbo_dirgral,comentario_dirgral, timeres_dirgral, usuario_cancela, encbandera_especial)VALUES (%s,%s,%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
    GetSQLValueString($result0_datos['n_remision'], "int"),
    GetSQLValueString($result0_datos['n_agente'], "int"),
    GetSQLValueString($result0_datos['nom_age'], "text"),
    GetSQLValueString($result0_datos['fecha_alta'], "date"),
    GetSQLValueString($result0_datos['cve_cte'], "text"),
    GetSQLValueString($result0_datos['nom_cte'], "text"),
    GetSQLValueString($result0_datos['estatus'], "text"),
    GetSQLValueString($comentario, "text"),
    GetSQLValueString($result0_datos['moneda'], "int"),
    GetSQLValueString($result0_datos['plazo'], "int"),
    GetSQLValueString($result0_datos['tipo_venta'], "int"),
    GetSQLValueString($result0_datos['total'], "double"),
    GetSQLValueString($motivo, "text"),
    GetSQLValueString($fecha_hoy, "date"),

    GetSQLValueString($vbo_gestor, "int"),
    GetSQLValueString($comentario_gestor, "text"),
    GetSQLValueString($timeres_gestor, "date"),

    GetSQLValueString($vbo_gerente, "int"),
    GetSQLValueString($comentario_gerente, "text"),
    GetSQLValueString($timeres_gerente, "date"),

    GetSQLValueString($vbo_jefecyc, "int"),
    GetSQLValueString($comentario_jefecyc, "text"),
    GetSQLValueString($timeres_jefecyc, "date"),

    GetSQLValueString($vbo_dircom, "int"),
    GetSQLValueString($comentario_dircom, "text"),
    GetSQLValueString($timeres_dircom, "date"),

    GetSQLValueString($vbo_dirgral, "int"),
    GetSQLValueString($comentario_dirgral, "text"),
    GetSQLValueString($timeres_dirgral, "date"),
    GetSQLValueString($_SESSION["usuario_valido"], "text"),
    GetSQLValueString($result0_datos['encbandera_especial'], "int")    

    );
  @mysqli_query($conecta1, $insertSQL) or die (mysqli_error($conecta1)); 
//eliminar el registo de encabezado
 
 $deleteSQL=sprintf("DELETE FROM  encabeza_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
	   GetSQLValueString($remision, 'int'),
           GetSQLValueString($agente, 'int'),
           GetSQLValueString($cliente, 'text'));

@mysqli_query($conecta1, $deleteSQL) or die (mysqli_error($conecta1));

//revisar producto por producto
$string_productos =sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s and terminada=1",
                         GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));        

$result_detalle=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));


while ($rowp = mysqli_fetch_array($result_detalle)) {
     $insertSQL2 = sprintf("INSERT INTO borrados_detalle (n_remision, n_agente, nom_age, fecha_alta, cve_cte, nom_cte, cve_prod,
                  nom_prod, cant_prod, precio_prod, dcto_prod, total_prod, moneda_prod, cant_falta, autorizado,total_prodmxp,
                 tipo_cambio, precio_condcto, precio_politica, estatus, bonificacion)VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s)",
                   GetSQLValueString($rowp['n_remision'], "int"),
                   GetSQLValueString($rowp['n_agente'], "int"),
                   GetSQLValueString($rowp['nom_age'], "text"),
                   GetSQLValueString($rowp['fecha_alta'], "date"),
                   GetSQLValueString($rowp['cve_cte'], "text"),
                   GetSQLValueString($rowp['nom_cte'], "text"),
                   GetSQLValueString($rowp['cve_prod'], "text"),
                   GetSQLValueString($rowp['nom_prod'], "text"),
                   GetSQLValueString($rowp['cant_prod'], "double"),
                   GetSQLValueString($rowp['precio_prod'], "double"),
                   GetSQLValueString($rowp['dcto_prod'], "double"),
                   GetSQLValueString($rowp['total_prod'], "double"),
                   GetSQLValueString($rowp['moneda_prod'], "int"),
                   GetSQLValueString($rowp['cant_falta'], "double"),
                    GetSQLValueString($rowp['autorizado'], "int"),
                   GetSQLValueString($rowp['total_prodmxp'], "double"),
                   GetSQLValueString($rowp['tipo_cambio'], "double"),
                   GetSQLValueString($rowp['precio_condcto'], "double"),
                   GetSQLValueString($rowp['precio_politica'], "double"),
                   GetSQLValueString($rowp['estatus'], "text"),
                   GetSQLValueString($rowp['bonificacion'], "text"));
   @mysqli_query($conecta1, $insertSQL2) or die (mysqli_error($conecta1));
   
   
    
}
//elimina todos los productos de la remision
$deleteSQLprod=sprintf("DELETE FROM  detalle_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
	   GetSQLValueString($remision, 'int'),
           GetSQLValueString($agente, 'int'),
           GetSQLValueString($cliente, 'text'));

@mysqli_query($conecta1, $deleteSQLprod) or die (mysqli_error($conecta1));

//elimina el historial de la remision
$deleteSQLhistoria=sprintf("DELETE FROM  historia_estatus where n_remision=%s and n_agente=%s and cve_cte=%s",
	   GetSQLValueString($remision, 'int'),
           GetSQLValueString($agente, 'int'),
           GetSQLValueString($cliente, 'text'));

@mysqli_query($conecta1, $deleteSQLhistoria) or die (mysqli_error($conecta1));


folio_pedido_cancelar($remision);
echo '<h5>Remision Eliminada con éxito</h5>';
  
}



//Datos Pedido encabezado
$string_encabezado=  sprintf("select * from encabeza_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
                      GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));     
$query_encabezado=mysqli_query($conecta1, $string_encabezado) or die (mysqli_error($conecta1));
$encabezado=  mysqli_fetch_assoc($query_encabezado);


$string_productos =sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s and terminada=1",
                         GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));        

$queryremisiones=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));

///Suma del Pedido

$consulta_sql=sprintf("Select  sum(total_prod) as subtotal from detalle_pedido where n_remision=%s and n_agente=%s and cve_cte=%s",
               GetSQLValueString($remision, 'int'),
                         GetSQLValueString($agente, 'int'),
                         GetSQLValueString($cliente, 'text'));   

$resultado2=mysqli_query($conecta1, $consulta_sql) or die (mysqli_error($conecta1));
$suma_pedidos=mysqli_fetch_assoc($resultado2);


///varibla centinela que nos indica si se puede cancelar o modificar el pedido

//$centi1=  revisa_pedido_xprod_surtido($remision, $agente, $cliente);    //si el valor es 1 nos indica que no se ha facturado nada de los productos 0 si existe algo facturado


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
      <?php
         print'<p>Cliente: '.$encabezado['nom_cte'].'  </p> ';
            print'<p>Fecha: '.$encabezado['fecha_alta'].'  </p> ';
            print'<p>Plazo: '.$encabezado['plazo'].' dias </p> ';
           
            print'<p>Método de Pago: '.$encabezado['medio_pago'].' </p> ';
           
            print'<p>Observaciones: '.$encabezado['observacion'].' </p> ';
            print'<p>Total Remisión: $'.number_format($suma_pedidos['subtotal'], 2, '.', ',').' </p> ';
            
            print'<p>Comentario: '.$encabezado['observacion'].' </p> ';
            print'<p>Destino: '.$encabezado['destino'].' </p> ';
      
      
      ?>
          
          
           <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>Producto</th>
                           <th>Cantidad</th>
                           <th>$PLista</th>
                           <th>%Dcto</th>
                           <th>Total</th>
                           <th>Estatus</th>
                      
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php while ($rowl = mysqli_fetch_array($queryremisiones)) {
                           
                           
                           
                           ?>
                            <tr>                         
                                <td><?php echo $rowl['cve_prod'].'-'.$rowl['nom_prod']; ?></td> 
                                <td><?php echo $rowl['cant_prod']; ?></td> 
                                <td><?php echo $rowl['precio_prod']; ?></td> 
                                <td><?php echo number_format($rowl['dcto_prod'], 2, '.', ','); ?></td>
                                <td><?php echo '$'.number_format($rowl['total_prod'], 2, '.', ','); ?></td> 
                                <td><?php echo $rowl['estatus']; ?></td> 
                           </tr>
                           
                        <?php     }  ?>
                   </tbody>    
                   
               </table>
           </div>      
          
        
              <form method="POST" action="pedido_detalle_representante.php">
                  Motivo para Cancelar el Pedido
                  <select required name="motivo" id="motivo"   >
                        <option value="TECNICO">--TECNICO--</option>
                        <option value="EXISTENCIA">--EXISTENCIA--</option>
                        <option value="PRECIO">--PRECIO--</option>
                        <option value="CREDITO">--CREDITO--</option>
                        <option value="DOCUMENTO">--DOCUMENTO--</option>
                        <option value="CAPTURA">--CAPTURA--</option>
                </select>
                  <input type="text" required name="comentario" id="comentario"  size="50" /> 
                  <input type="submit" name="cancelar_pedido" value="Cancelar Pedido" onclick="return confirm('¿Esta Seguro de Cancelar el Pedido?');">
                  <input type="hidden" name="agente" id="agente" value="<?php echo $agente;  ?>" />
                  <input type="hidden" name="remision" id="remision" value="<?php echo $remision;  ?>" />
                  <input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente;  ?>" />
              </form>
            
          
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