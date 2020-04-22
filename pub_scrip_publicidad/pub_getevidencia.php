<?php

////***pub_getevidencia.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :pub_getevidencia.php 
 	Fecha  Creacion : 15/08/2017  
	Descripcion :
                    Escrip encargado  de Obtener  las  Evidencia  en formato  html  
			
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

$folio_e = filter_input(INPUT_POST, "FL");


   $string_get_info = sprintf("select  nom_archivo from  pedidos.pub_evidencia_publicidad where  folio=%s", 
                              GetSQLValueString($folio_e,"int")) ;
   $qery_info = mysqli_query($conecta1, $string_get_info);
 /////**** Cadena  para  mostrar  Resultados 
    $strGetResul ="";
  while($rowevi = mysqli_fetch_array($qery_info)){
                                         
    $strGetResul .= "<tr><td> <a target='_blank' href='pub_evi/".$rowevi['nom_archivo']."'>".$rowevi['nom_archivo']."</a></td></tr>";                                      
 }

$arrayPrecio = array('allelem' =>$strGetResul);
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayPrecio); 


?>  
