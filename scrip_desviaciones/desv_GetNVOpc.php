<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_GetNVOpc.php 
 	Fecha  Creacion : 29/06/2017
	Descripcion  : 
 *              Escrip  Diseñado  para  OBTENER  LOS   DIFERENTES Niveles de  Respuesta de las  Desviaciones  
 * 
 * Modificacion
 * 
 * 05/07/2017 =====> Modificacion se  Agregar al  Obteto de retorno del  Escript  el  campo 
 *     SELCULT con  los  siguentes   estodos:
 *          En tiendase que  SELCULT EN ESTADO   0 => Este Elemento NO es  Un Cultiivo 
 *                           SELCULT EN ESTADO   1 => Este Elemento Es  Un Cultiivo 
 *                           SELCULT EN ESTADO   2 => Este Elemento Es  Un Cultiivo Y Pertenece a otro  Nivel       
 * 
 * 
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');
 
///05/07/2017 =====> Modificacion se  Agregar al  Obteto de retorno del  Escript  el  campo 
$SELCULT = 0 ;

/////****Funcion para  Retornar Arreglo de  Cultivos 
function   GetCultivos($conecta1)
{
   $qerycul = mysqli_query($conecta1,"select  id, tipo_cultivo  from  pedidos.desv_cultivos" );
  
    $arrayJson =  array();
    while($row = mysqli_fetch_array($qerycul))
    {
    $obj  =  array("NVL1" =>$row['id'],"COMENT" => utf8_decode($row['tipo_cultivo']),"OPCULTIVO"=>0); 

        array_push($arrayJson, $obj);
    }
    
    return $arrayJson;
}
 
 
 
 
////***Opcion Seleccionada
$OPCNV = filter_input(INPUT_POST,'OpcNvl');
////**Numero de  Nivel a Obtener 
$num_NVL =filter_input(INPUT_POST,'NUMVL');
////***Opcion de Cultivoa 
$opcUltivo  = filter_input(INPUT_POST,'OPCULT');
////***Obtenemos  la variacion 
$VARi  = filter_input(INPUT_POST,'VARi');
//////****Codicion para  Obtener   el 1  Primer Nivel  de  Opciones 
if($num_NVL ==1 ){
/*Consulta para  Obtener  las  Opciones del  Nvl2   Dependientod  del  Nvl1 

SELECT * FROM pedidos.desv_respDesv  where NVL2 != 0  AND    NVL3 = 0  AND   NVL1 =%s  >Obtendendremos la opcion del Nvel  1 
*/
$strGetNvl = sprintf("SELECT NVL2,COMENT,OPCULTIVO,VAR FROM pedidos.desv_respDesv  where NVL2 != 0  AND    NVL3 = 0  AND   NVL1 =%s AND (VAR =%s OR VAR =0) ", GetSQLValueString($OPCNV, "int"),GetSQLValueString($VARi, "int")); 
$qeryGeNvl  = mysqli_query($conecta1, $strGetNvl);
/////**Obtenemos el numero de  Resultados 
    $NurOW = mysqli_num_rows($qeryGeNvl);
$arrayJson =  array();
while($row = mysqli_fetch_array($qeryGeNvl))
{
    $obj  =  array("NVL1" =>$row['NVL2'],"COMENT" => utf8_decode($row['COMENT']),"OPCULTIVO"=>$row['OPCULTIVO'],"VAR"=>$row['VAR']);
    
    array_push($arrayJson, $obj);
}
}
//////****Codicion para  Obtener   el 2 Segundo  Nivel  de  Opciones 
if($num_NVL ==2 ){

    //***********************************************************************
    /*Consulta para Obtner  el Nivel  3  de  Opciones*/
    $strGetNvl = sprintf("SELECT NVL3,COMENT,OPCULTIVO,VAR  FROM pedidos.desv_respDesv  where NVL3 !=0 AND  NVL4 =0   and  OPCULTIVO != 1 AND  NVL2=%s AND (VAR =%s OR VAR =0)", GetSQLValueString($OPCNV, "int"),GetSQLValueString($VARi, "int")); 
    $qeryGeNvl  = mysqli_query($conecta1, $strGetNvl);
    /////**Obtenemos el numero de  Resultados 
    $NurOW = mysqli_num_rows($qeryGeNvl);
    
    $arrayJson =  array();
   
     while($row = mysqli_fetch_array($qeryGeNvl))
     { 
        if($row['OPCULTIVO']==1 || $row['OPCULTIVO'] == 2 )
            {
                 $arrayJson = GetCultivos($conecta1);
              
                if($row['OPCULTIVO']==1 ){$SELCULT =1;} if($row['OPCULTIVO'] == 2 ){$SELCULT =2;} 
            }else {
                
                $obj  =  array("NVL1" =>$row['NVL3'],"COMENT" => utf8_encode($row['COMENT']),"OPCULTIVO"=>$row['OPCULTIVO'],"VAR"=>$row['VAR']);
                array_push($arrayJson, $obj);
            }
        
    }
        
  
}
//////****Codicion para  Obtener   el 3 Segundo  Nivel  de  Opciones 
if($num_NVL ==3 ){

    //***********************************************************************
    /*Consulta para Obtner  el Nivel  4  de  Opciones*/
    $strGetNvl = sprintf("SELECT NVL4,COMENT,OPCULTIVO,VAR  FROM pedidos.desv_respDesv  where   NVL4 !=0 AND  NVL3 =%s AND (VAR =%s OR VAR =0)", GetSQLValueString($OPCNV, "int"),GetSQLValueString($VARi, "int")); 
    $qeryGeNvl  = mysqli_query($conecta1, $strGetNvl);
    /////**Obtenemos el numero de  Resultados 
    $NurOW = mysqli_num_rows($qeryGeNvl);
    
    $arrayJson =  array();

     
        while($row = mysqli_fetch_array($qeryGeNvl))
        {   
            if($row['OPCULTIVO']==1 || $row['OPCULTIVO'] == 2 )
            {
                $arrayJson = GetCultivos($conecta1);
                
                if($row['OPCULTIVO']==1 ){$SELCULT =1;} if($row['OPCULTIVO'] == 2 ){$SELCULT =2;} 
                 
            }else {
                
                $obj  =  array("NVL1" =>$row['NVL4'],"COMENT" => utf8_encode($row['COMENT']),"OPCULTIVO"=>$row['OPCULTIVO'],"VAR"=>$row['VAR']);
                array_push($arrayJson, $obj);
            }
        }
      
}


///****Generamos Json De Resultado 
$arrayResult  =   array( 
                    "RES" =>$cult,
                    "OBJ" => json_encode($arrayJson),
                    "ELMN" => $NurOW,
                    "CULT" => $strGetNvl,
                    "SELCULT"=> $SELCULT 
                    );
 ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayResult); 

?> 