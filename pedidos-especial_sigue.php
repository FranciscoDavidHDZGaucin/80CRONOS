<?php
/*
 *
 *
 *
 *
 *
 *Aquí los representantes eligen los productos, se generan los cálculos, se checa si
 * un producto tiene bonificación o convenio
 * PEDIDOS ESPECIALES
 *
 *
 *
 */



require_once('header.php');
//require_once('Connections/conecta1.php');
require_once('funciones.php');
//require_once('correos_array.php');
require_once('formato_datos.php');
//require_once('buscar_email.php');   //se deshabilito ya que la funcion email se agrego al archivo funciones.php
//mysqli_select_db($conecta1, $database_conecta1);
require_once('Connections/conecta1.php');
require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");
//mysqli_set_charset($conecta1, 'utf8');



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




$idagente = $_SESSION["usuario_agente"];
$nombreagen = $_SESSION["usuario_nombre"];
$zona = $_SESSION["Zona"];
$localidad = $_SESSION['localidad'];

$querylista10="SELECT * FROM plataformaproductosl10";
$resultadolista10 = mssql_query($querylista10);

/*
$folio = $_REQUEST['folio'];
$_SESSION['folio'] = $folio;
*/




$tarifaglobal=$_SESSION['tarifa_global'];

$querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s",
                GetSQLValueString($idagente, "int"));

$cliente = mssql_query($querycliente);

$nombre=$_SESSION["cliente"];
$codigo = $nombre;

if ($_SESSION["cliente"]==9999){   //solo aplica a los Agentes Locales

     $frozenfor="N";
     $clientenombre="VENTA DE CONTADO A CLIENTE NO DADO DE ALTA EN SAP";
     $saldo = $datoscliente['Balance'];
     $dias = $datoscliente['ExtraDays'];
     $limite = $datoscliente['CreditLine'];
     $mail = $datoscliente['E_Mail'];
}else{
     $querydatos = sprintf("Select * FROM clientes_cronos WHERE CardCode = %s",
                GetSQLValueString($codigo, "text"));
    $clientedatos = mssql_query($querydatos);

    $datoscliente=  mssql_fetch_array($clientedatos);
    $frozenfor=$datoscliente['frozenFor'];
     $clientenombre=$datoscliente['CardName'];
     $saldo = $datoscliente['Balance'];
     $dias = $datoscliente['ExtraDays'];
     $limite = $datoscliente['CreditLine'];
     $mail = $datoscliente['E_Mail'];
}




   $moneda=  $_SESSION["moneda"];

  ///Buscar el producto y obtener Precio IVA e IEPS tambien se contempla el tipo de moneda
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




             $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' ORDER BY ItemName");

           // $query_prod = mssql_query($string_prod);
           // $datos_prod=mssql_fetch_assoc($query_prod);
            $tabla = mssql_query($string_prod);


    }else{
            $string_prod=sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode=%s and Currency='USD' ORDER BY ItemName",
                GetSQLValueString($_REQUEST['producto'], "text"));

           $query_prod = mssql_query($string_prod);
            $datos_prod=mssql_fetch_assoc($query_prod);

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

         ///*****INCI VALIDACION PARA  eXPORTACIONES
          if($idagente == 123) ///****Validamos   si el Usuario  es de  Exportaciones
          {
                 $string_prod=("SELECT * FROM plataformaproductosl1 WHERE   itemCode  like '%EXP' OR  itemCode  like '%EXPNIC'     ORDER BY ItemName");
           }else{
                  $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='USD' ORDER BY ItemName");

          }
          ////****Termino de  Validacion de  Exportaciones *****************************





       ///  $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='USD' ORDER BY ItemName");

            $tabla = mssql_query($string_prod);
    }


 }else{
     if ($moneda==1) {
            ///Obtener el listado general de los productos, considerar la moneda elegida que en este caso es MXP
             $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' ORDER BY ItemName");

           // $query_prod = mssql_query($string_prod);
           // $datos_prod=mssql_fetch_assoc($query_prod);
            $tabla = mssql_query($string_prod);
    }else{

          ///*****INCI VALIDACION PARA  eXPORTACIONES
          if($idagente == 123) ///****Validamos   si el Usuario  es de  Exportaciones
          {
                 $string_prod=("SELECT * FROM plataformaproductosl1 WHERE   itemCode  like '%EXP' OR  itemCode  like '%EXPNIC'  ORDER BY ItemName");
           }else{
                  $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='USD' ORDER BY ItemName");

          }
          ////****Termino de  Validacion de  Exportaciones *****************************


            $tabla = mssql_query($string_prod);
        //    $quey_prod = mssql_query($string_prod);
        //    $datos_prod=mssql_fetch_assoc($query_prod);
    }
 }

///*****INCI VALIDACION PARA  eXPORTACIONES
 if($idagente == 123) ///****Validamos   si el Usuario  es de  Exportaciones
  {
      $string_prod=("SELECT * FROM plataformaproductosl1 WHERE   itemCode  like '%EXP' OR  itemCode  like '%EXPNIC'     ORDER BY ItemName");
  }else{
      $string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' ORDER BY ItemName");

  }
////****Termino de  Validacion de  Exportaciones *****************************


