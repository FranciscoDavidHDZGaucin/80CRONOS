<?php

///****coti_delOrUpdprod.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_delOrUpdprod.php
 	Fecha  Creacion : 30/05/2017
	Descripcion  : 
 *                  Escrip  para   Eliminar Un producto o  Actualizar  un producto  
  */


require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

$estQery =true; 
///***keyOpc => 1  Update   keyOpc => 2 Elimina 
$estUpdateOrDel =  filter_input(INPUT_POST, 'keyOpc');
$ObjProdJson  = json_decode(filter_input(INPUT_POST, 'ObjProd' ));

////****
IF($estUpdateOrDel ==2)
{
   ///Obtenemos  el  Id 
  ///***Validamos que no Exista el  Cliente  para el  folio 
$strxispROD = sprintf("SELECT  id  from  coti_detalle_cotizacion  where folio=%s  and  cve_prod=%s ",
        GetSQLValueString($ObjProdJson->{'folio'}, "int"),GetSQLValueString($ObjProdJson->{'cve_prod'}, "text"));

$qerExispROD= mysqli_query($conecta1, $strxispROD);

$fetExispROD = mysqli_fetch_array($qerExispROD);

$string_qery_delete_adi = sprintf("DELETE FROM coti_detalle_cotizacion  where id=%s", 
 GetSQLValueString($fetExispROD['id'], "int"));
    $qeryDEL  = mysqli_query($conecta1, $string_qery_delete_adi);
    
    
    IF(!$qeryDEL){    $estQery =false;       }
    
}

$arreresl = array(  "EstQery" =>$estQery );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arreresl); 