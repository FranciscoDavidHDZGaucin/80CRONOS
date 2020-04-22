<?php
require_once('header.php');
//require_once('Connections/conecta1.php');

  require_once('formato_datos.php');
 //mysqli_select_db($conecta1, $database_conecta1);
   require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");    
 
$idagente = $_SESSION["usuario_agente"];
$nombreagen = $_SESSION["usuario_nombre"];
$zona = $_SESSION["Zona"];

$querylista10="SELECT * FROM plataformaproductosl10";
$resultadolista10 = mssql_query($querylista10);

$querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s",
GetSQLValueString($idagente, "int"));
 
$cliente = mssql_query($querycliente);



$_SESSION['foliorep'] = $_REQUEST['folio'];
$tarifaglobal=$_SESSION['tarifa_global'];
$folio = $_SESSION['foliorep'];



$querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s",
GetSQLValueString($idagente, "int"));
 
$cliente = mssql_query($querycliente);

$nombre=$_SESSION["cliente"];
$servdist= $_SESSION["servdist"];



$_SESSION['fechainicio'] = $_POST['fechainicio'];
$_SESSION['fechafin'] = $_POST['fechafin'];

$_SESSION['plazoespecial']  = $_POST['plazoespecial'];
 

 
$codigo = $nombre;
    
    $querydatos = sprintf("Select * FROM clientes_cronos WHERE CardCode = %s",
    GetSQLValueString($codigo, "text"));
    $clientedatos = mssql_query($querydatos);
    
    $datoscliente=  mssql_fetch_array($clientedatos);
     
     $saldo = $datoscliente['Balance'];
     $dias = $datoscliente['ExtraDays'];
     $limite = $datoscliente['CreditLine'];
     $mail = $datoscliente['E_Mail'];
 
 $moneda=1;
  ///Buscar el producto y obtener Precio IVA e IEPS tambien se contempla el tipo de moneda
 
 IF (isset($_REQUEST['eliminar'])){
    
    $_SESSION['fechainicio'] = $_REQUEST['fechainicio'];
    $_SESSION['fechafin'] = $_REQUEST['fechafin'];
     
     $_SESSION['foliorep'] = $_REQUEST['folio'];
     
     $eliminar_registro2= sprintf("DELETE FROM detalle_convenio WHERE id_detalle=%s",
            GetSQLValueString($_REQUEST['eliminar'], "int"));
      $eliminar2= mysqli_query($conecta1, $eliminar_registro2) or die (mysqli_error($conecta1));
  
  }
  
  
  
 if ($_REQUEST['producto']!=""){
     
     
     
     if ($moneda==1) {
            ///Obtener el listado general de los productos, considerar la moneda elegida que en este caso es MXP
            $string_prod=sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode=%s and Currency='MXP' ORDER BY ItemName",
                GetSQLValueString($_REQUEST['producto'], "text"));
           // echo $string_prod;
            $query_prod = mssql_query($string_prod);
           $datos_prod=mssql_fetch_assoc($query_prod);
           
           //lista4
            $string_prod4=sprintf("SELECT * FROM plataformaproductosl4 WHERE ItemCode=%s and Currency='MXP' ORDER BY ItemName",
                GetSQLValueString($_REQUEST['producto'], "text"));
           // echo $string_prod;
            $query_prod4 = mssql_query($string_prod4);
           $datos_prod4=mssql_fetch_assoc($query_prod4);
           
           
           //lista5
            $string_prod5=sprintf("SELECT * FROM plataformaproductosl5 WHERE ItemCode=%s and Currency='MXP' ORDER BY ItemName",
                GetSQLValueString($_REQUEST['producto'], "text"));
           // echo $string_prod;
            $query_prod5 = mssql_query($string_prod5);
           $datos_prod5=mssql_fetch_assoc($query_prod5);
           
           
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
$bonitabla = mssql_query($string_listadoprod);



  IF (isset($_POST['agregar'])){
      
$clienteconvenio = $_POST['cliente'];
      
      
$querycliente2=sprintf("SELECT * FROM clientes_cronos WHERE CardCode=%s",
GetSQLValueString($clienteconvenio, "text"));
$cliente2 = mssql_query($querycliente2);
$fetchcliente = mssql_fetch_array($cliente2);

$clientenombre = $fetchcliente['CardName'];
      
  
      $cveprod=$_POST['producto'];
      
      
      $string_nombre=  sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode =%s",
      GetSQLValueString($cveprod, "text"));
      $querynombre = mssql_query($string_nombre);
      $fetchnombre=mssql_fetch_assoc($querynombre);
      
      
      $buscamp = sprintf("SELECT * FROM costos WHERE cve_articulos=%s",
                GetSQLValueString($cveprod, "text"));
      $querymp = mysqli_query($conecta1, $buscamp) or die (mysqli_error($conecta1));
    $fetchmp = mysqli_fetch_assoc($querymp);
    $boni_costomp=$fetchmp['costo'];
      
      $nombreprod=$fetchnombre['ItemName'];
      $unidad=$fetchnombre['U_UnidadMedida'];
      $factordist = $fetchnombre['SalFactor4'];
      $cantidad = $_POST['volumen'];
      $precioprod=$_POST['precio'];
      $dctoprod=$_POST['descuento'];
      $ieps=$_POST['ieps'];
      $iva = $_POST['iva'];
      $totalprod = $_POST['subtotal'];  //aqui se cambio, anted decía total
      $moneda = $_SESSION["moneda"];
      $preciorep = $_POST['preciosub'];
      $preciosin=$_POST['totalsin'];
      
       $bonificacion = $_POST['bonificacion'];
       $preciofactura=$_POST['preciofactura'];
       $totalfactura=$_POST['totalfactura'];
       $boni_precioporunidad = $_POST['boniprecio'];
        $boni_cantidadporunidad = $_POST['bonicantidad'];
        $boni_productoid = $_POST['boniprodcuto'];
        $boni_precioventa=$_POST['boniprecio2'];
        $boni_cantidadcalculo=$_POST['bonicantidad2'];
        
        
        
        //para buscar el costo mp 2
        $buscamp2 = sprintf("SELECT * FROM costos WHERE cve_articulos=%s",
                GetSQLValueString($boni_productoid, "text"));
      $querymp2 = mysqli_query($conecta1, $buscamp2) or die (mysqli_error($conecta1));
    $fetchmp2 = mysqli_fetch_assoc($querymp2);
    $boni_costomp2=$fetchmp2['costo'];
        
       
        if($bonificacion!=1){
        $bonificacion = 0;
        $boni_precioporunidad = 0;
        $boni_cantidadporunidad = 0;
        $boni_productoid = 0;
        $boni_precioventa= 0;
        $boni_cantidadcalculo= 0;
        $boni_costomp2=0;
        }
       
      $estatus='A';
      
      
      $autorizar = 0;
      
       if( $iva>0 ){
          $iva100 = $iva/100;
      } else {
          $iva100 =.0;
      }
     
      $insertproducto = sprintf("INSERT INTO detalle_pedido SET n_remision=%s, n_agente=%s, nom_age=%s, fecha_alta=CURDATE(), "
              . "cve_cte=%s, nom_cte=%s, cve_prod=%s, nom_prod=%s, cant_prod=%s, precio_prod=%s, dcto_prod=%s, ieps=%s, iva=%s, "
              . "total_prod=%s, moneda_prod=%s, litkg_unidad=%s, fact_ds=%s, precio_representante=%s,au_gerente=%s,au_dc=%s,au_an=%s, estatus=%s,"
              . "precio_pagar=%s, precio_factura=%s, total_factura=%s,"
              . "boni_precioporunidad=%s, boni_cantidadporunidad=%s,boni_productoid=%s, boni_precioventa=%s, boni_cantidadcalculo=%s, "
              . "boni_estado=%s, boni_costomp=%s, boni_bonificadomp=%s, bandera_especial=1",
      GetSQLValueString($_SESSION['foliorep'], "int"),
      GetSQLValueString($idagente, "int"),
      GetSQLValueString($nombreagen, "text"),
      GetSQLValueString($clienteconvenio, "text"),
      GetSQLValueString($clientenombre, "text"),
      GetSQLValueString($cveprod, "text"),
      GetSQLValueString($nombreprod, "text"),
      GetSQLValueString($cantidad, "double"),
      GetSQLValueString($precioprod, "double"),
      GetSQLValueString($dctoprod, "double"),
      GetSQLValueString($ieps, "double"),
      GetSQLValueString($iva100, "double"),
      GetSQLValueString($totalprod, "double"),
      GetSQLValueString($moneda, "int"),
      GetSQLValueString($unidad, "text"),
      GetSQLValueString($factordist, "double"),
      GetSQLValueString($preciorep, "double"),
      GetSQLValueString($autorizar, "int"),
      GetSQLValueString($autorizar, "int"),
      GetSQLValueString($autorizar, "int"),
              
              GetSQLValueString($estatus, "text"),
               GetSQLValueString($preciosin, "double"),
              GetSQLValueString($preciofactura, "double"),
              GetSQLValueString($totalfactura, "double"),
              
              GetSQLValueString($boni_precioporunidad, "double"),
              GetSQLValueString($boni_cantidadporunidad, "double"),
              GetSQLValueString($boni_productoid, "text"),
              GetSQLValueString($boni_precioventa, "double"),
              GetSQLValueString($boni_cantidadcalculo, "double"),
              GetSQLValueString($bonificacion, "int"),
              GetSQLValueString($boni_costomp, "double"),
              GetSQLValueString($boni_costomp2, "double"));
           
   $queryinsertprod=mysqli_query($conecta1, $insertproducto) or die (mysqli_error($conecta1));
       

 }
 
  IF (isset($_POST['guardar'])){
      
$clienteconvenio2 = $_POST['cliente'];
           
$querycliente3=sprintf("SELECT * FROM clientes_cronos WHERE CardCode=%s",
GetSQLValueString($clienteconvenio2, "text"));
$cliente3 = mssql_query($querycliente3);
$fetchcliente2 = mssql_fetch_array($cliente3);


$fechainicio = $_POST['fechainicio'];
$fechafin = $_POST['fechafin'];

$clientenombre2 = $fetchcliente2['CardName'];
      
      
      $totalprod2 = $_POST["totaltodo"];
      $servdist = $_POST['totalservdist'];
      
      $plazoespecial = $_POST['plazoespecial'];
      
      $observacion = $_POST['observacion'];
      
      $status='A';
      
      
      $insertencabeza = sprintf("INSERT INTO encabeza_pedido SET n_remision = %s, fecha_alta=CURDATE(),"
              . "cve_cte=%s, nom_cte=%s,estatus=%s, n_agente=%s, observacion=%s, moneda=%s, plazo=%s, tipo_venta=%s,"
              . "total=%s,nom_age=%s,fact_ds=%s, encplazo_especial=%s ",
              GetSQLValueString($_SESSION['foliorep'], "int"),
              GetSQLValueString($clienteconvenio2, "text"),
              GetSQLValueString($clientenombre2, "text"),
              GetSQLValueString($status, "text"),
              GetSQLValueString($idagente, "int"),
              GetSQLValueString($observacion, "text"),
              GetSQLValueString($moneda, "int"),
              GetSQLValueString($plazo, "int"),
              GetSQLValueString($tipoventa, "int"),
              GetSQLValueString($totalprod2, "double"),
              GetSQLValueString($nombreagen, "text"),
              GetSQLValueString($servdist, "double"),
              GetSQLValueString($plazoespecial, "int") );
      
      $queryinsertencabeza=mysqli_query($conecta1, $insertencabeza) or die (mysqli_error($conecta1));
      
       $MM_restrictGoTo = "index.php";
  header("Location: ". $MM_restrictGoTo); 
      
      
  }
 $stringtabla = sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s ",
