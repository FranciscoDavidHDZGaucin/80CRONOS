<?php

///adi_vent_pro.php 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_vent_pro.php 
 	Fecha  Creacion : 11/11/2016
	Descripcion  : 
              Script Para Obtener la venta de el Producto 
  *
	Modificado  Fecha  : 
  *         17/11/2016   Se  Quito  de  las  consultas de ventas el  valor de los  almacenes 
  *                      Copia Respaldo  de  la  cadena  antes  de modificacion 
  *                 ****** "Select  sum(cantidad)as cantidad    from  ventas_adicionales  where   WhsCode like '".$cve_alamacen."'  AND  agente like ".$cve_agente." AND  codigo like  '".$cve_producto."' AND Annio = YEAR('".$fecha_req."') AND  Mes = Month('".$fecha_req."')";
  *                 ****** "select  sum(Quantity)as cantidad  from  devoluciones_adicionales  where Agente like  ".$cve_agente." AND  ItemCode  like '".$cve_producto."' AND WhsCode ='".$cve_alamacen."' AND  Annio = YEAR('".$fecha_req."')  AND Mes =   MONTH('".$fecha_req."')";

  *         28/11/2016  Se  procedio  a  agregar  consultas   necesarias  para obtener  los valores de los  siguientes parametros 
  *                         *** Venta Total   de  Rtes  BOdega 
  *                         *** Proyeccion Totales por  Bodega s
*/
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
////***Conexion   Sap 
require_once('../conexion_sap/sap.php');
///***Seleccion de la Bd 
 ///mssql_select_db("AGROVERSA");
 
$fecha_req  = filter_input(INPUT_POST, 'fech_req');
$cve_agente = filter_input(INPUT_POST, 'usuario_agente');
$cve_producto = filter_input(INPUT_POST, 'cdg_pro');
$cve_alamacen    =  filter_input(INPUT_POST,'alma');
////****************************************************************

 
///**Cadena para la consulta  ventas_adicionales 
// $strin_cadena_ventas_adicionales = "Select codigo,desc1,agente,cantidad,WhsCode as cve_almacen ,falta_fac  from  dbo.ventas_adicionales  where   WhsCode like '".$cve_alamacen."' AND  agente like".$cve_agente." AND  codigo like ".$cve_producto." AND falta_fac =  (SELECT CONVERT(CHAR(23),CONVERT(DATETIME,'".$fecha_req."',101),121))";
$strin_cadena_ventas_adicionales =   "Select  sum(cantidad)as cantidad    from  ventas_adicionales  where  agente like ".$cve_agente." AND  codigo like  '".$cve_producto."' AND Annio = YEAR('".$fecha_req."') AND  Mes = Month('".$fecha_req."')";
      
///***Cadena  para la consulta  devoluciones_adicionales
$string_cadena_devoluciones=  "select  sum(Quantity)as cantidad  from  devoluciones_adicionales  where Agente like  ".$cve_agente." AND  ItemCode  like '".$cve_producto."' AND  Annio = YEAR('".$fecha_req."')  AND Mes =   MONTH('".$fecha_req."')";
/////*** Cadena para   obtener el inventario del almacen 
$string_cedena_inventario =sprintf("Select OnHand from cronos_existencias where ItemCode=%s and WhsCode=%s",
                            GetSQLValueString($cve_producto,"text"),
                            GetSQLValueString($cve_alamacen,"text"));
///****Cadena  para  Obtener  la  Venta  Total  de la  Bodega
$string_cadena_Get_VenTotal_Bodega  = "Select sum(cantidad)as VEN_POR_BODEGA    from   ventas_adicionales  where WhsCode  = '".$cve_alamacen."' and   codigo like  '".$cve_producto."' AND Annio = YEAR('".$fecha_req."') AND  Mes = Month('".$fecha_req."')";
///****Cadena  para  Obtener  las   Devoluciones  en   Bodega  
$string_cedena_Get_Devo_Bodega  = "select  sum(Quantity)as DEVO_POR_BODEGA  from  devoluciones_adicionales  where  WhsCode ='".$cve_alamacen."' AND  ItemCode  like '".$cve_producto."' AND   Annio = YEAR('".$fecha_req."')  AND Mes =   MONTH('".$fecha_req."')";
///****Cadena  para   Obtener  las  proyecciones  Totales  por  Producto 
$string_cadena_get_proyec_totls_bodega = "SELECT sum(demanda)as ProyeTotls  FROM    pronostico where   cve_alma = '".$cve_alamacen."'  and   cve_prod = '".$cve_producto."'  and   anio  = year('".$fecha_req."')  AND  mes = month('".$fecha_req."') ";
////**********Inicio Peticiones*******************************************************************************
///****Realizamos  Petecion  Para Obtener  las  ventas  mediante el  Agente   y  producto
$qery_ventas_adicionales = mssql_query($strin_cadena_ventas_adicionales);
///****Realizamos  Petecion Para  Obtener las  devoluciones   mediante ele  Agente  y   producto
$qery_devoluciones_adicionales = mssql_query($string_cadena_devoluciones); 
///****Realizamos  Petecion  Para  Obtener  el  Inventario 
$qery_cadena_inventarios= mssql_query($string_cedena_inventario);
///****Realizamos  Petecion  Para  Obtener  La  Venta  Total   de  RTES  Bodega 
$qery_VenTotal_Bodega = mssql_query($string_cadena_Get_VenTotal_Bodega);
///****Realizamos  Petecion  Para  Obtener  Las  Devolucion  Totales por   Bodega
$qery_Devo_Bodega = mssql_query($string_cedena_Get_Devo_Bodega);
///****Realizamos  Peticion  para  Obtner  las  Proyecciones  Totales  
///$qery_proyec_bodega  = mysqli_query($conecata1, $string_cadena_get_proyec_totls_bodega);
///***Objeto  Myslq
$mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

