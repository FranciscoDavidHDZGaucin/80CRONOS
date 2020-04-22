<?php

//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   
 require_once('conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");    
 
 
 $moneda=1;
  ///Buscar el producto y obtener Precio IVA e IEPS tambien se contempla el tipo de moneda
 if ($_REQUEST['producto']!=""){
     if ($moneda==1) {
            ///Obtener el listado general de los productos, considerar la moneda elegida que en este caso es MXP
            $string_prod=sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode=%s and Currency='MXP' ORDER BY ItemName",
                GetSQLValueString($_REQUEST['producto'], "text"));
           // echo $string_prod;
            $query_prod = mssql_query($string_prod);
           $datos_prod=mssql_fetch_assoc($query_prod);
    }else{
            $string_prod=sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode=%s and Currency='USD' ORDER BY ItemName",
                GetSQLValueString($_REQUEST['producto'], "text"));
            
           $query_prod = mssql_query($string_prod);
            $datos_prod=mssql_fetch_assoc($query_prod);
    }        
     //Obtener los impuestos aplicados al producto elegido
    //Primero obtenemos el IEPS si es que lo tiene
      $ieps=sprintf("SELECT * FROM plataformaieps WHERE ItemCode=%s",
                GetSQLValueString($_REQUEST['producto'], "text"));
       $quey_ieps = mssql_query($ieps);
       $datos_ieps=mssql_fetch_assoc($quey_ieps);
    
    //Segundo obtenemos el IVA si es que lo tiene
       $iva=sprintf("SELECT * FROM plataformaiva WHERE ItemCode=%s",
                GetSQLValueString($_REQUEST['producto'], "text"));
       $quey_iva = mssql_query($iva);
       $datos_iva=mssql_fetch_assoc($quey_iva);
    
     //  echo $iva;
       
       
       
       
       
 }else{
     if ($moneda==1) {
            ///Obtener el listado general de los productos, considerar la moneda elegida que en este caso es MXP
            $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' ORDER BY ItemName");
              
           // $query_prod = mssql_query($string_prod);
           // $datos_prod=mssql_fetch_assoc($query_prod);
    }else{
            $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='USD' ORDER BY ItemName");
              
            
        //    $quey_prod = mssql_query($string_prod);
        //    $datos_prod=mssql_fetch_assoc($query_prod);
    }        
 }
 
 
 
 
 
$string_listadoprod=("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' ORDER BY ItemName");
$tabla = mssql_query($string_listadoprod);


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
   

    <title>Cronos</title>

    <!-- Bootstrap core CSS -->
    <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Loading Flat UI -->
    <link href="select3/dist/css/flat-ui.css" rel="stylesheet">

    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">
    

      <!--<link href="select2/bootstrap.min.css" rel="stylesheet">-->   
        <!--<link href="select2/jasny-bootstrap.min.css" rel="stylesheet">-->   
     <link href="select2/gh-pages.css" rel="stylesheet">
      <link href="select2/select2.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!--<link href="select2/navmenu-reveal.css" rel="stylesheet">-->

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
   <div class="navbar navbar-inverse navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
          </button>
          <a class="navbar-brand" href="index.php">Sistema Cronos</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Inicio</a></li>          
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Precalificacion</a></li>
                <li><a href="productos.php">Generar Pedido</a></li>
                <li><a href="#">Consultar Pedido</a></li>
                <li class="divider"></li>
                <li><a href="existencias.php">Existencias</a></li>
              </ul>
            </li>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reclamaciones <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Reclamaciones Producto</a></li>
                <li><a href="#">Reportes Producto/Empaque Pedido</a></li>
                <li class="divider"></li>
                <li><a href="#">Reclamaciones Entrega</a></li>
                <li><a href="#">Reportes Entrega/Servicio</a></li>
              </ul>
            </li>
            <li><a href="#">Guía Visitas</a></li>
            <li><a href="#">Link</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Cerrar Sesión</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="canvas">
  

      <div class="container">
        <div class="page-header">
          <h1>Bienvenido</h1>
        </div>
          <form name="forma1" method="POST" action="clientes.php">
         <div class="col-md-8">
               <label for="productos" class="control-label">Productos</label>   
               <div class="input-group input-group select2-bootstrap-prepend">
                   <span class="input-group-btn">
                            <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                                    <span class="glyphicon glyphicon-search"></span>
                            </button>
                    </span>
                    <select name="producto" class="form-control select2" id="producto"  onchange="this.form.submit()" >
                       
                     <?php

                       while ($row=mssql_fetch_array($tabla))
                               
                         {
                             if ($row['ItemCode']==$_REQUEST['producto']){

                              echo '<option selected value="'.$row['ItemCode'].'">'.$row['ItemName'].'-'.$row['ItemCode'].'</option>';	
                             }else{
                                     echo '<option value="'.$row['ItemCode'].'">'.$row['ItemName'].'-'.$row['ItemCode'].'</option>';	
                             }	
                         }
                     ?>
                     </select>
               </div>  
               
              </div>
         <div class="col-md-4">
                  <table >
                      <thead>
                          <tr>
                              <th>Clave</th>
                              <th>Precio</th>
                              <th>IEPS</th>
                              <th>IVA</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                             <td><input type="text" id="clave" name="clave"  value="<?php echo $datos_prod['ItemCode'] ?>" class="form-control" readonly placeholder="Codigo"> <input type="hidden" id="nombre_prod" name="nombre_prod" value="<?php echo $datos_prod['ItemName'] ?>"></td>
                             <td><input type="text" id="precio" name="precio" value="<?php echo $datos_prod['Price'] ?>" class="form-control" readonly></td>
                             <td><input type="text" id="ieps" name="ieps" value="<?php echo $datos_ieps['Rate'] ?>" class="form-control" readonly ></td>
                             <td><input type="text" id="iva" name="iva" value="<?php echo $datos_iva['Rate'] ?>" class="form-control" readonly ></td> 
                               
                          </tr>
                          
                      </tbody>    
                      
                  </table>
                  
              </div>    
          </form>
      </div><!-- /.container -->
    </div>
      

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="select2/jquery.min.1.10.2.js"></script>
    <script src="select2/select2.js"></script>       

   <script src="select2/buscar-cool.js"></script>       
    
    <script src="select2/bootstrap.min.js"></script>
    <script src="select2/jasny-bootstrap.min.js"></script>
  </body>
</html>
