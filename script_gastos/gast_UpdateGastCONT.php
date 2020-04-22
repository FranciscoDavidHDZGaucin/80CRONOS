<?php   
	/////*****gast_UpdateGastCONT.php
	/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_addGastoGen.php 
 	Fecha  Creacion : 17/11/2017 
	Descripcion  : 
			   Archivo   Para  Modificar  Informcion del  Gasto   Por Parte  del   Departamento de  Contaduria.
*/ 

///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 
///***Obtenemos  los  Arreglos  json 		
$JSON_INFO =  filter_input(INPUT_POST, 'INFOj');
///***Convertimos  LA  INFORMACION  JSON
$Arreglo_MAIN = json_decode($JSON_INFO );


	$CVEgast= $Arreglo_MAIN->{'cve_gasto'} ;//Numero  de  Agente
    $PagosubTotal= $Arreglo_MAIN->{'subtotl'} ;//Numero  de  Agente
    $PagoIva = $Arreglo_MAIN->{'pagoIva'} ; ///FOLIO 
    $FchPago = $Arreglo_MAIN->{'fechPago'} ;///Fecha
    $Coment= $Arreglo_MAIN->{'comen'} ;///CONCEPTO
    $totlapag  =$Arreglo_MAIN->{'totalPa'} ; 
    
////****Generamos  Cadena  De Update 
        $StrUpdate = sprintf("UPDATE  poliza  SET subtot_pago=%s,iva_pago=%s,f_pago=%s,comentario=%s,pago=%s  where id=%s",
         GetSQLValueString($PagosubTotal,"double"),
         GetSQLValueString($PagoIva,"double"),
         GetSQLValueString($FchPago,"date"),
         GetSQLValueString($Coment,"text"),
         GetSQLValueString($totlapag,"int"),       
         GetSQLValueString($CVEgast,"int"));
        ///****Generamos  Qery  
        $qery_Udate = mysqli_query($conecta1, $StrUpdate) ; 
        ///***Validar  Qery  Cabeza
        if(!$qery_Udate)
        {   ///***Error insert Consulta 
           $ExitCAB = 0; 
           $Error = mysqli_error($conecta1);
        }else{
            ///**Insert Correct
            $ExitCAB = 1; 
            $Error= "Nel Perro";
        }



 $GASTrESULTADO =   Array(
 			"Res001" => $ExitCAB, 
 		    "Error" =>$Error  );
///**Convertimos a  Json  
  $convert_json  =  json_encode($GASTrESULTADO);
  header('Content-type: application/json');
echo  $convert_json ;


?> 