if(!$ROW =$mysqli_PRO->query($string_cadena_get_proyec_totls_bodega)){
   
}else{ 
    $fec =$ROW->fetch_array(MYSQLI_ASSOC);
   ///***Arreglos  Accociativo 
    $fetch_proyecciones_bodega = $fec['ProyeTotls'];
}



///**********Convercion a   Arreglos  Accociativos****
///****Arreglos  Accociativo  Ventas  midiante  Agente   y Producto
$fetch_ventas_adicionales  = mssql_fetch_array($qery_ventas_adicionales);
///****Arreglos  Accociativo  Devoluciones  Mediante  Agente   y  Producto
$fetch_devoluciones_adicionales  = mssql_fetch_array($qery_devoluciones_adicionales);
///****Arreglos  Accociativo  Para  Obtener  ele Inventario 
$fetch_inventario = mssql_fetch_array($qery_cadena_inventarios);
///****Arreglos  Accociativo   Venta  Totalde  RTES  Bodega
$fetch_Total_RTES_Bodega = mssql_fetch_array($qery_VenTotal_Bodega); 
///****Arreglos  Accociativo   Devoluciones DE  VENTA  TOTALES   RTES  Bodega
$fecha_Devoluciones_Bodega =mssql_fetch_array($qery_Devo_Bodega); 


///******Inicio de Obtencion de  las  cantidades 
///***Obtenemos la  Cantidad
$CANTIDAD_VENTAS = $fetch_ventas_adicionales['cantidad'];
///***Obtenemos la  Devolucion 
$CANTIDAD_DEVOLUCIONES = $fetch_devoluciones_adicionales['cantidad'];
///****Obtenemos la Cantidad   Venta  Totales  RTES Bodega
$CAN_VENTOTLS =  $fetch_Total_RTES_Bodega['VEN_POR_BODEGA'];
///****Obtenemos la Cantidad  Devolucion  RTES  Bodegas
$CAN_DEVOLUCIONES_VENTOTAL_RTES =  $fetch_Total_RTES_Bodega['DEVO_POR_BODEGA'];
////**** Resta  Elementos 
$res_Can_Ventas_Can_Devoluciones = $CANTIDAD_VENTAS - $CANTIDAD_DEVOLUCIONES;
////*** Resta  de elementos  Venta  Total de  RTES  Bodega
$res_vet_RTES_BODEGA  =  $CAN_VENTOTLS- $CAN_DEVOLUCIONES_VENTOTAL_RTES ;
////****Ejacutamos  la  funcion  para   obtener
$INVENTARIO = $fetch_inventario['OnHand'];
///***Convertimos    asocitivo  la  respuesta
$array_json  =   array(
    'VDI'=>$CANTIDAD_VENTAS,
    'CADEV'=>$CANTIDAD_DEVOLUCIONES,
    'VT'=>$res_Can_Ventas_Can_Devoluciones ,
    'IV'=>$INVENTARIO,
    ////****Elementos  Ventasd  Bodega
    'VEN_POR_BODEGA'=>$CAN_VENTOTLS,
    'DEVO_POR_BODEGA'=>$CAN_DEVOLUCIONES_VENTOTAL_RTES,
    'VTSBODEGA' =>$res_vet_RTES_BODEGA,
    ///*****Elemento   proyeccion Total  por  Bodegas
     'PROYBDTLS'=> $fetch_proyecciones_bodega
 );
///****Convercion 
$Json_Obje = json_encode($array_json); 
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $Json_Obje;             
