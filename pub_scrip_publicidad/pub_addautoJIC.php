<?php
/////***pub_addautoJIC.php.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_addautoJIC.php.php
 	Fecha  Creacion : 01/06/2017
	Descripcion  :
 *                  Autorizar   Estatus  del Jefe  Ic   
 * 
  */

///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 

///**Obtenemos la Informacion 
$NumFolio =  filter_input(INPUT_POST, 'nFol');
$ESTAU    =  filter_input(INPUT_POST, 'EstAu');
////************************************************
///**Obtenemos  el Id 
 $strGetID = sprintf("select   id ,estUpdate  from  pedidos.pub_encabeza_publicidad   where pub_folio =%s",
 GetSQLValueString($NumFolio,"int"));
 ///**Realizamos  Consula
$qerId = mysqli_query($conecta1, $strGetID); 
///***Convertios en  Fetch  consulta 
$fetGeID = mysqli_fetch_array($qerId);
//****
$strgEUPDAeST = sprintf("Update pub_encabeza_publicidad set auto_JINC=%s,auto_fe_JINC= now()  where  id =%s ",
GetSQLValueString($ESTAU,"int"),GetSQLValueString($fetGeID['id'],"int"));

$qeryUpdate = mysqli_query($conecta1, $strgEUPDAeST);
////********Realizaos  el  Calculo de la  solicitud  Si el  estUpdate = 0 
if($fetGeID['estUpdate']==0)
{   
    ///**Estatus Modificacion Precio Total 
    $EstTotProd=true; 
    ///****Realizamos el  Calculos
    $strGETpROD =sprintf("SELECT   id,cantidad_solici,precio_unitario,Descripcion_produc FROM pedidos.pub_detalle_publicidad  where pub_folio=%s", GetSQLValueString($NumFolio, "int")  );
    $qeryProd  =  mysqli_query($conecta1,$strGETpROD ); 
    while($row =mysqli_fetch_array( $qeryProd ))
    {
        ////****Realizamos  calculo  precio_total_por_pro
        $PrTolProd = round(($row['cantidad_solici']*$row['precio_unitario']),2);
        ////*********Iniciamos las  Modificaciones
        $strUpdatProd = sprintf("update pub_detalle_publicidad   set precio_total_por_pro=%s  where id =%s",
        GetSQLValueString($PrTolProd, "double"),GetSQLValueString($row['id'], "int")
        );
        ///***Realisamos el qery  
        $qeryUpdatProd = mysqli_query($conecta1, $strUpdatProd);
        if(!$qeryUpdatProd)
        {
            $EstTotProd =false ;
            break;
        } 
    }
    IF($EstTotProd==true){
    
    ////***Inicio Calculo Totales ******************************************************************************************************************************
    $strGetTotales = "Select   Get_TotalAllProdd(".$NumFolio.") as  SumaTotalProd ,   Total_WhitIVA_Prod (".$NumFolio.") as  Iva  ,  GetTotalSolicitud (".$NumFolio.") as SumaTotal  ";
                $qeryTotales  =  mysqli_query($conecta1, $strGetTotales );
                $ResTotal = mysqli_fetch_array( $qeryTotales );
    /////********************
    ////****Generamos Cadena Para  Modificar  los  Totales Encab 
    $strUdtals = sprintf("update pub_encabeza_publicidad set  sub_total_so=%s,iva=%s,Tota_sol=%s   where  id =%s",
     GetSQLValueString($ResTotal['SumaTotalProd'], "double"),GetSQLValueString($ResTotal['Iva'], "double"),GetSQLValueString($ResTotal['SumaTotal'], "double"),GetSQLValueString($fetGeID['id'], "int"));

    ////***Ejecutamos la  Instruccion 
     $qeryEncabez = mysqli_query($conecta1, $strUdtals);            
    ///****Fin Calculo Totales******************************************************************************************************************************************************
    }
     
     
     
     
     
     
} 



if(!$qeryUpdate)
{
    echo  0;
}else {
    
    echo   1;
}
 
 
 
 ?>