$tabla = mssql_query($string_prod);

 //   codigo para las bonificaciones
 IF (isset($_POST['producto'])){


     $productobusqueda=$_POST['producto'];

     $_SESSION['producto'] = $productobusqueda;


     /*
    $buscarconvenio = sprintf("SELECT * FROM detalle_convenio WHERE n_agente=%s AND cve_cte=%s AND cve_prod=%s",
            GetSQLValueString($idagente, "int"),
            GetSQLValueString($nombre, "text"),
            GetSQLValueString($productobusqueda, "text") );

      $querybuscarconvenio= mysqli_query($conecta1, $buscarconvenio) or die (mysqli_error($conecta1));

      $fecthbusqueda = mysqli_fetch_assoc($querybuscarconvenio);

      $precioconvenio = $fecthbusqueda['precio_representante'];
      $cantidadconvenio = $fecthbusqueda['cant_prod'];
      $boniproductoid = $fecthbusqueda['boni_productoid'];
      $boniprecioventa = $fecthbusqueda['boni_precioventa'];
      $bonipesos = $fecthbusqueda['boni_precioporunidad'];
      $bonifactor = $fecthbusqueda['boni_cantidadporunidad'];



      $boniproductobusqueda=sprintf("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' AND  ItemCode=%s ",
                            GetSQLValueString($boniproductoid, "text"));
      $boniqueryproductobusqueda = mssql_query($boniproductobusqueda);
      $bonifetchproductobusqueda=mssql_fetch_assoc($boniqueryproductobusqueda);
      $boninombreproductobusqueda = $bonifetchproductobusqueda['ItemName'];
      $boniestado=$fecthbusqueda['boni_estado'];

      $_SESSION['boniestado'] = $boniestado;

      */

 }



  IF (isset($_REQUEST['eliminar'])){

     $cveprodeliminat = $_REQUEST['eliminarprod'];


     $eliminar_registro= sprintf("DELETE FROM detalle_pedido WHERE id_detalle=%s",
            GetSQLValueString($_REQUEST['eliminar'], "int"));
      $eliminar= mysqli_query($conecta1, $eliminar_registro) or die (mysqli_error($conecta1));



      $eliminarhistorial=sprintf("DELETE FROM historia_estatus where n_remision=%s and  n_agente=%s and cve_cte=%s and cve_prod=%s",
		              GetSQLValueString($_SESSION['folio'], "int"),
					  GetSQLValueString($idagente, "int"),
					  GetSQLValueString($nombre, "text"),
					  GetSQLValueString($cveprodeliminat, "text"));

	      $queryeliminarhistorial= mysqli_query($conecta1, $eliminarhistorial) or die (mysqli_error($conecta1));
  }


  IF (isset($_POST['agregar'])){

if( $_POST['preciosub']<=0)
{

 $men='<div class="alert alert-danger" role="alert">
  El precio Asignado por el Agente, Debe ser mayor a 0
</div>';



}


    if( $_POST['precio']<$_POST['preciosub'])
{

 $men='<div class="alert alert-danger" role="alert">
  El precio Asignado por el Agente, Debe ser menor al Precio Original
</div>';



}
if($_POST['preciosub']>0 && $_POST['precio']>=$_POST['preciosub'])
{



      $cveprod= $_POST['producto'];


      ///Precio lista  10
      $consultacomparacion = sprintf($querylista10." WHERE ItemCode=%s ",
                            GetSQLValueString($cveprod, "text"));
      $querycomparacion = mssql_query($consultacomparacion);
      $fetchcomparacion=mssql_fetch_assoc($querycomparacion);
      $productocomparado = $fetchcomparacion['Price'];

      $string_nombre=  sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode =%s",
                        GetSQLValueString($cveprod, "text"));
      $querynombre = mssql_query($string_nombre);
      $fetchnombre=mssql_fetch_assoc($querynombre);

      $nombreprod=$fetchnombre['ItemName'];
      $unidad=$fetchnombre['U_UnidadMedida'];
      $factordist = $fetchnombre['SalFactor4'];

      $cantidad = $_POST['volumen'];
      $precioprod=$_POST['precio'];
      $dctoprod=$_POST['descuento'];
      $ieps=$_POST['ieps'];
      $iva = $_POST['iva'];
      $totalprod = $_POST['total'];
      $moneda = $_SESSION["moneda"];
      $preciorep = $_POST['preciosub'];
      $estatus;


      $preciosin=$_POST['totalsin'];

      $totalfactura=$_POST['totalfactura'];
       $preciofactura=$_POST['preciofactura'];

      $bonificacion = $_POST['bonificacion'];



        if( $iva>0 ){
          $iva100 = $iva/100;
      } else {
          $iva100 =.0;
      }
      ///codigo generado por EAGA 10-10-2016

      if($_POST['incrementopp']){
          //si aplica el 6% de incremento por ser a credito
        //  $p_factura=$preciorep*(1+($_SESSION['incrementopp']/100)); ///se le agrega el procentaje de incremento

           $p_factura=$preciorep/(1-($_SESSION['incrementopp']/100)); ///se le agrega el procentaje de incremento cambioa el calculo considerando el factor
      }else{
          $p_factura=$preciorep;
      }

      $mieps=$p_factura*($ieps/100);
      $antesiva=$p_factura+$mieps;
      $miva=$antesiva*$iva100;
      $p_pagar=$antesiva+$miva;

      $dctoprod=100*(1-($p_factura/$precioprod));   ///el verdadero descuento del producto
      $totalprod=$p_factura*$cantidad;
      $totalfactura=$p_pagar*$cantidad;

      ///


      if($p_factura>$productocomparado && $p_factura<$precioprod){    /// if($preciorep>$productocomparado && $preciorep<$precioprod){

          $autorizar = 1;
          $estatus = 'E';

      }else{
          $autorizar = 0;
          $estatus = 'A';
      }

      if($bonificacion==1){
          $autorizar=0;
          $estatus='A';
      }

        if ($cveprod=="DISTLIQ" OR $cveprod=="DISTPOLV" OR $cveprod=="SERVDIST"){  //Los servicios de distribucion no es necesario autorizarlos  13-09-2016  EAG
              $autorizar = 1;
              $estatus = 'E';
              $dctoprod=0;    //Servicio de distribucion no tiene descuento
              $precioprod=$preciorep;   //es el mismo que el representante asigno
        }




      if($_SESSION["moneda"]==0){
          $totalprodmx = $totalprod * $_SESSION["tipo_cambio"];
          $totalfacturamxp=$totalfactura * $_SESSION["tipo_cambio"];

      }else {
          $totalprodmx = $totalprod;
          $totalfacturamxp=$totalfactura;
      }



       if ($bonificacion==1){
	    $bonificacion_ley="S";
	    $autorizar=0;
	    $estatus="A";
	    $totalprod=0;
	    $totalprodmx=0;
            $totalfactura=0;
            $totalfacturamxp=0;
        }




      $insertproducto = sprintf("INSERT INTO detalle_pedido SET n_remision=%s, n_agente=%s, nom_age=%s, fecha_alta=CURDATE(), "
              . "cve_cte=%s, nom_cte=%s, cve_prod=%s, nom_prod=%s, cant_prod=%s, precio_prod=%s, dcto_prod=%s, ieps=%s, iva=%s, "
              . "total_prod=%s, moneda_prod=%s,cant_falta=%s, litkg_unidad=%s, fact_ds=%s, precio_representante=%s,"
              . "au_gerente=%s,au_dc=%s, estatus=%s, precio_condcto=%s, precio_politica=%s,tipo_cambio=%s,total_prodmxp=%s, "
              . "precio_pagar=%s, precio_factura=%s, total_factura=%s,bonificacion=%s, bandera_especial=1",
                    GetSQLValueString($_SESSION['folio'], "int"),
                    GetSQLValueString($idagente, "int"),
                    GetSQLValueString($nombreagen, "text"),
                    GetSQLValueString($nombre, "text"),
                    GetSQLValueString($clientenombre, "text"),
                    GetSQLValueString($cveprod, "text"),
                    GetSQLValueString($nombreprod, "text"),
                    GetSQLValueString($cantidad, "double"),
                    GetSQLValueString($precioprod, "double"),
                    GetSQLValueString($dctoprod, "double"),
                    GetSQLValueString($ieps, "double"),
                    GetSQLValueString($iva100, "double"),
                    GetSQLValueString($totalfactura, "double"),
                    GetSQLValueString($moneda, "int"),
                    GetSQLValueString($cantidad, "double"),
                    GetSQLValueString($unidad, "text"),
                    GetSQLValueString($factordist, "double"),
                    GetSQLValueString($preciorep, "double"),

                    GetSQLValueString($autorizar, "int"),
                    GetSQLValueString($autorizar, "int"),

                    GetSQLValueString($estatus, "text"),

                    GetSQLValueString($p_factura, "double"),   ///precio que incluye el incremento si aplica
                    GetSQLValueString($productocomparado, "double"),

                    GetSQLValueString($_SESSION["tipo_cambio"], "double"),
                    GetSQLValueString($totalfacturamxp, "double"),

                    GetSQLValueString($preciorep, "double"),
                    GetSQLValueString($p_pagar, "double"),
                    GetSQLValueString($totalfacturamxp, "double"),
                    GetSQLValueString($bonificacion_ley, "text")
              );



      $queryinsertprod=mysqli_query($conecta1, $insertproducto) or die (mysqli_error($conecta1));




$consulta_sql3=sprintf("Select id_detalle from detalle_pedido where n_remision=%s and  n_agente=%s and cve_cte=%s and cve_prod=%s",
		              GetSQLValueString($_SESSION['folio'], "int"),
					  GetSQLValueString($idagente, "int"),
					  GetSQLValueString($nombre, "text"),
					  GetSQLValueString($cveprod, "text"));

		$resultado3=mysqli_query($conecta1,$consulta_sql3) or die (mysqli_error($conecta1));
		$id_detalle=mysqli_fetch_assoc($resultado3);


   // Agregar registro al historial
	   $fecha_hoy2=date("Y-m-d H:i:s");
           $fecha_hoy3=date("Y-m-d");
	   $comentario1="Alta Producto/Pedido";
		/// Crear registro en la tabla de historia_estatus
		//Nota en la fecha de alta contiene la fecha promesa de Entrega del producto  24-07-2013
							$insertSQL = sprintf("INSERT INTO historia_estatus (id, n_remision, cve_cte, n_agente, fecha_alta, nom_cte, nom_age, fecha, cve_prod, nom_prod, estatus_a, estatus_b,comentario, comentario1, comentario2)VALUES (%s,%s,%s,%s,%s,%s,%s,%s, %s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($id_detalle['id_detalle'], "int"),
							   GetSQLValueString($_SESSION['folio'], "int"),
							   GetSQLValueString($nombre, "text"),
							   GetSQLValueString($idagente, "int"),
                                                           GetSQLValueString($fecha_hoy3, "date"),
							   GetSQLValueString($clientenombre, "text"),
							   GetSQLValueString($nombreagen, "text"),
							   GetSQLValueString($fecha_hoy2, "date"),
                                                          GetSQLValueString($cveprod, "text"),
							   GetSQLValueString($nombreprod, "text"),
							   GetSQLValueString($preciorep, "text"),
							   GetSQLValueString($precioprod, "text"),
                                                           GetSQLValueString($cantidad, "double"),
                                                        GetSQLValueString($comentario1, "text"),
                                                        GetSQLValueString($totalprod, "double")
                                                               );
							   $queryhistorial=mysqli_query($conecta1,$insertSQL) or die (mysqli_error($conecta1));



 }
 }
  IF (isset($_POST['guardar'])){

      $checarautoriza = sprintf("SELECT * FROM detalle_pedido WHERE n_remision = %s AND au_gerente = 1 ",
                        GetSQLValueString($folio, "int"));
      $queryautoriza=mysqli_query($conecta1, $checarautoriza) or die (mysqli_error($conecta1));

      $rownsautoriza = mysqli_fetch_row($queryautoriza);
      IF($rownsautoriza>0){
          $status = 'E';
      }else{
          $status = 'A';
      }

	       $usuario="credito";
               $destinatario='erikito1981@gmail.com';
              // $destinatario2="badame@agroversa.com.mx";
               $destinatario2[0]='egonzalez@agroversa.com.mx';  //jefe de credito y cobranza
               $destinatario2[1]=mail_otros("auxcxc");   //auxiliar de cyc   Argentina
	       $destinatario2[2]=mail_otros("sistemas");  //auxiliar de sistemas   Erik
               $destinatario2[3]=mail_otros("asisdircom");  //asistente de direccion
               $destinatario2[4]=$_SESSION["email"];  //vendedor
               $destinatario2[5]=mail_otros("serviciocte");  //Atencion al Cliente e mena

               $destinatario2[6]=  gestor_mail($_SESSION['usuario_agente']);  //Gestor Credito y Cobranza


     //Funcion para validar si el pedido sera bloqueado por credito o no el resultado d$_SESSION['plazo']ebe ser C=Credito o E=Emitida
        $status=  avisocxc($nombre, $_POST["totaltodo"], $_SESSION['plazo'], $_SESSION["moneda"], $destinatario, $destinatario2,$_SESSION['folio']);


      $observacion =$_POST['observaciones'];
      $destino = $_SESSION["destino"];
      $moneda = $_SESSION["moneda"];
      $plazo = $_SESSION['plazo'];
      $tipoventa = $_SESSION["pago"];
      $totalprod2 = $_POST["totaltodo"];
      $servdist = $_POST['totalservdist'];
      $ORD =  $_SESSION["ORD"] ;
      $MTPG = $_SESSION["MTPG"];

      $dcto_pp=$_SESSION["dcto_pp"];

      //Total en Pesos siempre
      if ($moneda==0){
          $total_pesos=$totalprod2*$_SESSION["tipo_cambio"];
      }else{
          $total_pesos=$totalprod2;
      }


      $updatestring = sprintf("UPDATE detalle_pedido SET terminada=1 WHERE n_remision=%s",
                        GetSQLValueString($_SESSION['folio'], "int"));
      $queryupdate=mysqli_query($conecta1, $updatestring) or die (mysqli_error($conecta1));

       $metodo_pago=$_SESSION['metodo_pago'];  ///Dato necesario para identificar como se pagara la factura  23-01-2017

      $tipo_agente= revisa_zona($_SESSION["Zona"]);   ///asignar el tipo de agente 1=Local  2=Foraneo  3=Verur, Maquila
      $insertencabeza = sprintf("INSERT INTO encabeza_pedido SET vbo_gerente=1, comentario_gerente='AUTORIZADO PLATAFORMA', timeres_gerente=NOW(),vbo_gestor=1, comentario_gestor='AUTORIZADO PLATAFORMA',timeres_gestor=NOW(),  
       n_remision = %s, fecha_alta=CURDATE(),"
              . "cve_cte=%s, nom_cte=%s,estatus=%s, n_agente=%s, observacion=%s,destino=%s, moneda=%s, plazo=%s, tipo_venta=%s, medio_pago=%s,"
              . "total=%s,total_p=%s,nom_age=%s,fact_ds=%s,localidad=%s, encbandera_especial=1, id_entregas=%s, tipo_agente=%s, indica_entrega=%s, bodega_entrega=%s,opCFDI=%s ,ORD=%s,MTDPG =%s ,dcto_pp=%s",
              GetSQLValueString($_SESSION['folio'], "int"),
              GetSQLValueString($nombre, "text"),
              GetSQLValueString($clientenombre, "text"),
             ///GetSQLValueString($status, "text"),
             GetSQLValueString('C', "text"),
              GetSQLValueString($idagente, "int"),
              GetSQLValueString($observacion, "text"),
              GetSQLValueString($destino, "text"),
              GetSQLValueString($moneda, "int"),
              GetSQLValueString($plazo, "int"),
              GetSQLValueString($tipoventa, "int"),
               GetSQLValueString($metodo_pago, "text"),
              GetSQLValueString($totalprod2, "double"),
               GetSQLValueString($total_pesos, "double"),  ///este dato falto 26-09-16
              GetSQLValueString($nombreagen, "text"),
              GetSQLValueString($servdist, "double"),
              GetSQLValueString($localidad, "int"),
              GetSQLValueString($_SESSION["destinoid"], "int"),
               GetSQLValueString($tipo_agente, "int"),    ///este dato falto 25-10-2016 EAGA
                GetSQLValueString($_SESSION["indicaentrega"], "int"),       ///este dato falto 17-11-2016 EAGA
               GetSQLValueString($_SESSION["indicaentrega_bodega"], "text"),   ///este dato falto 17-11-2016 EAGA
               GetSQLValueString($_SESSION["OPCFDI"], "text"),  ///este dato falto 17-11-2016 EAGA
               GetSQLValueString($ORD, "text"),///agregado  02/01/2018
                GetSQLValueString($MTPG, "text"),  /// agregado  02/01/2018
                GetSQLValueString($dcto_pp, "text")
        );
      $queryinsertencabeza=mysqli_query($conecta1, $insertencabeza) or die (mysqli_error($conecta1));


       ////09-09-2014
                  $razones= razonescxc($nombre, $_POST["totaltodo"], $_SESSION['plazo'], $_SESSION["moneda"], $destinatario, $destinatario2);

                            if ($status=="C"){
                                //insertar un registro en la tabla de motivo_cyc
                                $insermotivo = sprintf("INSERT INTO motivo_cyc (n_remision,cve_cte,n_agente,razon)VALUES (%s,%s,%s,%s)",
                                                               GetSQLValueString($_SESSION['folio'], "int"),
                                                               GetSQLValueString($nombre, "text"),
                                                               GetSQLValueString($idagente, "int"),
                                                               GetSQLValueString($razones, "text"));
                                $r=mysqli_query($conecta1, $insermotivo) or die (mysqli_error($conecta1));
                            }

                     avisovta($nombre, $_SESSION['folio'],  $_SESSION["usuario_agente"],  $_SESSION["usuario_tipo"], $_SESSION["email"], $destinatario2);    //Notifica los productos que necesitan autorización

                     if($_SESSION["moneda"]==0){ $monedabien= 'USD';}else{$monedabien='MXN';}
                       //Agente Local
                      $today=date("Y-m-d H:i:s");
                            $usuario_notifica="faclocal";
                            $destinatario_notifica="erikito1981@gmail.com";
                            $destinatario2_notifica[0]=email("facforanea");
                            $destinatario2_notifica[1]=email("faclocal");

                            $fromname="Pedidos de Ventas CRONOS";
                            $subject="Remision Creada No.: ".$_SESSION['folio'];
                            $mensaje="<p> Hora Captura Plataforma : ".$today."</p>";
                            $mensaje.="<p> Cliente : ".$nombre."</p>";
                            $mensaje.="<p> Nombre Cliente : ".$clientenombre."</p>";
                            $mensaje.="<p> Agente : ".$_SESSION['usuario_nombre']."</p>";
                            $mensaje.="<p> Moneda : ".$monedabien."</p>";
                            $mensaje.="<p> Monto Total del Pedido : ".$totalprod2."</p>";
                            $mensaje.="<p> Plazo : ".$_SESSION['plazo']."</p>";
                             $mensaje.="<p>CRONOS 2016</p>";

                     //funcion que notifica por mail del pedido nuevo a Agente,Facturista,Gerente,y mas
                       notifica_pedidonuevo($_SESSION['folio'], $nombre, $clientenombre,  $_SESSION["usuario_agente"], $_SESSION['usuario_nombre'], $monedabien, $totalprod2, $_SESSION['plazo'], $_SESSION["email"]);
                     //    correos($fromname,$subject,$destinatario_notifica,$destinatario2_notifica,$mensaje);   //Mandar correo


    //Actualiza tabla de folios
    folio_pedido_guardar($_SESSION["usuario_agente"], $_SESSION['folio'],1);


  $MM_restrictGoTo = "index.php";
  header("Location: ". $MM_restrictGoTo);


  }


  IF (isset($_POST['descartar'])){

      ///Descartar el pedido actual

     $string_descartar=sprintf("delete  from detalle_pedido where n_remision=%s and  n_agente=%s and cve_cte=%s and terminada=0",
		              GetSQLValueString($_SESSION['folio'], "int"),
			      GetSQLValueString($_SESSION["usuario_agente"], "int"),
		              GetSQLValueString($_SESSION["cliente"], "text"));

     @mysqli_query($conecta1, $string_descartar) or die (mysqli_error($conecta1));
	$MM_restrictGoTo = "index.php";
      header("Location: ". $MM_restrictGoTo);

  }



 $stringtabla = sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and bandera_especial=1",
                GetSQLValueString($_SESSION['folio'], "int"));

