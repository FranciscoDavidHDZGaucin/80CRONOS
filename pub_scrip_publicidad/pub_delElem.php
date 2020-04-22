<?php
///**pub_delElem_publicidad.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_delElem.php
 	Fecha  Creacion : 10/05/2017
	Descripcion  : 
 *                  Escrip para eliminar Solicitud 
  */
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***VARIABLE  RESPUESTA
$CTR_RES=1;
///****Objeto Mysql
$obj_mysql =  new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
///***Obtenemos la  cadena 
$NumFolio = filter_input(INPUT_POST, 'NFO');
if($NumFolio != null ||$NumFolio != "" ){
///**Buscamos Id  
///**Tb  Encabeza
$str_Enca_ID  = sprintf("SELECT id  from pub_encabeza_publicidad where  pub_folio =%s ",
 GetSQLValueString($NumFolio, "int"));
///**Tb  Detalle
$str_Detll_ID  = sprintf("SELECT id  from pub_detalle_publicidad where  pub_folio =%s ",
 GetSQLValueString($NumFolio, "int"));
///***************************************************************
///Realizamos los  Qerys  para  Obtener los Id  
///**Qery  Enca 
$qery_enca = $obj_mysql->query($str_Enca_ID);
///***Qery Det 
$qery_det = $obj_mysql->query($str_Detll_ID);
///***Lo convertimos  en  Asocciativo 
//**Obteneos  Encabezado
$idEnca = mysqli_fetch_array($qery_enca);



///***Generesmo  cadena para el  Qery 
$string_qery_delete_pubEnca = sprintf("DELETE FROM pub_encabeza_publicidad  where id=%s", 
 GetSQLValueString($idEnca['id'], "int"));

    //**Obtenemos Detalle
    while($idDet  = mysqli_fetch_array($qery_det)){
    ///***Generesmo  cadena para el  Qery 
    $string_qery_delete_pubDetalle = sprintf("DELETE FROM pub_detalle_publicidad  where id=%s", 
     GetSQLValueString($idDet['id'], "int"));
    ///***Ejecutamos 
        if (!$qerRes =$obj_mysql->query($string_qery_delete_pubDetalle))
        {
               $CTR_RES = 0;
        }
    }

    ///***Ejecutamos 
   if( !$qerRes =$obj_mysql->query($string_qery_delete_pubEnca)){
       $CTR_RES = 0 ; 
   }

}else {
    $CTR_RES = 5;
} 

$arreglo_res =  array(
    "RES"=> $CTR_RES  
);
$jsonres= json_encode($arreglo_res);
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $jsonres; 
 
    ?> 