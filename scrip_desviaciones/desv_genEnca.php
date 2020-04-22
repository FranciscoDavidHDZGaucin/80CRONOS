<?php

///***
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_genEnca.php
 	Fecha  Creacion : 26/07/2017
	Descripcion  :
 *                  Escrip para Generar  los Encabezados  de  las desviaciones 
 * 
 *      Modificaciones:
 *                 26/07/2017      Generamos  Encabezado  para  las  Desviaciones 
 *                                 El Encabezado esta en funcion del  Agente  
 *                  
 *       
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

$feIni  = filter_input(INPUT_POST, 'FechIni');
$Finfe  = filter_input(INPUT_POST, 'FinFech');
///**Variable  Est 
$Est = true; ///Si alguno de los elementos tiene  algun error
//Elemento a Retornar en caso de Error
$errEle ;



  /////***Incio  agregado  el  26/07/2017 Generamos el  Encabezado  de las  desviaciones  
   
    
   ////*Obtenemos Consulta  para  Generar  el  encabezado 
    $strGet = sprintf("SELECT  concat(cve_agente,year(fech_Ini),month(fech_Ini)) as cve_desvi,cve_agente ,fech_Ini,fech_fin  FROM pedidos.desv_desviaciones_8020 where  fech_Ini =%s    group by cve_agente",
                        GetSQLValueString($feIni, "date"));
    $qeryGetEnca = mysqli_query($conecta1, $strGet);
    while ($row = mysqli_fetch_array($qeryGetEnca)) {
        
        ////***Generamos cadena  de Insert  
        $strcab = sprintf("INSERT  INTO pedidos.desv_encabeza_desviacion SET  cve_desvi=%s,cve_agente=%s,fech_Ini=%s,fech_fin=%s, fech_make_desv=NOW() ",
                 GetSQLValueString($row["cve_desvi"], "int"),
                 GetSQLValueString($row["cve_agente"], "int"),
                 GetSQLValueString($feIni, "date"),
                 GetSQLValueString($Finfe, "date")
                
                );
        $qeryInsEncabeza = mysqli_query($conecta1, $strcab);
         if(!$qeryInsEncabeza){
              $Est=false ; 
              $errEle = $strcab;
              break;

          }   
       
    }
    
   
   ////***Fin ****************************************************************************************
    ///****Generamos Json De Resultado 
$arrayResult  =   array(
   "ElemError" => "Hola :T !!!",
   "Est" => $Est,
    "EXIDES"=> ":V "
);
 ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayResult); 


?> 