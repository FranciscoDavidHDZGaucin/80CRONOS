<?php

////pub_delevidenciwhitfl.php  
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_delevidenciwhitfl.php 
 	Fecha  Creacion : 10/08/2017
	Descripcion  : 
  *             Escrip para Eliminar  de la Bd  y del  Servidor las  Evidencia
  */
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php');

function  DelEvidenciBd($areEviden ,$conecxion)
{   
    $est = true;
    foreach ($areEviden  as $Evi )
    {
             ///**Eliminamos la Imagen Anterior a
            unlink('../pub_evi/'.$Evi['NOM']); 

        ////****Generamos  Cadena  De insercion  DELETE FROM adicionales  where
        $String_Insert_Prod = sprintf("DELETE FROM pub_evidencia_publicidad  where id=%s",
                        GetSQLValueString($Evi['ID'], "int"));
        ///****Generamos  Qery  
        $qery_InsertProd = mysqli_query($conecxion, $String_Insert_Prod) ; 
        ///***Validar  Qery  Cabeza
        if(!$qery_InsertProd)
        {   ///***Error insert Consulta 
           $est = FALSE; 
        }
        
    }  
    return  $est ;
}



///***Obtenemos  el Folio 		
$FOLIO  =  filter_input(INPUT_POST, 'fl');
////***Obtenemos el Id  del  Numero de  Folio 
$stringGetID = sprintf("select id , nom_archivo from  pedidos.pub_evidencia_publicidad  where folio =%s",
 GetSQLValueString($FOLIO, "int"));
$qery_Getid = mysqli_query($conecta1,$stringGetID);

$ExDet =1;
$est = true;
while($row = mysqli_fetch_array($qery_Getid))
{
  ///   $Evi =  Array("ID"=>$row['id'],"NOM"=>$row['nom_archivo']);
     ///**Eliminamos la Imagen Anterior a
            unlink('../pub_evi/'.$row['nom_archivo']); 

        ////****Generamos  Cadena  De insercion  DELETE FROM adicionales  where
        $String_Insert_Prod = sprintf("DELETE FROM pub_evidencia_publicidad  where id=%s",
                        GetSQLValueString($row['id'], "int"));
        ///****Generamos  Qery  
        $qery_InsertProd = mysqli_query($conecta1, $String_Insert_Prod) ; 
        ///***Validar  Qery  Cabeza
        if(!$qery_InsertProd)
        {   ///***Error insert Consulta 
           $est = FALSE; 
        }
    
    
}

 $pub_arreglo  =   Array(
 			"Res001" => $est ///Retornamos  Resultado Insert Cabecera
                      
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;


?> 

