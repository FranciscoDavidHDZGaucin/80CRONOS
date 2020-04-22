<?php 
///****coti_GetPreGen.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_GetPreGen.php
 	Fecha  Creacion : 16/05/2017
	Descripcion  : 
			eSCRIP PARA  Obtener El precio  General  de  Un producto 
  */
require_once('../formato_datos.php');
 require_once('../Connections/conecta1.php');
 require_once('../conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");  


  ///***+Funcion para  Obtener el Precio Unitario 
function  Get_PrecioProd($cve_pro)
{
 ///**+Generamos Cadena
 $str_prod = sprintf("SELECT ItemCode,Price FROM  plataformaproductosl1 where  Currency = 'MXP' and  itemCode =%s",
 GetSQLValueString($cve_pro, "text"));
 ///**Obtenemos  Qery   
 $qerProd =  mssql_query($str_prod); 
 ///**Convertimos  a Fetch    
 $fetchElm =mssql_fetch_array($qerProd);
 ///***Retornamos el Precio
   return  $fetchElm['Price'];
} 


 $arrayPrecio = array('restotal' =>  Get_PrecioProd(filter_input(INPUT_POST, 'cvePro')) );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayPrecio); 


?> 