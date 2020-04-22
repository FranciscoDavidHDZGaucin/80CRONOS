<?php
///****
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : convenios_cancelacion.php
 	Fecha  Creacion : 30/12/2016 
	Descripcion  : 
                 Script para  modificar el  estatus del 
 *               convenio   para esto utilizamos la  taba encabeza_convenio.php 
	Modificado  Fecha  : 
*/
 require_once('formato_datos.php');
  require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 mssql_select_db("AGROVERSA");   
 
 ///***Obtenemos la remision a  modificar 
 $remision   = filter_input(INPUT_POST, 'REMI');
///***Generamos  la  cadena  
  $string_generar_consulta = sprintf("UPDATE encabeza_convenio SET estatus ='C' ,fecha_cancelacion= now()    where  n_remision=%s",
                                       GetSQLValueString($remision, "int"));
///***Ejecutamos el  Qery 
  $qery_update_convenios = mysqli_query($conecta1,$string_generar_consulta);