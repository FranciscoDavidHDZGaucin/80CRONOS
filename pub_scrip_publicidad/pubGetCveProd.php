<?php
 ///***pub_get_cve_prod.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pubGetCveProd.php 
 	Fecha  Creacion : 03/05/2017
	Descripcion  : 
    *                   Escrip  para Obtener la  Cve de  los  Productos
  */
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php'); 
///***************************************************************
//Creamos  el  arreglo de resultado
$arrey =  array(); 
///Qery  para obtener  los  Productos   
$qery_get_prod = mysqli_query($conecta1, "SELECT codig_prod FROM pedidos.pub_catalogo_publicidad  where  imagen_prod !='' ");
//Generamos  el arreglo de Retorno 
while($row = mysqli_fetch_array($qery_get_prod)){
    array_push($arrey, $row['codig_prod']); 
}
///*Areglo para convertir en Json
$array_json  = array ("COD"=>json_encode($arrey)); 
//*Convertir  Json
$json_resultado = json_encode($array_json);
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $json_resultado; 
?> 

