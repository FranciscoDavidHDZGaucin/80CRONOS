<?php
///****pub_updateCatProd.php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :pub_updateCatProd.php 
 	Fecha  Creacion : 13/06/2017 
	Descripcion  : 
			Archivo  para Modificar   Un Produtocto del  Catalogo   

  */
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 

///***Obtenemos  los  Arreglos  json 		
$JSON_PROD=  filter_input(INPUT_POST, 'OBJ');


              

            ///***Convertimos el Encabe A JSON
            $Arreglo_fetch = json_decode($JSON_PROD );
             //**Obtenemos los  Valores  
            $cve_prod = $Arreglo_fetch->{'cve_prod'} ; ///cLAVEAS 
            $nom_prod = $Arreglo_fetch->{'nom_prod'} ;///Fecha
            $PU= $Arreglo_fetch->{'PU'} ;///Zona 
            $Cant= $Arreglo_fetch->{'Cant'} ;///Region o Unidad
            $preTotal= $Arreglo_fetch->{'preTotal'} ;///Cliente
             $descrip= $Arreglo_fetch->{'DES'} ;
             
                //***Obtener  Id  del  Producto  
            $strGetIdprod = sprintf("select id from  pedidos.pub_catalogo_publicidad where  codig_prod =%s", GetSQLValueString($cve_prod, "text"));
            $max_id_cre_pagares = mysqli_query($conecta1,$strGetIdprod );
            $res_id = mysqli_fetch_array($max_id_cre_pagares);
            $plus_one_id = $res_id['id'];


        ////****Generamos  Cadena  De insercion 
        $String_Insert_Prod = sprintf("UPDATE pub_catalogo_publicidad  SET articulo=%s,precio_unitario=%s,cantidad=%s,precio_total=%s,descripcion=%s where id=%s",
         GetSQLValueString($nom_prod, "text"),
         GetSQLValueString($PU, "double"),
         GetSQLValueString($Cant, "int"),
         GetSQLValueString($preTotal, "double"),
         GetSQLValueString($descrip, "text"),
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