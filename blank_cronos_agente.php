<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : 
 	Fecha  Creacion : 
	Descripcion  : 
	Copia  archivo     parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
*/
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 
require_once('header.php');
///***Conexion  sap
require_once('conexion_sap/sap.php');
/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///**Uso de  la Base  de Datos
mssql_select_db("AGROVERSA"); 
///****FIN    Librerias  Utilizadas  en Cronos 
?> 
<div  class="container"> 
</div>
 <?php require_once('foot.php');?>     