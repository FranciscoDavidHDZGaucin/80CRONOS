<?php
////***gat_delet_gasto.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :gat_delet_gasto.php 
 	Fecha  Creacion : 15/12/2017 
	Descripcion  : 
			Archivo  para Eliminar Elemtos del  Gastos  

  */
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 

///***Obtenemos  los  Arreglos  json 		
$cvegasto=  filter_input(INPUT_POST, 'CVE');

        ////****Generamos  Cadena  De insercion  DELETE FROM adicionales  where
        $String_deletePoliza = sprintf("DELETE FROM pedidos.poliza  where id=%s",
                        GetSQLValueString($cvegasto, "int"));
        ///****Generamos  Qery  
       $qery_delGasto = mysqli_query($conecta1, $String_deletePoliza) ; 
        ///***Validar  Qery  Cabeza
        if(!$qery_delGasto)
        {   ///***Error insert Consulta 
           $ExitCAB = 0; 
        }else{
            ///**Insert Correct
            $ExitCAB = 1; 
        }


 $pub_arreglo  =   Array(
 			"Res001" => $ExitCAB, 
                        "Cadenafroma" =>$String_deletePoliza
         
                       
 	);
///**Convertimos a  Json  
  $convert_json  =  json_encode($pub_arreglo);
  header('Content-type: application/json');
echo  $convert_json ;


?>

