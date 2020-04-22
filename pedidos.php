<?php
require_once('header.php');
//require_once('Connections/conecta1.php');
    require_once('conexion_sap/sap.php');
    require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
    require_once('funciones.php');
   require_once('Connections/conecta1.php');

   require_once('_FILES_ORD/_OBJ_ORD.php');
/// mssql_select_db("AGROVERSA"); HOLA :3

define('MB', 1048576);
$folio = folio_pedido($_SESSION["usuario_agente"]);

$STRG_SELE_FILE  = sprintf("SELECT ID, REMISION,DOCUMENTO FROM pedidos.TB_LOG_CARGA_ORDC_PEDIDOS where REMISION =%s",
        GetSQLValueString($folio, "int")
    );
    $SERESULT =mysqli_query($conecta1,$STRG_SELE_FILE) ;

    $numero_filas = mysqli_num_rows($SERESULT);
    // echo $numero_filas;

    if($numero_filas > 0){
        $_SESSION["SIHAYARCHIVOS"] = "1";
    }else{
        $_SESSION["SIHAYARCHIVOS"] = "0";
    }

$_SESSION["pruebaFiles"] = $_FILES ;



$idagente = $_SESSION["usuario_agente"];

$querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s OR U_agente2=%s",
GetSQLValueString($idagente, "int"),
        GetSQLValueString($idagente, "int"));

$cliente = mssql_query($querycliente);




$string_estados=("select * from estados_mexico order by nom_ent");
$sql_estados=mysqli_query($conecta1, $string_estados) or die (mysqli_error($conecta1));

if ($_REQUEST['estado']!=""){

     $q=$_REQUEST['estado'];
      $string_localidad=sprintf("select * from ciudades_mexico where cve_ent=%s order by nombre_completo",
                    GetSQLValueString($q, "int"));
     $sql_ciudades=mysqli_query($conecta1, $string_localidad) or die (mysqli_error($conecta1));

     $indica_entrega=$_POST['indicaentrega'];
     $indicaentrega_bodega=$_POST['indicaentrega_bodega'];

 }

IF(isset($_POST['DROPFILE']))
{
     /**Iniciamos proceso para eliminar  documento Orden de compra  */



}


 $moneda=1;
 $folio = folio_pedido($_SESSION["usuario_agente"]);
 $exis_ORDCOMPRA =  Existe_ORDC($folio,$conecta1);
