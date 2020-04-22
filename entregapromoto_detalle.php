<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : entregapromoto_detalle.php
 	Fecha  Creacion : 20/09/2016
	Descripcion  : 
	 Copia  archivo   entregapromoto_detalle.php   parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
*/
///***Inicio Checamos que el  Usuario  siga  Logeado  
session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}   
///***Fin  Checamos que el  Usuario  siga  Logeado
///****Agregamos librerias
require_once('formato_datos.php');
require_once('Connections/conecta1.php');  
///***Conexion  sap
require_once('conexion_sap/sap.php');
///**Uso de  la Base  de Datos
///mssql_select_db("AGROVERSA"); 
///****
$historial=sprintf("SELECT * FROM historial_e WHERE folio=%s",
 GetSQLValueString($_REQUEST['dato_id'], "int"));

$resconsulta = mysqli_query($conecta1, $historial) or die (mysqli_error($conecta1));

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
      <!--*****Inicio  Codigo Copia---->
      <legend> Historial de comentarios </legend>
                    <table  rules="all" border="1">
                <tr>
                 <th >Fecha</th>
                 <th >Comentario</th>
                </tr>
                <?php WHILE ($registro1=  mysqli_fetch_array($resconsulta)){  ?>
                    <tr>
                        <td><?php echo $registro1['fecha'];?></td>
                      <td><?php echo $registro1['comentario'];?></td>
                        </tr>
    
                    <?php } ?>
            </table>
      <!--*****FIN  Codigo Copia---->
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