<?php

//////*pub_GetableCatProd 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_GetableCatProd.php
 	Fecha  Creacion : 10/06/2017  
	Descripcion  :
				Escrip  para Obtener los productos del  Catalogo de  Publiciadad
			
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');
$strGetELMS =  "select codig_prod,articulo,descripcion,cantidad,precio_unitario,precio_total,imagen_prod from  pedidos.pub_catalogo_publicidad ";
$qery_det_pub = mysqli_query($conecta1,$strGetELMS);
$AregloConvert  =  Array() ; 
while  ($fetch_elem  =  mysqli_fetch_array($qery_det_pub)) {

	$ArObje =  Array("cveProd"=>$fetch_elem['codig_prod'],"nomProd"=>$fetch_elem['articulo'],"Descrip"=>$fetch_elem['descripcion'],"canti"=>$fetch_elem['cantidad'],"PU"=>$fetch_elem['precio_unitario'],"PreTotl"=>$fetch_elem['precio_total'],"IMG" =>$fetch_elem['imagen_prod'] );
      
		

	array_push($AregloConvert , $ArObje );
}

$arrayPrecio = array('allelem' =>json_encode($AregloConvert));
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayPrecio); 


?>  