// $_SESSION["ordcompra_delete"] = $exis_ORDCOMPRA ;

 IF (isset($_POST['continuar'])){

$_SESSION["observaciones"] = $_POST['observaciones'];
$_SESSION["domicilio"] = $_POST['domicilio'];
$_SESSION["credito"] = $_POST['credito'];
$_SESSION["limite"] = $_POST['limite'];
$_SESSION["plazo"] = $_POST['plazo'];
$_SESSION["moneda"] = $_POST['moneda'];
$_SESSION["pago"] = $_POST['pago'];
$_SESSION["cliente"] = $_POST['cliente'];
$_SESSION["localidad"] =720908;/// $_POST['localidad']; ///Dato obsoleto ya no se va a usar 03-12-2016 dato default del sistema  Localidad Torreon
$_SESSION["folio"] = $folio;
$_SESSION["servdist"] = 2;
$_SESSION["destino"] = $_POST['destino'];
$_SESSION["destinoid"] = $_POST['destinoid'];

$_SESSION["metodo_pago"] = $_POST['metodo_pago'];   ///Dato necesario para identificar como se pagara la factura  23-01-2017

///Cambio realizado el 17-11-2016
$_SESSION["indicaentrega"] = $_POST['indicaentrega'];
$_SESSION["indicaentrega_bodega"]=$_POST['indicaentrega_bodega'];    //Cliente o Agente entregan el Pedido
///******Agregado de Opcion CFDI
$_SESSION["OPCFDI"] = $_POST['opcFDI'];

$_SESSION["MTPG"] = $_POST['MTPG'];
///$_SESSION["ORD"] = $_POST['ORD'];

 $_SESSION["dcto_pp"] = $_POST["dcto_pp"];


 if(  ($_SESSION["moneda"] != "" || $_SESSION["pago"] != "" || $_SESSION["cliente"] != "" ||
 $_SESSION["destino"] != "" || $_SESSION["destinoid"] != "" || $_SESSION["metodo_pago"] != "" || $_SESSION["indicaentrega"] != "" || $_SESSION["indicaentrega_bodega"] != "" || $_SESSION["opcFDI"] != "" ||
 $_SESSION["MTPG"] != "") && $_SESSION["pago"] == "2"  ){


    $_SESSION["ORD"] = "N/A";
    $_SESSION["POB_ORD"] = "N/A";

    $MM_restrictGoTo = "pedidos_sigue.php";

    header("Location: ". $MM_restrictGoTo);

 } if( ($_SESSION["plazo"] != "" || $_SESSION["moneda"] != "" || $_SESSION["pago"] != "" || $_SESSION["cliente"] != "" ||
 $_SESSION["destino"] != "" || $_SESSION["destinoid"] != "" || $_SESSION["metodo_pago"] != "" || $_SESSION["indicaentrega"] != "" || $_SESSION["indicaentrega_bodega"] != "" || $_SESSION["opcFDI"] != "" ||
 $_SESSION["MTPG"] != "") && $_SESSION["pago"] == "1"  ){



     /*===========VALIDACION ODEN DE COMPRA======================================================================= */
$str_valiORD  = sprintf("select count(*) as exisOrd  from  pedidos.encabeza_pedido    where  ORD = %s AND  cve_cte = %s  ",
GetSQLValueString($_POST['ORD'], "text"),
GetSQLValueString($_POST['cliente'], "text"));
$rsord  = mysqli_query($conecta1,$str_valiORD );
$rowORDS = mysqli_fetch_array($rsord);

if($rowORDS['exisOrd'] == 0 &&  $rowORDS['exisOrd'] != NULL  )
{


$_SESSION["ORD"] =$_POST['ORD'];
$VALORD = 0;
/*
echo '1='.$_SESSION["indicaentrega"].'<br>';
echo '2='.$_SESSION["indicaentrega_bodega"].'<br>';
*
*/
if ($exis_ORDCOMPRA["numcol"] == 0 ){

///echo "HOLA PERROO :V " ;

// $POB_ORD= CARGAR_ARCHIVO($_FILES,$folio,$conecta1);
/* echo '</BR>';
echo  $POB_ORD->GET_MENSAJE_ARCHIVO();
echo '</BR>';
echo $POB_ORD->GET_ERROR_OBJ();
echo '</BR>';
echo 'TIPODE ARCHIVO ';
echo $POB_ORD->GET_TIPOARCHIVO();
echo 'Nombre del Archivo';
echo $POB_ORD->GET_NOMBRE_TEMPORAL();
echo "Nuevo Nombre del  Documento";
echo  $POB_ORD->GET_NEW_DIRECCION();

echo  "<h2> Resultado Almacenado Archivo ";
echo  $retVal = ($POB_ORD->RESULTADO_ORDC()) ? "CORRECTO" : "INCORRECTO" ;*/

// if($POB_ORD->RESULTADO_ORDC() ){
// $_SESSION["POB_ORD"] = $POB_ORD->JSON_OBJCLASS()  ; ///Enviamos el Objeto  _ord agregado en libreria  _OBJ_ORD

// $INS_ODRC = sprintf("INSERT INTO pedidos.TB_LOG_CARGA_ORDC_PEDIDOS SET REMISION =%s, DOCUMENTO=%s , TYPEDOCTO=%s ",
// GetSQLValueString($_SESSION["folio"], "int"),
// GetSQLValueString( $POB_ORD->GET_NEW_DIRECCION(), "text"),
// GetSQLValueString($POB_ORD->GET_TIPOARCHIVO(), "text")
// );

// $queryinsertencabeza=mysqli_query($conecta1, $INS_ODRC) or die (mysqli_error($conecta1));



// $MM_restrictGoTo = "pedidos_sigue.php";

// header("Location: ". $MM_restrictGoTo);


// }
//SUBIR VARIOS ARCHIVOS EN ORDEN DE COMPRA, SUMA HASTA 20 MB
$sum = 0;
// Count total files
$countfiles = count($_FILES['archORD']['name']);
for($j=0;$j<$countfiles;$j++){
    $sum = $_FILES['archORD']['size'][$j] + $sum;
}

$sumMB = round($sum / 1048576, 2);
$_SESSION["SUMMB"] =$sumMB;


if($sum < 20*MB){
// Looping all files
for($i=0;$i<$countfiles;$i++){

 $bandera = 0;
 $tipoArchi = $_FILES['archORD']['type'][$i];
 $filename = $_FILES['archORD']['name'][$i];

 $RESUL_FUNCTION['Office_elem']=0;
       if(strpos($tipoArchi, "pdf"))
       {
            $NombreOr ="REM_ORDC".$folio.'.pdf'; ///$_FILES['ARCH']['name'];
       }else{

                if(strpos($tipoArchi, "jpg"))
                 {
                      $NombreOr ="REM_ORDC".$folio.'.jpg';
                      $bandera = 1;
                 }else{
                     if(strpos($tipoArchi, "jpeg"))
                     {
                         $NombreOr ="REM_ORDC".$folio.'.jpg';
                         $bandera = 1;
                     }
                     else {
                             if(strpos($tipoArchi,"jpe"))
                             {
                                 $NombreOr ="REM_ORDC".$folio.'.jpg';
                                 $bandera = 1;
                             }
                             else {
                                     if(strpos($tipoArchi, "jfif"))
                                     {
                                         $NombreOr ="REM_ORDC".$folio.'.jpg';
                                         $bandera = 1;
                                     }
                                     else
                                     {
                                         if(strpos($tipoArchi, "png")||strpos($tipoArchi, "PNG"))
                                         {
                                            $NombreOr ="REM_ORDC".$folio.'.png';
                                            $bandera = 1;
                                         }else {
                                                    switch ($tipoArchi) {
                                                        case "application/vnd.ms-excel":
                                                            $NombreOr ="REM_ORDC".$folio.'.csv';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            $bandera = 1;
                                                            break;
                                                        case "text/xls":
                                                            $NombreOr ="REM_ORDC".$folio.'.xls';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            $bandera = 1;
                                                            break;
                                                        case "text/xlsx":
                                                            $NombreOr ="REM_ORDC".$folio.'.xlsx';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            $bandera = 1;
                                                            break;
                                                        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                                                            $NombreOr ="REM_ORDC".$folio.'.xlsx';
                                                            $bandera = 1;
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            break;
                                                        case "text/csv":
                                                            $NombreOr ="REM_ORDC".$folio.'.csv';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            $bandera = 1;
                                                            break;
                                                        case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                                                            $NombreOr ="REM_ORDC".$folio.'.xls';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            $bandera = 1;
                                                            break;
                                                        case "application/vnd.ms-excel":
                                                            $NombreOr ="REM_ORDC".$folio.'.csv';
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                            $bandera = 1;
                                                            break;
                                                        default:
                                                           echo "El formato del archivo no es válido";
                                                    }
                                                }
                                             //////*****Fin  Validacion Documento  Office
                                         }
                                     
                             }
                     }

                 }

         }
         if($bandera==0){
            if(strpos($tipoArchi, "dot")||strpos($tipoArchi, "doc")||strpos($tipoArchi, "docx")||strpos($tipoArchi, "dotm")||strpos($tipoArchi, "docm")||strpos($tipoArchi, "dotx")||strpos($tipoArchi, "dotm")){
                $NombreOr ="REM_ORDC".$folio.'.docx';
                $RESUL_FUNCTION['Office_elem']=1;
            }
        }

         if(strpos($tipoArchi, "doc")||strpos($tipoArchi, "docx")||strpos($tipoArchi, "xls")||strpos($tipoArchi, "csv")||strpos($tipoArchi, "xlsx")||strpos($tipoArchi, "ppt")
                  ||strpos($tipoArchi, "pptx")|| strpos($tipoArchi, "pdf")||strpos($tipoArchi, "jpg")||strpos($tipoArchi, "png")||strpos($tipoArchi, "jpeg")||strpos($tipoArchi, "jpe")
                       ||$RESUL_FUNCTION['Office_elem']==1)
               {
                //  $filename
               move_uploaded_file($_FILES['archORD']['tmp_name'][$i],'ORDC_PEDIDOS/'.$i.'-'.$NombreOr);

                $INS_ODRC = sprintf("INSERT INTO pedidos.TB_LOG_CARGA_ORDC_PEDIDOS SET REMISION =%s, DOCUMENTO=%s , TYPEDOCTO=%s ",
               GetSQLValueString($_SESSION["folio"], "int"),
               GetSQLValueString( 'ORDC_PEDIDOS/'.$i.'-'.$NombreOr, "text"),
               GetSQLValueString($tipoArchi, "text")
               );

               $queryinsertencabeza=mysqli_query($conecta1, $INS_ODRC) or die (mysqli_error($conecta1));


               $MM_restrictGoTo = "pedidos_sigue.php";

               header("Location: ". $MM_restrictGoTo);
               }else {
                echo '<script type="text/javascript">alert("Ocurrió un error");</script>';
               }

 // Upload file


}
}else {
    echo '<script type="text/javascript">alert("Excediste los 20 mb en el total de los archivos seleccionados.");</script>';
   }

}
if ($exis_ORDCOMPRA["numcol"] >= 1 ){

$POB_ORD = new _ord();

$Archivo_precargado = $_SESSION["ordcompra_delete"];
$POB_ORD->new_ruta =  $Archivo_precargado['numdocto'];
$_SESSION["POB_ORD"] = $POB_ORD->JSON_OBJCLASS()  ;
$MM_restrictGoTo = "pedidos_sigue.php";

header("Location: ". $MM_restrictGoTo);
}


}else
{
$VALORD = 1;

}
/*========FIN===VALIDACION ODEN DE COMPRA======================================================================= */

 } if($_SESSION["destino"] == "" || $_SESSION["indicaentrega_bodega"] == "" ){



 }



 }
 IF(isset($_POST['ARCHIVOFILE']))
 {
    try {
        $objetoORD = NEW   OBJ_ORD();

        $objetoORD->ADD_FILE($_FILES );

        if($objetoORD->GET_ESTATUS_ARCHIVO() ==1 ){

        echo  "TODO BIEN" ;

        }

    } catch (Exception $th) {
       echo  $th;
    }



 }

 IF (isset($_POST['cliente'])){

     $codigo = $_POST['cliente'];
     if ($codigo==9999){     //Solo aplica para los agentes locales
         $saldo=0;
         $dias=0;
         $limite=0;
         $mail="";

     }else{
          $querydatos = sprintf("Select * FROM clientes_cronos WHERE CardCode = %s",
                    GetSQLValueString($codigo, "text"));
          $clientedatos = mssql_query($querydatos);

          $datoscliente=  mssql_fetch_array($clientedatos);
          $frozenfor=$datoscliente['frozenFor'];
          $saldo = $datoscliente['Balance'];
          $dias = $datoscliente['ExtraDays'];
          $limite = $datoscliente['CreditLine'];
          $mail = $datoscliente['E_Mail'];
          $destino_id=$_POST['destinoid'];
     }
     //** Variable   destino   agregada   09/08/2016
     $querydestino  = sprintf("SELECT id,calle ,colonia  FROM  dir_entregas  where   cve_cte = %s ",
     GetSQLValueString($codigo, "text"));
     ///***
     $destinos_resquery  =mysqli_query($conecta1, $querydestino) or die (mysqli_error($conecta1));

 }