$tablaquery=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));

$tablaservicio=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));
$tablaservicio2=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));
//$tablaservicio3=mysqli_query($conecta1, $stringtabla) or die (mysqli_error($conecta1));




 $centinela6=revisaincremento6($_SESSION["Zona"],$_SESSION["plazo"]);
 $aplica_cajacerrada=revisa_solocajacerrada($_SESSION["Zona"]);   ///0= No aplica   1=SI aplica 31-10-2016


 ///excepcion para el cliente solictado por Lromero 30-11-2016 EAGA
 switch ($_SESSION["cliente"]) {

        case "C002600":
             $aplica_cajacerrada=0;
        break;

        case "C003238":
             $aplica_cajacerrada=0;
        break;

        case "C002010":
             $aplica_cajacerrada=0;
        break;

         case "C003574":
             $aplica_cajacerrada=0;
        break;
        case "C002623":
                $aplica_cajacerrada=0;
           break;
        case "C002622":
                $aplica_cajacerrada=0;
           break;
        case "C004271":
                $aplica_cajacerrada=0;
           break;
        case "C003254":
                $aplica_cajacerrada=0;
           break;
        case "C003736":
                $aplica_cajacerrada=0;
           break;
          case "C002091":
                $aplica_cajacerrada=0;
           break;
          case "C001870":
                $aplica_cajacerrada=0;
           break;
          case "C003526":
                $aplica_cajacerrada=0;
           break;
          case "C003736":
                $aplica_cajacerrada=0;
           break;
         case "C004054":
                $aplica_cajacerrada=0;
           break;
        case "C004346":
                $aplica_cajacerrada=0;
           break;
        case "C003096":
                $aplica_cajacerrada=0;
           break;
        case "C010159":
                $aplica_cajacerrada=0;
           break;
        case "C001927":
                $aplica_cajacerrada=0;
           break;
        case "C001248":
                $aplica_cajacerrada=0;
           break;
        case "C003184":
                $aplica_cajacerrada=0;
           break;
        case "C001786":
                $aplica_cajacerrada=0;
           break;
       case "C002553":
                $aplica_cajacerrada=0;
           break;
         case "C002555":
                $aplica_cajacerrada=0;
           break;
          case "C002525":
                $aplica_cajacerrada=0;
           break;
        case "C003663":
                $aplica_cajacerrada=0;
           break;
        case "C002111":    //Solicitado por Adrian almacen 20-01-2017
                $aplica_cajacerrada=0;
           break;
        case "C003689":    //Solicitado por Adrian almacen 20-01-2017
                $aplica_cajacerrada=0;
           break;
         case "C003388":    //Solicitado por Adrian almacen 20-01-2017
                $aplica_cajacerrada=0;
           break;
          case "C002607":    //Solicitado por Adrian almacen 20-01-2017
                $aplica_cajacerrada=0;
           break;
        case "C003843":    //Solicitado por Jorge Perez  07-04-2017
                $aplica_cajacerrada=0;
           break;
        case "C001748":    //Solicitado por Jose Luis  08-04-2017
                $aplica_cajacerrada=0;
           break;
       case "C003455":    //Solicitado por Jose Luis  25-04-2017
                $aplica_cajacerrada=0;
           break;
       case "C002736":    //Solicitado por Jose Luis  10-05-2017
                $aplica_cajacerrada=0;
           break;
         case "C004346":    //Solicitado por Jose
                $aplica_cajacerrada=0;
           break;
           case "C002645":    //Solicitado por Jose
                $aplica_cajacerrada=0;
           break;
      case "C002683":    //Solicitado por Adrian Reyes
                $aplica_cajacerrada=0;
           break;
 }



 /*   Desconocido 09-08-2016
$queryinsert = sprintf("SELECT * FROM detalle_pedido WHERE n = %s",
GetSQLValueString($codigo, "text"));
$facturadatos = mssql_query($queryinsert);
*/



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



