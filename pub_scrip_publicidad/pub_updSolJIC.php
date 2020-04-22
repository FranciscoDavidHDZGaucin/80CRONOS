<?php

////****pub_updSolJIC.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :pub_updSolJIC.php 
 	Fecha  Creacion : 05/06/2017 
	Descripcion  : 
 *              Escrip  Diseñado  para  Modificar  la  Solicituds de Publicidad 
 *              Por parte del Jefe de Inteligencia  Comercial 
  */
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php');

///**estqery => Estado  de los elementos  de  consulta 
$estqery = true;
////****Obtenemos el  Folio 
$FOLIO  = filter_input(INPUT_POST, 'masNf');
///****TOtales
$JSONTOTALES = filter_input(INPUT_POST, 'Ttls');
///****Productos  Modificados 
$JSONUPDATEPROD = filter_input(INPUT_POST, 'ProdUp');
///***Obtenemos los Areglo de  las  Totales
$AreTOTALES = json_decode($JSONTOTALES);


////***Obtenemos el  Folio de la  Plicidad
$strIDcab = sprintf("select id from pub_encabeza_publicidad where  pub_folio =%s ",
 GetSQLValueString($FOLIO, "int"));
///***Realisamos el qery  
$qeryIDcAB = mysqli_query($conecta1, $strIDcab);
////****Convertimos  a Feth 
$fethElm = mysqli_fetch_array($qeryIDcAB);
////****
if(!$qeryIDcAB)
{
    $estqery = false;
} 

////****Generamos Cadena Para  Modificar  los  Totales Encab 
$strUdtals = sprintf("update pub_encabeza_publicidad set  sub_total_so=%s,iva=%s,Tota_sol=%s ,estUpdate=1  where  id =%s",
 GetSQLValueString($AreTOTALES->{"subTotl"}, "double"),GetSQLValueString($AreTOTALES->{"iva"}, "double"),GetSQLValueString($AreTOTALES->{"Total"}, "double"),GetSQLValueString($fethElm['id'], "int"));

////***Ejecutamos la  Instruccion 
 $qeryEncabez = mysqli_query($conecta1, $strUdtals);

if(!$qeryEncabez)
{
    $estqery = false;
}
///***El Siguiente  pasos Modificaos los Productos
////***Convertimos el   Areglo Json a
$Areglo_Prod = json_decode($JSONUPDATEPROD);

foreach($Areglo_Prod as  $ELEM)
{
    //***Obtenmos   el Id del  Producto 
    ////***Obtenemos el  Folio de la  Plicidad
    $strIDprod = sprintf("select id from pub_detalle_publicidad where  pub_folio =%s and  pub_cvepro=%s ",
     GetSQLValueString($FOLIO, "int"),GetSQLValueString($ELEM->{'cve_prod'}, "text"));
    ///***Realisamos el qery  
    $qeryIDprod = mysqli_query($conecta1, $strIDprod);
    ////****Convertimos  a Feth 
    $fethProd = mysqli_fetch_array($qeryIDprod);
    ////****
    if(!$qeryIDprod)
    {
        $estqery = "Error Id";
        break;
    } 
//******************************************************************************    
////*********Iniciamos las  Modificaciones
$strUpdatProd = sprintf("update pub_detalle_publicidad   set cantidad_solici =%s,precio_unitario=%s ,precio_total_por_pro=%s  where id =%s",
 GetSQLValueString($ELEM->{'cant_sol'}, "double"),GetSQLValueString($ELEM->{'PU'}, "double"),GetSQLValueString($ELEM->{'pretotal'}, "double"),GetSQLValueString($fethProd['id'], "int")
        );

///***Realisamos el qery  
    $qeryUpdatProd = mysqli_query($conecta1, $strUpdatProd);
    
    ////****
    if(!$qeryUpdatProd)
    {
        $estqery = "Error Update";
        break;
    } 

    
}

 $pub_arreglo  =   Array(
 			"Res001" => $estqery///, "Obj"=>$JSONUPDATEPROD //json_decode($Areglo_Prod)
         
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;
?> 