$string_listadoprod=("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' ORDER BY ItemName");
$tabla = mssql_query($string_listadoprod);

///*****Incio Codigo  para  AGREGAR  un nuevo  Destino 15/08/2016
///* Hacemos Consulta para  los  estados tb  estados_mexico
$string_con_estados = "Select id,nom_ent FROM estados_mexico  ORDER BY  nom_ent;";
$resul_con_estados = mysqli_query($conecta1,$string_con_estados ) or   die   (mysqli_errno($conecta1));
//***Fin  de Consulta  Estados
///***Capturamos  Datos
IF(isset($_POST['guardar']))
{
 $_cve_cliente = $_POST['cve_cliente'];
 $_calle =$_POST['calle'];
 $_colonia  =$_POST['colonia'];
 $_ciudad = $_POST['ciudad'];
 $_cp  = $_POST['cp'] ;
 $_estado = $_POST['estado'];
 $_pais  =$_POST['pais'];
 $str_insert_new_destino  = sprintf("INSERT  INTO dir_entregas  SET  "
         . "cve_cte=%s,calle=%s,colonia=%s,ciudad =%s,cp=%s,estado=%s,pais=%s;",
 GetSQLValueString($_cve_cliente, "text"),
 GetSQLValueString($_calle, "text"),
 GetSQLValueString($_colonia, "text"),
 GetSQLValueString($_ciudad, "text"),
 GetSQLValueString($_cp, "text"),
 GetSQLValueString($_estado, "text"),
 GetSQLValueString($_pais, "text"));

 $insert_new_destino   = mysqli_query($conecta1, $str_insert_new_destino)or die (mysqli_error($conecta1));
}
///*****Fin Codigo para  Agregar  un nuevo  Destino
 require_once('header.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


 ///modificacion 17-11-2016

 $string_bodegas=sprintf("select t0.almacen, t1.nombre_alma from matriz_almacen_abastecimiento t0 inner join vista_nombrealmacen t1 on t0.almacen=t1.almacen where n_agente=%s",
                  GetSQLValueString($idagente,"int"));
 $query_bodegas=mysqli_query($conecta1, $string_bodegas)or die (mysqli_error($conecta1));


?>
 <!-- script para calcular automáticamente si el precio es menor a algún precio de lista -->
 <script type="text/javascript">


 function calculate() {

 var descuento = document.forma1.descuento.value;
 var preciopublico = document.forma1.precio.value;

 var precio4 = document.forma1.precio4.value;
 var precio5 = document.forma1.precio5.value;

 var calculo = preciopublico*(descuento/100);
 var resultado = preciopublico-calculo;
 resultado = resultado.toFixed(2);
 document.forma1.subtotal.value = resultado;

 if(resultado < precio4) {
     alert("Requiere autorización");
 }

 }


 </script>

 <script type="text/javascript">
  var validaORD  = <?PHP echo $VALORD;  ?>;

    var resultadoORDFILE = <?php echo  $th;  ?>
 console.log(resultadoORDFILE);




if(validaORD == 1)
{
    alert("LO SENTIMOS NO SE PUEDE CONTINUAR CON EL PROCESO DADO A QUE LA ORDEN DE COMPRA YA EXISTE EN EL SISTEMA")

}

</script>

<script type="text/javascript">

function mostrar(plazo){
document.getElementById(plazo).style.display= "inline";
}
function ocultar(plazo){
document.getElementById(plazo).style.display= "none";
}
function inicial(){
  if (document.getElementById('optionsRadios2').value==="2")
	{

	ocultar('plazo') ;
    document.getElementById('MTPG').value="PUE";
    document.getElementById("plazo").value = "";
    document.getElementById("ORD").value = "";
    document.getElementById('ORD').disabled = true;
    document.getElementById('archORD').disabled = true;



	}
    ocultarButton()

}

function ocultarButton(){
    document.getElementById('DROPFILE').disabled = true;
}

function mostrarButton(){
    document.getElementById('DROPFILE').disabled = false;
}

function ocultar_indicabodega(){

     document.getElementById('indicaentrega_bodega').style.display = 'none';


}

function validpay(){
    if(document.getElementById('MTPG').value=="PPD" ){

document.getElementById('pago').value="1";
document.getElementById('ORD').disabled = true;

} if(document.getElementById('MTPG').value=="PUE" ){
document.getElementById('pago').value="2";
}
}

function prueba2(){

   if(document.getElementById('credito').value="" ){

       document.getElementById('continuar').disabled = true;

   } else{
       document.getElementById('continuar').disabled = false;
   }
}

var regex = new RegExp("(.*?)\.(csv)$");

function triggerValidation(el) {
  if (!(regex.test(el.value.toLowerCase()))) {
    el.value = '';
    alert('Please select correct file format');
  }
}


function definirie_indicabodega(){

    var dato1=document.getElementById("indicaentrega").value;

   // alert (dato1);

    if (dato1!=3){
     //   alert ('Ocultar Bodegas');
       document.getElementById('indicaentrega_bodega').style.display = 'inline';

    }else{
         document.getElementById('indicaentrega_bodega').style.display = 'none';

    }
    //document.getElementById('indicaentrega_bodega').style.display = 'inline-block';
}

function inicial2(){
    if (document.getElementById('optionsRadios1').value==="1")
  {

       mostrar('plazo') ;
           document.getElementById("MTPG").value = "PPD";

           document.getElementById("metodo_pago").value = "99";
           document.getElementById('ORD').disabled = false;
           document.getElementById('archORD').disabled = false;

  }
  mostrarButton()

}

 window.onload = ocultar_indicabodega;




</script>
<style>
.ESPDF {
    margin-top: 50px;
}

</style>
<form method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="page-header">
            <h3>Crear pedido Folio: <?php  echo $folio;?></h3>
        </div>

        <div class=" col-md-12 col-sm-12 col-lg-12">
            <p>Cliente: </p>
            <div class="input-group input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                <select name="cliente" class="form-control select2" id="cliente" onchange="this.form.submit()" required>
                    <option>Cliente</option>
                    <?php
                    while ($row = mssql_fetch_array($cliente)) {
                        if ($row['CardCode'] == $_REQUEST['cliente']&& $row['validFor']=='Y') {

                            echo '<option selected value="' . $row['CardCode'] . '">' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                        } else {
                            echo '<option value="' . $row['CardCode'] . '">' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                        }
                        if($row['CardCode'] == $_REQUEST['cliente']&& $row['validFor']=='N')
                                       {
                                        echo '<script type="text/javascript">alert(\'Este Cliente está bloqueado, favor de solicitar Permiso\');</script>';
                                       }




                    }
                     if ($_SESSION['usuario_tipo']==1)    //este cliente solo aplica para usuarios locales
                        {
                            if ($codigo=="9999"){
                                echo '<option selected value="C009996">VENTAS CONTADO </option>';
                            }else{
                                 echo '<option value="C009996">VENTAS CONTADO </option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

         <div class="ESPDF col-lg-12 col-sm-12">
           <div class="row">
             <div    class="col-lg-6 col-sm-12">
                   Forma de Pago
                   <select class="form-control" id="metodo_pago" name="metodo_pago"  required>


                     <option value="01" <?php if ($_POST["metodo_pago"]=="01" ){  echo 'selected'; } ?> >01-Efectivo</option>
                       <option value="02" <?php if ($_POST["metodo_pago"]=="02" ){  echo 'selected'; } ?>>02-Cheque Nominativo</option>
                       <option value="03" <?php if ($_POST["metodo_pago"]=="03" ){  echo 'selected'; } ?> >03-Transferencia electronica de fondos</option>
                       <option value="04" <?php if ($_POST["metodo_pago"]=="04" ){  echo 'selected'; } ?>>04-Tarjeta de credito</option>
                       <option value="05" <?php if ($_POST["metodo_pago"]=="05" ){  echo 'selected'; } ?>>05-Monedero Electronico</option>
                       <option value="06" <?php if ($_POST["metodo_pago"]=="06" ){  echo 'selected'; } ?>>06-Dinero Electronico</option>
                       <option value="08" <?php if ($_POST["metodo_pago"]=="08" ){  echo 'selected'; } ?>>08-Vales de Despensa</option>

                       <option value="12" <?php if ($_POST["metodo_pago"]=="12" ){  echo 'selected'; } ?>>12-Dacion en Pago</option>
                       <option value="13" <?php if ($_POST["metodo_pago"]=="13" ){  echo 'selected'; } ?>>13-Pago por Subrogación</option>
                       <option value="14" <?php if ($_POST["metodo_pago"]=="14" ){  echo 'selected'; } ?>>14-Pago por consignación</option>
                       <option value="15" <?php if ($_POST["metodo_pago"]=="15" ){  echo 'selected'; } ?>>15-Condonación</option>
                       <option value="17" <?php if ($_POST["metodo_pago"]=="17" ){  echo 'selected'; } ?>>17-Compensación</option>

                       <option value="23" <?php if ($_POST["metodo_pago"]=="23" ){  echo 'selected'; } ?>>23-Novacion</option>
                       <option value="24" <?php if ($_POST["metodo_pago"]=="24" ){  echo 'selected'; } ?>>24-Confusión</option>
                       <option value="25" <?php if ($_POST["metodo_pago"]=="25" ){  echo 'selected'; } ?>>25-Remisión de deuda</option>
                       <option value="26" <?php if ($_POST["metodo_pago"]=="26" ){  echo 'selected'; } ?>>26-Prescripción o caducidad</option>
                       <option value="27" <?php if ($_POST["metodo_pago"]=="27" ){  echo 'selected'; } ?>>27-A satisfacción del acreedor</option>

                       <option value="28" <?php if ($_POST["metodo_pago"]=="28" ){  echo 'selected'; } ?>>28-Tarjeta de débito</option>
                       <option value="29" <?php if ($_POST["metodo_pago"]=="29" ){  echo 'selected'; } ?>>29-Tarjeta de servicios</option>
                       <option value="30" <?php if ($_POST["metodo_pago"]=="30" ){  echo 'selected'; } ?>>30-Aplicación de anticipos</option>
                       <option value="31" <?php if ($_POST["metodo_pago"]=="31" ){  echo 'selected'; } ?>>31-Intermediario pagos</option>
                       <option  selected value="99" <?php if ($_POST["metodo_pago"]=="99" ){  echo 'selected'; } ?> >99-Por Definir</option>

                   </select>
                </div>

                <div  class="col-lg-6 col-sm-12">
                      Seleccion De CFDI
                        <select id="opcFDI"  class="form-control" name="opcFDI"  required>
                          <option value="">CFDI</option>
                          <option value="D01" <?php if ($_POST["opcFDI"]=="D01" ){  echo 'selected'; } ?>> D01-Honorarios medicos, dentales y gastos hospitales</option>
                          <option value="D02" <?php if ($_POST["opcFDI"]=="D02" ){  echo 'selected'; } ?>>D02-Gastos medicos por incapacidad o discapacidad</option>
                          <option value="D03" <?php if ($_POST["opcFDI"]=="D03" ){  echo 'selected'; } ?>>D03-Gastos Funerales</option>
                          <option value="D04" <?php if ($_POST["opcFDI"]=="D04" ){  echo 'selected'; } ?>>D04-Donativos</option>
                          <option value="D05" <?php if ($_POST["opcFDI"]=="D05" ){  echo 'selected'; } ?>>D05-Intereses reales efectivamente por creditos hipotecarios(casa Habitacion)</option>
                          <option value="D06" <?php if ($_POST["opcFDI"]=="D06" ){  echo 'selected'; } ?>>D06-Aportaciones voluntarias al SAR</option>
                          <option value="D07" <?php if ($_POST["opcFDI"]=="D07" ){  echo 'selected'; } ?>>D07-Primas por seguros de gastos mmedicos</option>
                          <option value="D08" <?php if ($_POST["opcFDI"]=="D08" ){  echo 'selected'; } ?>>D08-Gasto de transportacion escolar obligatoria</option>
                          <option value="D09" <?php if ($_POST["opcFDI"]=="D09" ){  echo 'selected'; } ?>>D09-Deposito en cuentas par el ahorro,proma que tenga como base planes de pen</option>
                          <option value="D10" <?php if ($_POST["opcFDI"]=="D10" ){  echo 'selected'; } ?>>D10-Pagos por servicios educativos(colegiaturas)</option>
                          <option value="G01" <?php if ($_POST["opcFDI"]=="G01" ){  echo 'selected'; } ?>>G01-Adquicion de mercancia</option>
                          <option value="G02" <?php if ($_POST["opcFDI"]=="G02" ){  echo 'selected'; } ?>>G02-Devoluciones,descuento o bonificaciones</option>
                          <option value="G03" <?php if ($_POST["opcFDI"]=="G03" ){  echo 'selected'; } ?>>G03-Gastos en General</option>
                          <option value="I01" <?php if ($_POST["opcFDI"]=="I01" ){  echo 'selected'; } ?>>I01-Construcciones</option>
                          <option value="I02" <?php if ($_POST["opcFDI"]=="I02" ){  echo 'selected'; } ?>>I02-Mobilario</option>
                          <option value="I03" <?php if ($_POST["opcFDI"]=="I03" ){  echo 'selected'; } ?>>I03-Equipo de Transporte</option>
                          <option value="I04" <?php if ($_POST["opcFDI"]=="I04" ){  echo 'selected'; } ?>>I04-Equip de Computo y accesorios</option>
                          <option value="I05" <?php if ($_POST["opcFDI"]=="I05" ){  echo 'selected'; } ?>>I05-Dado,Troqueles,Moldes,Matrices y Herramental</option>
                          <option value="I06" <?php if ($_POST["opcFDI"]=="I06" ){  echo 'selected'; } ?>>I06-Comunicaciones telefonicas</option>
                          <option value="I07" <?php if ($_POST["opcFDI"]=="I07" ){  echo 'selected'; } ?>>I07-Comunicaciones satelitales</option>
                          <option value="I08" <?php if ($_POST["opcFDI"]=="I08" ){  echo 'selected'; } ?>>I08-Otra maquinaria y equipo</option>
                          <option value="P01" <?php if ($_POST["opcFDI"]=="P01" ){  echo 'selected'; } ?>>P01-Por Definir</option>


                        </select>
                </div>
              </div>
         </div>
         <div  class="ESPDF col-lg-12 col-sm-12">
           <div  class="col-lg-4  col-sm-12">
                 Seleccion De Metodo de Pago
                   <select  class="form-control" id="MTPG" name="MTPG" onchange="validpay()"  required>


                     <option  value="PUE">PUE --Pago en una sola Exhibicion</option>
                     <option selected value="PPD">PPD --Pago en parcialidades o diferido</option>

                   </select>
           </div>
           <div  class="col-lg-2 col-sm-12">
           </div>
           <div  class="col-lg-3 col-sm-12">


                   <div class="form-group">
                          <label class="col-sm-12 ">Ingrese Orden de compra </label>
                                    <div  class="auto-style2">
                                             <input   type ="text"  class ="form-control" name ="ORD" id ="ORD"  placeholder="">
                                    </div>
                   </div>
                   <div class="form-group">

                   

                                                <?php
                                                  ///  print_r($exis_ORDCOMPRA);
                                                    if  ($_SESSION["SIHAYARCHIVOS"] != "1" ) { ?>
                                                        <label class="col-sm-12 ">Ingrese Archivo de Orden compra </label>
                                                                <div  class="auto-style2">
                                                 <input required id="archORD" type="file" class ="form-control" accept=".docx,.png,.jpg,.jpeg,.pdf"  name="archORD[]" multiple />
                                                 <?php  }else{ ?>

                                                <button data-toggle="modal" data-target="#filesModal" name="VIEWFILE" id="VIEWFILE" type="button"  class="btn btn-lg btn-danger"> Archivos</button>

                                                 <?php } ?>


                                    </div>
                   </div>



           </div>
           <div  class="col-lg-3 col-sm-12">
           </div>
         </div>
        <div class="ESPDF col-lg-12 col-sm-12 ">
            <div  class="col-lg-8 col-sm-12">
                  <div class="row">
                      Indicacion de Entrega
                    <select required name='indicaentrega'  class="form-control" id='indicaentrega' onchange="definirie_indicabodega()" >
                         <option value="">Indicación entrega</option>
                       <option value='3'  <?php if ($indica_entrega==3){  echo 'selected'; }  ?> >Logistica</option>
                        <option value='1'  <?php if ($indica_entrega==1){  echo 'selected'; }  ?>>Cliente Recoge</option>
                        <option value='2'  <?php if ($indica_entrega==2){  echo 'selected'; }  ?>>Agente  Entrega</option>
                         <option value='4'  <?php if ($indica_entrega==4){  echo 'selected'; }  ?>>Consignación</option>


                    </select>
                </div>
                <div class="row">
                     <select   class="form-control" name="indicaentrega_bodega" id="indicaentrega_bodega" >

                            <option value="">Favor de Elegir Bodega</option>
                            <?php
                            while ($rowie = mysqli_fetch_array($query_bodegas)) {

                                if ($indicaentrega_bodega==$rowie['almacen']){
                                      echo '<option selected value="' . $rowie['almacen'] . '">' .$rowie['almacen'].'-'.$rowie['nombre_alma'] . '</option>';
                                }else{
                                      echo '<option value="' . $rowie['almacen'] . '">' .$rowie['almacen'].'-'.$rowie['nombre_alma'] . '</option>';
                                }


                            }
                            ?>

                      </select>
                </div>
                <div class="ESPDF row">
                        <!--Seccion Destino  Agregado   09/08/2016 -->
                        <div class="input-group input-group select2-bootstrap-prepend">
                            <span class="input-group-btn">
                                <!--<button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">-->
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" data-select2-open="select2-button-addons-multi-input-group">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </span>
                            <select name="destinoid" class="form-control select2" id="destino" required>
                                <option value="">Destino</option>
                                <?php
                                while ($file = mysqli_fetch_array($destinos_resquery)) {

                                    echo '<option  value="' . $file['id'] . '">' . $file['calle'].'--'.$file['colonia']. '</option>';

                                }
                                ?>
                            </select>
                       </div>

                </div>
            </div>
            <!-- OPCIONES -->
            <div class="col-lg-4 col-sm-12">
              <div class="form-group">

                        <div class="col-lg-2 col-sm-12">
                            <label>Pago:</label>
                            <label class="radio">
                                <input type="radio" data-toggle="radio" name="pago" id="optionsRadios1" value="1" data-radiocheck-toggle="radio" checked="" onChange="inicial2()" required>
                                Crédito
                            </label>
                            <label class="radio">
                                <input type="radio" data-toggle="radio" name="pago" id="optionsRadios2" value="2" data-radiocheck-toggle="radio"  onChange="inicial()">
                                Contado
                            </label>
                        </div>
                          <div class="col-lg-1 col-sm-12"></div>
                        <div class="col-lg-1 col-sm-12">
                            <div class="row">
                                <label>Moneda:</label>
                                <label class="radio">
                                    <input type="radio" data-toggle="radio" name="moneda" id="optionsRadios3" value="0" data-radiocheck-toggle="radio" required>
                                    USD
                                </label>
                                <label class="radio">
                                    <input type="radio" data-toggle="radio" name="moneda" id="optionsRadios4" value="1" data-radiocheck-toggle="radio" checked="">
                                    MXN
                                </label>
                            </div>
                            <div class="row" id="plazo">
                                <label>Plazo:</label>
                                <select name="plazo" >
                                    <option value="0">Elija</option>
                                    <?php
                                    $div = $dias / 30;
                                    IF (isset($_POST['cliente'])) {
                                        $cont = 0;
                                        while ($cont < $div) {
                                            $cont = $cont + 1;
                                            echo '<option>' . ($cont * 30) . '</option>';
                                        }
                                    }
                                    ?>

                                </select>






                            </div>
                        </div>





            </div>

          </div>

        </div>


        <div class=" ESPDF form-group">


            <!--<div class="col-lg-8" >
                <label>¿Necesita Serv. de Dist.?</label>

                <select name="servdist" required>
                    <option>Elija</option>
                    <option value='1'>Sí</option>
                    <option value='2'>No</option>

                </select>

            </div>-->
            <!--*****************************************-->
            <div class="col-lg-12 col-sm-12">
              <div  class="row">
                  <div   class="col-lg-4 col-sm-12">
                       <label>Límite de Crédito: <?php //echo $div; ?></label>
                      <input name="limite" type="text" class="form-control" value="<?php $limite; ?>" placeholder="<?php echo number_format($limite, 2, '.', ',') ?>" disabled="disabled">
                  </div>
                  <div class ="col-lg-4">
                      <label>Crédito disponible:</label>
                      <input name="credito" type="text" class="form-control" value="<?php $corriente = $limite - $saldo; ?>"  placeholder="<?php $corriente = $limite - $saldo;
                          echo number_format($corriente, 2, '.', ',');
                          ?>" disabled="disabled">
                  </div>
              </div>
            </div>
            <div class="col-lg-12 col-sm-12">
                    <div class="form-group">
                               <label class="radio col-sm-12">
                                Aplicar 6% Descuento Pronto Pago
                                </label>
                                <div class="col-sm-3 ">
                                        <div class="col-sm-6"> Si
                                        <input disabled  type="radio" name="dcto_pp" id="dcto_ppsi"  value="1" > </div>
                                        <div class="col-sm-6"> No
                                        <input disabled type="radio" name="dcto_pp" id="dcto_ppno"  value="0" checked> </div>
                                </div>
                    </div>
                        <script>
                            var  radi_ppsi = document.getElementById("dcto_ppsi");
                            radi_ppsi.addEventListener('click',function(){
                                   put_comentarios();

                            });


                           document.getElementById("dcto_ppno").onclick = function() {
                                        put_comentarios();

                            };

                            function put_comentarios()
                                {
                                    if(document.getElementById('dcto_ppsi').checked)
                                    {
                                        /// var  txtdestino  =  document.getElementById('destino') ;

                                        txtdestino.textContent ="Aplicar 6% Descuento Pronto Pago";
                                       /// console.log("HOLA PPSI")
                                        document.getElementById('destinoTXT').value = "Aplicar 6% Descuento Pronto Pago";

                                    }else if(document.getElementById('dcto_ppno').checked) {

                                        document.getElementById('destinoTXT').value = "";
                                       /// console.log("HOLA PPNO")
                                    }
                                }
                        </script>
            </div>

            <div class="col-lg-10">
                <div class="row">
                    <label>Comentarios</label>
                   <input id="destinoTXT" name="destino" type="text" class="form-control" placeholder="Comentarios" required>
               </div>
            </div>
        </div>




        <div class="row">
            <div class="col-md-12">
                <p>
                    <button id="continuar" name="continuar" type="submit" class="btn btn-lg btn-primary pull-right" >Continuar</button>

                </p>
            </div>
        </div>
    </div>

</form>
<br>

         <!---Inicio contenedor  Modal ---->
         <script src="jquery/dist/jquery.min.js"> </script>
        <!--Script   Validacion Datos  Forma-->
      <!--  <script type ="text/javascript" src="valida_dat_new_destino.js" ></script>
          <!-- Modal -->
                <div class="modal fade" id="filesModal" role="dialog">
                  <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>


                            <h4 class="modal-title"   >Orden de compra</h4>
                            <input type="text" style ="visibility:hidden;"  name ="cve_cliente"  value= "<?php echo  $codigo; ?> " >

                       </div>
                      <div class="modal-body">

         <div  class ="panel panel-default">
             <div   class ="panel-body">
                  <!--Inicio FormaCONTENEDORA-->
                    <div class ="col-lg-12 col-md-12">

                    <table  class="table table-responsive table-hover">
             <thead>
                 <tr>

                     <th>NOMBRE</th>
                     <th>ARCHIVO</th>
                 </tr>
             </thead>
             <tbody>
                 <?php

                 $sumMB = $sum + count($_FILES['archORD']['name']);

                 WHILE ($registros= mysqli_fetch_array($SERESULT)){
                    list($noValue, $nomDocu) = split('[/]', $registros['DOCUMENTO']);


                     ?>
                 <tr>


                    <td><?php echo $nomDocu;?></td>

                     <td><a href="<?php  echo   $registros['DOCUMENTO'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;">
                                                        <img src="images/file_pdf.png" title="Orden de compra Agregada" /></a></td>
                    <td><a href= "_FILES_ORD/_FILE_DELETE.php?id=<?php echo $registros['ID'];?>&name=<?php echo $registros['DOCUMENTO'];?>"><button   type="button" class="btn btn-lg btn-danger">Eliminar Archivo</button></a> </td>



                                                    <script>
                                                    function  _adver_delete_files()
                                                                {
                                                                    var txt;
                                                                var r = confirm("Esta apunto de Eliminar el Documento de Orden de Compra");
                                                                if (r == true) {
                                                                    txt = "Aceptar !";
                                                                    SEND_FILES_ORDC()
                                                                } else {
                                                                    txt = "Cancelar";
                                                                }
                                                                ///document.getElementById("demo").innerHTML = txt;



                                                                }

                                                     function  SEND_FILES_ORDC()
                                                                    {
                                                                        var REQUEST   = new  XMLHttpRequest();
                                                                            REQUEST.open("POST","_FILES_ORD/_FILE_DELETE.php");
                                                                            REQUEST.setRequestHeader("Content-Type", "application/json");
                                                                            REQUEST.send()
                                                                            <?php $_SESSION["IDARCH"]= $registros['ID']; ?>
                                                                            REQUEST.onreadystatechange = function(){


                                                                            if(REQUEST.readyState == 4 && REQUEST.status == 200){

                                                                                ////REQUEST.responseText
                                                                                var OBJT = JSON.parse(REQUEST.responseText)
                                                                                console.log(OBJT)
                                                                                if(OBJT.ERROR == 888)
                                                                                 {
                                                                                    location.reload(true);

                                                                                 }

                                                                                ////*
                                                                            }
                                                                        }



                                                                    }
                                                    </script>

                 </tr>
                   <?php



                 } ?>
             </tbody>


         </table>
         <p>NOTA: La suma de los archivos debe de ser menor a 20 Mb, por el momento tus archivos suman <?php echo $_SESSION["SUMMB"]; ?> Mb. </p>

                        </div>
                        <!--Fin FormaCONTENEDORA-->


             </div>
         </div>

                      </div>
                      <div class="modal-footer">


                      </div>
                    </div>
    <!--Inicio Form-->

                  </div>
                </div>

                <div class="modal fade" id="myModal" role="dialog">
                  <div class="modal-dialog">
    <!--Inicio Form-->
     <form id ="fornewdes" method="POST"   class ="form-horizontal"   role ="form">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>


                            <h4 class="modal-title"   >Nuevo Destino</h4>
                            <input type="text" style ="visibility:hidden;"  name ="cve_cliente"  value= "<?php echo  $codigo; ?> " >

                       </div>
                      <div class="modal-body">

         <div  class ="panel panel-default">
             <div   class ="panel-body">
                  <!--Inicio FormaCONTENEDORA-->
                    <div class ="col-lg-12 col-md-12">
                     <!--Contenedor_01 de  Forma-->
                    <div class ="col-lg-6 col-md-6">
                                <!--Calle -->
                                <div id ="_divcalle" class ="form-group">
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                      <label    class ="control-label" >Calle : </label>
                                      <input  required type="text" class="form-control" name="calle"  id ="calle"  placeholder="Ingrese Calle">
                                   </div>
                                </div>
                                <!----->
                                <!--Colonia  -->
                                <div  id ="_divcolonia" class="form-group">
                                      <div class ="col-lg-12 col-md-12  col-sm-12" >
                                         <label   class ="control-label">Colonia :</label>
                                         <input required type  ="text"  class ="form-control"  name="colonia" id="colonia" placeholder="Ingrese Colonia">
                                    </div>
                                </div>
                                <!---->
                                <!--Ciudad -->
                                <div id ="_divciudad"    class ="form-group">
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                      <label  class ="control-label">Ciudad :</label>
                                      <input required type ="text"  class ="form-control" name ="ciudad"  id="ciudad"  placeholder="Ingrese Ciudad">
                                    </div>
                                </div>
                                <!---->

                        </div>
                       <!--FIN Contenedor_01 de  Forma-->
                        <!--Contenedor_02 de  Forma-->
                        <div class ="col-lg-6 col-md-6">
                             <!--C.p  -->
                                <div  id="_divcp"  class ="form-group">
                                    <div class ="col-lg-6 col-md-6  col-sm-6" >
                                        <label   class ="control-label">Cp :</label>
                                        <input  type ="text"  class ="form-control" name ="cp" id ="cp" placeholder="Ingrese  C.p">
                                    </div>
                                </div>
                                <!--Estado-->
                                <div   class ="form-group">
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                     <label    class ="control-label">Estado :</label>
                                     <!--Inicio Selectd Estado--->
                                    <div class="input-group input-group select2-bootstrap-prepend">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </button>
                                            </span>
                                        <select required  name="estado" class="form-control select2" id="estado" required>
                                                <option value="">Elija</option>
                                                <?php
                                                ///**Mostramos Todos los estados  que existen  en  la  tb_estados_mexico
                                                while ($file = mysqli_fetch_array($resul_con_estados)) {

                                                    echo '<option selected value="' . $file['id'] . '">' . utf8_encode($file['nom_ent']). '</option>';
                                                }
                                                ?>
                                            </select>
                                       </div>
                                   <!--FIN Selectd Estado-->
                                    </div>
                                </div>
                                <!--Pais-->
                                <div id ="_divpais" class ="form-group">
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                      <label    class ="control-label">Pais :</label>
                                      <input required  type ="text"  class ="form-control" name ="pais" id ="pais"  placeholder="Ingrese  Pais">
                                    </div>
                                </div>
                        </div>
                        <!--FIN Contenedor_02 de  Forma-->
                        </div>
                        <!--Fin FormaCONTENEDORA-->


             </div>
         </div>

                      </div>
                      <div class="modal-footer">

                          <button type="submit"  id ="guardar"  name ="guardar"  class="btn btn-success">
                                 Guardar
                                 <span class="glyphicon glyphicon-floppy-disk"></span>
                         </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                Close
                        </button>
                      </div>
                    </div>
     </form>
         <!--Fin Form--->
                  </div>
                </div>
         <!---Fin contenedor  Modal--->

<!-- /.container -->

 <?php require_once('foot.php');?>