function isInt (i) {
		return (i % 1) == 0;
}


function validarcajacerrada(){
var volumen = document.forma1.volumen.value;
var facor_empaque = document.forma1.salpackun.value;

var empaques;
var resultado;

empaques=volumen/facor_empaque;
dato=isInt(empaques);
//resultado=isInteger(dato);
//alert (dato);

if (dato==false){
  alert ('Cantidad no válida');

   document.getElementById("volumen").focus();
    return false;
}

return true;


}




function calculate() {
 var incrementopp= document.forma1.incrementopp.checked;
var descuento = document.forma1.descuento.value;
var ipp=document.forma1.ipp.value;

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
var p_factura;

///codigo modificado por EAGA  10-10-16
if (incrementopp){
    // alert (ipp);
    p_factura=preciopublico*(1+(ipp/100));
  // alert (p_factura);



}else{
   // alert ('sin incremento');
    p_factura=preciopublico;
   //  alert (p_factura);
}




//descuento
///var calculo = (preciopublico*100)/precio;
var calculo = (p_factura*100)/precio;
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








///preciofactura= sub_totalsin/(0.94);
preciofactura= sub_totalsin;
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
</script>

<div class="container">
    <form name="forma1" method="POST" action="pedidos-especial_sigue.php"  <?php if($aplica_cajacerrada>0){ echo 'onsubmit="return validarcajacerrada()"'; }  ?>  >
    <div class="page-header">
        <h3>Pedido Especial Folio: <?php echo $_SESSION['folio']; echo $string_descartar; ?></h3>




        <div class="col-md-4">
            <br>

            <p>Nombre: <?php echo $clientenombre;?></p>
            <p>Domicilio: <?php echo $_SESSION["destino"];?></p>
            <p>Crédito Disponible: <?php $corriente = $limite-$saldo; echo number_format($corriente, 2, '.', ','); ?></p>
            <p>Límite de Crédito: <?php echo number_format($limite, 2, '.', ',');?></p>
              <p><strong>Metodo De Pago </strong><?php    echo   $_SESSION["MTPG"];  ?>  </p>
              <p> <strong>Orden De Compra </strong>
              <?php if  ($_SESSION["SIHAYARCHIVOS"] == "1" ) { ?>
                <button data-toggle="modal" data-target="#filesModal" name="VIEWFILE" id="VIEWFILE" type="button"  class="btn btn-lg btn-danger"> Archivos</button>
                                                 <?php  } ?>
              </p>
            </div>
            <div class="col-md-6">
                <br>
            <p>Plazo: <?php echo $_SESSION['plazo'];?></p>
            <p>Moneda: <?php if($_SESSION["moneda"]==0){ echo 'USD'; }else{ echo 'MXN'; }?></p>
            <p>Pago: <?php if($_SESSION["pago"]==1){ echo 'Crédito';}else{ echo 'Contado';}?>, Método Pago:<?php  echo $_SESSION['metodo_pago']; ?></p>
            <p>Destino: <?php
             list($calle,$colonia,$ciudad,$cp,$estado,$pais)=dir_entregas($_SESSION["destinoid"]);

            echo $calle.', '.$colonia.','.$ciudad.', '.$cp.', '.$estado.', '.$pais;


            ?></p>
            <p>Indicaciones Entrega:<?php
            if ($_SESSION["indicaentrega"]==3){
                   ///echo  $_SESSION["indicaentrega"];///  " A LOGISTICA";  ///El encargado de entregar el producto es Logistica
                   echo 'Logistica';
            }else{
               echo    indica_entrega($_SESSION["indicaentrega"])." en ".   nombre_almacen($_SESSION["indicaentrega_bodega"]);    //Cliente o Agente entregan el Pedido

            }



            ?></p>

            <p><strong>Orden de Compra </strong><?php echo   $_SESSION["ORD"] ;
                                                      ///  $_SESSION["MTPG"];
             ?>  </p>


        </div>


    </div>




        <div class="col-md-12">
            <div class="alert alert-danger"> <?php
                         ///codigo que nos ayudara para mostrar al agente si el pedido que esta capturando va estar detenido por Credito o por Autorización de Ventas
                    $totalprod2 = $_POST["totaltodo"];
                    $destinatario="";
                    $destinatario2="";
                    $mensaje_ayuda=  razonescxc($codigo, $totalprod2, $_SESSION['plazo'], $_SESSION['pago'], $destinatario, $destinatario2);
                    echo $mensaje_ayuda.'<br>';
                    ///


                 //notificar en pantalla si existen productos que necesitaran autorización

                  $productos_xautorizar=sprintf("SELECT cve_prod, nom_prod, cant_prod, estatus FROM detalle_pedido WHERE n_remision=%s and n_agente=%s and cve_cte=%s and estatus='A'",
                                 GetSQLValueString($_SESSION['folio'], "int"),
                                 GetSQLValueString($_SESSION["usuario_agente"], "int"),
                                 GetSQLValueString($codigo, "text"));
                 $query_xautorizar =mysqli_query($conecta1, $productos_xautorizar) or die (mysqli_error($conecta1));



                     WHILE ($prodxautorizar= mysqli_fetch_array($query_xautorizar)){
                         ///si entro en este codigo nos indica que si hay productos que van ha necestar autorización

                         if ($prodxautorizar=='A'){
                             $leyendacyx="Necesita Autorización";
                             $cent1=1;
                         }else{
                              $leyendacyx="";
                               $cent1=0;
                         }

                         $centi2=  revisa_existe_prod($prodxautorizar['cve_prod'], $_SESSION["usuario_agente"], $prodxautorizar['cant_prod']);

                         if ($centi2==0){
                             $leyendaexiste="No hay Existencia";
                         }else{
                              $leyendaexiste="";
                         }


                        if ($cent1>1 or $centi2==0){
                              echo $leyendacyx.$prodxautorizar['cve_prod'].'-'.$prodxautorizar['nom_prod'].','.$leyendaexiste.'<br>';   //mostrar la leyende solo cuando tenga algunos de los 2 casos
                         }



                     }


            ?>
            </div>

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

            <div class="col-md-2">
                <label>Volumen:</label>
                <input id="volumen" name="volumen" type="text" <?php if($aplica_cajacerrada>0){ echo 'onchange="validarcajacerrada()"'; }  ?> class="form-control">

            </div>

            <div class="col-md-2">
                <label>Precio:</label>
                <input id="preciosub" required="" name="preciosub" type="text" value="<?php if($precioconvenio>0){ echo $precioconvenio;}  ?>"  class="form-control"   onchange="calculate();">

            </div>


            <div class="col-md-2">
                   <label class="checkbox" for="checkbox1">
                    <input type="checkbox" value="1" id="checkbox1" data-toggle="checkbox" name="incrementopp" <?php  if ($centinela6==1){ echo 'checked';  }else{ echo 'disabled';   }   ?> >
                    Incremento 6% al Precio
                </label>
                <label class="checkbox" for="checkbox1">
                    <input type="checkbox" value="1" id="checkbox1" data-toggle="checkbox" name="bonificacion">
                    Bonificación
                </label>

            </div>


        </div>
       <?php echo  $men; ?>
        <p>

        </p>
        <br>


        <div class="col-md-8">

            <table >
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Precio</th>
                        <th>Empaque</th>
                        <th>IEPS</th>
                        <th>IVA</th>


                    </tr>
                </thead>
                <tbody>
           <tr>
                        <td><input type="text" id="clave" name="clave"  value="<?php echo $datos_prod['ItemCode'] ?>" class="form-control" readonly placeholder="Codigo"> <input type="hidden" id="nombre_prod" name="nombre_prod" value="<?php echo $datos_prod['ItemName'] ?>"></td>
                        <td><input  style="text-align: right" type="text" id="precio" name="precio" value="<?php echo $datos_prod['Price'] ?>" class="form-control" readonly></td>
                        <td><input  style="text-align: right" type="text" id="salpackun" name="salpackun" value="<?php echo $datos_prod['SalPackUn'] ?>" class="form-control" readonly></td>
                        <td ><input  style="text-align: right" type="text" id="ieps" name="ieps" value="<?php if($datos_ieps['Rate']==""){echo 0;}else{echo $datos_ieps['Rate'];} ?>" class="form-control" readonly ></td>
                        <td ><input  style="text-align: right" type="text" id="iva" name="iva" value="<?php if($datos_iva['Rate']==""){echo 0;}else{echo $datos_iva['Rate'];} ?>" class="form-control" readonly ></td>
                        <td style="display:none;"><<input type="text" id="descuento" name="descuento" class="form-control" readonly ></td>

                        <td style="display:none;"><input type="hidden" id="subtotal" name="subtotal" class="form-control" readonly ></td>
                        <td><input style="display:none;" type="text" id="subtotal2" name="subtotal2" class="form-control" readonly ></td>

                        <td style="display:none;"><<input style="display:none;" type="text" id="total" name="total" class="form-control" readonly ></td>

<!-- ////////////////////////////////calculos de precio pagar y factura    -->
                        <td style="display:none;" ><input type="hidden" id="totalsin" name="totalsin" class="form-control" readonly ></td>
                        <td ><input  style="display:none;" type="text" id="totalsin2" name="totalsin2" class="form-control" readonly ></td>


                        <td  style="display:none;"><input type="hidden" id="preciofactura" name="preciofactura" class="form-control" readonly ></td>
                         <td style="display:none;" ><input  style="text-align: right" type="text" id="preciofactura2" name="preciofactura2" class="form-control" readonly ></td>


                        <td style="display:none;" ><input type="hidden" id="totalfactura" name="totalfactura" class="form-control" readonly ></td>
                        <td style="display:none;"><input  style="text-align: right" type="text" id="totalfactura2" name="totalfactura2" class="form-control" readonly ></td>




                <input type="hidden" id="ipp" name="ipp" value="<?php echo $_SESSION['incrementopp']; ?>" class="form-control"  readonly >
                <input type="hidden" id="precio4" name="precio4" value="<?php echo $datos_prod4['Price'] ?>" class="form-control"  readonly >
                <input type="hidden" id="precio5" name="precio5" value="<?php echo $datos_prod5['Price'] ?>" class="form-control"  readonly >

                </tr>

                </tbody>

            </table>

        </div>
        <br>
         <p>
                     <button name="agregar" type="submit" class="btn btn-lg btn-primary pull-right">Agregar</button>
    </form>
 <div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                  <tr>
                     <th>Código</th>
                     <th>Producto</th>
                     <th>Cant</th>
                     <th>Precio</th>
                     <th>Precio Rem </th>
                     <th>Dcto %</th>
                     <th>IEPS</th>
                     <th>IVA</th>
                     <th>Precio Fac</th>
                     <th>Precio Pag</th>
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
                     <td><?php echo '$'.$registro1['precio_prod'];?></td>
                     <td><?php echo '$'.$registro1['precio_representante'];?></td>
                     <td><?php echo number_format((float)$registro1['dcto_prod'], 2, '.', '');?></td>
                     <td><?php echo floor($registro1['ieps']).'%';?></td>
                     <td><?php echo ($registro1['iva']*100).'%';?></td>
                     <td><?php echo '$'.number_format($registro1['precio_condcto'], 2, '.', ',');?></td>
                     <td><?php echo '$'.number_format($registro1['precio_factura'], 2, '.', ',');?></td>
                     <td><?php

                      if(is_null($registro1['bonificacion'])){
                        echo '$'.number_format($registro1['precio_condcto']*$registro1['cant_prod'], 2, '.', ',');

                      }

                     ?> </td>



                     <td><?php echo '$'.number_format($registro1['precio_factura']*$registro1['cant_prod'], 2, '.', ',');?></td>
                     <td><a href="pedidos-especial_sigue.php?eliminar=<?php echo $registro1['id_detalle']; ?>&eliminarprod=<?php echo $registro1['cve_prod']; ?>"  onclick="return confirm('¿Está Seguro de Eliminar?')"><img src="images/eliminar.png"/></a></td>




                 </tr>
                   <?php
                   $total = $total +$registro1['total_prod'];



                 } ?>
                 <tr>
                     <td>Total: <?php echo '$'.number_format($total, 2, '.', ',');?> </td>
                      <input type="hidden" name="totaltodo" value="<?php echo $total ?>" class="form-control"  readonly >


                 </tr>
             </tbody>


         </table>



             </div>
                 </p>




    <br>

    <div class= " col-lg-6" style="<?php  echo "display: none;";   ///aqui iba un codigo el cual checaba la variable servdist=2 EAGA ?>">


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

        <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Leyenda</th>
                     <th>Cantidad</th>
                 </tr>
             </thead>
             <tbody>
                 <?php
                 //No se incluye el producto bonificado
                 $stringtabla3 = sprintf("SELECT * FROM detalle_pedido WHERE n_remision=%s and isnull(bonificacion) ",
                                     GetSQLValueString($_SESSION['folio'], "int"));
                 $tablaservicio3=mysqli_query($conecta1, $stringtabla3) or die (mysqli_error($conecta1));
                 WHILE ($filaimporte= mysqli_fetch_array($tablaservicio3)){


                 switch($filaimporte['ieps']){
                     case 0:
                         $calculoieps0=0;
                         $ieps = 0;
                         break;
                     case 6:
                         $calculoieps6=.06;
                         $subieps6 = $calculoieps6 * $filaimporte['precio_condcto'] * $filaimporte['cant_prod'];
                         $calculoiepss6 += $subieps6;
                         $ieps=$subieps6;
                         break;
                     case 7:
                         $calculoieps7=.07;
                         $subieps7 = $calculoieps7 * $filaimporte['precio_condcto'] * $filaimporte['cant_prod'];
                         $calculoiepss7 += $subieps7;
                         $ieps=$subieps7;

                         break;
                     case 9:
                         $calculoieps9=.09;
                         $subieps9 = $calculoieps9 * $filaimporte['precio_condcto'] * $filaimporte['cant_prod'];
                         $calculoiepss9 += $subieps9;
                         $ieps=$subieps9;
                         break;


                 }

                 $acumulado+=$ieps;




                  $calculosubtotal = $filaimporte['cant_prod'] * $filaimporte['precio_prod'];

                  $subtotalimporte += $calculosubtotal;

                  $calculodescuento = $filaimporte['cant_prod'] * $filaimporte['precio_prod'] * ($filaimporte['dcto_prod'] / 100);
                  $totaldescuento += $calculodescuento;

                  $caliva = (($filaimporte['precio_condcto'] * $filaimporte['cant_prod'])+$ieps) * ($filaimporte['iva']);
                  $totaliva +=  $caliva;
                 }
                 ?>
                     <tr>
                     <td>Subtotal</td>
                     <td><?php echo '$'.number_format(floor($subtotalimporte));  ?></td>
                     </tr>
                     <tr>
                     <td>Descuento</td>
                     <td><?php echo '$'.number_format(floor($totaldescuento)); ?></td>
                     </tr>
                     <tr>
                     <td>IVA 16%</td>
                     <td><?php echo '$'.number_format(floor($totaliva));  ?></td>
                     </tr>
                     <tr>
                     <td>IEPS 6.0</td>
                     <td><?php echo '$'.number_format(floor($calculoiepss6)); ?></td>
                     </tr>
                     <tr>
                     <td>IEPS 7.0</td>
                     <td><?php echo '$'.number_format(floor($calculoiepss7)); ?></td>
                     </tr>
                     <tr>
                     <td>IEPS 9.0</td>
                     <td><?php echo '$'.number_format(floor($calculoiepss9)); ?></td>
                     </tr>
                     <tr>
                     <td>Serv. De Dist.</td>
                     <td><?php if($servdist==1){ echo '$'.number_format(floor($totalsd));} else {$totalsd=0; echo '$'.number_format(floor($totalsd));} ?></td>
                     </tr>
                     <tr>
                     <td>Total</td>
                     <td><?php $totalfinaltodos= $subtotalimporte-$totaldescuento+$totaliva+$calculoiepss6+$calculoiepss7+$calculoiepss9+$totalsd;
                     echo '$'.number_format($totalfinaltodos, 2, '.', ',');?></td>
                     </tr>






             </tbody>



         </table>

    </div>
     <form name="forma2" method="POST" action="pedidos-especial_sigue.php">
    <div class="col-lg-10">
                <label>Observaciones</label>
                <input name="observaciones" type="text" class="form-control" placeholder="Comentarios Generales" >
            </div>

    <div class=" col-lg-12" >
      <p>
                     <button name="guardar" type="submit" class="btn btn-lg btn-primary pull-right" onClick="return confirm('¿Guardar Pedido?');">Guardar Pedido</button>
                     <button name="descartar" type="submit" class="btn btn-lg btn-danger pull-right" onClick="return confirm('¿Esta Seguro de Descartar el Pedido?');">Descartar Pedido</button>
                       <input type="hidden" name="totaltodo" value="<?php echo $total ?>" class="form-control"  readonly >
                       <input type="hidden" name="totalservdist" value="<?php echo $totalsd ?>" class="form-control"  readonly >
                 </p>
    </div>
     </form>

</div><!-- /.container -->

<div class="modal fade" id="filesModal" role="dialog">
                  <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>


                            <h4 class="modal-title"   >Orden de compra</h4>
                            

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

                 WHILE ($registros= mysqli_fetch_array($SERESULT)){
                    list($noValue, $nomDocu) = split('[/]', $registros['DOCUMENTO']);


                     ?>
                 <tr>


                    <td><?php echo $nomDocu;?></td>

                     <td><a href="<?php  echo   $registros['DOCUMENTO'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;">
                                                        <img src="images/file_pdf.png" title="Orden de compra Agregada" /></a></td>
                    

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

 <?php require_once('foot.php');?>
