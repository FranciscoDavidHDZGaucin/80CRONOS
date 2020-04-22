<?php
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo :enviar_carga.php 
 	Fecha  Creacion : 27/01/2017
	Descripcion  : 
            Scrip encargado  de Cargar  la  Informacion a la  Base de  Datos
  *         Retorna  Informa  de la carga 
	Modificado  Fecha  : 
*/

//  Include PHPExcel_IOFactory
include '../Classes/PHPExcel/IOFactory.php';
include '../Connections/conecta1.php';
include 'obj_proyeccion.php';
////***Obtenemos  todos los  elementos  Json 
$json_arreglo = filter_input(INPUT_POST, 'ELEMENTOS'); 
////***Obtenemos  el  numero  de  elementos  existentes
$num_elemntos = filter_input(INPUT_POST,'NELEM');
////***Convertimos   el  Json  en  el  areglo de objetos 
$Arreglo_Obj_JSON =  json_decode($json_arreglo);
//**Definimos el  Arreglo  Contenedor de todas  las  Proyecciones 
$areglo_proyecciones  =  array();
$obtener_id_uno =1;
for($j =0 ; $j<$num_elemntos-1;  $j ++)
{  
    /////***Obtenemos El  Objeto del  Json 
   $arreglo  =  (array) $Arreglo_Obj_JSON[$j];  
   ///**Generamos el  Objeto 
   $obj_Proye = new Proyeccion($arreglo['num_elem'],$arreglo['cve_almacen'],$arreglo['cve_agente'],$arreglo['cve_producto'],$arreglo['mes'],$arreglo['year'],$arreglo['cant_dem']);
   ///** Generamos cadena de consulta Para  Probar Existencia
   $string_existe_Proyeccion  = sprintf("SELECT count(*) as RE FROM pronostico  where   cve_alma =%s  &&  cve_age=%s  && anio=%s   && mes =%s  && cve_prod =%s",
                                       GetSQLValueString($obj_Proye->Get_Cve_Almacen(), "int"),
                                       GetSQLValueString( $obj_Proye->Get_Cve_Agente(), "int"),
                                       GetSQLValueString($obj_Proye->Get_Year(), "int"),
                                       GetSQLValueString( $obj_Proye->Get_Mes(), "int"),
                                       GetSQLValueString( $obj_Proye->Get_Cve_producto(), "text"));
  ////****Generamos  Objeto Mysql*******
                $mysqli_PRO = new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1);                      
                if (!$ROW=$mysqli_PRO->query($string_existe_Proyeccion))
                {
                  $obj_Proye->Set_error_mesage("Error en Conexion !!!!!".$string_exis_alma);
                }else{
                    $RESUL =$ROW->fetch_array(MYSQLI_ASSOC);
                    ////********Consulta para  Obtener el  ultimo  Id de  la  tabla para  asignarselo *******************************
                    if($obtener_id_uno==1)
                    {
                        $max_id_cre_pagares = "SELECT MAX(id) AS id FROM pronostico";
                        if (!$ROW=$mysqli_PRO->query($max_id_cre_pagares))
                        {
                              $obj_Proye->Set_error_mesage("Error en Conexion !!!!!".$string_exis_alma);
                        }else{
                                $res_id = $ROW->fetch_array(MYSQLI_ASSOC);
                                $ID_M = $res_id['id'];
                        }
                        $obtener_id_uno=0;
                    }
                }
                   
////****Validamos que exista el elemento ****************************************
    if ($RESUL['RE']==1){
         ///update
            $string_insertarm2=sprintf("UPDATE  pronostico set demanda=%s,id_carga_M=%s,fecha_carga_M=NOW() where cve_age=%s and  cve_alma=%s and mes=%s and anio=%s and cve_prod=%s",
                      GetSQLValueString($obj_Proye->Get_Demanda(), "int"),
                      GetSQLValueString($ID_M, "int"),
                      GetSQLValueString($obj_Proye->Get_Cve_Agente(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Cve_Almacen(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Mes(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Year(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Cve_producto(), "text")
                    );
             ///***Asignamos estado de la proyeccion
           $obj_Proye->Set_error_obj(3);
     }else{
         ///insert
            $string_insertarm2=sprintf("INSERT INTO  pronostico set demanda=%s,cve_age=%s,cve_alma=%s,mes=%s,anio=%s,cve_prod=%s,id_carga_M=%s,fecha_carga_M=NOW() ",
                       GetSQLValueString($obj_Proye->Get_Demanda(), "int"),
                      GetSQLValueString($obj_Proye->Get_Cve_Agente(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Cve_Almacen(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Mes(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Year(), "int"),  
                      GetSQLValueString($obj_Proye->Get_Cve_producto(), "text"),
                      GetSQLValueString($ID_M, "int"));
             ///***Asignamos estado de la proyeccion
           $obj_Proye->Set_error_obj(2);
     }
////*************************************************************************************
                if (!$ROW=$mysqli_PRO->query($string_insertarm2))
                {
                  $obj_Proye->Set_error_mesage("Error en Conexion !!!!!".$string_exis_alma);
                
                }
                ////***Agregamo elemento  Arreglo    
                 array_push($areglo_proyecciones,$obj_Proye) ;
}
   $array_json  = array(
            "Estado"=> "Fin Escrip",
           "PROYEC" =>  json_encode($areglo_proyecciones)
         );        
        $json_resultado = json_encode($array_json);
   ///****Fin Condicion  
        ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
    echo $json_resultado; 




?>