GetSQLValueString($folio = $_SESSION['foliorep'], "int"));
 
$tablaquery=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));

$tablaservicio=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));
$tablaservicio2=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));
$tablaservicio3=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));
 

 
 
$queryinsert = sprintf("SELECT * FROM detalle_pedido WHERE n = %s",
GetSQLValueString($codigo, "text"));
$facturadatos = mssql_query($queryinsert);


 

?> 
 <!-- script para calcular automáticamente si el precio es menor a algún precio de lista -->
<script type="text/javascript">
    
function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function calculate() {

var descuento = document.forma1.descuento.value;
var preciopublico = document.forma1.preciosub.value;
var precio = document.forma1.precio.value;
var subtotal = document.forma1.subtotal.value;
var total = document.forma1.total.value;
var ieps = document.forma1.ieps.value;
var volumen = document.forma1.volumen.value;
var iva = document.forma1.iva.value;
var cantidad;
var sub_ieps;
var sub_iva;
var precio4 = document.forma1.precio4.value;
var precio5 = document.forma1.precio5.value;
var subtotal_sin;
var sub_iepssin;
var sub_ivasin;
var sub_totalsin;
var preciofactura;
var totalfactura;
var subtotalsin;
var resultadopreciopagar;


//descuento
var calculo = (preciopublico*100)/precio;
calculo = 100-calculo;
var resultado = calculo;
resultado = resultado.toFixed(3);

if(ieps!==0){
    ieps=(ieps/100);
   
}
if(iva!==0){
    iva=(iva/100);
    
}

//precioobjetivo
var precioobj;
precioobj= precio*(1-(resultado/100));

//subtotal
subtotal=preciopublico*volumen;
sub_ieps=(subtotal)*ieps;
sub_iva=((subtotal)+sub_ieps)*iva;

total=subtotal+sub_ieps+sub_iva;


//total sin volumen
subtotalsin= parseFloat(preciopublico);
sub_iepssin=(subtotalsin)*ieps;
sub_ivasin=(subtotalsin)*iva;
sub_totalsin = (sub_ivasin + sub_iepssin) + subtotalsin;


preciofactura= sub_totalsin/(0.94);

totalfactura=preciofactura*volumen;
total=subtotal+sub_ieps+sub_iva;


//total=total/1000

var resultadototal = total.toFixed(2);


resultado = parseFloat(resultado).toFixed(2);
subtotal = parseFloat(subtotal).toFixed(2);
resultadototal = parseFloat(resultadototal).toFixed(2);
sub_totalsin=parseFloat(sub_totalsin).toFixed(2);
preciofactura=parseFloat(preciofactura).toFixed(2);
totalfactura=parseFloat(totalfactura).toFixed(2);




document.forma1.descuento.value = resultado;
document.forma1.subtotal.value = subtotal;
document.forma1.total.value = resultadototal;
document.forma1.totalsin.value=sub_totalsin;
document.forma1.preciofactura.value=preciofactura;
document.forma1.totalfactura.value=totalfactura;


document.forma1.subtotal2.value = addCommas(subtotal);
document.forma1.totalsin2.value=addCommas(sub_totalsin);
document.forma1.preciofactura2.value=addCommas(preciofactura);
document.forma1.totalfactura2.value=addCommas(totalfactura);



//if(resultado < precio4) {
//    alert("Requiere autorización");
//}

}

