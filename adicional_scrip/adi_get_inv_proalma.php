<?php
///// *adi_get_inv_proalma.php 
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_get_inv_proalma.php
 	Fecha  Creacion : 18/11/2016
	Descripcion  : 
               Script  para   obtener  el  inventario y  las  proyecciones  
  *            de   un producto  en  un almacen predeterminado.s
	Modificado  Fecha  : 
  *      ******   23/11/2016   Se  agrego  a la funcion  adi_get_proye_for_all_almacen (cve_producto,  cve_almacen,  Fecha) 
  *     
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
////***Conexion   Sap 
require_once('../conexion_sap/sap.php');


///***Seleccion de la Bd 
// mssql_select_db("AGROVERSA");
////****Obtenemos  Datos 
$cve_producto = filter_input(INPUT_POST, 'cdg_pro');
$cve_alamacen = filter_input(INPUT_POST,'alma');
$fecha_Reque  = filter_input(INPUT_POST,'fech'); 
////*******************************
/////*** Cadena para   obtener el inventario del almacen 
$string_cedena_inventario =sprintf("Select OnHand as Iven from cronos_existencias where ItemCode=%s and WhsCode=%s",
                            GetSQLValueString($cve_producto,"text"),
                            GetSQLValueString($cve_alamacen,"text"));
////***  Cadena para Obtener  la  Proyeccio de  Todos  los  almacenes  adi_get_proye_for_all_almacen(cve_producto varchar(20) , num_almacen varchar(20),fecha  date )
$string_cedena_proyeccion= sprintf("select adi_get_proye_for_all_almacen (%s ,%s,%s) as Proye",
                            GetSQLValueString($cve_producto,"text"),
                            GetSQLValueString($cve_alamacen,"text"), 
                            GetSQLValueString($fecha_Reque,"date"));
/////*****Ejecucion  Peticiones
$qery_inventario   = mssql_query($string_cedena_inventario);
$qery_proyeccion   = mysqli_query($conecta1,$string_cedena_proyeccion );
///***Convertimos los  resultados  a  fech  array
$res_fetch_invent = mssql_fetch_array($qery_inventario);
$res_fetch_proyeccion = mysqli_fetch_array($qery_proyeccion); 
////****Generamos  Arreglo  for  Json
$arra_to_send =  array(
    "INV"=>$res_fetch_invent['Iven'] , 
    "PRO"=>$res_fetch_proyeccion['Proye']
    
);
////***Convertimos el  array en  JSON para Enviarlo s
$res_json = json_encode($arra_to_send);
///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $res_json;    

?> 
