<?php

//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   
 require_once('conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");    
 
 if (isset($_REQUEST['cardcode'])){
    $cliente=$_POST['cardcode'];
    $tasa1=$_POST['ti_normal'];
    $tasa=($_POST['ti_normal'])/100;
    $string_edo=sprintf("SELECT * FROM saldos_facturas WHERE CardCode=%s order by DocNum",
                GetSQLValueString($cliente,"text"));
    
   $estadocta=mssql_query($string_edo);
    
}
 
 $suma1=0;
 $suma2=0;
 $suma3=0;
 
$stringsql="Select CardCode, CardName FROM OCRD Where CardType='C' order by CardName";
$querycte=mssql_query($stringsql);


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

      <link href="select2/bootstrap.min.css" rel="stylesheet">   
        <link href="select2/jasny-bootstrap.min.css" rel="stylesheet">   
     <link href="select2/gh-pages.css" rel="stylesheet">
      <link href="select2/select2.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="select2/navmenu-reveal.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navmenu navmenu-default navmenu-fixed-left">
      <a class="navmenu-brand" href="menu.php">Plataforma Pedidos</a>
      <ul class="nav navmenu-nav">
           <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Pedidos <b class="caret"></b></a>
          <ul class="dropdown-menu navmenu-nav">
            <li><a href="#">Precalificacion</a></li>
            <li><a href="#">Generar Pedido</a></li>
             <li><a href="#">Consultar Pedido</a></li>
           </ul>
        </li>
        <li><a href="clientes.php">Existencias</a></li>
        
     
      </ul>
       <ul class="nav navmenu-nav">
      <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Reclamaciones <b class="caret"></b></a>
          <ul class="dropdown-menu navmenu-nav">
            <li><a href="#">Reclamaciones Producto</a></li>
            <li><a href="#">Reportes Producto/Empaque Pedido</a></li>
              <li class="divider"></li>
             <li><a href="#">Reclamaciones Entrega</a></li>
            <li><a href="#">Reportes Entrega/Servicio</a></li>
           </ul>
        </li>
     
        <li><a href="#">Guía Visitas</a></li>
        <li><a href="#">Link</a></li>
      
        <li><a href="#">Cerrar Sesión</a></li>
       
      </ul>
    </div>

    <div class="canvas">
      <div class="navbar navbar-default navbar-fixed-top">
        <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-recalc="false" data-target=".navmenu" data-canvas=".canvas">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

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
