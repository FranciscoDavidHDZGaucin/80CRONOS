<?php

////****pub_delprodcat
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :pub_delprodcat.php 
 	Fecha  Creacion : 13/06/2017 
	Descripcion  : 
			Archivo  para Eliminar Elemtos del  Catalogo  

  */
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 

///***Obtenemos  los  Arreglos  json 		
$cveProd=  filter_input(INPUT_POST, 'CVE');

                //***Obtener  Id  del  Producto  
            $strGetIdprod = sprintf("select id,imagen_prod from  pedidos.pub_catalogo_publicidad where  codig_prod =%s", GetSQLValueString($cveProd, "text"));
            $max_id_cre_pagares = mysqli_query($conecta1,$strGetIdprod );
            $res_id = mysqli_fetch_array($max_id_cre_pagares);
            $plus_one_id = $res_id['id'];
            
            ///**Eliminamos la Imagen Anterior a
            unlink('../pub_catalogo/'.$res_id['imagen_prod']); 

        ////****Generamos  Cadena  De insercion  DELETE FROM adicionales  where
        $String_Insert_Prod = sprintf("DELETE FROM pub_catalogo_publicidad  where id=%s",
                        GetSQLValueString($plus_one_id, "int"));
        ///****Generamos  Qery  
        $qery_InsertProd = mysqli_query($conecta1, $String_Insert_Prod) ; 
        ///***Validar  Qery  Cabeza
        if(!$qery_InsertProd)
        {   ///***Error insert Consulta 
           $ExitCAB = 0; 
        }else{
            ///**Insert Correct
            $ExitCAB = 1; 
        }


 $pub_arreglo  =   Array(
 			"Res001" => $ExitCAB  ///Retornamos  Resultado Insert Cabecera
                       
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;


?> 