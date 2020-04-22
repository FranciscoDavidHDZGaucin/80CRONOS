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
 mssql_select_db("AGROVERSA");    
 

$remision = $_REQUEST['remision'];


$string_productos =sprintf("SELECT * FROM detalle_convenio WHERE n_remision=%s",
                         GetSQLValueString($remision, 'int'));
$queryremisiones=mysqli_query($conecta1, $string_productos) or die (mysqli_error($conecta1));

$listaprecios;
$listacomercial = "plataformaproductosl8";
$numerogerente = $_SESSION["usuario_rol"];



if($numerogerente=="3"){
    $listaprecios = "plataformaproductosl4";
} else if ($numerogerente=="1"){
    $listaprecios = "plataformaproductosl5";
} else if ($zona=="6"){
    $listaprecios = "plataformaproductosl6";
} else if ($zona==""){
    
} else if ($zona=="2"){
    $listaprecios = "plataformaproductosl3";    
} else if ($zona=="10"){
    $listaprecios = "plataformaproductosl8";    
}

$querylista="SELECT * FROM ".$listaprecios;
$resultadolista = mssql_query($querylista);


$string_encabeza = sprintf("SELECT * FROM encabeza_convenio WHERE n_remision=%s",
      GetSQLValueString($remision, 'int'));
$queryencabeza=mysqli_query($conecta1, $string_encabeza) or die (mysqli_error($conecta1));
$fetchremisionencabeza = mysqli_fetch_assoc($queryencabeza);

$comentario = $fetchremisionencabeza['observacion'];
$porcentaje = $fetchremisionencabeza['por_regreso'];

$num_convenio=$fetchremisionencabeza['n_remision'];
$nombre_cliente=$fetchremisionencabeza['nom_cte'];
$ntipo=$fetchremisionencabeza['tipo_convenio'];