function mostrar(bonificacion){
document.getElementById(bonificacion).style.display= "inline";
}
function ocultar(bonificacion){
document.getElementById(bonificacion).style.display= "none";
}
function inicial(){
	if (document.getElementById('checkbox1').checked)
	{
        mostrar('bonificacion') ;
	} else {
            ocultar('bonificacion');
        }
      
	
}

function bonicalculate() {
    
    var precioproducto = document.forma1.preciosub.value;
    var cantidadproducto = document.forma1.volumen.value;
    var boniprecio = document.forma1.boniprecio.value;
    var bonicantidad= document.forma1.bonicantidad.value;
    var boniprecio2 = document.forma1.boniprecio2.value;
//    
//    
    var cantidad;
    
    
    if(boniprecio!==""){
    
    
        cantidad = (boniprecio*cantidadproducto)/boniprecio2;
        
    }
    
    if(bonicantidad!==""){
       cantidad = (bonicantidad*cantidadproducto) ;
    }
        
        
        document.forma1.bonicantidad2.value=cantidad; 
       
     
}


</script>

<div class="container">
     <form name="forma1" method="POST" action="pedidos_especiales_representantes.php?folio=<?php echo$_SESSION['foliorep'];?>">
    <div class="row">
        
        <h3>Folio: <?php echo $_SESSION['foliorep']; ?></h3>
    
       <div class="col-md-12">
           <br>
           
           <h2>Pedido especial</h2>
           
      
           <label>Días de crédito</label>
           <input type="text" name="plazoespecial" value="<?php echo $_SESSION['plazoespecial'];?>">
             <p>Cliente: </p>
             <div class="input-group input-group select2-bootstrap-prepend">
                 <span class="input-group-btn">
                     <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                         <span class="glyphicon glyphicon-search"></span>
                     </button>
                 </span>
                 <select name="cliente" class="form-control select2" id="cliente" onchange="this.form.submit()">
                     <option>Cliente</option>  
                     <?php
                     while ($row = mssql_fetch_array($cliente)) {
                         if ($row['CardCode'] == $_REQUEST['cliente']) {

                             echo '<option selected value="' . $row['CardCode'] . '">' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                         } else {
                             echo '<option value="' . $row['CardCode'] . '">' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                         }
                     }
                     ?>
                 </select>
             </div>
         </div>
        
        
    </div>


   

        <div class="col-md-12">
            <label for="productos" class="control-label">Productos</label>   
            <div class="input-group input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                <select name="producto" class="form-control select2" id="producto"  onchange="this.form.submit()" >
                    <option>Producto</option>
                    <?php
                    while ($row = mssql_fetch_array($tabla)) {
                        if ($row['ItemCode'] == $_REQUEST['producto']) {

                            echo '<option selected value="' . $row['ItemCode'] . '">' . $row['ItemName'] . '-' . $row['ItemCode'] . '</option>';
                        } else {
                            echo '<option value="' . $row['ItemCode'] . '">' . $row['ItemName'] . '-' . $row['ItemCode'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>  
            <br>
        </div>
         <div class="container">
            <div class="row">
             <div class="col-lg-2">
                 <label>Volumen:</label>
                 <input name="volumen" id="volumen" type="text" class="form-control" >

             </div>

             <div class="col-lg-2">
                 <label>Precio:</label>  
                 <input name="preciosub" id="preciosub" type="text" class="form-control"  onchange="calculate();" >

             </div>
       </div>
            <div class="row">
             <div class="col-lg-2">   
                 <label>
                     <input type="checkbox" value="1" id="checkbox1" data-toggle="checkbox" name="bonificacion"   onChange="inicial()">
                     Bonificación
                 </label>
             </div>
            
             <div class="col-lg-8" id="bonificacion" style="display:none;">    
                 <div class="col-lg-4" >
                     <label>Bon. por $:</label>
                     <input name="boniprecio" id="boniprecio" type="text" class="form-control" >

                 </div>
                 <div class="col-lg-4">
                     <label>Bon. por Q:</label>  
                     <input name="bonicantidad" id="bonicantidad" type="text" class="form-control">

                 </div>
             <div class="col-lg-12">
              <select name="boniprodcuto" id="boniproducto" class="form-control select2" id="boniproducto" >
                    <option>Producto</option>
                    <?php
                    while ($row = mssql_fetch_array($bonitabla)) {
                        if ($row['ItemCode'] == $_REQUEST['producto']) {

                            echo '<option selected value="' . $row['ItemCode'] . '">' . $row['ItemName'] . '-' . $row['ItemCode'] . '</option>';
                        } else {
                            echo '<option value="' . $row['ItemCode'] . '">' . $row['ItemName'] . '-' . $row['ItemCode'] . '</option>';
                        }
                    }
                    ?>
                </select>
             </div>
             <div class="col-lg-4">
                 <label>Precio Bonificación:</label>  
                 <input name="boniprecio2" id="boniprecio2" type="text" class="form-control"  onChange="bonicalculate()" >

             </div>
                 
                 <div class="col-lg-4">  
                     <table >
                         <thead>
                             <tr>
                                 <th>Cantidad</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr>
                                 <td><input type="text" id="bonicantidad2" name="bonicantidad2" class="form-control" readonly ></td>           
                             </tr>
                         </tbody>    

                     </table>
                 </div>
               
                 
             </div>
                
             </div>



         </div>
     
        <p>
            
        </p>
        <br>
          
        
        <div class="col-md-8">
 
            <table >
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Precio</th>
                        <th>IEPS</th>
                        <th>IVA</th>
                        <th>Importe</th>
                        <th>Precio Pagar</th>
                        <th>Precio Factura</th>
                        <th>Total Factura</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" id="clave" name="clave"  value="<?php echo $datos_prod['ItemCode'] ?>" class="form-control" readonly placeholder="Codigo"> <input type="hidden" id="nombre_prod" name="nombre_prod" value="<?php echo $datos_prod['ItemName'] ?>"></td>
                        <td><input  style="text-align: right" type="text" id="precio" name="precio" value="<?php echo $datos_prod['Price'] ?>" class="form-control" readonly></td>
                        <td ><input  style="text-align: right" type="text" id="ieps" name="ieps" value="<?php if($datos_ieps['Rate']==""){echo 0;}else{echo $datos_ieps['Rate'];} ?>" class="form-control" readonly ></td>
                        <td ><input  style="text-align: right" type="text" id="iva" name="iva" value="<?php if($datos_iva['Rate']==""){echo 0;}else{echo $datos_iva['Rate'];} ?>" class="form-control" readonly ></td> 
                        <td style="display:none;"><<input type="text" id="descuento" name="descuento" class="form-control" readonly ></td> 
                       
                        <td style="display:none;"><input type="hidden" id="subtotal" name="subtotal" class="form-control" readonly ></td> 
                        <td><input  style="text-align: right" type="text" id="subtotal2" name="subtotal2" class="form-control" readonly ></td> 
                        
                        <td style="display:none;"><input type="text" id="total" name="total" class="form-control" readonly ></td> 
                        
<!-- ////////////////////////////////calculos de precio pagar y factura    -->
                        <td style="display:none;" ><input type="hidden" id="totalsin" name="totalsin" class="form-control" readonly ></td>
                        <td ><input  style="text-align: right" type="text" id="totalsin2" name="totalsin2" class="form-control" readonly ></td> 
                        
                        
                        <td  style="display:none;"><input type="hidden" id="preciofactura" name="preciofactura" class="form-control" readonly ></td> 
                         <td ><input  style="text-align: right" type="text" id="preciofactura2" name="preciofactura2" class="form-control" readonly ></td> 
                         
                         
                        <td style="display:none;" ><input type="hidden" id="totalfactura" name="totalfactura" class="form-control" readonly ></td> 
                        <td ><input  style="text-align: right" type="text" id="totalfactura2" name="totalfactura2" class="form-control" readonly ></td>
                        
                        
                        

                        
                <input type="hidden" id="precio4" name="precio4" value="<?php echo $datos_prod4['Price'] ?>" class="form-control"  readonly >
                <input type="hidden" id="precio5" name="precio5" value="<?php echo $datos_prod5['Price'] ?>" class="form-control"  readonly >

                </tr>

                </tbody>    

            </table>

        </div>
        <br>
         <p>
                     <button name="agregar" type="submit" class="btn btn-lg btn-primary pull-right">Agregar</button>
 <div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                <tr>
                     <th>Código</th>
                     <th>Producto</th>
                     <th>Cantidad</th>
<!--                     <th>Precio</th>-->
                     <th>Precio Remisión </th>
                     <th>Dcto %</th>
                     <th>IEPS</th>
                     <th>IVA</th>
                     <th>Precio Pagar</th>
                     <th>Precio Factura</th>
                     <th>Total Factura</th>
                     <th>Venta Total</th>
                     <th>Eliminar</th>
            

                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mysqli_fetch_array($tablaquery)){  ?>
                 <tr>
                     <td><?php echo $registro1['cve_prod'];?></td>  
                     <td><?php echo $registro1['nom_prod'];?></td> 
                     <td><?php echo floor($registro1['cant_prod']);?></td> 
                     <!--<td><?php //echo '$'.$registro1['precio_prod'];?></td>--> 
                     <td><?php echo '$'.number_format(ceil($registro1['precio_representante']));?></td>
                     <td><?php echo number_format((float)$registro1['dcto_prod'], 2, '.', '');?></td> 
                     <td><?php echo floor($registro1['ieps']).'%';?></td> 
                     <td><?php echo ($registro1['iva']*100).'%';?></td>
                     <td><?php echo '$'.number_format(ceil($registro1['precio_pagar']));?></td>
                     <td><?php echo '$'.number_format(ceil($registro1['precio_factura']));?></td>
                     <td><?php echo '$'.number_format(ceil($registro1['total_factura']));?></td>
                     <td><?php echo '$'.number_format(floor($registro1['total_prod']));?></td> 
                     <td><a href="convenios_representantes.php?eliminar=<?php echo $registro1['id_detalle']; ?>&folio=<?php echo $_SESSION['foliorep']; ?>&fechainicio=<?php echo $_REQUEST['fechainicio']; ?>&fechafin=<?php echo $_POST['fechafin']; ?>&cliente=<?php echo $_POST['cliente']; ?>"  onclick="return confirm('¿Está Seguro de Eliminar?')"><img src="images/eliminar.png"/></a></td>
             
                
                     
                     
                 </tr>
                  <?php 
                           
                           IF ($registro1['boni_estado']==1){
                               
                               $string_nombreboni=  sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode =%s",
                               GetSQLValueString($registro1['boni_productoid'], "text"));
                               
                               $querynombreboni = mssql_query($string_nombreboni);
                               $fetchnombreboni=mssql_fetch_assoc($querynombreboni);
                               
                               $nombreprodboni=$fetchnombreboni['ItemName'];
                           
                           ?>
                           <tr bgcolor="#DBF0F4">
                               <td>Producto Bonificado</td>
                               <td><?php echo $nombreprodboni ; ?></td> 
                               <td>Cantidad</td>
                               <td><?php echo number_format(ceil($registro1['boni_cantidadcalculo'])); ?></td>
                           </tr>
                           
                           <?php   }
                             ?>
                 
                   <?php 
                   $total = $total +$registro1['total_prod'];
                   
                   
                   
                 } ?>
                 <tr>
                     <td>Total: <?php echo '$'.number_format(ceil($total));?> </td>  
                      <input type="hidden" name="totaltodo" value="<?php echo $total ?>" class="form-control"  readonly >

                     
                 </tr>
             </tbody>


         </table>
     
     
         
             </div>
                 </p>
        
         
        
    
    <br>
    
    <div class= " col-lg-6" style="display: none;">
        
       
       
         
            
      <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Servicio de Dist</th>
                     <th>KL/LT</th>
                     <th>Tarifa</th>
                     <th>Sub_total</th>
                     <th>IVA</th>
                     <th>Total</th>

            

                 </tr>
             </thead>
             <tbody>
               
                 <?php 
         
                 WHILE ($fila= mysqli_fetch_array($tablaservicio2)){
                     

                 IF($fila['litkg_unidad']=='KILOS'){
                     
                     $calcfactkilos = $fila['cant_prod'] * $fila['fact_ds'];
                     $sumakilos += $calcfactkilos;
                 }else{
                     $calcfactlitros = $fila['cant_prod'] * $fila['fact_ds'];
                     $sumalitros += $calcfactlitros;
                 }
                     ?>
                          
                 
             
                 
               <?php  }
               
               
               $subkilos = $sumakilos*$tarifaglobal;
               $sublitros = $sumalitros*$tarifaglobal;
               
               $ivakilos = $subkilos * 1.16;
               $ivalitros = $sublitros*1.16;
               
               $ivatotalsd=$subkilos+$sublitros;
               $ivarepresentar = $ivatotalsd * .16;
               
               $totalsd = $ivakilos + $ivalitros;
               ?>
                <tr>
                      <td>Kilos</td> 
                     <td><?php echo number_format(floor($sumakilos));?></td> 
                     <td><?php echo number_format(floor($tarifaglobal)); ?></td>    
                      <td><?php echo number_format(floor($subkilos)); ?></td>  
                      <td>16%</td> 
                      <td><?php echo number_format(floor($ivakilos)); ?></td> 
                </tr>
                <tr>
                      <td>Litros</td> 
                     <td><?php echo number_format(floor($sumalitros));?></td> 
                     <td><?php echo number_format(floor($tarifaglobal)); ?></td> 
                     <td><?php echo number_format(floor($sublitros)); ?></td>  
                     <td>16%</td> 
                     <td><?php echo number_format(floor($ivalitros)); ?></td> 
                </tr>
             </tbody>


         </table>
        
    </div>
    
    <div class=" col-lg-6">
        
        <table  class="table table-responsive table-hover" hidden >
             <thead>
                 <tr>
                     <th>Leyenda</th>
                     <th>Cantidad</th>
                 </tr>
             </thead>
             <tbody>
                 <?php 
        
                 WHILE ($filaimporte= mysqli_fetch_array($tablaservicio3)){  
                 
               
                 switch($filaimporte['ieps']){
                     case 0:
                         $calculoieps0=0;
                         $ieps = 0;
                         break;
                     case 6:
                         $calculoieps6=.06;
                         $subieps6 = $calculoieps6 * $filaimporte['precio_representante'] * $filaimporte['cant_prod'];
                         $calculoiepss6 += $subieps6;
                         $ieps=$subieps6;
                         break;
                     case 7:
                         $calculoieps7=.07;
                         $subieps7 = $calculoieps7 * $filaimporte['precio_representante'] * $filaimporte['cant_prod'];
                         $calculoiepss7 += $subieps7;
                         $ieps=$subieps7;
                         
                         break;
                     case 9:
                         $calculoieps9=.09;
                         $subieps9 = $calculoieps9 * $filaimporte['precio_representante'] * $filaimporte['cant_prod'];
                         $calculoiepss9 += $subieps9;
                         $ieps=$subieps9;
                         break;
                     
                     
                 }
                 
                 $acumulado+=$ieps;
                   
                   
                   
                   
                  $calculosubtotal = $filaimporte['cant_prod'] * $filaimporte['precio_prod'];
                   
                  $subtotalimporte += $calculosubtotal;
                  
                  $calculodescuento = $filaimporte['cant_prod'] * $filaimporte['precio_prod'] * ($filaimporte['dcto_prod'] / 100);
                  $totaldescuento += $calculodescuento;
                  
                  $caliva = (($filaimporte['precio_representante'] * $filaimporte['cant_prod'])+$ieps) * ($filaimporte['iva'] / 100);
                  $totaliva +=  $caliva;
                 } 
                 ?>
                     <tr>
                     <td>Total</td>
                     <td><?php echo number_format(floor($subtotalimporte));  ?></td>
                     </tr>
                    
                     

                 
                     
                     
                 
             </tbody>

<input type="hidden" name="totalservdist" value="<?php echo $totalsd ?>" class="form-control"  readonly >

         </table>
        
    </div>
    
    <div class=" col-lg-8" >
   
        
        <p>
            <label for="comment">Comentario:</label>
  <textarea name="observacion" class="form-control" rows="5" id="comment"></textarea>
        </p>
    </div>
    <div class=" col-lg-12" >
        <p>
            <button name="guardar" type="submit" class="btn btn-lg btn-primary pull-right" onClick="alert('Pedido Especial Guardado')">Guardar Convenio</button>

        </p>
    </div>
     </form>

</div><!-- /.container -->
  
 <?php require_once('foot.php');?>     