function tipo_convenio($ntipo){
    
    switch ($ntipo) {
        case 0:
                $leyenda="Convenio";
        

            break;

        case 1:
                $leyenda="Lista de Precios";
        

            break;
    }
    return $leyenda;
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
    <!--JQuery jquery.min.js" -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script> 
  </head>
  <script>
      $(document).ready(function(){
          
          ///**** Mostramos Modal  
          $('#btn_cancel').click(function(){
               
               if (confirm('Esta Seguro de Cancelar el  Convenio')) {
                    // Save it!
                    //    $("#resultado").text("Correcto");
                    ///****Realizamos la peticion 
                    
            console.log ($('#remision').val());       
                  
                 $.ajax({
                      type:'POST',
                      url: 'convenios_cancelacion.php',
                      data: {REMI:$('#remision').val() },
                      
                  }); 
                } else {
                    // Do nothing!
                      
                }
          });
           ///******* $('#btn_save').click(function(){     
          
          
      });
  </script> 
 
  <body>
      <div class="container">   
          <h5>Convenio#:<?php echo $num_convenio;   ?></h5>
          <h5>Tipo:<?php echo tipo_convenio($ntipo);   ?></h5>
          <h5>Cliente:<?php echo utf8_encode($nombre_cliente);   ?></h5>
          
          <input id="remision"  type="hidden" <?php  echo  "value='".$remision."'" ?>>  
            <div class="table-responsive">
               <table  class="table table-responsive">
                   <thead>
                       <tr>
                           <th>Codigo</th> 
                           <th>Producto</th>
                           <th>Cantidad</th>
                           <th>Precio</th>
                         
                           <th>Total</th>
                           <th>CMP</th>
                           <th>Costo Actual</th> 
                           <th>Costo Total</th>
                           <th>CMG</th>
                           <th>Porcentaje</th>
                      
                           
                       </tr>
                       
                   </thead>
                   <tbody>
                       <?php while ($rowl = mysqli_fetch_array($queryremisiones)) {
                           
                           
                           $totalimporte = $rowl['total_prod']+$totalimporte;
                           $totalcostomp = $rowl['boni_costomp']+ $totalcostomp;
                           ?>
                            <tr>  
                                <td><?php echo $rowl['cve_prod']; ?></td>
                                <td><?php echo $rowl['nom_prod']; ?></td> 
                                <td><?php echo number_format(ceil($rowl['cant_prod'])); ?></td> 
                                <td><?php echo '$'.number_format($rowl['precio_representante'], 2, '.', ','); ?></td> 
                                
                                <td><?php echo '$'.number_format(ceil($rowl['total_prod'])); ?></td> 
                                <td><?php echo '$'.$rowl['boni_costomp']; ?></td> 
                                <!--Costo Actual --> 
                                <td><?php  
                                     $string_costo_actual = sprintf("select  costo from  pedidos.costos  where   cve_articulos =%s ",
                                                            GetSQLValueString($rowl['cve_prod'],"text"));
                                     $qery = mysqli_query($conecta1, $string_costo_actual);
                                     $fetch_costo_alctual = mysqli_fetch_array($qery);
                                     echo  '$'. number_format($fetch_costo_alctual['costo'],2,'.',',');
                                ?></td>
                                <!-------->
                                <td>
                                    
                                    <?php 
                                    
                                    $costototal=$rowl['boni_costomp']*$rowl['cant_prod'];
                                    echo '$'.number_format(ceil($costototal));
                                    
                                    $sumacostototal = $costototal +$sumacostototal;
                                    
                                    ?>
                                    
                                </td>
                                 <td>
                                    
                                    <?php 
                                    
                                    $CMG=$rowl['total_prod']-$costototal;
                                    echo '$'.number_format(ceil($CMG));
                                    
                                    $sumacmg = $CMG + $sumacmg;
                                    
                                    
                                    
                                    ?>
                                    
                                </td>
                                 <td>
                                    
                                    <?php 
                                    
                                        $bonicostototal2 = $rowl['boni_bonificadomp']*$rowl['boni_cantidadcalculo'];
                                    
                                    $porcentajedc=($CMG+$bonicostototal2)/$rowl['total_prod'];
                                    
                                    $porcentajedc2=$porcentajedc*100;
                                    echo number_format(ceil($porcentajedc2)).'%';
                                    
                                    ?>
                                    
                                </td>
                                  <td>
                                      
                                  </td>
                               
                           </tr>
                           <?php 
                           
                           IF ($rowl['boni_estado']==1){
                               
                               $string_nombreboni=  sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode =%s",
                               GetSQLValueString($rowl['boni_productoid'], "text"));
                               
                               $querynombreboni = mssql_query($string_nombreboni);
                               $fetchnombreboni=mssql_fetch_assoc($querynombreboni);
                               
                               $nombreprodboni=$fetchnombreboni['ItemName'];
                           
                           ?>
                           <tr bgcolor="#DBF0F4">
                               
                               <td><?php echo $nombreprodboni; ?></td> 
                              
                               <td><?php echo number_format(ceil($rowl['boni_cantidadcalculo'])); ?></td>
                               <td><?php echo '$'.number_format(ceil($rowl['boni_precioventa'])); ?></td>
                               <td><?php echo '$'.'0'; ?></td>
                               
                               <td><?php echo '$'.number_format(ceil($rowl['boni_bonificadomp'])); ?></td>
                               
                                <td><?php 
                                
                                $bonicostototal = $rowl['boni_bonificadomp']*$rowl['boni_cantidadcalculo'];
                                
                                echo '$'.number_format(ceil($bonicostototal));
                                
                                
                                ?></td>
                                
                                
                                  <td><?php 
                                
                                $CMGboni = $bonicostototal;
                                
                                echo '$'.'-'.number_format(ceil($CMGboni));
                                
                                $totalcmgboni= $CMGboni+$totalcmgboni;
                                
                                
                                ?></td>
                                
                        
                                  
                           </tr>
                           
                                
                           
                           <?php   }
                           }  ?>
                           
                           <tr>
                               <td>                                  
                               </td>
                               <td>                                   
                               </td>
                               <td>                                   
                               Totales
                               </td>
                              <!--///////////totales/////////////////////// -->
                               <td>
                                <?php 
                                echo '$'.number_format(ceil($totalimporte));
                                ?>
                               </td>
                               <td>
                                <?php
                                 echo '$'.number_format(ceil($totalcostomp));
                                ?>
                               </td>
                               <td>
                                    <?php
                                      echo '$'.number_format(ceil($sumacostototal));
                                    
                                    ?> 
                               </td>
                               <td>
                                    <?php
                                    
                                    $restaconsuma = $sumacmg - $totalcmgboni;
                                       echo '$'.number_format(ceil($restaconsuma));
                                    ?>
                               </td>
                               
                                <td>
                                    <?php
                                    $sumaconboni= $restaconsuma+$CMGboni;
                                    
                                    $porcentajeultimo = ($sumaconboni /$totalimporte);
                                    
                                    $totalporcentaje = $porcentajeultimo*100;
                                    
                                    echo number_format($totalporcentaje).'%';
                                       
                                    
                                    ?>
                               </td>
                              
                               
                           </tr>
                           
                   </tbody>    
                   
               </table>
           </div>      
          
          
            
            <div class=" col-lg-8" >
        <label for="comment">% de nota de crédito si se cumple en tiempo:</label>

                <select disabled  name="por_regreso" >
                    <option  value="0">Elija</option>
                    <option <?php if($porcentaje==3){ echo "selected";}  ?> value="3">3%</option>
                    <option <?php if($porcentaje==6){ echo "selected";}  ?>  value="6">6%</option>
                </select>
        
        <p>
            <label for="comment">Comentario convenio:</label>
  <textarea name="observacion" class="form-control" rows="5" id="comment" readonly><?php echo $comentario; ?></textarea>
        </p>
        <div  class="row"> 
            <button id="btn_cancel" type="button" class="btn btn-danger"> Cancelar  Convenio</button> 
            <p id="resultado"> </p> 
        </div> 
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